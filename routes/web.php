<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\MidtransAutoSyncController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\PickupQrController;
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
Route::get('/pesanan', [PesananController::class, 'indexPembeli'])
    ->name('pesanan');

Route::get('/pembeli/pesanan', [PesananController::class, 'indexPembeli'])
    ->name('pembeli.pesanan');

Route::get('/keranjang', [KeranjangController::class, 'index'])
    ->name('keranjang');

Route::get('/pembeli/keranjang', [KeranjangController::class, 'index'])
    ->name('pembeli.keranjang');


/*
|--------------------------------------------------------------------------
| LOGIN, LOGOUT & REGISTER
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store');


/*
|--------------------------------------------------------------------------
| HALAMAN PEMBELI (Aksi Khusus yang Memerlukan Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Aksi Keranjang
    Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'tambah'])
        ->name('keranjang.tambah');

    Route::post('/keranjang/tambah-qty/{id}', [KeranjangController::class, 'tambahQty'])
        ->name('keranjang.tambahQty');

    Route::post('/keranjang/kurang-qty/{id}', [KeranjangController::class, 'kurangQty'])
        ->name('keranjang.kurangQty');

    Route::post('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])
        ->name('keranjang.hapus');


    // Aksi Checkout Pesanan
    Route::post('/pembeli/checkout', [PesananController::class, 'checkout'])
        ->name('pembeli.checkout');


    // Aksi Batalkan Pesanan oleh Pembeli
    Route::post('/pembeli/pesanan/{id}/batal', [PesananController::class, 'batalkanPembeli'])
        ->name('pembeli.pesanan.batal');


    // Halaman Pembayaran & Midtrans Sandbox
    Route::get('/pembeli/pembayaran/{id}', [PesananController::class, 'showPembayaran'])
        ->name('pembeli.pembayaran.show');

    Route::post('/pembeli/midtrans/{id}/qris', [PesananController::class, 'buatQrisMidtrans'])
        ->name('pembeli.midtrans.qris');

    Route::post('/pembeli/midtrans/{id}/status', [PesananController::class, 'cekStatusMidtrans'])
        ->name('pembeli.midtrans.status');

    // AUTO-SYNC STATUS PEMBAYARAN MIDTRANS
    Route::post(
        '/pembeli/pembayaran/{id}/auto-sync',
        [MidtransAutoSyncController::class, 'check']
    )
    ->middleware('throttle:30,1')
    ->name('pembeli.pembayaran.auto-sync');


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


    // =========================================================
    // PROFIL PEGAWAI
    // =========================================================

    Route::get('/pegawai/profil', [ProfileController::class, 'index'])
        ->name('pegawai.profil');

    Route::put('/pegawai/profil', [ProfileController::class, 'update'])
        ->name('pegawai.profil.update');

    Route::delete('/pegawai/profil/foto', [ProfileController::class, 'deletePhoto'])
        ->name('pegawai.profil.deletePhoto');

    Route::put('/pegawai/profil/password', [ProfileController::class, 'updatePassword'])
        ->name('pegawai.profil.password');


    // =========================================================
    // PROFIL ADMIN
    // =========================================================

    Route::get('/admin/profil', [ProfileController::class, 'index'])
        ->name('admin.profil');

    Route::put('/admin/profil', [ProfileController::class, 'update'])
        ->name('admin.profil.update');

    Route::delete('/admin/profil/foto', [ProfileController::class, 'deletePhoto'])
        ->name('admin.profil.deletePhoto');

    Route::put('/admin/profil/password', [ProfileController::class, 'updatePassword'])
        ->name('admin.profil.password');


    // =========================================================
    // RATING DAN ULASAN PRODUK PEMBELI
    // =========================================================

    // Menampilkan form rating
    Route::get('/pembeli/pesanan/{id}/rating', [RatingController::class, 'create'])
        ->name('pembeli.rating.form');

    // Menyimpan rating dan ulasan
    Route::post('/pembeli/pesanan/{id}/rating', [RatingController::class, 'store'])
        ->name('pembeli.rating.store');

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


    // Katalog Pegawai -> Menampilkan SEMUA produk
    Route::get('/pegawai/katalog', function () {
        $produks = Produk::latest()->get();

        return view('pegawai.katalog', compact('produks'));
    })->name('pegawai.katalog');


    // Halaman Pesanan Pegawai
    Route::get('/pegawai/pesanan', function () {
        abort_unless(
            in_array(auth()->user()->role, ['pegawai', 'admin'], true),
            403
        );

        $pesanans = Pesanan::with('user')->latest()->get();

        $idPesananLunas = DB::table('pembayarans')
            ->where('status', 'Berhasil')
            ->pluck('id_pesanan')
            ->map(fn ($id) => (string) $id)
            ->all();

        return view('pegawai.pesanan', compact(
            'pesanans',
            'idPesananLunas'
        ));
    })->name('pegawai.pesanan');


    // =========================================================
    // SCANNER QR CODE PENGAMBILAN PESANAN
    // =========================================================

    // Memeriksa kode QR, status pesanan, dan pembayaran
    Route::post('/pegawai/pesanan/pickup/check', [PickupQrController::class, 'check'])
        ->middleware('throttle:30,1')
        ->name('pegawai.pickup.check');


    // Konfirmasi pengambilan setelah kode QR dinyatakan valid
    Route::post('/pegawai/pesanan/pickup/confirm', [PickupQrController::class, 'confirm'])
        ->middleware('throttle:20,1')
        ->name('pegawai.pickup.confirm');


    // =========================================================
    // AKSI PENGELOLAAN PESANAN PEGAWAI
    // =========================================================

    // Mengubah Menunggu Konfirmasi -> Diproses
    Route::post('/pegawai/pesanan/{id}/proses', function ($id) {
        abort_unless(
            in_array(auth()->user()->role, ['pegawai', 'admin'], true),
            403
        );

        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status !== 'Menunggu Konfirmasi') {
            return redirect()->route('pegawai.pesanan')
                ->with('error', 'Pesanan tidak dapat diproses karena statusnya tidak sesuai.');
        }

        $sudahBayar = DB::table('pembayarans')
            ->where('id_pesanan', $pesanan->id_pesanan)
            ->where('status', 'Berhasil')
            ->exists();

        if (!$sudahBayar) {
            return redirect()->route('pegawai.pesanan')
                ->with('error', "Pesanan #{$id} belum dibayar. Proses ditolak.");
        }

        $pesanan->status = 'Diproses';
        $pesanan->save();

        return redirect()->route('pegawai.pesanan')
            ->with('success', "Pesanan #{$id} kini sedang diproses.");
    })->name('pegawai.pesanan.proses');


    // Mengubah Diproses -> Selesai (Siap Diambil)
    Route::post('/pegawai/pesanan/{id}/selesai', function ($id) {
        abort_unless(
            in_array(auth()->user()->role, ['pegawai', 'admin'], true),
            403
        );

        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status !== 'Diproses') {
            return redirect()->route('pegawai.pesanan')
                ->with('error', 'Hanya pesanan yang sedang diproses dapat diselesaikan.');
        }

        $sudahBayar = DB::table('pembayarans')
            ->where('id_pesanan', $pesanan->id_pesanan)
            ->where('status', 'Berhasil')
            ->exists();

        if (!$sudahBayar) {
            return redirect()->route('pegawai.pesanan')
                ->with('error', "Pesanan #{$id} belum dibayar. Tidak bisa ditandai siap diambil.");
        }

        $pesanan->status = 'Selesai';
        $pesanan->save();

        return redirect()->route('pegawai.pesanan')
            ->with('success', "Pesanan #{$id} siap diambil.");
    })->name('pegawai.pesanan.selesai');


    // Batalkan Pesanan
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

        $totalPendapatan = Pesanan::where('status', 'Selesai')
            ->sum('total_harga');

        $pesananTerbaru = Pesanan::with('user')
            ->latest()
            ->take(5)
            ->get();

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


    // Katalog Admin -> Menampilkan SEMUA produk
    Route::get('/admin/katalog', function () {
        $produks = Produk::latest()->get();

        return view('admin.katalog', compact('produks'));
    })->name('admin.katalog');


    Route::get('/admin/produk/tambah', function () {
        return view('admin.tambah_produk');
    })->name('admin.produk.create');


    // =========================================================
    // CRUD PRODUK ADMIN
    // =========================================================

    // Tambah Produk
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
            $validated['foto'] = $request->file('foto')
                ->store('produk', 'public');
        }

        Produk::create($validated);

        return redirect()
            ->route('admin.katalog')
            ->with('success', 'Data produk baru berhasil ditambahkan ke sistem.');
    })->name('admin.produk.store');


    // Halaman Edit Produk
    Route::get('/admin/produk/edit/{id}', function ($id) {
        $produk = Produk::findOrFail($id);

        return view('admin.produk_edit', compact('produk'));
    })->name('admin.produk.edit');


    // Update Produk
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
            $validated['foto'] = $request->file('foto')
                ->store('produk', 'public');
        }

        $produk->update($validated);

        if ($request->hasFile('foto') && $fotoLama) {
            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($fotoLama);
        }

        return redirect()
            ->route('admin.katalog')
            ->with('success', "Data produk '{$produk->nama_produk}' berhasil diperbarui.");
    })->name('admin.produk.update');


    // Hapus Produk
    Route::delete('/admin/produk/hapus/{id}', function ($id) {
        $produk = Produk::findOrFail($id);
        $namaProduk = $produk->nama_produk;

        if ($produk->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($produk->foto);
        }

        $produk->delete();

        return redirect()
            ->route('admin.katalog')
            ->with('success', "Produk '{$namaProduk}' telah dihapus secara permanen.");
    })->name('admin.produk.destroy');


    // =========================================================
    // KELOLA AKUN ADMIN
    // =========================================================

    Route::get('/admin/kelola-akun', function () {
        $users = User::all();

        return view('admin.kelola_akun', compact('users'));
    })->name('admin.kelola_akun');


    // Ubah Role Pengguna
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


    // Aktifkan / Nonaktifkan Akun
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


    // =========================================================
    // LAPORAN ADMIN
    // =========================================================

    Route::get('/admin/laporan', function () {
        $pesanans = Pesanan::latest()->get();

        return view('admin.laporan', compact('pesanans'));
    })->name('admin.laporan');

    require __DIR__ . '/notifikasi.php';
    
});
