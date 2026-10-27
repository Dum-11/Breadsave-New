<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\KeranjangItem;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    // Menampilkan daftar pesanan secara umum (admin / umum)
    public function index()
    {
        $pesanans = Pesanan::all();

        return view('pesanan', [
            'pesanans' => $pesanans,
            'totalPesanan' => $pesanans->count(),
            'menungguKonfirmasi' => $pesanans->where('status', 'Menunggu Konfirmasi')->count(),
            'diproses' => $pesanans->where('status', 'Diproses')->count(),
            'selesai' => $pesanans->where('status', 'Selesai')->count(),
            'dibatalkan' => $pesanans->where('status', 'Dibatalkan')->count(),
        ]);
    }

    // Menampilkan daftar pesanan khusus untuk pembeli yang sedang login
    public function indexPembeli()
    {
        // Mengambil data pesanan yang hanya milik user yang sedang login
        $pesanans = Pesanan::where('id_user', Auth::id())->get();

        return view('pembeli.pesanan', [
            'pesanans' => $pesanans,
            'totalPesanan' => $pesanans->count(),
            'menungguKonfirmasi' => $pesanans->where('status', 'Menunggu Konfirmasi')->count(),
            'diproses' => $pesanans->where('status', 'Diproses')->count(),
            'selesai' => $pesanans->where('status', 'Selesai')->count(),
            'dibatalkan' => $pesanans->where('status', 'Dibatalkan')->count(),
        ]);
    }

    // Memproses keranjang belanja pembeli menjadi pesanan (Checkout)
    public function checkout(Request $request)
    {
        $userId = Auth::id();
        
        // Ambil item keranjang berdasarkan user yang sedang login
        $items = KeranjangItem::where('id_user', $userId)->with('produk')->get();

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        // Hitung total harga pesanan
        $totalHarga = 0;
        foreach ($items as $item) {
            $harga = $item->produk->harga_diskon ?? $item->produk->harga_asli ?? 0;
            $totalHarga += $harga * $item->jumlah;
        }

        // Buat pesanan baru dengan status 'Menunggu Konfirmasi' (agar masuk ke panel pegawai)
        Pesanan::create([
            'id_user' => $userId,
            'tanggal_pesan' => now(),
            'total_harga' => $totalHarga,
            'status' => 'Menunggu Konfirmasi',
        ]);

        // Kosongkan keranjang belanja setelah berhasil checkout
        KeranjangItem::where('id_user', $userId)->delete();

        return redirect()->route('pembeli.pesanan')->with('success', 'Pesanan berhasil dibuat dan diteruskan ke pegawai!');
    }
}