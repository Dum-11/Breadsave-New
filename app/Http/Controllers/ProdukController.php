<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index()
    {
        // Mengambil semua data produk langsung dari tabel 'produks' di database
        $produks = DB::table('produks')->get(); 

        // Menampilkan halaman utama (index) dan mengirim data produk
        return view('index', compact('produks'));
    }
}