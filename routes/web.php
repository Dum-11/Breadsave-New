<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\ProfileController;

use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| HALAMAN UTAMA & PUBLIK (Saring Kadaluarsa & Stok Presisi)
|--------------------------------------------------------------------------
*/

// Beranda Utama -> Redirect ke Beranda Pembeli
Route::get('/', function () {
    return redirect()->route('pembeli.beranda');
})->name('home');

// Beranda Pembeli -> Hanya Tampilkan Roti Aktif & Belum Kadaluarsa
Route::get('/pembeli/beranda', function () {
    $produks = Produk::where('stok', '>', 0)
        ->where(function ($query) {
            $query->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
        })
        ->latest()
        ->get();

    return view('pembeli.beranda', compact('produks'));
})->name('pembeli.beranda');

// Katalog Produk -> Redirect ke Katalog Pembeli
Route::get('/katalog', function () {
    return redirect()->route('pembeli.katalog');
})->name('katalog');

// Katalog Pembeli -> Hanya Tampilkan Roti Aktif & Belum Kadaluarsa
Route::get('/pembeli/katalog', function () {
    $produks = Produk::where('stok', '>', 0)
        ->where(function ($query) {
            $query->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
        })
        ->latest()
        ->get();

    return view('pembeli.katalog', compact('produks'));
})->name('pembeli.katalog');

// Pesanan & Keranjang (Publik & Pembeli)
Route::get('/pesanan', [PesananController::class, 'indexPembeli'])->name('pesanan');
Route::get('/pembeli/pesanan', [PesananController::class, 'indexPembeli'])->name('pembeli.pesanan');

Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang');
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


    // =========================================================
    // PROFIL AKUN PEMBELI
    // =========================================================

    // Menampilkan halaman profil akun
    Route::get('/pembeli/profil', [ProfileController::class, 'index'])
        ->name('pembeli.profil');

    // Memperbarui informasi profil
    Route::put('/pembeli/profil', [ProfileController::class, 'update'])
        ->name('pembeli.profil.update');

    // Menghapus foto profil
    Route::delete('/pembeli/profil/foto', [ProfileController::class, 'deletePhoto'])
        ->name('pembeli.profil.deletePhoto');

    // Memperbarui password
    Route::put('/pembeli/profil/password', [ProfileController::class, 'updatePassword'])
        ->name('pembeli.profil.password');

});


