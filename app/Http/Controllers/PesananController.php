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
            $namaTabelKeranjang = null;
            if (Schema::hasTable('keranjang_items')) {
                $namaTabelKeranjang = 'keranjang_items';
            } elseif (Schema::hasTable('keranjang')) {
                $namaTabelKeranjang = 'keranjang';
            } elseif (Schema::hasTable('keranjangs')) {
                $namaTabelKeranjang = 'keranjangs';
            }

            if (!$namaTabelKeranjang) {
                return redirect()->back()->with('error', 'Sistem gagal memproses: Tabel keranjang tidak ditemukan.');
            }

            $items = DB::table($namaTabelKeranjang)->where('id_user', $userId)->get();

            // Feedback & Error Prevention
            if ($items->isEmpty()) {
                return redirect()->back()->with('error', 'Keranjang belanja Anda masih kosong. Silakan pilih produk terlebih dahulu.');
            }

            $totalHarga = 0;

            foreach ($items as $item) {
                $produk = DB::table('produks')
                            ->where('id_produk', $item->id_produk)
                            ->lockForUpdate()
                            ->first();

                // Validasi ketat sebelum checkout diproses
                if (!$produk || ($produk->expired_at && $produk->expired_at < now()) || $produk->stok < $item->jumlah) {
                    $namaProduk = $produk->nama_produk ?? 'Produk tidak dikenal';
                    return redirect()->back()->with('error', "Checkout dibatalkan: Stok '{$namaProduk}' tidak mencukupi atau sudah kedaluwarsa.");
                }

                $harga = $produk->harga_diskon ?? $produk->harga_asli ?? 0;
                $totalHarga += $harga * $item->jumlah;
            }

            $idPesanan = DB::table('pesanans')->insertGetId([
                'id_user' => $userId,
                'tanggal_pesan' => now(),
                'total_harga' => $totalHarga,
                'status' => 'Menunggu Konfirmasi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

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

                    if (Schema::hasColumn('detail_pesanans', 'harga')) {
                        $insertData['harga'] = $hargaSatuan;
                    }
                    if (Schema::hasColumn('detail_pesanans', 'subtotal')) {
                        $insertData['subtotal'] = $hargaSatuan * $item->jumlah;
                    }

                    DB::table('detail_pesanans')->insert($insertData);
                }

                // Kurangi stok produk secara real-time
                DB::table('produks')
                    ->where('id_produk', $item->id_produk)
                    ->decrement('stok', $item->jumlah);
            }

            DB::table($namaTabelKeranjang)->where('id_user', $userId)->delete();

            return redirect()->route('pembeli.pesanan')->with('success', 'Checkout berhasil! Pesanan Anda telah diteruskan ke toko.');
        });
    }

    // Fungsi Pembatalan Pesanan oleh Pegawai Toko (Easy Reversal)
    public function batalkanPesanan($id)
    {
        return DB::transaction(function () use ($id) {
            $pesanan = DB::table('pesanans')->where('id_pesanan', $id)->first();

            if (!$pesanan) {
                return redirect()->back()->with('error', 'Gagal membatalkan: Data pesanan tidak ditemukan.');
            }
            if ($pesanan->status === 'Dibatalkan') {
                return redirect()->back()->with('error', 'Pesanan ini sudah dalam status dibatalkan.');
            }

            if (Schema::hasTable('detail_pesanans')) {
                $detailItems = DB::table('detail_pesanans')->where('id_pesanan', $pesanan->id_pesanan)->get();
                foreach ($detailItems as $item) {
                    DB::table('produks')
                        ->where('id_produk', $item->id_produk)
                        ->increment('stok', $item->jumlah);
                }
            }

            DB::table('pesanans')
                ->where('id_pesanan', $pesanan->id_pesanan)
                ->update([
                    'status' => 'Dibatalkan',
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Pesanan dibatalkan. Stok produk otomatis dikembalikan ke etalase.');
        });
    }

    // Fungsi Pembatalan Pesanan oleh Pembeli (Easy Reversal)
    public function batalkanPembeli($id)
    {
        $userId = Auth::id();

        return DB::transaction(function () use ($id, $userId) {
            $pesanan = DB::table('pesanans')
                ->where('id_user', $userId)
                ->where('id_pesanan', $id)
                ->first();

            if (!$pesanan) {
                return redirect()->back()->with('error', 'Gagal membatalkan: Pesanan tidak ditemukan atau bukan milik Anda.');
            }
            if ($pesanan->status !== 'Menunggu Konfirmasi') {
                return redirect()->back()->with('error', 'Pembatalan ditolak: Pesanan sudah diproses atau selesai.');
            }

            if (Schema::hasTable('detail_pesanans')) {
                $detailItems = DB::table('detail_pesanans')->where('id_pesanan', $pesanan->id_pesanan)->get();
                foreach ($detailItems as $item) {
                    DB::table('produks')
                        ->where('id_produk', $item->id_produk)
                        ->increment('stok', $item->jumlah);
                }
            }

            DB::table('pesanans')
                ->where('id_pesanan', $pesanan->id_pesanan)
                ->update([
                    'status' => 'Dibatalkan',
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Pesanan berhasil Anda batalkan.');
        });
    }
}