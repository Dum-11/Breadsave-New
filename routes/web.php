<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\KeranjangController;

use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| HALAMAN UTAMA & PUBLIK (Redirect Paksa ke URL /pembeli/...)
|--------------------------------------------------------------------------
*/

// Beranda Utama -> Mengalihkan ke URL /pembeli/beranda
Route::get('/', function () {
    return redirect()->route('pembeli.beranda');
})->name('home');

Route::get('/pembeli/beranda', [ProdukController::class, 'index'])->name('pembeli.beranda');

// Katalog Produk -> Mengalihkan ke URL /pembeli/katalog
Route::get('/katalog', function () {
    return redirect()->route('pembeli.katalog');
})->name('katalog');

Route::get('/pembeli/katalog', function () {
    $produks = Produk::latest()->get();
    return view('pembeli.katalog', compact('produks'));
})->name('pembeli.katalog');

// Pesanan -> Mengalihkan ke URL /pembeli/pesanan
Route::get('/pesanan', function () {
    return redirect()->route('pembeli.pesanan');
})->name('pesanan');

Route::get('/pembeli/pesanan', [PesananController::class, 'indexPembeli'])->name('pembeli.pesanan');

// Keranjang -> Mengalihkan ke URL /pembeli/keranjang
Route::get('/keranjang', function () {
    return redirect()->route('pembeli.keranjang');
})->name('keranjang');

Route::get('/pembeli/keranjang', [KeranjangController::class, 'index'])->name('pembeli.keranjang');


/*
|--------------------------------------------------------------------------
| LOGIN, LOGOUT & REGISTER
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');


/*
|--------------------------------------------------------------------------
| HALAMAN PEMBELI (Aksi Khusus yang Memerlukan Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Aksi Keranjang
    Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::post('/keranjang/tambah-qty/{id}', [KeranjangController::class, 'tambahQty'])->name('keranjang.tambahQty');
    Route::post('/keranjang/kurang-qty/{id}', [KeranjangController::class, 'kurangQty'])->name('keranjang.kurangQty');
    Route::post('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');

    // Aksi Checkout Pesanan
    Route::post('/pembeli/checkout', [PesananController::class, 'checkout'])->name('pembeli.checkout');

    // Aksi Batalkan Pesanan oleh Pembeli
    Route::post('/pembeli/pesanan/{id}/batal', [PesananController::class, 'batalkanPembeli'])->name('pembeli.pesanan.batal');

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

    Route::post('/pegawai/pesanan/{id}/batal', [PesananController::class, 'batalkanPesanan'])->name('pegawai.pesanan.batal');

});


/*
|--------------------------------------------------------------------------
| HALAMAN ADMIN (Memerlukan Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/admin/beranda', function () {
        $totalProduk = Produk::count();
        $totalPesanan = Pesanan::count();
        $totalPengguna = User::count();

        $totalPendapatan = Pesanan::where('status', 'Selesai')->sum('total_harga');
        $pesananTerbaru = Pesanan::with('user')->latest()->take(5)->get();

        $statusCounts = [
            'menunggu'   => Pesanan::where('status', 'Menunggu Konfirmasi')->count(),
            'diproses'   => Pesanan::where('status', 'Diproses')->count(),
            'selesai'    => Pesanan::where('status', 'Selesai')->count(),
            'dibatalkan' => Pesanan::where('status', 'Dibatalkan')->count(),
        ];

        return view('admin.beranda', compact(
            'totalProduk',
            'totalPesanan',
            'totalPengguna',
            'totalPendapatan',
            'pesananTerbaru',
            'statusCounts'
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

    Route::put('/admin/kelola-akun/{id}/role', function (Request $request, $id) {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        $request->validate(['role' => 'required|in:admin,pegawai,pembeli']);
        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', "Role akun {$user->email} berhasil diubah!");
    })->name('admin.kelola_akun.role');

    Route::put('/admin/kelola-akun/{id}/status', function ($id) {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat memblokir akun Anda sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'diaktifkan' : 'diblokir / dinonaktifkan';
        return redirect()->back()->with('success', "Akun {$user->email} berhasil {$statusText}!");
    })->name('admin.kelola_akun.status');

    Route::get('/admin/laporan', function () {
        $pesanans = Pesanan::latest()->get();
        return view('admin.laporan', compact('pesanans'));
    })->name('admin.laporan');

});