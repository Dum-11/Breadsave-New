<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    // Memproses keranjang belanja pembeli menjadi pesanan (Checkout dengan proteksi stok / Race Condition)
    public function checkout(Request $request)
    {
        $userId = Auth::id();
        
        // Menggunakan Database Transaction agar aman dari tabrakan stok bersamaan
        return DB::transaction(function () use ($userId) {
            
            // 1. Ambil item keranjang berdasarkan user yang sedang login
            $items = DB::table('keranjang_items')->where('id_user', $userId)->get();

            if ($items->isEmpty()) {
                return redirect()->back()->with('error', 'Keranjang belanja Anda masih kosong.');
            }

            $totalHarga = 0;

            // 2. Lakukan validasi dan penguncian stok per produk (lockForUpdate)
            foreach ($items as $item) {
                // Ambil data produk terbaru dari tabel 'produks' dengan lock
                $produk = DB::table('produks')
                            ->where('id_produk', $item->id_produk)
                            ->lockForUpdate()
                            ->first();

                // Cek apakah produk ada, belum kedaluwarsa, dan stok mencukupi
                if (!$produk || $produk->expired_at < now() || $produk->stok < $item->jumlah) {
                    $namaProduk = $produk->nama_produk ?? 'Produk';
                    return redirect()->back()->with('error', "Maaf, stok untuk produk '{$namaProduk}' tidak mencukupi, sudah habis, atau telah kedaluwarsa.");
                }

                // Hitung harga (asumsi ada kolom harga_diskon / harga_asli di tabel produks atau keranjang)
                // Sesuaikan jika tabel keranjang Anda sudah menyimpan harga secara langsung
                $harga = $produk->harga_diskon ?? $produk->harga_asli ?? 0;
                $totalHarga += $harga * $item->jumlah;
            }

            // 3. Buat pesanan baru ke tabel 'pesanans'
            $idPesanan = DB::table('pesanans')->insertGetId([
                'id_user' => $userId,
                'tanggal_pesan' => now(),
                'total_harga' => $totalHarga,
                'status' => 'Menunggu Konfirmasi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Kurangi stok produk secara aman dan masukkan ke detail pesanan (jika ada tabel detail_pesanans)
            foreach ($items as $item) {
                // Kurangi stok di tabel produks
                DB::table('produks')
                    ->where('id_produk', $item->id_produk)
                    ->decrement('stok', $item->jumlah);

                // Masukkan ke detail pesanan (Opsional, jika tabel detail_pesanans ada)
                // Pastikan struktur tabel detail_pesanans Anda sesuai (id_pesanan, id_produk, jumlah, dll)
                /*
                DB::table('detail_pesanans')->insert([
                    'id_pesanan' => $idPesanan,
                    'id_produk' => $item->id_produk,
                    'jumlah' => $item->jumlah,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                */
            }

            // 5. Kosongkan keranjang belanja setelah berhasil checkout
            DB::table('keranjang_items')->where('id_user', $userId)->delete();

            return redirect()->route('pembeli.pesanan')->with('success', 'Pesanan berhasil dibuat dan diteruskan ke pegawai!');
        });
    }
}