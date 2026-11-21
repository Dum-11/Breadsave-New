<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\BreadSaverNotification;

class PesananController extends Controller
{
    // Menampilkan daftar pesanan secara umum (admin / umum)
    public function index()
    {
        $pesanans = DB::table('pesanans')->get();

        return view('pesanan', [
            'pesanans' => $pesanans,
            'totalPesanan' => $pesanans->count(),
            'menungguKonfirmasi' => $pesanans
                ->where('status', 'Menunggu Konfirmasi')->count(),
            'diproses' => $pesanans
                ->where('status', 'Diproses')->count(),
            'selesai' => $pesanans
                ->where('status', 'Selesai')->count(),
            'dibatalkan' => $pesanans
                ->where('status', 'Dibatalkan')->count(),
        ]);
    }

    // ==========================================
    // 2. DAFTAR PESANAN PEMBELI
    // ==========================================

    public function indexPembeli()
    {
        $userId = Auth::id();

        $pesanans = DB::table('pesanans as p')
            ->leftJoin('pembayarans as b', 'p.id_pesanan', '=', 'b.id_pesanan')
            ->where('p.id_user', $userId)
            ->select(
                'p.*',
                'b.metode as metode_pembayaran',
                'b.status as status_pembayaran',
                'b.total_bayar',
                'b.waktu_bayar'
            )
            ->orderBy('p.id_pesanan', 'desc')
            ->get();

        $pesanans = $pesanans->map(function ($pesanan) {
            $items = DB::table('detail_pesanans as dp')
                ->join('produks as pr', 'dp.id_produk', '=', 'pr.id_produk')
                ->where('dp.id_pesanan', $pesanan->id_pesanan)
                ->select(
                    'pr.id_produk',
                    'pr.nama_produk',
                    'pr.foto',
                    'dp.jumlah',
                    'dp.subtotal'
                )
                ->get();

            $pesanan->items_preview = $items;
            $pesanan->jumlah_item = $items->sum('jumlah');
            $pesanan->jumlah_produk = $items->count();
            $pesanan->nama_produk_utama = optional($items->first())->nama_produk;
            $pesanan->foto_produk_utama = optional($items->first())->foto;

            return $pesanan;
        });

        return view('pembeli.pesanan', [
            'pesanans' => $pesanans,
            'totalPesanan' => $pesanans->count(),
            'menungguKonfirmasi' => $pesanans
                ->where('status', 'Menunggu Konfirmasi')->count(),
            'diproses' => $pesanans
                ->where('status', 'Diproses')->count(),
            'selesai' => $pesanans
                ->where('status', 'Selesai')->count(),
            'dibatalkan' => $pesanans
                ->where('status', 'Dibatalkan')->count(),
        ]);
    }

    // ==========================================
    // 3. CHECKOUT DAN PEMBAYARAN SIMULASI
    // ==========================================

