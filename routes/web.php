<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\KeranjangController;

use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| HALAMAN UTAMA & PUBLIK (Tanpa Auth)
|--------------------------------------------------------------------------
*/

// Beranda (Bisa diakses tanpa login)
Route::get('/', [ProdukController::class, 'index'])
    ->name('home');
Route::get('/pembeli/beranda', [ProdukController::class, 'index'])
    ->name('pembeli.beranda'); // Alias

// Katalog Umum (Bisa diakses tanpa login)
Route::get('/katalog', function () {
    $produks = Produk::latest()->get();
    return view('pembeli.katalog', compact('produks'));
})->name('katalog');
Route::get('/pembeli/katalog', function () {
    $produks = Produk::latest()->get();
    return view('pembeli.katalog', compact('produks'));
})->name('pembeli.katalog'); // Alias

// Pesanan Umum (Bisa diakses tanpa login) - Sesuai kode awalmu
Route::get('/pesanan', [PesananController::class, 'index'])
    ->name('pesanan');

// Keranjang Umum (Bisa diakses tanpa login) - Sesuai kode awalmu
Route::get('/keranjang', function () {
    return view('keranjang');
})->name('keranjang');


/*
|--------------------------------------------------------------------------
| LOGIN DAN LOGOUT
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');


/*
|--------------------------------------------------------------------------
| HALAMAN PEMBELI (Memerlukan Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Pesanan Pembeli Khusus Login
    Route::get('/pembeli/pesanan', [PesananController::class, 'indexPembeli'])
        ->name('pembeli.pesanan');

    // Keranjang Pembeli Khusus Login
    Route::get('/pembeli/keranjang', [KeranjangController::class, 'index'])
        ->name('pembeli.keranjang');

    // Tambah Produk ke Keranjang
    Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'tambah'])
        ->name('keranjang.tambah');

    // Tambah Jumlah Produk
    Route::post('/keranjang/tambah-qty/{id}', [KeranjangController::class, 'tambahQty'])
        ->name('keranjang.tambahQty');

    // Kurangi Jumlah Produk
    Route::post('/keranjang/kurang-qty/{id}', [KeranjangController::class, 'kurangQty'])
        ->name('keranjang.kurangQty');

    // Hapus Produk dari Keranjang
    Route::post('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])
        ->name('keranjang.hapus');

    // Checkout
    Route::post('/pembeli/checkout', [PesananController::class, 'checkout'])
        ->name('pembeli.checkout');

});


/*
|--------------------------------------------------------------------------
| HALAMAN PEGAWAI (Memerlukan Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/pegawai/beranda', function () {
        $jumlahProduk = Produk::count();
        $jumlahPesanan = Pesanan::count();
        $pesananMenunggu = Pesanan::where('status', 'Menunggu Konfirmasi')->count();
        $pesananDiproses = Pesanan::where('status', 'Diproses')->count();
        $pesananSelesai = Pesanan::where('status', 'Selesai')->count();
        $pesananDibatalkan = Pesanan::where('status', 'Dibatalkan')->count();
        $jumlahAkun = User::count();

        $totalProduk = $jumlahProduk;
        $pesananBaru = $pesananMenunggu;
        $totalAkun = $jumlahAkun;
        $jumlahPesananMenunggu = $pesananMenunggu;
        $jumlahPesananDiproses = $pesananDiproses;
        $jumlahPesananSelesai = $pesananSelesai;
        $jumlahPesananDibatalkan = $pesananDibatalkan;

        return view('pegawai.beranda', compact(
            'jumlahProduk', 'totalProduk', 'jumlahPesanan',
            'pesananMenunggu', 'pesananBaru', 'pesananDiproses',
            'pesananSelesai', 'pesananDibatalkan', 'jumlahPesananMenunggu',
            'jumlahPesananDiproses', 'jumlahPesananSelesai',
            'jumlahPesananDibatalkan', 'jumlahAkun', 'totalAkun'
        ));
    })->name('pegawai.beranda');

    Route::get('/pegawai/katalog', function () {
        $produks = Produk::latest()->get();
        return view('pegawai.katalog', compact('produks'));
    })->name('pegawai.katalog');

    Route::get('/pegawai/pesanan', function () {
        $pesanans = Pesanan::latest()->get();
        return view('pegawai.pesanan', compact('pesanans'));
    })->name('pegawai.pesanan');

    Route::post('/pegawai/pesanan/{id}/proses', function ($id) {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = 'Diproses';
        $pesanan->save();
        return redirect()->route('pegawai.pesanan')->with('success', 'Pesanan berhasil diproses.');
    })->name('pegawai.pesanan.proses');

    Route::post('/pegawai/pesanan/{id}/selesai', function ($id) {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = 'Selesai';
        $pesanan->save();
        return redirect()->route('pegawai.pesanan')->with('success', 'Pesanan berhasil diselesaikan.');
    })->name('pegawai.pesanan.selesai');

});


/*
|--------------------------------------------------------------------------
| HALAMAN ADMIN (Memerlukan Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/admin/beranda', function () {
        $jumlahProduk = Produk::count();
        $jumlahPesanan = Pesanan::count();
        $jumlahAkun = User::count();

        $totalProduk = $jumlahProduk;
        $totalPesanan = $jumlahPesanan;
        $totalPengguna = $jumlahAkun;

        return view('admin.beranda', compact(
            'jumlahProduk', 'jumlahPesanan', 'jumlahAkun',
            'totalProduk', 'totalPesanan', 'totalPengguna'
        ));
    })->name('admin.beranda');

    Route::get('/admin/katalog', function () {
        $produks = Produk::latest()->get();
        return view('admin.katalog', compact('produks'));
    })->name('admin.katalog');

    Route::get('/admin/produk/tambah', [ProdukController::class, 'create'])->name('admin.produk.create');
    Route::post('/admin/produk', [ProdukController::class, 'store'])->name('admin.produk.store');
    Route::get('/admin/produk/edit/{id}', [ProdukController::class, 'edit'])->name('admin.produk.edit');
    Route::put('/admin/produk/update/{id}', [ProdukController::class, 'update'])->name('admin.produk.update');
    Route::delete('/admin/produk/hapus/{id}', [ProdukController::class, 'destroy'])->name('admin.produk.destroy');

    Route::get('/admin/kelola-akun', function () {
        $users = User::all();
        return view('admin.kelola_akun', compact('users'));
    })->name('admin.kelola_akun');

    Route::get('/admin/laporan', function () {
        $pesanans = Pesanan::latest()->get();
        return view('admin.laporan', compact('pesanans'));
    })->name('admin.laporan');

});