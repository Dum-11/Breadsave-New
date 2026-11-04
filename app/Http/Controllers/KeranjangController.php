<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    // Menampilkan halaman keranjang pembeli
    public function index()
    {
        $userId = Auth::id();
        
        // Murni menggunakan id_produk tanpa ada pengecekan kolom id fiktif
        $keranjangs = DB::table('keranjang_items')
                        ->leftJoin('produks', 'keranjang_items.id_produk', '=', 'produks.id_produk')
                        ->where('keranjang_items.id_user', $userId)
                        ->select(
                            'keranjang_items.*', 
                            DB::raw('COALESCE(produks.nama_produk, "Produk") as nama_produk'),
                            DB::raw('COALESCE(produks.harga_diskon, produks.harga_asli, 0) as harga_diskon'),
                            DB::raw('COALESCE(produks.harga_asli, produks.harga_diskon, 0) as harga_asli'),
                            'produks.stok',
                            'produks.expired_at'
                        )
                        ->get();
        
        return view('pembeli.keranjang', compact('keranjangs'));
    }

    // Menambahkan produk ke keranjang dari katalog atau beranda
    public function tambah($id)
    {
        $userId = Auth::id();

        // Cari produk murni berdasarkan id_produk
        $produk = DB::table('produks')
                    ->where('id_produk', $id)
                    ->first();

        if (!$produk) {
            return redirect()->back()->with('error', 'Maaf, produk tidak ditemukan.');
        }

        // Cek apakah produk yang sama sudah ada di keranjang user
        $item = DB::table('keranjang_items')
                    ->where('id_user', $userId)
                    ->where('id_produk', $id)
                    ->first();

        if ($item) {
            DB::table('keranjang_items')
                ->where('id', $item->id)
                ->increment('jumlah');
        } else {
            DB::table('keranjang_items')->insert([
                'id_user' => $userId,
                'id_produk' => $id,
                'jumlah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('pembeli.keranjang')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Menambah kuantitas produk (+) langsung di halaman keranjang
    public function tambahQty($id)
    {
        $userId = Auth::id();
        
        DB::table('keranjang_items')
            ->where('id', $id)
            ->where('id_user', $userId)
            ->increment('jumlah');
        
        return redirect()->route('pembeli.keranjang');
    }

    // Mengurangi kuantitas produk (-) langsung di halaman keranjang
    public function kurangQty($id)
    {
        $userId = Auth::id();
        
        $item = DB::table('keranjang_items')
                    ->where('id', $id)
                    ->where('id_user', $userId)
                    ->first();

        if ($item) {
            if ($item->jumlah > 1) {
                DB::table('keranjang_items')->where('id', $id)->decrement('jumlah');
            } else {
                DB::table('keranjang_items')->where('id', $id)->delete();
            }
        }
        
        return redirect()->route('pembeli.keranjang');
    }

    // Menghapus produk dari keranjang
    public function hapus($id)
    {
        $userId = Auth::id();
        
        DB::table('keranjang_items')
            ->where('id', $id)
            ->where('id_user', $userId)
            ->delete();

        return redirect()->route('pembeli.keranjang')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}