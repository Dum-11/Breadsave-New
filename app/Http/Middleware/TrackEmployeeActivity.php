<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Menangkap status sebelum/sesudah aksi lama, tanpa mengubah controller lama. */
class TrackEmployeeActivity
{
    public function handle(Request $request, Closure $next)
    {
        $name = $request->route() ? $request->route()->getName() : '';
        $orderId = (int) $request->route('id');
        $productId = $orderId;
        $before = null;
        if (strpos($name, 'pegawai.pesanan.') === 0) {
            $p = DB::table('pesanans')->where('id_pesanan', $orderId)->first();
            $before = $p ? json_encode(['status' => $p->status, 'picked_up_at' => $p->picked_up_at ?? null]) : null;
        } elseif ($name === 'admin.produk.store') {
            $before = (int) DB::table('produks')->max('id_produk');
        } elseif (strpos($name, 'admin.produk.') === 0) {
            $p = DB::table('produks')->where('id_produk', $productId)->first();
            $before = $p ? json_encode(['stok' => $p->stok, 'harga_diskon' => $p->harga_diskon]) : null;
        } elseif ($name === 'pegawai.pickup.confirm') {
            $orderId = (int) DB::table('pesanans')->where('kode_pickup', strtoupper(trim((string) $request->input('kode_pickup'))))
                ->value('id_pesanan');
            $before = DB::table('pesanans')->where('id_pesanan', $orderId)->value('picked_up_at');
        }
        $response = $next($request);
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'pegawai'], true)) return $response;
        if ($response->getStatusCode() >= 400) return $response;
        if ($name === 'pegawai.pickup.confirm' && method_exists($response, 'getData')) {
            $data = $response->getData(true);
            if (empty($data['ok'])) return $response;
            $after = DB::table('pesanans')->where('id_pesanan', $orderId)->value('picked_up_at');
            if (!$before && $after) ActivityLogger::record('pickup_terverifikasi', 'pesanan', $orderId,
                'Kode pickup QR digunakan; transaksi berhasil diserahkan.');
        } elseif (strpos($name, 'pegawai.pesanan.') === 0) {
            $p = DB::table('pesanans')->where('id_pesanan', $orderId)->first();
            $after = $p ? json_encode(['status' => $p->status, 'picked_up_at' => $p->picked_up_at ?? null]) : null;
            if ($before && $after && $before !== $after) {
                ActivityLogger::record('ubah_pesanan', 'pesanan', $orderId,
                    'Sebelum: ' . $before . '; sesudah: ' . $after);
            }
        } elseif ($name === 'admin.produk.store') {
            $newProduct = DB::table('produks')->where('id_produk', '>', (int) $before)
                ->orderByDesc('id_produk')->first();
            if ($newProduct) ActivityLogger::record('tambah_produk', 'produk', (int) $newProduct->id_produk,
                'Produk baru: ' . $newProduct->nama_produk);
        } elseif (strpos($name, 'admin.produk.') === 0) {
            $p = DB::table('produks')->where('id_produk', $productId)->first();
            $after = $p ? json_encode(['stok' => $p->stok, 'harga_diskon' => $p->harga_diskon]) : null;
            if ($before !== $after) ActivityLogger::record('ubah_produk', 'produk', $productId,
                'Stok/harga berubah. Sebelum: ' . ($before ?: '-') . '; sesudah: ' . ($after ?: '-'));
        }
        return $response;
    }
}
