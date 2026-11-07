
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
| HALAMAN UTAMA
|--------------------------------------------------------------------------
*/

Route::get('/', [ProdukController::class, 'index'])
    ->name('home');


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
| KATALOG PRODUK UMUM
|--------------------------------------------------------------------------
*/

Route::get('/katalog', function () {

    $produks = Produk::latest()->get();

    return view('pembeli.katalog', compact('produks'));

})->name('katalog');


/*
|--------------------------------------------------------------------------
| PESANAN DAN KERANJANG UMUM
|--------------------------------------------------------------------------
*/

Route::get('/pesanan', [PesananController::class, 'index'])
    ->name('pesanan');

Route::get('/keranjang', function () {

    return view('keranjang');

})->name('keranjang');


/*
|--------------------------------------------------------------------------
| HALAMAN PEMBELI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Beranda Pembeli
    Route::get('/pembeli/beranda', [ProdukController::class, 'index'])
        ->name('pembeli.beranda');


    // Katalog Pembeli
    Route::get('/pembeli/katalog', [ProdukController::class, 'katalog'])
        ->name('pembeli.katalog');


    // Pesanan Pembeli
    Route::get('/pembeli/pesanan', [PesananController::class, 'indexPembeli'])
        ->name('pembeli.pesanan');


    // Keranjang Pembeli
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
| HALAMAN PEGAWAI
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // BERANDA PEGAWAI
    Route::get('/pegawai/beranda', function () {

        // Menghitung seluruh produk
        $jumlahProduk = Produk::count();

        // Menghitung seluruh pesanan
        $jumlahPesanan = Pesanan::count();


        // Menghitung pesanan menunggu konfirmasi
        $pesananMenunggu = Pesanan::where(
            'status',
            'Menunggu Konfirmasi'
        )->count();


        // Menghitung pesanan yang sedang diproses
        $pesananDiproses = Pesanan::where(
            'status',
            'Diproses'
        )->count();


        // Menghitung pesanan yang sudah selesai
        $pesananSelesai = Pesanan::where(
            'status',
            'Selesai'
        )->count();


        // Menghitung pesanan yang dibatalkan
        $pesananDibatalkan = Pesanan::where(
            'status',
            'Dibatalkan'
        )->count();


        // Menghitung seluruh akun pengguna
        $jumlahAkun = User::count();


        /*
         * Variabel tambahan untuk menyesuaikan
         * nama variabel yang digunakan pada Blade.
         */

        $totalProduk = $jumlahProduk;

        $pesananBaru = $pesananMenunggu;

        $totalAkun = $jumlahAkun;

        $jumlahPesananMenunggu = $pesananMenunggu;

        $jumlahPesananDiproses = $pesananDiproses;

        $jumlahPesananSelesai = $pesananSelesai;

        $jumlahPesananDibatalkan = $pesananDibatalkan;


        return view('pegawai.beranda', compact(

            'jumlahProduk',
            'totalProduk',

            'jumlahPesanan',

            'pesananMenunggu',
            'pesananBaru',

            'pesananDiproses',
            'pesananSelesai',
            'pesananDibatalkan',

            'jumlahPesananMenunggu',
            'jumlahPesananDiproses',
            'jumlahPesananSelesai',
            'jumlahPesananDibatalkan',

            'jumlahAkun',
            'totalAkun'

        ));

    })->name('pegawai.beranda');


    // KATALOG PRODUK PEGAWAI
    Route::get('/pegawai/katalog', function () {

        $produks = Produk::latest()->get();

        return view('pegawai.katalog', compact('produks'));

    })->name('pegawai.katalog');


    // DAFTAR PESANAN PEGAWAI
    Route::get('/pegawai/pesanan', function () {

        $pesanans = Pesanan::latest()->get();

        return view('pegawai.pesanan', compact('pesanans'));

    })->name('pegawai.pesanan');


    // PROSES PESANAN
    Route::post('/pegawai/pesanan/{id}/proses', function ($id) {

        $pesanan = Pesanan::findOrFail($id);

        $pesanan->status = 'Diproses';

        $pesanan->save();

        return redirect()
            ->route('pegawai.pesanan')
            ->with('success', 'Pesanan berhasil diproses.');

    })->name('pegawai.pesanan.proses');


    // SELESAIKAN PESANAN
    Route::post('/pegawai/pesanan/{id}/selesai', function ($id) {

        $pesanan = Pesanan::findOrFail($id);

        $pesanan->status = 'Selesai';

        $pesanan->save();

        return redirect()
            ->route('pegawai.pesanan')
            ->with('success', 'Pesanan berhasil diselesaikan.');

    })->name('pegawai.pesanan.selesai');

});


/*
|--------------------------------------------------------------------------
| HALAMAN ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // BERANDA ADMIN
    Route::get('/admin/beranda', function () {

        // Menghitung seluruh produk
        $jumlahProduk = Produk::count();

        // Menghitung seluruh pesanan
        $jumlahPesanan = Pesanan::count();

        // Menghitung seluruh akun
        $jumlahAkun = User::count();


        // Variabel tambahan untuk Blade Admin
        $totalProduk = $jumlahProduk;

        $totalPesanan = $jumlahPesanan;

        $totalPengguna = $jumlahAkun;


        return view('admin.beranda', compact(
            'jumlahProduk',
            'jumlahPesanan',
            'jumlahAkun',
            'totalProduk',
            'totalPesanan',
            'totalPengguna'
        ));

    })->name('admin.beranda');


    // KATALOG ADMIN
    Route::get('/admin/katalog', function () {

        $produks = Produk::latest()->get();

        return view('admin.katalog', compact('produks'));

    })->name('admin.katalog');


    // TAMBAH PRODUK
    Route::get('/admin/produk/tambah', [ProdukController::class, 'create'])
        ->name('admin.produk.create');


    // SIMPAN PRODUK BARU
    Route::post('/admin/produk', [ProdukController::class, 'store'])
        ->name('admin.produk.store');


    // EDIT PRODUK
    Route::get('/admin/produk/edit/{id}', [ProdukController::class, 'edit'])
        ->name('admin.produk.edit');


    // UPDATE PRODUK
    Route::put('/admin/produk/update/{id}', [ProdukController::class, 'update'])
        ->name('admin.produk.update');


    // HAPUS PRODUK
    Route::delete('/admin/produk/hapus/{id}', [ProdukController::class, 'destroy'])
        ->name('admin.produk.destroy');


    // KELOLA AKUN
    Route::get('/admin/kelola-akun', function () {

        $users = User::all();

        return view('admin.kelola_akun', compact('users'));

    })->name('admin.kelola_akun');


    // LAPORAN ADMIN
    Route::get('/admin/laporan', function () {

        $pesanans = Pesanan::latest()->get();

        return view('admin.laporan', compact('pesanans'));

    })->name('admin.laporan');

});
