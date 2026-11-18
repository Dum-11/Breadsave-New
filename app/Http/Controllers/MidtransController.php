<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    // ==========================================
    // MEMBUAT QRIS MIDTRANS
    // ==========================================

    public function buatQris($id)
    {
        // Pastikan pengguna sudah login
        abort_unless(Auth::check(), 403);

        // ======================================
        // 1. AMBIL DATA PESANAN
        // ======================================

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
                'b.metode',
                'b.status as status_pembayaran',
                'b.qr_url',
                'b.midtrans_order_id'
            )
            ->first();

        abort_if(!$pesanan, 404);

        // Hanya untuk metode QRIS
        if ($pesanan->metode !== 'QRIS') {
            return back()->with(
                'error',
                'Metode pembayaran bukan QRIS.'
            );
        }

        // Pesanan dibatalkan tidak bisa dibayar
        if (
            $pesanan->status_pesanan === 'Dibatalkan' ||
            $pesanan->status_pembayaran === 'Dibatalkan'
        ) {
            return back()->with(
                'error',
                'Pesanan sudah dibatalkan.'
            );
        }

        // Jangan membuat ulang pembayaran berhasil
        if ($pesanan->status_pembayaran === 'Berhasil') {
            return redirect()->route(
                'pembeli.pembayaran.show',
                $id
            );
        }

        // Gunakan QR yang sudah tersedia
        if (!empty($pesanan->qr_url)) {
            return redirect()->route(
                'pembeli.pembayaran.show',
                $id
            );
        }

        // ======================================
        // 2. AMBIL DETAIL PRODUK
        // ======================================

        $detail = DB::table('detail_pesanans as d')
            ->leftJoin(
                'produks as pr',
                'd.id_produk',
                '=',
                'pr.id_produk'
            )
            ->where('d.id_pesanan', $id)
            ->select(
                'd.id_produk',
                'd.jumlah',
                'd.subtotal',
                'pr.nama_produk'
            )
            ->get();

        if ($detail->isEmpty()) {
            return back()->with(
                'error',
                'Detail produk tidak ditemukan.'
            );
        }

        // ======================================
        // 3. SIAPKAN DATA JSON PRODUK
        // ======================================

        $items = [];
        $total = 0;

        foreach ($detail as $produk) {

            $jumlah = (int) $produk->jumlah;

            if ($jumlah < 1) {
                return back()->with(
                    'error',
                    'Jumlah produk tidak valid.'
                );
            }

            $subtotal = (float) $produk->subtotal;

            $harga = (int) round(
                $subtotal / $jumlah
            );

            if (
                $harga <= 0 ||
                abs(
                    ($harga * $jumlah) - $subtotal
                ) > 0.001
            ) {
                return back()->with(
                    'error',
                    'Harga produk tidak valid.'
                );
            }

            $items[] = [
                'id' => (string) $produk->id_produk,
                'price' => $harga,
                'quantity' => $jumlah,
                'name' => substr(
                    $produk->nama_produk ??
                    'Produk BreadSaver',
                    0,
                    50
                ),
            ];

            $total += $harga * $jumlah;
        }

        // ======================================
        // 4. VALIDASI TOTAL PEMBAYARAN
        // ======================================

        if (
            abs(
                $total - (float) $pesanan->total_harga
            ) > 0.001
        ) {
            return back()->with(
                'error',
                'Total pembayaran tidak sesuai dengan pesanan.'
            );
        }

        // ======================================
        // 5. MEMBUAT ID TRANSAKSI
        // ======================================

        $orderId = $pesanan->midtrans_order_id
            ?: 'BS-' . $pesanan->id_pesanan;

        // ======================================
        // 6. MEMBUAT JSON MIDTRANS
        // ======================================

        $payload = [
            'payment_type' => 'qris',

            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],

            'item_details' => $items,

            'qris' => [
                'acquirer' => 'gopay',
            ],
        ];

        // ======================================
        // 7. KONFIGURASI MIDTRANS
        // ======================================

        $serverKey = config('midtrans.server_key');

        if (empty($serverKey)) {
            return back()->with(
                'error',
                'Server Key Midtrans belum diatur di .env.'
            );
        }

        $baseUrl = config('midtrans.is_production')
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';

        // ======================================
        // 8. KIRIM JSON KE MIDTRANS API
        // ======================================

        try {

            $response = Http::withBasicAuth(
                    $serverKey,
                    ''
                )
                ->acceptJson()
                ->asJson()
                ->timeout(20)
                ->post(
                    $baseUrl . '/v2/charge',
                    $payload
                );

        } catch (\Illuminate\Http\Client\ConnectionException $e) {

            Log::error(
                'Midtrans Connection Error',
                [
                    'order_id' => $orderId,
                    'message' => $e->getMessage(),
                ]
            );

            return back()->with(
                'error',
                'Tidak dapat terhubung ke Midtrans. Periksa koneksi internet atau log Laravel.'
            );
        }

        // ======================================
        // 9. PERIKSA RESPONSE MIDTRANS
        // ======================================

        if (
            !$response->successful() ||
            (string) $response->json('status_code') !== '201'
        ) {

            // Simpan informasi error ke log Laravel
            Log::error('Midtrans QRIS Error', [
                'order_id' => $orderId,
                'http_status' => $response->status(),
                'status_code' => $response->json('status_code'),
                'status_message' => $response->json('status_message'),
                'error_messages' => $response->json('error_messages'),
                'validation_messages' => $response->json('validation_messages'),
            ]);

            // Ambil alasan error dari Midtrans
            $errorMessages = $response->json('error_messages');

            $pesanError = $response->json('status_message');

            if (
                is_array($errorMessages) &&
                count($errorMessages) > 0
            ) {
                $pesanError = implode(
                    '; ',
                    array_filter(
                        $errorMessages,
                        'is_string'
                    )
                );
            }

            if (
                !is_string($pesanError) ||
                trim($pesanError) === ''
            ) {
                $pesanError = 'Periksa log Laravel untuk informasi lebih lanjut.';
            }

            return back()->with(
                'error',
                'Midtrans Error HTTP ' .
                $response->status() .
                ': ' .
                $pesanError
            );
        }

        // ======================================
        // 10. AMBIL URL QRIS MIDTRANS
        // ======================================

        $actions = $response->json('actions', []);

        $qrAction = collect($actions)
            ->firstWhere(
                'name',
                'generate-qr-code-v2'
            );

        if (!$qrAction) {
            $qrAction = collect($actions)
                ->firstWhere(
                    'name',
                    'generate-qr-code'
                );
        }

        $qrUrl = $qrAction['url'] ?? null;

        if (!$qrUrl) {

            Log::error(
                'Midtrans QR URL Not Found',
                [
                    'order_id' => $orderId,
                    'transaction_id' =>
                        $response->json('transaction_id'),
                    'actions' => $actions,
                ]
            );

            return back()->with(
                'error',
                'Transaksi diterima Midtrans, tetapi URL QRIS tidak ditemukan. Periksa log sebelum mencoba membuat ulang transaksi.'
            );
        }

        // ======================================
        // 11. SIMPAN TRANSAKSI KE DATABASE
        // ======================================

        DB::table('pembayarans')
            ->where('id_pesanan', $id)
            ->where('status', 'Menunggu Pembayaran')
            ->update([
                'midtrans_order_id' => $orderId,

                'midtrans_transaction_id' =>
                    $response->json('transaction_id'),

                'midtrans_status' =>
                    $response->json('transaction_status'),

                'qr_url' => $qrUrl,

                'updated_at' => now(),
            ]);

        // ======================================
        // 12. KEMBALI KE HALAMAN PEMBAYARAN
        // ======================================

        return redirect()
            ->route(
                'pembeli.pembayaran.show',
                $id
            )
            ->with(
                'success',
                'QRIS Midtrans berhasil dibuat. Silakan lakukan pembayaran melalui Sandbox.'
            );
    }
}
