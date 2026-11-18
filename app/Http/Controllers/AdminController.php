<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function beranda()
    {
        // 1. Data Statistik Utama
        $totalProduk = DB::table('produks')->count();
        $totalPesanan = DB::table('pesanans')->count();
        $totalPengguna = DB::table('users')->count();
        $totalPendapatan = DB::table('pesanans')->where('status', 'Selesai')->sum('total_harga');

        // 2. Data Pesanan Terbaru (5 Transaksi Terakhir)
        $pesananTerbaru = DB::table('pesanans')
            ->leftJoin('users', 'pesanans.id_user', '=', 'users.id_user')
            ->select('pesanans.*', 'users.name as nama_pembeli')
            ->orderBy('pesanans.created_at', 'desc')
            ->limit(5)
            ->get();

        // 3. Ringkasan Status Pesanan
        $statusCounts = [
            'menunggu'   => DB::table('pesanans')->where('status', 'Menunggu Konfirmasi')->count(),
            'diproses'   => DB::table('pesanans')->where('status', 'Diproses')->count(),
            'selesai'    => DB::table('pesanans')->where('status', 'Selesai')->count(),
            'dibatalkan' => DB::table('pesanans')->where('status', 'Dibatalkan')->count(),
        ];

        return view('admin.beranda', compact(
            'totalProduk',
            'totalPesanan',
            'totalPengguna',
            'totalPendapatan',
            'pesananTerbaru',
            'statusCounts'
        ));
    }
}