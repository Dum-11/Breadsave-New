<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\KeranjangController;

// Rute untuk halaman utama (katalog roti umum - Hanya tampilkan yang belum kedaluwarsa)
Route::get('/', function () {
    $produks = DB::table('produks')->where('expired_at', '>=', now())->get();
    return view('index', compact('produks'));
});

// Rute untuk halaman login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute halaman katalog produk umum (Hanya tampilkan yang belum kedaluwarsa)
Route::get('/katalog', function () {
    $produks = DB::table('produks')->where('expired_at', '>=', now())->get();
    return view('katalog', compact('produks'));
})->name('katalog');

// Rute Pesanan umum
Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan');

// Route halaman keranjang umum
Route::get('/keranjang', function () {
    return view('keranjang');
})->name('keranjang');


// =========================================================================
// RUTE BERDASARKAN HAK AKSES / ROLE (Admin, Pegawai, Pembeli)
// =========================================================================

// 1. Rute Khusus Pembeli
Route::middleware(['auth'])->group(function () {
    // DIPERBAIKI: Beranda Pembeli kini mengambil dan mengirim data $produks
    Route::get('/pembeli/beranda', function () {
        $produks = DB::table('produks')
                    ->where('stok', '>', 0)
                    ->where('expired_at', '>=', now())
                    ->get();
                    
        return view('pembeli.beranda', compact('produks'));
    })->name('pembeli.beranda');

    // Katalog Pembeli (Hanya tampilkan produk yang belum kedaluwarsa)
    Route::get('/pembeli/katalog', function () {
        $produks = DB::table('produks')
                    ->where('stok', '>', 0)
                    ->where('expired_at', '>=', now())
                    ->get();
                    
        return view('pembeli.katalog', compact('produks'));
    })->name('pembeli.katalog');

    Route::get('/pembeli/pesanan', [PesananController::class, 'indexPembeli'])->name('pembeli.pesanan');
    Route::get('/pembeli/keranjang', [KeranjangController::class, 'index'])->name('pembeli.keranjang');

    Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::post('/keranjang/tambah-qty/{id}', [KeranjangController::class, 'tambahQty'])->name('keranjang.tambahQty');
    Route::post('/keranjang/kurang-qty/{id}', [KeranjangController::class, 'kurangQty'])->name('keranjang.kurangQty');
    Route::post('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');

    Route::post('/pembeli/checkout', [PesananController::class, 'checkout'])->name('pembeli.checkout');
});


// 2. Rute Khusus Pegawai Toko
Route::middleware(['auth'])->group(function () {
    // Beranda Pegawai (Terhubung ke Controller, hitung produk tersedia yang belum kedaluwarsa)
    Route::get('/pegawai/beranda', [App\Http\Controllers\PegawaiController::class, 'beranda'])->name('pegawai.beranda');

    // Katalog Pegawai (Hanya tampilkan produk yang belum kedaluwarsa)
    Route::get('/pegawai/katalog', function () {
        $produks = DB::table('produks')->where('expired_at', '>=', now())->get();
        return view('pegawai.katalog', compact('produks'));
    })->name('pegawai.katalog');

    // Rute update stok langsung dari halaman katalog pegawai
    Route::post('/pegawai/katalog/update-stok/{id}', function (\Illuminate\Http\Request $request, $id) {
        DB::table('produks')->where('id_produk', $id)->update([
            'stok' => $request->stok
        ]);
        return redirect()->back()->with('success', 'Stok berhasil diperbarui!');
    })->name('pegawai.katalog.updateStok');

    Route::get('/pegawai/pesanan', function () {
        $pesanans = DB::table('pesanans')->get(); 
        return view('pegawai.pesanan', compact('pesanans'));
    })->name('pegawai.pesanan');

    Route::post('/pegawai/pesanan/{id}/proses', function ($id) {
        DB::table('pesanans')->where('id_pesanan', $id)->update(['status' => 'Diproses']);
        return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi dan sedang disiapkan!');
    })->name('pegawai.pesanan.proses');

    Route::post('/pegawai/pesanan/{id}/selesai', function ($id) {
        DB::table('pesanans')->where('id_pesanan', $id)->update(['status' => 'Selesai']);
        return redirect()->back()->with('success', 'Pesanan telah selesai disiapkan!');
    })->name('pegawai.pesanan.selesai');
});


// 3. Rute Khusus Admin Toko (Kelola Produk: Tambah & Edit)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/beranda', function () {
        // Admin hanya menghitung total produk aktif yang belum kedaluwarsa di beranda
        $totalProduk = DB::table('produks')->where('expired_at', '>=', now())->count();
        $totalPesanan = 0; 
        $totalPengguna = DB::table('users')->count();

        return view('admin.beranda', compact('totalProduk', 'totalPesanan', 'totalPengguna'));
    })->name('admin.beranda');

    Route::get('/admin/katalog', function () {
        $produks = DB::table('produks')->get();
        return view('admin.katalog', compact('produks'));
    })->name('admin.katalog');

    // Rute Tambah Produk Admin
    Route::get('/admin/katalog/tambah', function () {
        return view('admin.tambah_produk');
    })->name('admin.katalog.tambah');

    Route::post('/admin/katalog/simpan', function (\Illuminate\Http\Request $request) {
        DB::table('produks')->insert([
            'nama_produk' => $request->nama_produk,
            'harga_asli' => $request->harga_asli,
            'harga_diskon' => $request->harga_diskon,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'expired_at' => $request->expired_at,
        ]);
        return redirect()->route('admin.katalog')->with('success', 'Produk berhasil ditambahkan!');
    })->name('admin.katalog.simpan');

    // Rute Edit Produk Admin
    Route::get('/admin/katalog/edit/{id}', function ($id) {
        $produk = DB::table('produks')->where('id_produk', $id)->first();
        return view('admin.edit_produk', compact('produk'));
    })->name('admin.katalog.edit');

    Route::post('/admin/katalog/update/{id}', function (\Illuminate\Http\Request $request, $id) {
        DB::table('produks')->where('id_produk', $id)->update([
            'nama_produk' => $request->nama_produk,
            'harga_asli' => $request->harga_asli,
            'harga_diskon' => $request->harga_diskon,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'expired_at' => $request->expired_at,
        ]);
        return redirect()->route('admin.katalog')->with('success', 'Produk berhasil diperbarui!');
    })->name('admin.katalog.update');
    
    Route::get('/admin/kelola-akun', function () {
        return view('admin.kelola_akun');
    })->name('admin.kelola_akun');

    Route::get('/admin/laporan', function () {
        return view('admin.laporan');
    })->name('admin.laporan');
});

// 4. Rute Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');