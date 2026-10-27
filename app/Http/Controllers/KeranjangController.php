<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeranjangItem;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    // Menampilkan halaman keranjang pembeli
    public function index()
    {
        // Mengambil item keranjang milik user yang sedang login beserta relasi produknya
        $keranjangs = KeranjangItem::where('id_user', Auth::id())->with('produk')->get();
        
        return view('pembeli.keranjang', compact('keranjangs'));
    }

    // Menambahkan produk ke keranjang dari katalog
    public function tambah($id)
    {
        $userId = Auth::id();
        $produk = Produk::findOrFail($id);
        $produkId = $produk->id_produk ?? $produk->id;

        // Cek apakah produk yang sama sudah ada di keranjang user
        $item = KeranjangItem::where('id_user', $userId)
                             ->where('id_produk', $produkId)
                             ->first();

        if ($item) {
            // Jika sudah ada, tambahkan jumlahnya (quantity)
            $item->increment('jumlah');
        } else {
            // Jika belum ada, buat data baru di tabel keranjang_items
            KeranjangItem::create([
                'id_user' => $userId,
                'id_produk' => $produkId,
                'jumlah' => 1
            ]);
        }

        return redirect()->route('pembeli.keranjang')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Menambah kuantitas produk (+) langsung di halaman keranjang
    public function tambahQty($id)
    {
        $item = KeranjangItem::where('id', $id)->where('id_user', Auth::id())->first();
        if ($item) {
            $item->increment('jumlah');
        }
        return redirect()->route('pembeli.keranjang');
    }

    // Mengurangi kuantitas produk (-) langsung di halaman keranjang
    public function kurangQty($id)
    {
        $item = KeranjangItem::where('id', $id)->where('id_user', Auth::id())->first();
        if ($item) {
            if ($item->jumlah > 1) {
                $item->decrement('jumlah');
            } else {
                $item->delete(); // Jika jumlahnya 1 lalu dikurangi, otomatis item dihapus
            }
        }
        return redirect()->route('pembeli.keranjang');
    }

    // Menghapus produk dari keranjang
    public function hapus($id)
    {
        KeranjangItem::where('id', $id)->where('id_user', Auth::id())->delete();
        return redirect()->route('pembeli.keranjang')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}