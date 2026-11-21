<?php

namespace App\Http\Middleware;

use App\Services\HargaDinamisService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Simpan ulang harga manual ketika admin benar-benar mengubah field harga.
 * Mengedit nama, foto, atau stok tidak boleh menimpa harga dasar yg disimpan.
 */
class SyncManualProductDiscount
{
    public function handle(Request $request, Closure $next)
    {
        $id = (int) $request->route('id');
        $sebelum = $id > 0 ? DB::table('produks')->where('id_produk', $id)->first() : null;

        $response = $next($request);
        if (!$sebelum || $response->getStatusCode() >= 400 ||
            !$request->has('harga_diskon') || $request->session()->has('errors')) {
            return $response;
        }

        $sesudah = DB::table('produks')->where('id_produk', $id)->first();
        if (!$sesudah) {
            return $response;
        }

        // Jika admin hanya mengubah deskripsi/foto/stok, baseline tidak berubah.
        if (abs((float) $sebelum->harga_diskon - (float) $sesudah->harga_diskon) < 0.005) {
            return $response;
        }

        DB::table('produks')->where('id_produk', $id)->update([
            'harga_diskon_dasar' => $sesudah->harga_diskon,
        ]);
        HargaDinamisService::sinkronkan(false, $id);
        return $response;
    }
}
