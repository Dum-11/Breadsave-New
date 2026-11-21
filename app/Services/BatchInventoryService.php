<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Produk.stok = stok tersedia; produk_batches.stok = distribusi stok tersedia.
 * Pemesanan lama mengurangi produk.stok; pemesanan baru dialokasikan FEFO tanpa
 * mengurangi produk.stok dua kali. Wajib dipanggil dalam transaksi checkout.
 */
class BatchInventoryService
{
    public static function reconcileCancelled(int $limit = 250): int
    {
        $orders = DB::table('pesanans as p')
            ->join('order_batch_allocations as a', 'a.id_pesanan', '=', 'p.id_pesanan')
            ->where('p.status', 'Dibatalkan')->whereNotNull('p.stock_restored_at')
            ->whereNull('a.released_at')->distinct()
            ->orderBy('p.id_pesanan')->limit($limit)->pluck('p.id_pesanan');
        $count = 0;
        foreach ($orders as $orderId) {
            DB::transaction(function () use ($orderId, &$count) {
                $order = DB::table('pesanans')->where('id_pesanan', $orderId)->lockForUpdate()->first();
                if (!$order || $order->status !== 'Dibatalkan' || !$order->stock_restored_at) return;
                $allocations = DB::table('order_batch_allocations')
                    ->where('id_pesanan', $orderId)->whereNull('released_at')->orderBy('id')->lockForUpdate()->get();
                foreach ($allocations as $a) {
                    $changed = DB::table('order_batch_allocations')->where('id', $a->id)
                        ->whereNull('released_at')->update(['released_at' => now(), 'updated_at' => now()]);
                    if ($changed) {
                        DB::table('produk_batches')->where('id', $a->id_batch)->increment('stok', (int) $a->jumlah);
                        $count++;
                    }
                }
            }, 3);
        }
        return $count;
    }

    public static function syncProduct(int $productId): void
    {
        $p = DB::table('produks')->where('id_produk', $productId)->lockForUpdate()->first();
        if (!$p) throw new RuntimeException('Produk tidak ditemukan.');
        $batches = DB::table('produk_batches')->where('id_produk', $productId)
            ->orderBy('expired_at')->lockForUpdate()->get();
        $expected = max(0, (int) $p->stok);
        if ($batches->isEmpty()) {
            // Impor stok legacy satu kali, tidak menyentuh produksi/pembayaran.
            DB::table('produk_batches')->insert([
                'id_produk' => $productId, 'kode_batch' => 'LEGACY-' . $productId,
                'stok' => $expected, 'expired_at' => $p->expired_at ?: now(),
                'created_at' => now(), 'updated_at' => now(),
            ]);
            return;
        }
        $actual = $batches->sum('stok');
        $delta = $expected - $actual;
        if ($delta > 0) {
            // Perubahan stok lewat form admin lama: tambahkan ke batch penyesuaian.
            $key = 'ADJUST-' . $productId;
            $existing = DB::table('produk_batches')->where('id_produk', $productId)
                ->where('kode_batch', $key)->first();
            if ($existing) {
                DB::table('produk_batches')->where('id', $existing->id)->update([
                    'stok' => DB::raw('stok + ' . (int) $delta),
                    'expired_at' => $p->expired_at ?: now(), 'updated_at' => now(),
                ]);
            } else {
                DB::table('produk_batches')->insert([
                    'id_produk' => $productId, 'kode_batch' => $key,
                    'stok' => $delta, 'expired_at' => $p->expired_at ?: now(),
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        } elseif ($delta < 0) {
            // Koreksi stok legacy di luar batch: kurangi batch paling akhir dahulu.
            $remaining = -$delta;
            foreach ($batches->sortByDesc('expired_at') as $batch) {
                if ($remaining <= 0) break;
                $take = min($remaining, (int) $batch->stok);
                DB::table('produk_batches')->where('id', $batch->id)->decrement('stok', $take);
                $remaining -= $take;
            }
        }
    }

    public static function availableAt(int $productId, Carbon $pickup): int
    {
        return (int) DB::table('produk_batches')->where('id_produk', $productId)
            ->where('expired_at', '>', $pickup->format('Y-m-d H:i:s'))->sum('stok');
    }

    public static function allocate(int $orderId, int $productId, int $quantity, Carbon $pickup): void
    {
        $remaining = $quantity;
        $batches = DB::table('produk_batches')->where('id_produk', $productId)
            ->where('stok', '>', 0)
            ->where('expired_at', '>', $pickup->format('Y-m-d H:i:s'))
            ->orderBy('expired_at')->orderBy('id')->lockForUpdate()->get();
        if ($batches->sum('stok') < $quantity || $quantity <= 0) {
            throw new RuntimeException('Batch FEFO tidak mencukupi hingga jadwal pickup.');
        }
        foreach ($batches as $batch) {
            if ($remaining <= 0) break;
            $take = min($remaining, (int) $batch->stok);
            DB::table('produk_batches')->where('id', $batch->id)->decrement('stok', $take);
            DB::table('order_batch_allocations')->insert([
                'id_pesanan' => $orderId, 'id_produk' => $productId,
                'id_batch' => $batch->id, 'jumlah' => $take,
                'created_at' => now(), 'updated_at' => now(),
            ]);
            $remaining -= $take;
        }
        self::refreshExpiry($productId);
    }

    public static function refreshExpiry(int $productId): void
    {
        $next = DB::table('produk_batches')->where('id_produk', $productId)
            ->where('stok', '>', 0)->min('expired_at');
        if ($next) DB::table('produks')->where('id_produk', $productId)
            ->update(['expired_at' => $next, 'updated_at' => now()]);
    }

    public static function expireBatches(int $limit = 200): int
    {
        $ids = DB::table('produk_batches')->where('stok', '>', 0)
            ->where('expired_at', '<=', now())->orderBy('expired_at')->limit($limit)->pluck('id');
        $total = 0;
        foreach ($ids as $id) {
            DB::transaction(function () use ($id, &$total) {
                $batch = DB::table('produk_batches')->where('id', $id)->lockForUpdate()->first();
                if (!$batch || $batch->stok <= 0 || Carbon::parse($batch->expired_at)->gt(now())) return;
                $product = DB::table('produks')->where('id_produk', $batch->id_produk)->lockForUpdate()->first();
                if (!$product) return;
                // Hanya stok yg benar-benar tersedia, bukan yg sudah dipesan.
                $quantity = min((int) $batch->stok, max(0, (int) $product->stok));
                if (!$quantity) return;
                DB::table('produk_batches')->where('id', $id)->decrement('stok', $quantity);
                DB::table('produks')->where('id_produk', $batch->id_produk)->decrement('stok', $quantity);
                DB::table('food_waste_records')->insertOrIgnore([
                    'id_produk' => $batch->id_produk, 'id_batch' => $id,
                    'jumlah' => $quantity, 'alasan' => 'kedaluwarsa', 'sumber' => 'otomatis',
                    'created_by' => null, 'tanggal_buang' => now(),
                    'event_key' => null,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                self::refreshExpiry((int) $batch->id_produk);
                $total += $quantity;
            }, 3);
        }
        return $total;
    }
}
