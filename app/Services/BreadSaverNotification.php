<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Notifikasi BreadSaver. Event key dibuat unik per penerima supaya
 * polling Midtrans, retry scheduler, dan klik ulang tidak menumpuk pesan.
 */
class BreadSaverNotification
{
    private static function create($userId, $orderId, string $event, string $type, string $title, string $message, ?string $url): void
    {
        if (!$userId) {
            return;
        }

        DB::table('notifikasi')->insertOrIgnore([
            'id_user' => $userId,
            'id_pesanan' => $orderId,
            'jenis' => $type,
            'judul' => $title,
            'pesan' => $message,
            'tautan' => $url,
            'event_key' => 'pesanan:' . $orderId . ':' . $event,
            'dibaca_pada' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private static function buyer($orderId, string $event, string $type, string $title, string $message): void
    {
        $idUser = DB::table('pesanans')
            ->where('id_pesanan', $orderId)
            ->value('id_user');

        self::create(
            $idUser, $orderId, $event, $type, $title, $message,
            route('pembeli.pesanan')
        );
    }

    /** Setelah Midtrans memastikan pembayaran, beri tahu pembeli dan staf. */
    public static function paid($orderId): void
    {
        self::buyer(
            $orderId, 'paid', 'pembayaran', 'Pembayaran Berhasil',
            'Pembayaran pesanan #' . $orderId . ' berhasil. Pesanan menunggu konfirmasi toko.'
        );

        $staf = DB::table('users')
            ->whereIn('role', ['pegawai', 'admin'])
            ->get(['id_user', 'role']);

        foreach ($staf as $user) {
            $tujuan = $user->role === 'admin'
                ? route('admin.laporan')
                : route('pegawai.pesanan');

            self::create(
                $user->id_user, $orderId, 'new-paid', 'pesanan',
                'Pesanan Baru Sudah Dibayar',
                'Pesanan #' . $orderId . ' sudah lunas dan menunggu diproses.',
                $tujuan
            );
        }
    }

    public static function processing($orderId): void
    {
        self::buyer(
            $orderId, 'processing', 'pesanan', 'Pesanan Sedang Diproses',
            'Pesanan #' . $orderId . ' sedang disiapkan oleh pegawai.'
        );
    }

    public static function ready($orderId): void
    {
        self::buyer(
            $orderId, 'ready', 'pesanan', 'Pesanan Siap Diambil',
            'Pesanan #' . $orderId . ' sudah siap. Tunjukkan QR pengambilan kepada pegawai.'
        );
    }

    public static function pickedUp($orderId): void
    {
        self::buyer(
            $orderId, 'picked-up', 'pengambilan', 'Pesanan Sudah Diambil',
            'Pengambilan pesanan #' . $orderId . ' berhasil diverifikasi. Terima kasih!'
        );
    }

    public static function cancelled($orderId): void
    {
        self::buyer(
            $orderId, 'cancelled', 'pembatalan', 'Pesanan Dibatalkan',
            'Pesanan #' . $orderId . ' dibatalkan. Jika belum dibayar, stok telah dilepas kembali.'
        );
    }
}
