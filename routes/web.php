<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\KeranjangController;
use App\Models\Produk;

// Rute untuk halaman utama (katalog roti umum)
Route::get('/', [ProdukController::class, 'index']);

// Rute untuk halaman login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute halaman katalog produk umum (bisa diakses publik)
Route::get('/katalog', function () {
    $produks = Produk::all();
    return view('katalog', compact('produks'));
})->name('katalog');

// Rute Pesanan umum (Bisa diakses publik agar bisa menampilkan tombol/halaman login jika belum masuk)
Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan');

// Route halaman keranjang umum (Bisa diakses publik)
Route::get('/keranjang', function () {
    return view('keranjang');
})->name('keranjang');


// =========================================================================
// RUTE BERDASARKAN HAK AKSES / ROLE (Admin, Pegawai, Pembeli)
// =========================================================================

// 1. Rute Khusus Pembeli
Route::middleware(['auth'])->group(function () {
    Route::get('/pembeli/beranda', function () {
        return view('pembeli.beranda');
    })->name('pembeli.beranda');

    Route::get('/pembeli/katalog', function () {
        $produks = Produk::all();
        return view('pembeli.katalog', compact('produks'));
    })->name('pembeli.katalog');

    // REVISI: Diubah menggunakan Controller agar data pesanan sesuai user yang login dapat dimuat
    Route::get('/pembeli/pesanan', [PesananController::class, 'indexPembeli'])->name('pembeli.pesanan');

    // Rute keranjang khusus pembeli dengan awalan /pembeli/
    Route::get('/pembeli/keranjang', [KeranjangController::class, 'index'])->name('pembeli.keranjang');

    // Rute untuk menambah produk ke keranjang
    Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');

    Route::post('/keranjang/tambah-qty/{id}', [KeranjangController::class, 'tambahQty'])->name('keranjang.tambahQty');
    Route::post('/keranjang/kurang-qty/{id}', [KeranjangController::class, 'kurangQty'])->name('keranjang.kurangQty');
    Route::post('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    // -------------------------------------

    // Rute untuk proses checkout pesanan dari keranjang
    Route::post('/pembeli/checkout', [PesananController::class, 'checkout'])->name('pembeli.checkout');
});


// 2. Rute Khusus Pegawai Toko
Route::middleware(['auth'])->group(function () {
    Route::get('/pegawai/beranda', function () {
        return view('pegawai.beranda');
    })->name('pegawai.beranda');

    Route::get('/pegawai/katalog', function () {
        $produks = \App\Models\Produk::all();
        return view('pegawai.katalog', compact('produks'));
    })->name('pegawai.katalog');

    Route::get('/pegawai/pesanan', function () {
        $pesanans = \App\Models\Pesanan::all(); 
        return view('pegawai.pesanan', compact('pesanans'));
    })->name('pegawai.pesanan');

    // Rute untuk memproses konfirmasi pesanan oleh pegawai
    Route::post('/pegawai/pesanan/{id}/proses', function ($id) {
        $pesanan = \App\Models\Pesanan::findOrFail($id);
        $pesanan->status = 'Diproses';
        $pesanan->save();

        return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi dan sedang disiapkan!');
    })->name('pegawai.pesanan.proses');

    // Rute untuk mengubah status pesanan menjadi Selesai oleh pegawai
    Route::post('/pegawai/pesanan/{id}/selesai', function ($id) {
        $pesanan = \App\Models\Pesanan::findOrFail($id);
        $pesanan->status = 'Selesai';
        $pesanan->save();

        return redirect()->back()->with('success', 'Pesanan telah selesai disiapkan!');
    })->name('pegawai.pesanan.selesai');
});


// 3. Rute Khusus Admin Toko
Route::middleware(['auth'])->group(function () {
    // Rute untuk Beranda/Dashboard Admin
    Route::get('/admin/beranda', function () {
        $totalProduk = \App\Models\Produk::count();
        $totalPesanan = 0; 
        $totalPengguna = \App\Models\User::count();

        return view('admin.beranda', compact('totalProduk', 'totalPesanan', 'totalPengguna'));
    })->name('admin.beranda');

    // Rute khusus Katalog Admin
    Route::get('/admin/katalog', function () {
        $produks = \App\Models\Produk::all();
        return view('admin.katalog', compact('produks'));
    })->name('admin.katalog');
    
    Route::get('/admin/kelola-akun', function () {
        return view('admin.kelola_akun');
    })->name('admin.kelola_akun');

    Route::get('/admin/laporan', function () {
        return view('admin.laporan');
    })->name('admin.laporan');
});