    public function checkout(Request $request)
    {
        // Pastikan hanya pembeli yang dapat checkout
        abort_unless(
            Auth::check() && Auth::user()->role === 'pembeli',
            403
        );

        $userId = Auth::id();

        // Validasi metode pembayaran dan pickup
        $data = $request->validate([
            'metode' => 'required|in:QRIS,Transfer Bank,E-Wallet',
            'waktu_pengambilan' => 'required|date',
        ]);

        // Menggunakan waktu lokal Indonesia
        $pickup = Carbon::parse(
            $data['waktu_pengambilan'],
            'Asia/Jakarta'
        );

        $sekarang = Carbon::now('Asia/Jakarta');

        // Minimal 30 menit, maksimal 7 hari
        if (
            $pickup->lt($sekarang->copy()->addMinutes(30)) ||
            $pickup->gt($sekarang->copy()->addDays(7))
        ) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Jadwal pengambilan minimal 30 menit dari sekarang dan maksimal 7 hari.'
                );
        }

        // Jam operasional pickup 08.00 - 20.00
        if (
            $pickup->format('H:i') < '08:00' ||
            $pickup->format('H:i') > '20:00'
        ) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Pengambilan hanya tersedia pukul 08.00 sampai 20.00 WIB.'
                );
        }

        return DB::transaction(function () use (
            $userId,
            $data,
            $pickup
        ) {

            // Cari tabel keranjang
            $namaTabelKeranjang = null;

            if (Schema::hasTable('keranjang_items')) {
                $namaTabelKeranjang = 'keranjang_items';
            } elseif (Schema::hasTable('keranjang')) {
                $namaTabelKeranjang = 'keranjang';
            } elseif (Schema::hasTable('keranjangs')) {
                $namaTabelKeranjang = 'keranjangs';
            }

            if (!$namaTabelKeranjang) {
                return redirect()->back()->with(
                    'error',
                    'Tabel keranjang tidak ditemukan.'
                );
            }

            // Ambil isi keranjang
            $items = DB::table($namaTabelKeranjang)
                ->where('id_user', $userId)
                ->orderBy('id_produk')
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                return redirect()->back()->with(
                    'error',
                    'Keranjang belanja masih kosong.'
                );
            }

            $totalHarga = 0;
            $detailPesanan = [];

            // ==================================
            // VALIDASI PRODUK DAN STOK
            // ==================================

            foreach ($items as $item) {

                $produk = DB::table('produks')
                    ->where('id_produk', $item->id_produk)
                    ->lockForUpdate()
                    ->first();

                if (!$produk) {
                    return redirect()->back()->with(
                        'error',
                        'Produk tidak ditemukan.'
                    );
                }

                if (
                    $item->jumlah < 1 ||
                    $produk->stok < $item->jumlah
                ) {
                    return redirect()->back()->with(
                        'error',
                        "Stok {$produk->nama_produk} tidak mencukupi."
                    );
                }

                // Produk harus masih layak saat pickup
                if (
                    $produk->expired_at &&
                    Carbon::parse(
                        $produk->expired_at,
                        'Asia/Jakarta'
                    )->lte($pickup)
                ) {
                    return redirect()->back()->with(
                        'error',
                        "Produk {$produk->nama_produk} sudah kedaluwarsa sebelum jadwal pickup."
                    );
                }

                // Harga produk
                $harga = (float) (
                    $produk->harga_diskon ??
                    $produk->harga_asli
                );

                if ($harga <= 0) {
                    return redirect()->back()->with(
                        'error',
                        "Harga {$produk->nama_produk} tidak valid."
                    );
                }

                $jumlah = (int) $item->jumlah;
                $subtotal = round($harga * $jumlah, 2);

                $totalHarga += $subtotal;

                $detailPesanan[] = [
                    'id_produk' => $produk->id_produk,
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal,
                ];
            }

            // ==================================
            // MEMBUAT PESANAN BARU
            // ==================================

            $idPesanan = DB::table('pesanans')
                ->insertGetId([
                    'id_user' => $userId,
                    'tanggal_pesan' => now(),
                    'waktu_pengambilan' => $pickup->format(
                        'Y-m-d H:i:s'
                    ),
                    'kode_pickup' => null,
                    'total_harga' => $totalHarga,
                    'status' => 'Menunggu Konfirmasi',
                    'payment_expires_at' => now()->addMinutes(10),
                    'stock_restored_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], 'id_pesanan');

            // ==================================
            // SIMPAN DETAIL DAN RESERVASI STOK
            // ==================================

            foreach ($detailPesanan as $detail) {

                DB::table('detail_pesanans')->insert([
                    'id_pesanan' => $idPesanan,
                    'id_produk' => $detail['id_produk'],
                    'jumlah' => $detail['jumlah'],
                    'subtotal' => $detail['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Reservasi stok produk
                DB::table('produks')
                    ->where('id_produk', $detail['id_produk'])
                    ->decrement(
                        'stok',
                        $detail['jumlah']
                    );
            }

            // ==================================
            // MEMBUAT PEMBAYARAN SIMULASI
            // ==================================

            DB::table('pembayarans')->insert([
                'id_pesanan' => $idPesanan,
                'metode' => $data['metode'],
                'status' => 'Menunggu Pembayaran',
                'total_bayar' => $totalHarga,
                'waktu_bayar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ==================================
            // KOSONGKAN KERANJANG
            // ==================================

            DB::table($namaTabelKeranjang)
                ->where('id_user', $userId)
                ->delete();

            return redirect()
                ->route('pembeli.pembayaran.show', $idPesanan)
                ->with(
                    'success',
                    'Silakan selesaikan pembayaran simulasi.'
                );

        }, 3);
    }

    // Menampilkan halaman pembayaran Midtrans
    public function showPembayaran($id)
    {
        $userId = Auth::id();

        $pesanan = DB::table('pesanans as p')
            ->leftJoin('pembayarans as b', 'p.id_pesanan', '=', 'b.id_pesanan')
            ->where('p.id_pesanan', $id)
            ->where('p.id_user', $userId)
            ->select(
                'p.*',
                'p.status as status_pesanan',
                'b.metode',
                'b.status as status_pembayaran',
                'b.total_bayar',
                'b.waktu_bayar'
            )
            ->first();

        abort_if(!$pesanan, 404);

        if (Schema::hasColumn('pembayarans', 'midtrans_order_id')) {
            $pembayaran = DB::table('pembayarans')->where('id_pesanan', $id)->first();
            $pesanan->midtrans_order_id = $pembayaran->midtrans_order_id ?? null;
            $pesanan->qr_url = $pembayaran->qr_url ?? null;
        } else {
            $pesanan->midtrans_order_id = null;
            $pesanan->qr_url = null;
        }

        return view('pembeli.pembayaran', compact('pesanan'));
    }

    // Buat QRIS Midtrans Sandbox dengan batas 10 menit sejak checkout.
    public function buatQrisMidtrans($id)
    {
        abort_unless(Auth::check() && Auth::user()->role === 'pembeli', 403);

        $pesanan = DB::table('pesanans as p')
            ->join('pembayarans as b', 'p.id_pesanan', '=', 'b.id_pesanan')
            ->where('p.id_pesanan', $id)
            ->where('p.id_user', Auth::id())
            ->select('p.*', 'b.metode', 'b.status as status_pembayaran',
                     'b.midtrans_order_id')
            ->first();

        abort_if(!$pesanan, 404);

        if ($pesanan->status === 'Dibatalkan' ||
            $pesanan->status_pembayaran !== 'Menunggu Pembayaran') {
            return back()->with('error', 'Pesanan tidak dapat dibayar lagi.');
        }

        if ($pesanan->payment_expires_at &&
            now()->gte(Carbon::parse($pesanan->payment_expires_at))) {
            return back()->with('error', 'Batas waktu pembayaran sudah habis.');
        }

        // Jangan membuat dua transaksi Midtrans untuk satu pesanan.
        if (!empty($pesanan->midtrans_order_id)) {
            return redirect()->route('pembeli.pembayaran.show', $id)
                ->with('success', 'QRIS sebelumnya sudah dibuat.');
        }

        $serverKey = env('MIDTRANS_SERVER_KEY', config('services.midtrans.server_key'));
        if (empty($serverKey)) {
            return back()->with('error', 'MIDTRANS_SERVER_KEY belum dikonfigurasi.');
        }

        $orderId = 'BREADSAVER-' . $pesanan->id_pesanan . '-' . Str::random(8);
        $payload = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) round($pesanan->total_harga),
            ],
            'qris' => ['acquirer' => 'gopay'],
        ];

        if ($pesanan->payment_expires_at) {
            // Kolom DATETIME mengikuti timezone Laravel (config/app.php).
            $awalCheckout = Carbon::parse($pesanan->payment_expires_at)
                ->subMinutes(10)->setTimezone('Asia/Jakarta');

            $payload['custom_expiry'] = [
                'order_time' => $awalCheckout->format('Y-m-d H:i:s O'),
                'expiry_duration' => 10,
                'unit' => 'minute',
            ];
        }

        try {
            $response = Http::timeout(15)->withBasicAuth($serverKey, '')
                ->post('https://api.sandbox.midtrans.com/v2/charge', $payload);
        } catch (\Throwable $e) {
            return back()->with('error', 'Midtrans belum dapat dihubungi. Coba lagi.');
        }

        if (!$response->successful()) {
            return back()->with('error', 'Gagal membuat QRIS Midtrans: ' .
                ($response->json('status_message') ?? 'Terjadi kesalahan.'));
        }

        $result = $response->json();
        $actions = collect($result['actions'] ?? []);
        $qrUrl = data_get($actions->firstWhere('name', 'generate-qr-code'), 'url')
            ?? data_get($actions->firstWhere('name', 'generate-qr-code-v2'), 'url');

        $updateData = [
            'midtrans_order_id' => $orderId,
            'qr_url' => $qrUrl,
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('pembayarans', 'midtrans_transaction_id')) {
            $updateData['midtrans_transaction_id'] = $result['transaction_id'] ?? null;
        }
        if (Schema::hasColumn('pembayarans', 'midtrans_status')) {
            $updateData['midtrans_status'] = $result['transaction_status'] ?? 'pending';
        }

        DB::table('pembayarans')->where('id_pesanan', $id)->update($updateData);

        return redirect()->route('pembeli.pembayaran.show', $id)
            ->with('success', 'QRIS Midtrans berhasil dibuat. Silakan scan QR.');
    }

    // Periksa STATUS transaksi existing. Jangan membuat QR baru di fungsi ini.
    public function cekStatusMidtrans($id)
    {
        abort_unless(Auth::check() && Auth::user()->role === 'pembeli', 403);

        $pesanan = DB::table('pesanans as p')
            ->join('pembayarans as b', 'p.id_pesanan', '=', 'b.id_pesanan')
            ->where('p.id_pesanan', $id)
            ->where('p.id_user', Auth::id())
            ->select('p.*', 'b.status as status_pembayaran', 'b.midtrans_order_id')
            ->first();
        abort_if(!$pesanan, 404);

        if (empty($pesanan->midtrans_order_id)) {
            return back()->with('error', 'QRIS Midtrans belum dibuat.');
        }

        $serverKey = env('MIDTRANS_SERVER_KEY', config('services.midtrans.server_key'));
        if (empty($serverKey)) {
            return back()->with('error', 'MIDTRANS_SERVER_KEY belum dikonfigurasi.');
        }

        try {
            $response = Http::timeout(15)->withBasicAuth($serverKey, '')
                ->get('https://api.sandbox.midtrans.com/v2/' .
                    rawurlencode($pesanan->midtrans_order_id) . '/status');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghubungi Midtrans. Coba lagi.');
        }

        if (!$response->successful()) {
            return back()->with('error', 'Gagal memeriksa status transaksi Midtrans.');
        }

        $status = $response->json('transaction_status');

        if (in_array($status, ['settlement', 'capture'], true)) {
            $disimpan = DB::transaction(function () use ($id, $response, $status) {
                $pesananTerkunci = DB::table('pesanans')
                    ->where('id_pesanan', $id)->lockForUpdate()->first();
                $pembayaranTerkunci = DB::table('pembayarans')
                    ->where('id_pesanan', $id)->lockForUpdate()->first();

                // Jangan mengaktifkan kembali pesanan yang stoknya telah dikembalikan.
                if (!$pesananTerkunci || !$pembayaranTerkunci ||
                    $pesananTerkunci->status === 'Dibatalkan' ||
                    $pesananTerkunci->stock_restored_at !== null) {
                    return false;
                }

                $pembaruan = ['status' => 'Berhasil', 'updated_at' => now()];
                if (!$pembayaranTerkunci->waktu_bayar) {
                    $pembaruan['waktu_bayar'] = now();
                }
                if (Schema::hasColumn('pembayarans', 'midtrans_status')) {
                    $pembaruan['midtrans_status'] = $status;
                }
                if (Schema::hasColumn('pembayarans', 'midtrans_transaction_id')) {
                    $pembaruan['midtrans_transaction_id'] = $response->json('transaction_id');
                }
                DB::table('pembayarans')->where('id_pesanan', $id)->update($pembaruan);

                // Unik per penerima: aman jika status dicek berkali-kali.
                BreadSaverNotification::paid($id);

                if (empty($pesananTerkunci->kode_pickup)) {
                    DB::table('pesanans')->where('id_pesanan', $id)->update([
                        'kode_pickup' => 'BS-' . strtoupper(Str::random(6)),
                        'updated_at' => now(),
                    ]);
                }
                return true;
            }, 3);

            if (!$disimpan) {
                return back()->with('error',
                    'Midtrans mencatat pembayaran berhasil, tetapi pesanan sudah dibatalkan. Hubungi admin untuk pemeriksaan.');
            }

            return redirect()->route('pembeli.pembayaran.show', $id)
                ->with('success', 'Pembayaran berhasil dikonfirmasi oleh Midtrans!');
        }

        if (in_array($status, ['expire', 'cancel', 'deny'], true)) {
            DB::table('pembayarans')->where('id_pesanan', $id)
                ->where('status', 'Menunggu Pembayaran')
                ->update(['status' => 'Dibatalkan', 'updated_at' => now()]);

            return redirect()->route('pembeli.pembayaran.show', $id)
                ->with('error', 'Transaksi Midtrans sudah kedaluwarsa atau dibatalkan.');
        }

        return redirect()->route('pembeli.pembayaran.show', $id)
            ->with('error', "Status Midtrans: {$status}. Pembayaran belum selesai.");
    }

    // ==========================================
    // 4. PEMBATALAN OLEH PEGAWAI / ADMIN
    // ==========================================

    // Pembatalan manual Pegawai/Admin.
    public function batalkanPesanan($id)
    {
        abort_unless(Auth::check() &&
            in_array(Auth::user()->role, ['pegawai', 'admin'], true), 403);

        $kendala = $this->cekKeamananPembatalanMidtrans($id);
        if ($kendala !== null) {
            return back()->with('error', $kendala);
        }

        return DB::transaction(function () use ($id) {
            $pesanan = DB::table('pesanans')->where('id_pesanan', $id)
                ->lockForUpdate()->first();
            if (!$pesanan) {
                return back()->with('error', 'Pesanan tidak ditemukan.');
            }
            if ($pesanan->status === 'Dibatalkan') {
                return back()->with('error', 'Pesanan sudah dibatalkan.');
            }
            if ($pesanan->status === 'Selesai') {
                return back()->with('error', 'Pesanan selesai tidak dapat dibatalkan.');
            }
            if ($pesanan->stock_restored_at !== null) {
                return back()->with('error', 'Stok pesanan ini sudah pernah dikembalikan.');
            }

            $pembayaran = DB::table('pembayarans')->where('id_pesanan', $id)
                ->lockForUpdate()->first();
            if ($pembayaran && $pembayaran->status === 'Berhasil') {
                return back()->with('error',
                    'Pembayaran sudah berhasil. Pembatalan memerlukan penanganan refund oleh admin.');
            }

            $detailItems = DB::table('detail_pesanans')
                ->where('id_pesanan', $id)->get();
            foreach ($detailItems as $item) {
                DB::table('produks')->where('id_produk', $item->id_produk)
                    ->increment('stok', $item->jumlah);
            }

            // Status dan penanda pengembalian stok disimpan dalam transaksi yg sama.
            DB::table('pesanans')->where('id_pesanan', $id)->update([
                'status' => 'Dibatalkan',
                'stock_restored_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('pembayarans')->where('id_pesanan', $id)
                ->where('status', 'Menunggu Pembayaran')->update([
                    'status' => 'Dibatalkan',
                    'updated_at' => now(),
                ]);

            BreadSaverNotification::cancelled($id);

            return back()->with('success',
                'Pesanan dibatalkan dan stok berhasil dikembalikan.');
        }, 3);
    }

    // ==========================================
    // 5. PEMBATALAN OLEH PEMBELI
    // ==========================================

    // Pembatalan manual oleh Pembeli.
    public function batalkanPembeli($id)
    {
        abort_unless(Auth::check() && Auth::user()->role === 'pembeli', 403);
        $userId = Auth::id();

        // Validasi kepemilikan sebelum melakukan pengecekan Midtrans.
        $milikPembeli = DB::table('pesanans')
            ->where('id_pesanan', $id)->where('id_user', $userId)->exists();
        abort_unless($milikPembeli, 404);

        $kendala = $this->cekKeamananPembatalanMidtrans($id);
        if ($kendala !== null) {
            return back()->with('error', $kendala);
        }

        return DB::transaction(function () use ($id, $userId) {
            $pesanan = DB::table('pesanans')
                ->where('id_user', $userId)->where('id_pesanan', $id)
                ->lockForUpdate()->first();
            if (!$pesanan) {
                return back()->with('error', 'Pesanan tidak ditemukan.');
            }
            if ($pesanan->status !== 'Menunggu Konfirmasi') {
                return back()->with('error', 'Pesanan sudah diproses atau dibatalkan.');
            }
            if ($pesanan->stock_restored_at !== null) {
                return back()->with('error', 'Stok pesanan sudah pernah dikembalikan.');
            }

            $pembayaran = DB::table('pembayarans')->where('id_pesanan', $id)
                ->lockForUpdate()->first();
            if ($pembayaran && $pembayaran->status === 'Berhasil') {
                return back()->with('error',
                    'Pesanan sudah dibayar. Hubungi toko untuk pembatalan.');
            }

            $detailItems = DB::table('detail_pesanans')
                ->where('id_pesanan', $id)->get();
            foreach ($detailItems as $item) {
                DB::table('produks')->where('id_produk', $item->id_produk)
                    ->increment('stok', $item->jumlah);
            }

            DB::table('pesanans')->where('id_pesanan', $id)->update([
                'status' => 'Dibatalkan',
                'stock_restored_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('pembayarans')->where('id_pesanan', $id)
                ->where('status', 'Menunggu Pembayaran')->update([
                    'status' => 'Dibatalkan',
                    'updated_at' => now(),
                ]);

            BreadSaverNotification::cancelled($id);

            return back()->with('success',
                'Pesanan berhasil dibatalkan. Stok telah dikembalikan.');
        }, 3);
    }

    /**
     * Sebelum stok dilepas, pastikan transaksi Midtrans yang pernah dibuat
     * sudah dalam status terminal (expired/cancel/deny).
     * Transaksi pending TIDAK BOLEH dibatalkan lokal saja.
     * Mengembalikan null jika aman, atau pesan error jika harus ditolak.
     */
    private function cekKeamananPembatalanMidtrans($id)
    {
        $pembayaran = DB::table('pembayarans')
            ->where('id_pesanan', $id)->first();
        if (!$pembayaran || empty($pembayaran->midtrans_order_id)) {
            return null;
        }

        $serverKey = env('MIDTRANS_SERVER_KEY', config('services.midtrans.server_key'));
        if (empty($serverKey)) {
            return 'Server Key Midtrans belum tersedia. Pembatalan ditunda demi keamanan stok.';
        }

        try {
            $response = Http::timeout(15)->withBasicAuth($serverKey, '')
                ->get('https://api.sandbox.midtrans.com/v2/' .
                    rawurlencode($pembayaran->midtrans_order_id) . '/status');
        } catch (\Throwable $e) {
            return 'Midtrans tidak dapat dihubungi. Pembatalan ditunda agar stok aman.';
        }

        if (!$response->successful()) {
            return 'Status Midtrans belum dapat dipastikan. Pembatalan ditunda.';
        }

        $status = $response->json('transaction_status');
        if (in_array($status, ['expire', 'cancel', 'deny'], true)) {
            return null;
        }
        if (in_array($status, ['settlement', 'capture'], true)) {
            return 'Transaksi sudah dibayar di Midtrans. Stok tidak boleh dikembalikan tanpa proses refund.';
        }
        return 'Transaksi Midtrans masih aktif atau belum pasti (' .
            ($status ?: 'unknown') . '). Tunggu kedaluwarsa atau batalkan transaksi di Midtrans dahulu.';
    }
}
