<?php

namespace App\Http\Middleware;

use App\Services\BatchInventoryService;
use App\Services\LoyaltyService;
use App\Services\PickupSlotService;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/** Decorator checkout lama: slot, FEFO dan voucher, tanpa mengganti controller Midtrans. */
class AdvancedCheckoutMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'pembeli') return $next($request);
        $raw = (string) $request->input('waktu_pengambilan');
        try {
            $pickup = Carbon::parse($raw, 'Asia/Jakarta')->second(0);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Pilih waktu pickup yang valid.');
        }
        // Pastikan checkout lama menyimpan slot persis HH:00/HH:30 (detik selalu 00),
        // sehingga kapasitas tidak bisa dilewati dengan parameter detik berbeda.
        $request->merge(['waktu_pengambilan' => $pickup->format('Y-m-d\TH:i')]);
        $voucher = strtoupper(trim((string) $request->input('voucher_kode', '')));
        $userId = (int) auth()->id();
        try {
            // Proses pembatalan sebelumnya agar stok batch tak tertinggal.
            BatchInventoryService::reconcileCancelled();
            return DB::transaction(function () use ($request, $next, $pickup, $voucher, $userId) {
                PickupSlotService::requireCapacity($pickup);
                if ($voucher !== '') {
                    $check = DB::table('loyalty_vouchers')->where('kode', $voucher)
                        ->where('id_user', $userId)->whereNull('dipakai_pesanan')
                        ->where('kadaluwarsa_at', '>', now())->lockForUpdate()->first();
                    if (!$check) throw new RuntimeException('Voucher tidak valid atau sudah dipakai.');
                }

                $items = DB::table('keranjang_items')->where('id_user', $userId)
                    ->orderBy('id_produk')->get(['id_produk', 'jumlah']);
                $temporaryExpiry = [];
                foreach ($items as $item) {
                    $productId = (int) $item->id_produk;
                    BatchInventoryService::syncProduct($productId);
                    if (BatchInventoryService::availableAt($productId, $pickup) < (int) $item->jumlah) {
                        throw new RuntimeException('Stok batch untuk waktu pickup tidak cukup. Pilih jadwal/roti lain.');
                    }
                    // Controller lama hanya mengecek expired_at agregat produk.
                    // Jika batch pertama kedaluwarsa sebelum pickup tetapi batch FEFO
                    // berikutnya cukup, pakai tanggal layak sementara DI DALAM transaksi.
                    // Segera kembalikan tanggal asli setelah order dialokasikan.
                    $legacyExpiry = DB::table('produks')->where('id_produk', $productId)->value('expired_at');
                    if ($legacyExpiry && Carbon::parse($legacyExpiry)->lte($pickup)) {
                        $eligibleExpiry = DB::table('produk_batches')->where('id_produk', $productId)
                            ->where('stok', '>', 0)
                            ->where('expired_at', '>', $pickup->format('Y-m-d H:i:s'))->max('expired_at');
                        if (!$eligibleExpiry) throw new RuntimeException('Tidak ada batch roti yang aman untuk pickup.');
                        DB::table('produks')->where('id_produk', $productId)
                            ->update(['expired_at' => $eligibleExpiry]);
                        $temporaryExpiry[] = $productId;
                    }
                }

                $previousId = (int) DB::table('pesanans')->where('id_user', $userId)->max('id_pesanan');
                // Checkout existing tetap menghitung harga, membuat detail, reservasi stok,
                // pembayaran menunggu, serta mengosongkan keranjang secara normal.
                $response = $next($request);
                $new = DB::table('pesanans')->where('id_user', $userId)
                    ->where('id_pesanan', '>', $previousId)->orderByDesc('id_pesanan')->first();
                if (!$new) {
                    foreach ($temporaryExpiry as $id) BatchInventoryService::refreshExpiry($id);
                    return $response;
                }

                $details = DB::table('detail_pesanans')->where('id_pesanan', $new->id_pesanan)->get();
                foreach ($details as $d) {
                    BatchInventoryService::allocate((int) $new->id_pesanan, (int) $d->id_produk,
                        (int) $d->jumlah, $pickup);
                }
                if ($voucher !== '') LoyaltyService::applyVoucher($userId, (int) $new->id_pesanan, $voucher);
                foreach ($temporaryExpiry as $id) BatchInventoryService::refreshExpiry($id);
                return $response;
            }, 3);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
