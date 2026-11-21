<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;
use App\Services\BreadSaverNotification;

class MidtransAutoSyncController extends Controller
{
    /**
     * Dipanggil dari browser pembeli saat pembayaran masih menunggu.
     * Status selalu diverifikasi ke Midtrans dari server, bukan dari browser.
     */
    public function check($id)
    {
        abort_unless(Auth::check() && Auth::user()->role === 'pembeli', 403);

        $pesanan = DB::table('pesanans')
            ->where('id_pesanan', $id)
            ->where('id_user', Auth::id())
            ->first();

        abort_if(!$pesanan, 404);

        $pembayaran = DB::table('pembayarans')
            ->where('id_pesanan', $id)
            ->first();

        abort_if(!$pembayaran, 404);

        // Jangan memanggil Midtrans lagi untuk pembayaran final.
        if ($pembayaran->status !== 'Menunggu Pembayaran' || $pesanan->status === 'Dibatalkan') {
            return response()->json([
                'status' => $pembayaran->status,
                'updated' => false,
            ]);
        }

        // QRIS belum dibuat: belum ada transaksi yang bisa dicek.
        if (empty($pembayaran->midtrans_order_id)) {
            return response()->json([
                'status' => 'Menunggu Pembayaran',
                'updated' => false,
            ]);
        }

        $serverKey = config('services.midtrans.server_key') ?: env('MIDTRANS_SERVER_KEY');
        if (empty($serverKey)) {
            return response()->json(['message' => 'Konfigurasi Midtrans belum tersedia.'], 503);
        }

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->acceptJson()
                ->timeout(8)
                ->get('https://api.sandbox.midtrans.com/v2/' . rawurlencode($pembayaran->midtrans_order_id) . '/status');
        } catch (Throwable $e) {
            report($e);
            return response()->json(['message' => 'Koneksi ke Midtrans sementara gagal.'], 503);
        }

        if (!$response->successful()) {
            return response()->json(['message' => 'Status Midtrans belum bisa diperiksa.'], 502);
        }

        $data = $response->json();
        if (!is_array($data) || ($data['order_id'] ?? '') !== $pembayaran->midtrans_order_id) {
            return response()->json(['message' => 'ID transaksi tidak cocok.'], 409);
        }

        // Pastikan nominal transaksi yang dikonfirmasi sama dengan pesanan.
        if (!isset($data['gross_amount']) ||
            (int) round((float) $data['gross_amount']) !== (int) round((float) $pembayaran->total_bayar)) {
            return response()->json(['message' => 'Nominal pembayaran tidak cocok.'], 409);
        }

        $statusMidtrans = $data['transaction_status'] ?? '';
        $lunas = $statusMidtrans === 'settlement' ||
            ($statusMidtrans === 'capture' && ($data['fraud_status'] ?? 'accept') === 'accept');
        $gagal = in_array($statusMidtrans, ['expire', 'cancel', 'deny'], true);

        if ($lunas || $gagal) {
            DB::transaction(function () use ($id, $lunas, $data, $statusMidtrans) {
                $order = DB::table('pesanans')
                    ->where('id_pesanan', $id)
                    ->lockForUpdate()
                    ->first();

                $bayar = DB::table('pembayarans')
                    ->where('id_pesanan', $id)
                    ->lockForUpdate()
                    ->first();

                if (!$order || !$bayar || $order->status === 'Dibatalkan' ||
                    $bayar->status !== 'Menunggu Pembayaran') {
                    return;
                }

                $perubahan = [
                    'status' => $lunas ? 'Berhasil' : 'Dibatalkan',
                    'updated_at' => now(),
                ];

                if ($lunas) {
                    $perubahan['waktu_bayar'] = now();
                }

                // Field Midtrans ini sudah dipakai BreadSaver saat ini.
                $perubahan['midtrans_status'] = $statusMidtrans;
                if (!empty($data['transaction_id'])) {
                    $perubahan['midtrans_transaction_id'] = $data['transaction_id'];
                }

                DB::table('pembayarans')
                    ->where('id_pesanan', $id)
                    ->update($perubahan);

                if ($lunas) {
                    BreadSaverNotification::paid($id);
                }

                if ($lunas && empty($order->kode_pickup)) {
                    DB::table('pesanans')
                        ->where('id_pesanan', $id)
                        ->update([
                            'kode_pickup' => 'BS-' . strtoupper(Str::random(6)),
                            'updated_at' => now(),
                        ]);
                }
            }, 3);
        }

        // Baca ulang agar hasilnya benar walaupun terjadi request bersamaan.
        $statusAkhir = DB::table('pembayarans')
            ->where('id_pesanan', $id)
            ->value('status');

        return response()->json([
            'status' => $statusAkhir,
            'updated' => $statusAkhir !== $pembayaran->status,
        ]);
    }
}
