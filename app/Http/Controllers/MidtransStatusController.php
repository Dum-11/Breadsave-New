<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Client\ConnectionException;

class MidtransStatusController extends Controller
{
    public function sinkronkan($id)
    {
        // 1. Hanya pembeli yang sudah login
        abort_unless(
            Auth::check() &&
            Auth::user()->role === 'pembeli',
            403
        );

        // 2. Ambil transaksi milik pembeli
        $pesanan = DB::table('pesanans as p')
            ->join(
                'pembayarans as b',
                'p.id_pesanan',
                '=',
                'b.id_pesanan'
            )
            ->where('p.id_pesanan', $id)
            ->where('p.id_user', Auth::id())
            ->select(
                'p.id_pesanan',
                'p.total_harga',
                'p.status as status_pesanan',
                'b.status as status_pembayaran',
                'b.midtrans_order_id',
                'b.midtrans_transaction_id'
            )
            ->first();

        abort_if(!$pesanan, 404);

        if ($pesanan->status_pembayaran === 'Berhasil') {
            return redirect()
                ->route('pembeli.pembayaran.show', $id)
                ->with('success', 'Pembayaran sudah dikonfirmasi.');
        }

        if (empty($pesanan->midtrans_order_id)) {
            return back()->with(
                'error',
                'Transaksi Midtrans belum dibuat.'
            );
        }

        // 3. Konfigurasi Midtrans
        $serverKey = config('midtrans.server_key');

        if (!$serverKey) {
            return back()->with(
                'error',
                'Server Key Midtrans belum tersedia.'
            );
        }

        $baseUrl = config('midtrans.is_production')
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';

        // 4. Ambil status langsung dari Midtrans
        try {
            $response = Http::withBasicAuth(
                    $serverKey,
                    ''
                )
                ->acceptJson()
                ->timeout(20)
                ->get(
                    $baseUrl . '/v2/' .
                    rawurlencode(
                        $pesanan->midtrans_order_id
                    ) . '/status'
                );

        } catch (ConnectionException $e) {
            report($e);

            return back()->with(
                'error',
                'Gagal menghubungi server Midtrans.'
            );
        }

        if (
            !$response->successful() ||
            (string) $response->json('status_code') !== '200'
        ) {
            Log::warning('Gagal cek status Midtrans', [
                'id_pesanan' => $id,
                'http_status' => $response->status(),
            ]);

            return back()->with(
                'error',
                'Status transaksi belum dapat diverifikasi.'
            );
        }

        // 5. Validasi identitas transaksi
        $orderId = $response->json('order_id');
        $transactionId = $response->json('transaction_id');
        $grossAmount = $response->json('gross_amount');
        $status = $response->json('transaction_status');
        $paymentType = $response->json('payment_type');

        if (
            $orderId !== $pesanan->midtrans_order_id ||
            !is_numeric($grossAmount) ||
            $paymentType !== 'qris' ||
            (
                $pesanan->midtrans_transaction_id &&
                $pesanan->midtrans_transaction_id !== $transactionId
            ) ||
            (int) round((float) $grossAmount) !==
            (int) round((float) $pesanan->total_harga)
        ) {
            Log::warning('Data transaksi Midtrans tidak cocok', [
                'id_pesanan' => $id,
                'order_id' => $orderId,
            ]);

            return back()->with(
                'error',
                'Data transaksi atau nominal pembayaran tidak cocok.'
            );
        }

        // 6. Pembayaran belum berhasil
        if ($status !== 'settlement') {
            DB::table('pembayarans')
                ->where('id_pesanan', $id)
                ->where('status', 'Menunggu Pembayaran')
                ->update([
                    'midtrans_status' => $status,
                    'updated_at' => now(),
                ]);

            return back()->with(
                'error',
                'Status Midtrans: ' . $status .
                '. Pembayaran belum dinyatakan berhasil.'
            );
        }

        // 7. Simpan status pembayaran berhasil
        $hasil = DB::transaction(function () use (
            $id,
            $transactionId
        ) {
            $pesanan = DB::table('pesanans')
                ->where('id_pesanan', $id)
                ->lockForUpdate()
                ->first();

            $pembayaran = DB::table('pembayarans')
                ->where('id_pesanan', $id)
                ->lockForUpdate()
                ->first();

            if (!$pesanan || !$pembayaran) {
                return 'tidak_ditemukan';
            }

            if (
                $pesanan->status === 'Dibatalkan' ||
                $pembayaran->status === 'Dibatalkan'
            ) {
                return 'dibatalkan';
            }

            if ($pembayaran->status === 'Berhasil') {
                return 'sudah_berhasil';
            }

            // Kode pickup unik berdasarkan ID pesanan
            $kodePickup = $pesanan->kode_pickup
                ?: 'BS-' . $id . '-' .
                   Str::upper(Str::random(6));

            DB::table('pembayarans')
                ->where('id_pesanan', $id)
                ->update([
                    'status' => 'Berhasil',
                    'midtrans_status' => 'settlement',
                    'midtrans_transaction_id' => $transactionId,
                    'waktu_bayar' => now(),
                    'updated_at' => now(),
                ]);

            DB::table('pesanans')
                ->where('id_pesanan', $id)
                ->update([
                    'kode_pickup' => $kodePickup,
                    'updated_at' => now(),
                ]);

            return 'berhasil';

        }, 3);

        if ($hasil === 'dibatalkan') {
            Log::warning(
                'Pembayaran Midtrans berhasil tetapi pesanan lokal dibatalkan',
                ['id_pesanan' => $id]
            );

            return back()->with(
                'error',
                'Midtrans menyatakan pembayaran berhasil, tetapi pesanan telah dibatalkan. Perlu pemeriksaan admin.'
            );
        }

        if ($hasil === 'tidak_ditemukan') {
            return back()->with(
                'error',
                'Data pesanan atau pembayaran tidak ditemukan.'
            );
        }

        return redirect()
            ->route('pembeli.pembayaran.show', $id)
            ->with(
                'success',
                'Pembayaran Midtrans berhasil diverifikasi!'
            );
    }
}
