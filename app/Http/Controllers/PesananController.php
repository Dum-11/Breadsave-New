<?php








namespace App\Http\Controllers;








use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;








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








    // Membuat transaksi QRIS Midtrans Sandbox
    public function buatQrisMidtrans($id)
    {
        $userId = Auth::id();








        $pesanan = DB::table('pesanans as p')
            ->leftJoin('pembayarans as b', 'p.id_pesanan', '=', 'b.id_pesanan')
            ->where('p.id_pesanan', $id)
            ->where('p.id_user', $userId)
            ->select('p.*', 'b.metode', 'b.status as status_pembayaran')
            ->first();








        abort_if(!$pesanan, 404);








        $serverKey = env('MIDTRANS_SERVER_KEY', config('services.midtrans.server_key'));








        if (empty($serverKey)) {
            return redirect()->back()->with(
                'error',
                'MIDTRANS_SERVER_KEY belum diatur di file .env.'
            );
        }








        $orderId = 'BREADSAVER-' . $pesanan->id_pesanan . '-' . time();








        $response = Http::withBasicAuth($serverKey, '')
            ->post('https://api.sandbox.midtrans.com/v2/charge', [
                'payment_type' => 'qris',
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) round($pesanan->total_harga),
                ],
                'qris' => [
                    'acquirer' => 'gopay',
                ],
            ]);








        if (!$response->successful()) {
            return redirect()->back()->with(
                'error',
                'Gagal membuat QRIS Midtrans: ' . ($response->json('status_message') ?? 'Terjadi kesalahan.')
            );
        }








        $result = $response->json();
        $qrUrl = collect($result['actions'] ?? [])
            ->firstWhere('name', 'generate-qr-code')['url'] ?? null;








        $updateData = ['updated_at' => now()];








        if (Schema::hasColumn('pembayarans', 'midtrans_order_id')) {
            $updateData['midtrans_order_id'] = $orderId;
        }








        if (Schema::hasColumn('pembayarans', 'qr_url')) {
            $updateData['qr_url'] = $qrUrl;
        }








        DB::table('pembayarans')
            ->where('id_pesanan', $id)
            ->update($updateData);








        return redirect()
            ->route('pembeli.pembayaran.show', $id)
            ->with('success', 'QRIS Midtrans berhasil dibuat. Silakan scan QR di bawah.');
    }








    // Mengecek status transaksi ke server Midtrans Sandbox
    public function cekStatusMidtrans($id)
    {
        $userId = Auth::id();








        $pesanan = DB::table('pesanans as p')
            ->leftJoin('pembayarans as b', 'p.id_pesanan', '=', 'b.id_pesanan')
            ->where('p.id_pesanan', $id)
            ->where('p.id_user', $userId)
            ->select('p.*', 'b.*')
            ->first();








        abort_if(!$pesanan, 404);








        $serverKey = env('MIDTRANS_SERVER_KEY', config('services.midtrans.server_key'));








        if (empty($serverKey) || empty($pesanan->midtrans_order_id)) {
            return redirect()->back()->with(
                'error',
                'Data transaksi Midtrans atau Server Key belum tersedia.'
            );
        }








        $response = Http::withBasicAuth($serverKey, '')
            ->get("https://api.sandbox.midtrans.com/v2/{$pesanan->midtrans_order_id}/status");








        if (!$response->successful()) {
            return redirect()->back()->with(
                'error',
                'Gagal memeriksa status ke Midtrans.'
            );
        }








        $status = $response->json('transaction_status');








        if (in_array($status, ['settlement', 'capture'])) {
            DB::table('pembayarans')
                ->where('id_pesanan', $id)
                ->update([
                    'status' => 'Berhasil',
                    'waktu_bayar' => now(),
                    'updated_at' => now(),
                ]);








            if (empty($pesanan->kode_pickup)) {
                DB::table('pesanans')
                    ->where('id_pesanan', $id)
                    ->update([
                        'kode_pickup' => 'BS-' . strtoupper(Str::random(6)),
                        'updated_at' => now(),
                    ]);
            }








            return redirect()
                ->route('pembeli.pembayaran.show', $id)
                ->with('success', 'Pembayaran berhasil dikonfirmasi oleh Midtrans!');
        }








        if (in_array($status, ['expire', 'cancel', 'deny'])) {
            DB::table('pembayarans')
                ->where('id_pesanan', $id)
                ->update([
                    'status' => 'Dibatalkan',
                    'updated_at' => now(),
                ]);








            return redirect()
                ->route('pembeli.pembayaran.show', $id)
                ->with('error', 'Transaksi pembayaran kedaluwarsa atau dibatalkan.');
        }








        return redirect()
            ->route('pembeli.pembayaran.show', $id)
            ->with('error', "Status transaksi saat ini: {$status}. Selesaikan pembayaran terlebih dahulu.");
    }








    // ==========================================
    // 4. PEMBATALAN OLEH PEGAWAI / ADMIN
    // ==========================================








    public function batalkanPesanan($id)
    {
        return DB::transaction(function () use ($id) {








            $pesanan = DB::table('pesanans')
                ->where('id_pesanan', $id)
                ->lockForUpdate()
                ->first();








            if (!$pesanan) {
                return redirect()->back()->with(
                    'error',
                    'Pesanan tidak ditemukan.'
                );
            }








            if ($pesanan->status === 'Dibatalkan') {
                return redirect()->back()->with(
                    'error',
                    'Pesanan sudah dibatalkan.'
                );
            }








            if ($pesanan->status === 'Selesai') {
                return redirect()->back()->with(
                    'error',
                    'Pesanan yang sudah selesai tidak dapat dibatalkan.'
                );
            }








            // Kembalikan stok produk
            $detailItems = DB::table('detail_pesanans')
                ->where('id_pesanan', $id)
                ->get();








            foreach ($detailItems as $item) {
                DB::table('produks')
                    ->where('id_produk', $item->id_produk)
                    ->increment('stok', $item->jumlah);
            }








            // Ubah status pesanan
            DB::table('pesanans')
                ->where('id_pesanan', $id)
                ->update([
                    'status' => 'Dibatalkan',
                    'updated_at' => now(),
                ]);








            // Batalkan pembayaran jika masih pending
            DB::table('pembayarans')
                ->where('id_pesanan', $id)
                ->where('status', 'Menunggu Pembayaran')
                ->update([
                    'status' => 'Dibatalkan',
                    'updated_at' => now(),
                ]);








            return redirect()->back()->with(
                'success',
                'Pesanan dibatalkan dan stok berhasil dikembalikan.'
            );








        }, 3);
    }








    // ==========================================
    // 5. PEMBATALAN OLEH PEMBELI
    // ==========================================








    public function batalkanPembeli($id)
    {
        $userId = Auth::id();








        return DB::transaction(function () use (
            $id,
            $userId
        ) {








            $pesanan = DB::table('pesanans')
                ->where('id_user', $userId)
                ->where('id_pesanan', $id)
                ->lockForUpdate()
                ->first();








            if (!$pesanan) {
                return redirect()->back()->with(
                    'error',
                    'Pesanan tidak ditemukan atau bukan milik Anda.'
                );
            }








            // Hanya pesanan belum diproses
            if ($pesanan->status !== 'Menunggu Konfirmasi') {
                return redirect()->back()->with(
                    'error',
                    'Pesanan sudah diproses atau selesai.'
                );
            }








            // Pembeli hanya dapat membatalkan
            // pesanan yang belum dibayar
            $pembayaran = DB::table('pembayarans')
                ->where('id_pesanan', $id)
                ->lockForUpdate()
                ->first();








            if (
                $pembayaran &&
                $pembayaran->status === 'Berhasil'
            ) {
                return redirect()->back()->with(
                    'error',
                    'Pesanan sudah dibayar. Hubungi toko untuk pembatalan.'
                );
            }








            // Kembalikan stok
            $detailItems = DB::table('detail_pesanans')
                ->where('id_pesanan', $id)
                ->get();








            foreach ($detailItems as $item) {
                DB::table('produks')
                    ->where('id_produk', $item->id_produk)
                    ->increment('stok', $item->jumlah);
            }








            // Update status pesanan
            DB::table('pesanans')
                ->where('id_pesanan', $id)
                ->update([
                    'status' => 'Dibatalkan',
                    'updated_at' => now(),
                ]);








            // Update pembayaran pending
            DB::table('pembayarans')
                ->where('id_pesanan', $id)
                ->where('status', 'Menunggu Pembayaran')
                ->update([
                    'status' => 'Dibatalkan',
                    'updated_at' => now(),
                ]);








            return redirect()->back()->with(
                'success',
                'Pesanan berhasil dibatalkan. Stok telah dikembalikan.'
            );








        }, 3);
    }
}