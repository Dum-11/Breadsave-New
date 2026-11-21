<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Guard tambahan: status stok tidak diubah, transaksi lama tetap berjalan. */
class RejectExpiredProducts
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $name = $request->route() ? $request->route()->getName() : null;
        $produkIds = [];
        $batas = now();
        $jumlahDalamKeranjang = [];

        if ($name === 'keranjang.tambah') {
            $id = (int) $request->route('id');
            $produkIds[] = $id;
            $jumlahDalamKeranjang[$id] = (int) DB::table('keranjang_items')
                ->where('id_user', Auth::id())->where('id_produk', $id)->sum('jumlah');
        } elseif ($name === 'keranjang.tambahQty') {
            $item = DB::table('keranjang_items')
                ->where('id', (int) $request->route('id'))
                ->where('id_user', Auth::id())
                ->first();
            if ($item) {
                $id = (int) $item->id_produk;
                $produkIds[] = $id;
                $jumlahDalamKeranjang[$id] = (int) $item->jumlah;
            }
        } elseif ($name === 'pembeli.checkout') {
            // Checkout harus valid hingga waktu pembeli benar-benar mengambil roti.
            if ($request->filled('waktu_pengambilan')) {
                try {
                    $batas = Carbon::parse($request->input('waktu_pengambilan'));
                } catch (\Throwable $e) {
                    return back()->withInput()->with('error', 'Waktu pengambilan tidak valid.');
                }
            }
            if (Schema::hasTable('keranjang_items')) {
                $produkIds = DB::table('keranjang_items')
                    ->where('id_user', Auth::id())->pluck('id_produk')->all();
            }
        } else {
            return $next($request);
        }

        if (!$produkIds) {
            return $next($request);
        }

        $produks = DB::table('produks')->whereIn('id_produk', $produkIds)->get();
        foreach ($produks as $produk) {
            if ($produk->expired_at && Carbon::parse($produk->expired_at)->lte($batas)) {
                return back()->withInput()->with(
                    'error',
                    'Produk ' . $produk->nama_produk . ' kedaluwarsa sebelum waktu pengambilan. Pilih produk lain.'
                );
            }
            if ((int) $produk->stok <= 0 ||
                (isset($jumlahDalamKeranjang[(int) $produk->id_produk]) &&
                 (int) $produk->stok <= $jumlahDalamKeranjang[(int) $produk->id_produk])) {
                return back()->with('error', 'Stok ' . $produk->nama_produk . ' tidak mencukupi.');
            }
        }
        return $next($request);
    }
}
