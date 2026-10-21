<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Models\Produk;

// Rute untuk halaman utama (katalog roti)
Route::get('/', [ProdukController::class, 'index']);

// Rute untuk halaman login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Rute halaman katalog produk.
// Mengambil seluruh data roti dari database dan menampilkannya
// melalui file resources/views/katalog.blade.php.
Route::get('/katalog', function () {
    $produks = Produk::all();

    return view('katalog', compact('produks'));
})->name('katalog');

/*
 * Halaman Pesanan.
 *
 * Controller dipisahkan dari file Blade agar pengambilan data database
 * tidak bercampur dengan kode tampilan.
 *
 * Dibandingkan menulis query langsung di route, controller lebih mudah
 * diuji, dikembangkan, dan digunakan kembali.
 */
Route::get('/pesanan', [PesananController::class, 'index'])
    ->name('pesanan');

// Route halaman keranjang.
Route::get('/keranjang', function () {
    return view('keranjang');
})->name('keranjang');
