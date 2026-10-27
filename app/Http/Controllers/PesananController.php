<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class PesananController extends Controller
{
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
}