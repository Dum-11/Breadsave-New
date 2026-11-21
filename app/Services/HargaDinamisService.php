<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Mempertahankan diskon manual di harga_diskon_dasar.
 * Sistem lama tetap membaca kolom harga_diskon sehingga katalog, keranjang,
 * subtotal checkout, dan Midtrans mendapat harga yang sama.
 */
class HargaDinamisService
{
    public static function hitung($hargaAsli, $hargaDasar, $tanggalExpired, Carbon $sekarang): float
    {
        $asli = max(0, (float) $hargaAsli);
        $manual = (float) $hargaDasar;
        $dasar = $manual > 0 ? min($asli, $manual) : $asli;

        if (!$tanggalExpired || $asli <= 0) {
            return round($dasar, 2);
        }

        $menit = $sekarang->diffInMinutes(Carbon::parse($tanggalExpired), false);
        // Produk telah kedaluwarsa diblokir di checkout, bukan dihapus stoknya.
        if ($menit <= 0 || $menit > 72 * 60) {
            return round($dasar, 2);
        }

        $persen = $menit <= 24 * 60 ? 0.70 : ($menit <= 48 * 60 ? 0.50 : 0.30);
        $hargaOtomatis = round($asli * (1 - $persen), 2);
        return max(0.01, round(min($dasar, $hargaOtomatis), 2));
    }

    /** @return array{diperiksa:int,diubah:int} */
    public static function sinkronkan(bool $simulasi = false, ?int $produkId = null): array
    {
        $hasil = ['diperiksa' => 0, 'diubah' => 0];
        $query = DB::table('produks')->orderBy('id_produk');
        if ($produkId !== null) {
            $query->where('id_produk', $produkId);
        }

        $query->chunkById(100, function ($produkList) use (&$hasil, $simulasi) {
            foreach ($produkList as $produk) {
                ++$hasil['diperiksa'];
                $dasar = $produk->harga_diskon_dasar;
                // Produk baru yang belum pernah dihitung otomatis diinisialisasi.
                if ($dasar === null) {
                    $dasar = $produk->harga_diskon;
                }

                $target = self::hitung(
                    $produk->harga_asli,
                    $dasar,
                    $produk->expired_at,
                    now()
                );

                $bedaHarga = abs((float) $produk->harga_diskon - $target) >= 0.005;
                $butuhInisialisasi = $produk->harga_diskon_dasar === null;
                if (!$bedaHarga && !$butuhInisialisasi) {
                    continue;
                }
                ++$hasil['diubah'];
                if ($simulasi) {
                    continue;
                }

                // Optimistic update menghindari menimpa perubahan harga admin yang bersamaan.
                DB::table('produks')
                    ->where('id_produk', $produk->id_produk)
                    ->where('harga_diskon', $produk->harga_diskon)
                    ->where('harga_asli', $produk->harga_asli)
                    ->update([
                        'harga_diskon_dasar' => $dasar,
                        'harga_diskon' => $target,
                        'updated_at' => now(),
                    ]);
            }
        }, 'id_produk', 'id_produk');

        return $hasil;
    }
}
