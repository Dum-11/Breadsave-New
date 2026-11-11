<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PesananController extends Controller
{
    // Menampilkan daftar pesanan secara umum (admin / umum)
    public function index()
    {
        $pesanans = DB::table('pesanans')->get();

        return view('pesanan', [
            'pesanans' => $pesanans,
            'totalPesanan' => $pesanans->count(),
            'menungguKonfirmasi' => $pesanans->where('status', 'Menunggu Konfirmasi')->count(),
            'diproses' => $pesanans->where('status', 'Diproses')->count(),
            'selesai' => $pesanans->where('status', 'Selesai')->count(),
            'dibatalkan' => $pesanans->where('status', 'Dibatalkan')->count(),
        ]);
    }

    // Menampilkan daftar pesanan khusus untuk pembeli yang sedang login
    public function indexPembeli()
    {
        $userId = Auth::id();
        $pesanans = DB::table('pesanans')->where('id_user', $userId)->get();

        return view('pembeli.pesanan', [
            'pesanans' => $pesanans,
            'totalPesanan' => $pesanans->count(),
            'menungguKonfirmasi' => $pesanans->where('status', 'Menunggu Konfirmasi')->count(),
            'diproses' => $pesanans->where('status', 'Diproses')->count(),
            'selesai' => $pesanans->where('status', 'Selesai')->count(),
            'dibatalkan' => $pesanans->where('status', 'Dibatalkan')->count(),
        ]);
    }

    // Memproses keranjang belanja pembeli menjadi pesanan (Checkout Aman & Filter Kadaluarsa)
    public function checkout(Request $request)
    {
        $userId = Auth::id();
        
        return DB::transaction(function () use ($userId) {
            
            // 1. Deteksi nama tabel keranjang yang tersedia di database
            $namaTabelKeranjang = null;
            if (Schema::hasTable('keranjang_items')) {
                $namaTabelKeranjang = 'keranjang_items';
            } elseif (Schema::hasTable('keranjang')) {
                $namaTabelKeranjang = 'keranjang';
            } elseif (Schema::hasTable('keranjangs')) {
                $namaTabelKeranjang = 'keranjangs';
            }

            if (!$namaTabelKeranjang) {
                return redirect()->back()->with('error', 'Tabel keranjang tidak ditemukan di database.');
            }

            // 2. Ambil item keranjang berdasarkan user
            $items = DB::table($namaTabelKeranjang)->where('id_user', $userId)->get();

            if ($items->isEmpty()) {
                return redirect()->back()->with('error', 'Keranjang belanja Anda masih kosong.');
            }

            $totalHarga = 0;

            // 3. Validasi, penguncian stok, & pengecekan status kedaluwarsa per produk
            foreach ($items as $item) {
                $produk = DB::table('produks')
                            ->where('id_produk', $item->id_produk)
                            ->lockForUpdate()
                            ->first();

                // Cek apakah produk ditemukan, apakah sudah kadaluarsa (expired_at < now()), atau stok tidak mencukupi
                if (!$produk || ($produk->expired_at && $produk->expired_at < now()) || $produk->stok < $item->jumlah) {
                    $namaProduk = $produk->nama_produk ?? 'Produk';
                    return redirect()->back()->with('error', "Maaf, stok untuk produk '{$namaProduk}' tidak mencukupi, habis, atau telah kedaluwarsa.");
                }

                $harga = $produk->harga_diskon ?? $produk->harga_asli ?? 0;
                $totalHarga += $harga * $item->jumlah;
            }

            // 4. Buat pesanan baru di tabel 'pesanans'
            $idPesanan = DB::table('pesanans')->insertGetId([
                'id_user' => $userId,
                'tanggal_pesan' => now(),
                'total_harga' => $totalHarga,
                'status' => 'Menunggu Konfirmasi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 5. Simpan item ke detail_pesanans (penyesuaian kolom dinamis) & kurangi stok produk
            foreach ($items as $item) {
                if (Schema::hasTable('detail_pesanans')) {
                    $hargaSatuan = DB::table('produks')->where('id_produk', $item->id_produk)->value('harga_diskon') ?? 0;

                    $insertData = [
                        'id_pesanan' => $idPesanan,
                        'id_produk'  => $item->id_produk,
                        'jumlah'     => $item->jumlah,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Cek kolom mana yang ada di tabel detail_pesanans
                    if (Schema::hasColumn('detail_pesanans', 'harga')) {
                        $insertData['harga'] = $hargaSatuan;
                    }
                    if (Schema::hasColumn('detail_pesanans', 'subtotal')) {
                        $insertData['subtotal'] = $hargaSatuan * $item->jumlah;
                    }

                    DB::table('detail_pesanans')->insert($insertData);
                }

                DB::table('produks')
                    ->where('id_produk', $item->id_produk)
                    ->decrement('stok', $item->jumlah);
            }

            // 6. Kosongkan isi keranjang belanja user
            DB::table($namaTabelKeranjang)->where('id_user', $userId)->delete();

            return redirect()->route('pembeli.pesanan')->with('success', 'Pesanan berhasil dibuat dan diteruskan ke pegawai toko!');
        });
    }

    // Fungsi Pembatalan Pesanan oleh Pegawai Toko (Dengan Pengembalian Stok Otomatis)
    public function batalkanPesanan($id)
    {
        return DB::transaction(function () use ($id) {
            $pesanan = DB::table('pesanans')->where('id_pesanan', $id)->first();

            if (!$pesanan) {
                return redirect()->back()->with('error', 'Data pesanan tidak ditemukan.');
            }

            if ($pesanan->status === 'Dibatalkan') {
                return redirect()->back()->with('error', 'Pesanan ini sudah dibatalkan sebelumnya.');
            }

            // 1. Kembalikan stok produk jika terdapat di tabel detail_pesanans
            if (Schema::hasTable('detail_pesanans')) {
                $detailItems = DB::table('detail_pesanans')->where('id_pesanan', $pesanan->id_pesanan)->get();
                foreach ($detailItems as $item) {
                    DB::table('produks')
                        ->where('id_produk', $item->id_produk)
                        ->increment('stok', $item->jumlah);
                }
            }

            // 2. Update status pesanan menjadi Dibatalkan
            DB::table('pesanans')
                ->where('id_pesanan', $pesanan->id_pesanan)
                ->update([
                    'status' => 'Dibatalkan',
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan dan stok produk telah dikembalikan!');
        });
    }

    // Fungsi Pembatalan Pesanan oleh Pembeli (Hanya status 'Menunggu Konfirmasi')
    public function batalkanPembeli($id)
    {
        $userId = Auth::id();

        return DB::transaction(function () use ($id, $userId) {
            $pesanan = DB::table('pesanans')
                ->where('id_user', $userId)
                ->where('id_pesanan', $id)
                ->first();

            if (!$pesanan) {
                return redirect()->back()->with('error', 'Data pesanan tidak ditemukan atau bukan milik Anda.');
            }

            if ($pesanan->status !== 'Menunggu Konfirmasi') {
                return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan karena sedang diproses atau sudah selesai.');
            }

            // 1. Kembalikan stok produk jika ada di tabel detail_pesanans
            if (Schema::hasTable('detail_pesanans')) {
                $detailItems = DB::table('detail_pesanans')->where('id_pesanan', $pesanan->id_pesanan)->get();
                foreach ($detailItems as $item) {
                    DB::table('produks')
                        ->where('id_produk', $item->id_produk)
                        ->increment('stok', $item->jumlah);
                }
            }

            // 2. Ubah status pesanan menjadi Dibatalkan
            DB::table('pesanans')
                ->where('id_pesanan', $pesanan->id_pesanan)
                ->update([
                    'status' => 'Dibatalkan',
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan dan stok telah dikembalikan.');
        });
    }
}