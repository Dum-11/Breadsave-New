<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    /**
     * Menampilkan halaman beranda pegawai toko.
     */
    public function beranda()
    {
        // Menghitung produk tersedia: stok > 0 DAN tanggal kedaluwarsa belum terlewat
        $produktersedia = DB::table('produks')
                            ->where('stok', '>', 0)
                            ->where('expired_at', '>=', now())
                            ->count();
        
        // Menghitung pesanan perlu diproses langsung dari tabel 'pesanans' database
        $pesananPerluDiproses = DB::table('pesanans')->where('status', 'pending')->count();

        // Mengirim data ke view pegawai.beranda
        return view('pegawai.beranda', compact('produktersedia', 'pesananPerluDiproses'));
    }
}