/*
|--------------------------------------------------------------------------
| HALAMAN PEGAWAI (Menampilkan Semua Produk untuk Pengelolaan Toko)
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

    // Katalog Pegawai -> Menampilkan SEMUA produk (tanpa saring kedaluwarsa)
    Route::get('/pegawai/katalog', function () {
        $produks = Produk::latest()->get();
        return view('pegawai.katalog', compact('produks'));
    })->name('pegawai.katalog');

    Route::get('/pegawai/pesanan', function () {
        $pesanans = Pesanan::latest()->get();
        return view('pegawai.pesanan', compact('pesanans'));
    })->name('pegawai.pesanan');

    // Aksi Pesanan: UI/UX Error Prevention & Feedback
    Route::post('/pegawai/pesanan/{id}/proses', function ($id) {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status !== 'Menunggu Konfirmasi') {
            return redirect()->route('pegawai.pesanan')->with(
                'error',
                "Gagal: Pesanan #{$id} tidak dapat diproses karena statusnya saat ini '{$pesanan->status}'."
            );
        }

        $pesanan->status = 'Diproses';
        $pesanan->save();

        return redirect()->route('pegawai.pesanan')->with(
            'success',
            "Pesanan #{$id} kini sedang diproses."
        );
    })->name('pegawai.pesanan.proses');

    Route::post('/pegawai/pesanan/{id}/selesai', function ($id) {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status !== 'Diproses') {
            return redirect()->route('pegawai.pesanan')->with(
                'error',
                "Gagal: Hanya pesanan berstatus 'Diproses' yang dapat diselesaikan."
            );
        }

        $pesanan->status = 'Selesai';
        $pesanan->save();

        return redirect()->route('pegawai.pesanan')->with(
            'success',
            "Pesanan #{$id} telah berhasil diselesaikan."
        );
    })->name('pegawai.pesanan.selesai');

    Route::post('/pegawai/pesanan/{id}/batal', [PesananController::class, 'batalkanPesanan'])
        ->name('pegawai.pesanan.batal');

});


/*
|--------------------------------------------------------------------------
| HALAMAN ADMIN (Menampilkan Semua Produk untuk Management)
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

    // Katalog Admin -> Menampilkan SEMUA produk (tanpa saring kedaluwarsa)
    Route::get('/admin/katalog', function () {
        $produks = Produk::latest()->get();
        return view('admin.katalog', compact('produks'));
    })->name('admin.katalog');

    Route::get('/admin/produk/tambah', function () {
        return view('admin.tambah_produk');
    })->name('admin.produk.create');

    // Aksi CRUD Produk Admin (Dengan UI/UX Feedback)
    Route::post('/admin/produk', function (Request $request) {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:100',
            'harga_asli' => 'required|numeric|min:0',
            'harga_diskon' => 'required|numeric|min:0|lte:harga_asli',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'expired_at' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        }

        Produk::create($validated);

        return redirect()
            ->route('admin.katalog')
            ->with('success', 'Data produk baru berhasil ditambahkan ke sistem.');
    })->name('admin.produk.store');

    Route::get('/admin/produk/edit/{id}', function ($id) {
        $produk = Produk::findOrFail($id);
        return view('admin.produk_edit', compact('produk'));
    })->name('admin.produk.edit');

    Route::put('/admin/produk/update/{id}', function (Request $request, $id) {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:100',
            'harga_asli' => 'required|numeric|min:0',
            'harga_diskon' => 'required|numeric|min:0|lte:harga_asli',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'expired_at' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoLama = $produk->foto;

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($validated);

        if ($request->hasFile('foto') && $fotoLama) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($fotoLama);
        }

        return redirect()
            ->route('admin.katalog')
            ->with('success', "Data produk '{$produk->nama_produk}' berhasil diperbarui.");
    })->name('admin.produk.update');

    Route::delete('/admin/produk/hapus/{id}', function ($id) {
        $produk = Produk::findOrFail($id);
        $namaProduk = $produk->nama_produk;

        if ($produk->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()
            ->route('admin.katalog')
            ->with('success', "Produk '{$namaProduk}' telah dihapus secara permanen.");
    })->name('admin.produk.destroy');

    Route::get('/admin/kelola-akun', function () {
        $users = User::all();
        return view('admin.kelola_akun', compact('users'));
    })->name('admin.kelola_akun');

    // Manajemen Akun: Error Prevention & Feedback Deskriptif
    Route::put('/admin/kelola-akun/{id}/role', function (Request $request, $id) {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with(
                'error',
                'Keamanan: Anda tidak diperbolehkan mengubah role akun Anda sendiri.'
            );
        }

        $request->validate([
            'role' => 'required|in:admin,pegawai,pembeli'
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with(
            'success',
            "Peran untuk pengguna {$user->email} berhasil diubah menjadi {$request->role}."
        );
    })->name('admin.kelola_akun.role');

    Route::put('/admin/kelola-akun/{id}/status', function ($id) {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with(
                'error',
                'Keamanan: Anda tidak diperbolehkan memblokir akun Anda sendiri.'
            );
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusText = $user->is_active
            ? 'diaktifkan kembali'
            : 'diblokir / dinonaktifkan';

        return redirect()->back()->with(
            'success',
            "Status akun {$user->email} berhasil {$statusText}."
        );
    })->name('admin.kelola_akun.status');

    Route::get('/admin/laporan', function () {
        $pesanans = Pesanan::latest()->get();
        return view('admin.laporan', compact('pesanans'));
    })->name('admin.laporan');

});
