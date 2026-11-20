<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Services\BreadSaverNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired {--dry-run : Tampilkan tindakan tanpa mengubah data atau membatalkan transaksi Midtrans}';

    protected $description = 'Batalkan pesanan yang lewat batas bayar, pastikan status Midtrans, dan kembalikan stok satu kali';

    public function handle()
    {
        $dryRun = (bool) $this->option('dry-run');
        $serverKey = config('services.midtrans.server_key') ?: env('MIDTRANS_SERVER_KEY');
        $checked = 0;
        $cancelled = 0;
        $paid = 0;
        $skipped = 0;

        if ($dryRun) {
            $this->info('MODE UJI: tidak ada transaksi atau stok yang akan diubah.');
        }

        $orders = DB::table('pesanans as p')
            ->join('pembayarans as b', 'p.id_pesanan', '=', 'b.id_pesanan')
            ->where('p.status', 'Menunggu Konfirmasi')
            ->whereNull('p.stock_restored_at')
            ->whereNotNull('p.payment_expires_at')
            ->where('p.payment_expires_at', '<=', now())
            ->where('b.status', 'Menunggu Pembayaran')
            ->orderBy('p.id_pesanan')
            ->select('p.id_pesanan', 'p.payment_expires_at', 'b.midtrans_order_id')
            ->get();

        foreach ($orders as $order) {
            ++$checked;
            $id = $order->id_pesanan;
            $remoteOrderId = $order->midtrans_order_id;

            // Jika QRIS belum dibuat, beri jeda 2 menit setelah tenggat untuk
            // menghindari bentrokan dengan request pembuatan QRIS yang masih berjalan.
            if (empty($remoteOrderId)) {
                if (now()->lt(Carbon::parse($order->payment_expires_at)->addMinutes(2))) {
                    $this->line("#{$id}: menunggu jeda keamanan pembuatan QRIS.");
                    ++$skipped;
                    continue;
                }

                if ($dryRun) {
                    $this->line("#{$id}: akan dibatalkan (tidak ada transaksi Midtrans, lewat tenggat + 2 menit).");
                    continue;
                }

                if ($this->cancelLocal($id, null)) {
                    $this->info("#{$id}: dibatalkan, stok dikembalikan.");
                    ++$cancelled;
                } else {
                    ++$skipped;
                }
                continue;
            }

            if (empty($serverKey)) {
                $this->warn("#{$id}: Server Key Midtrans tidak tersedia; dilewati demi keamanan stok.");
                ++$skipped;
                continue;
            }

            $statusData = $this->getMidtransStatus($remoteOrderId, $serverKey);
            if ($statusData === null) {
                $this->warn("#{$id}: gagal memastikan status Midtrans; dilewati.");
                ++$skipped;
                continue;
            }

            $status = $statusData['transaction_status'] ?? null;

            if (in_array($status, ['settlement', 'capture'], true)) {
                if ($dryRun) {
                    $this->line("#{$id}: sudah dibayar di Midtrans; akan disinkronkan, bukan dibatalkan.");
                } elseif ($this->syncPaid($id, $remoteOrderId, $statusData)) {
                    $this->info("#{$id}: pembayaran berhasil disinkronkan; stok tidak dikembalikan.");
                    ++$paid;
                } else {
                    ++$skipped;
                }
                continue;
            }

            if ($status === 'pending') {
                if ($dryRun) {
                    $this->line("#{$id}: pending; perlu membatalkan di Midtrans sebelum stok bisa kembali.");
                    continue;
                }

                // Batalkan transaksi Midtrans lebih dulu. Jangan lepas stok
                // selama pembeli masih bisa menyelesaikan pembayaran.
                if (!$this->cancelMidtrans($remoteOrderId, $serverKey)) {
                    $this->warn("#{$id}: gagal membatalkan transaksi Midtrans; stok tidak diubah.");
                    ++$skipped;
                    continue;
                }

                // Verifikasi ulang hasil cancel; hanya status final yang aman.
                $statusData = $this->getMidtransStatus($remoteOrderId, $serverKey);
                $status = $statusData['transaction_status'] ?? null;
            }

            if (in_array($status, ['settlement', 'capture'], true)) {
                if ($this->syncPaid($id, $remoteOrderId, $statusData)) {
                    $this->info("#{$id}: pembayaran terdeteksi setelah pembatalan dicoba; pesanan dipertahankan.");
                    ++$paid;
                } else {
                    ++$skipped;
                }
                continue;
            }

            if (!in_array($status, ['expire', 'cancel', 'deny'], true)) {
                $this->warn("#{$id}: status Midtrans belum final (" . ($status ?: 'tidak diketahui') . "); dilewati.");
                ++$skipped;
                continue;
            }

            if ($dryRun) {
                $this->line("#{$id}: transaksi Midtrans {$status}; akan dibatalkan dan stok dikembalikan.");
                continue;
            }

            if ($this->cancelLocal($id, $remoteOrderId)) {
                $this->info("#{$id}: dibatalkan (Midtrans {$status}); stok dikembalikan.");
                ++$cancelled;
            } else {
                ++$skipped;
            }
        }

        $this->info("Selesai. Diperiksa: {$checked}, dibatalkan: {$cancelled}, disinkronkan lunas: {$paid}, dilewati: {$skipped}.");
        return 0;
    }

    private function getMidtransStatus($orderId, $serverKey)
    {
        try {
            $response = Http::timeout(15)
                ->withBasicAuth($serverKey, '')
                ->get('https://api.sandbox.midtrans.com/v2/' . rawurlencode($orderId) . '/status');

            return $response->successful() ? $response->json() : null;
        } catch (\Throwable $e) {
            Log::warning('Gagal mengecek Midtrans pada pembatalan otomatis', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function cancelMidtrans($orderId, $serverKey)
    {
        try {
            $response = Http::timeout(15)
                ->withBasicAuth($serverKey, '')
                ->post('https://api.sandbox.midtrans.com/v2/' . rawurlencode($orderId) . '/cancel');

            // Jangan berasumsi sukses berarti stok aman. Caller tetap
            // memanggil GET /status dan memerlukan status final.
            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('Gagal membatalkan Midtrans secara otomatis', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function cancelLocal($id, $expectedMidtransOrderId)
    {
        return DB::transaction(function () use ($id, $expectedMidtransOrderId) {
            // Urutan lock sama seperti controller pembayaran/pembatalan.
            $order = DB::table('pesanans')
                ->where('id_pesanan', $id)->lockForUpdate()->first();
            if (!$order || $order->status !== 'Menunggu Konfirmasi' ||
                $order->stock_restored_at !== null ||
                !$order->payment_expires_at ||
                now()->lt(Carbon::parse($order->payment_expires_at))) {
                return false;
            }

            $payment = DB::table('pembayarans')
                ->where('id_pesanan', $id)->lockForUpdate()->first();
            if (!$payment || $payment->status !== 'Menunggu Pembayaran' ||
                ($payment->midtrans_order_id ?: null) !== $expectedMidtransOrderId) {
                return false;
            }

            $details = DB::table('detail_pesanans')
                ->where('id_pesanan', $id)->get();
            foreach ($details as $detail) {
                DB::table('produks')
                    ->where('id_produk', $detail->id_produk)
                    ->increment('stok', (int) $detail->jumlah);
            }

            DB::table('pesanans')->where('id_pesanan', $id)->update([
                'status' => 'Dibatalkan',
                'stock_restored_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('pembayarans')->where('id_pesanan', $id)->update([
                'status' => 'Dibatalkan',
                'updated_at' => now(),
            ]);

            BreadSaverNotification::cancelled($id);
            return true;
        }, 3);
    }

    private function syncPaid($id, $remoteOrderId, $statusData)
    {
        return DB::transaction(function () use ($id, $remoteOrderId, $statusData) {
            $order = DB::table('pesanans')
                ->where('id_pesanan', $id)->lockForUpdate()->first();
            $payment = DB::table('pembayarans')
                ->where('id_pesanan', $id)->lockForUpdate()->first();

            if (!$order || !$payment || $order->status === 'Dibatalkan' ||
                $order->stock_restored_at !== null ||
                $payment->midtrans_order_id !== $remoteOrderId) {
                Log::error('Midtrans sudah lunas tetapi pesanan tak dapat disinkronkan otomatis', [
                    'id_pesanan' => $id,
                    'midtrans_order_id' => $remoteOrderId,
                ]);
                return false;
            }

            $updates = [
                'status' => 'Berhasil',
                'updated_at' => now(),
            ];
            if (!$payment->waktu_bayar) {
                $updates['waktu_bayar'] = now();
            }
            if (Schema::hasColumn('pembayarans', 'midtrans_status')) {
                $updates['midtrans_status'] = $statusData['transaction_status'] ?? 'settlement';
            }
            if (Schema::hasColumn('pembayarans', 'midtrans_transaction_id')) {
                $updates['midtrans_transaction_id'] = $statusData['transaction_id'] ?? null;
            }

            DB::table('pembayarans')->where('id_pesanan', $id)->update($updates);
            BreadSaverNotification::paid($id);

            if (empty($order->kode_pickup)) {
                DB::table('pesanans')->where('id_pesanan', $id)->update([
                    'kode_pickup' => 'BS-' . strtoupper(Str::random(6)),
                    'updated_at' => now(),
                ]);
            }
            return true;
        }, 3);
    }
}
