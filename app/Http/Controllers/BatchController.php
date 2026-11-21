<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use App\Services\BatchInventoryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BatchController extends Controller
{
    public function index()
    {
        abort_unless(auth()->check() && in_array(auth()->user()->role, ['admin','pegawai'], true), 403);
        $products = DB::table('produks')->orderBy('nama_produk')->get();
        $batches = DB::table('produk_batches as b')->join('produks as p', 'p.id_produk', '=', 'b.id_produk')
            ->select('b.*', 'p.nama_produk')->orderBy('b.expired_at')->limit(500)->get();
        return view('breadsaver_advanced.batches', compact('products', 'batches'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
        $data = $request->validate([
            'id_produk' => 'required|integer|exists:produks,id_produk',
            'kode_batch' => 'required|regex:/^[A-Za-z0-9_-]+$/|max:80',
            'stok' => 'required|integer|between:1,10000',
            'expired_at' => 'required|date|after:now',
        ]);
        $productId = (int) $data['id_produk'];
        try {
            DB::transaction(function () use ($data, $productId) {
                BatchInventoryService::syncProduct($productId);
                if (DB::table('produk_batches')->where('id_produk', $productId)
                    ->where('kode_batch', $data['kode_batch'])->exists()) {
                    throw new RuntimeException('Kode batch sudah dipakai untuk produk ini.');
                }
                DB::table('produk_batches')->insert([
                    'id_produk' => $productId, 'kode_batch' => strtoupper($data['kode_batch']),
                    'stok' => (int) $data['stok'],
                    'expired_at' => Carbon::parse($data['expired_at'], 'Asia/Jakarta')->format('Y-m-d H:i:s'),
                    'created_by' => auth()->id(), 'created_at' => now(), 'updated_at' => now(),
                ]);
                DB::table('produks')->where('id_produk', $productId)->increment('stok', (int) $data['stok']);
                BatchInventoryService::refreshExpiry($productId);
            }, 3);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
        ActivityLogger::record('tambah_batch', 'produk', $productId,
            'Batch ' . $data['kode_batch'] . ' stok +' . $data['stok']);
        return back()->with('success', 'Batch FEFO berhasil ditambahkan.');
    }
}
