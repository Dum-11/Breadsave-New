<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        // Mengambil semua data produk dari database
        $produks = Produk::all(); 
        // Menampilkan halaman utama (index) dan mengirim data produk
        return view('index', compact('produks'));
    }
}