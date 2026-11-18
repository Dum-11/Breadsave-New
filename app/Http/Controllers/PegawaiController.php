<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\User;

class PegawaiController extends Controller
{
    /**
     * Menampilkan halaman beranda pegawai toko.
     */
    public function beranda()
    {
        // Visibilitas: Hitung ketersediaan produk (stok > 0 & belum kedaluwarsa)
        $produktersedia = DB::table('produks')
                            ->where('stok', '>', 0)
                            ->where('expired_at', '>=', now())
                            ->count();
       
        // PERBAIKAN BUG: Ubah 'pending' jadi 'Menunggu Konfirmasi' agar data terbaca
        $pesananPerluDiproses = DB::table('pesanans')
                                ->where('status', 'Menunggu Konfirmasi')
                                ->count();

        // Data tambahan untuk memastikan view pegawai.beranda lama tidak error
        $jumlahProduk = Produk::count();
        $jumlahPesanan = Pesanan::count();
        $pesananMenunggu = $pesananPerluDiproses;
        $pesananDiproses = Pesanan::where('status', 'Diproses')->count();
        $pesananSelesai = Pesanan::where('status', 'Selesai')->count();
        $pesananDibatalkan = Pesanan::where('status', 'Dibatalkan')->count();
        $jumlahAkun = User::count();

        return view('pegawai.beranda', [
            'produktersedia' => $produktersedia,
            'pesananPerluDiproses' => $pesananPerluDiproses,
            'jumlahProduk' => $jumlahProduk, 
            'totalProduk' => $jumlahProduk, 
            'jumlahPesanan' => $jumlahPesanan,
            'pesananMenunggu' => $pesananMenunggu, 
            'pesananBaru' => $pesananMenunggu, 
            'pesananDiproses' => $pesananDiproses,
            'pesananSelesai' => $pesananSelesai, 
            'pesananDibatalkan' => $pesananDibatalkan,
            'jumlahPesananMenunggu' => $pesananMenunggu,
            'jumlahPesananDiproses' => $pesananDiproses, 
            'jumlahPesananSelesai' => $pesananSelesai,
            'jumlahPesananDibatalkan' => $pesananDibatalkan, 
            'jumlahAkun' => $jumlahAkun, 
            'totalAkun' => $jumlahAkun
        ]);
    }
}