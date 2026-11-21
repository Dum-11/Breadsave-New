<?php

namespace App\Http\Controllers;

use App\Services\BatchInventoryService;
use App\Services\LoyaltyService;
use App\Services\PickupSlotService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RescueBoxController extends Controller
{
    private function buyer(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pembeli', 403);
    }

    public function index()
    {
        $this->buyer();
        $products = DB::table('produks')->where('stok', '>', 0)
            ->where('expired_at', '>', now()->addMinutes(30))
            ->where('expired_at', '<=', now()->addHours(72))
            ->orderBy('expired_at')->limit(20)->get();
        return view('breadsaver_advanced.rescue-box', compact('products'));
    }

    public function checkout(Request $request)
    {
        $this->buyer();
        $data = $request->validate([
            'jumlah' => 'required|integer|between:3,5',
            'waktu_pengambilan' => 'required|date',
            'voucher_kode' => 'nullable|string|max:32',
        ]);
        $pickup = Carbon::parse($data['waktu_pengambilan'], 'Asia/Jakarta')->second(0);
        $userId = (int) auth()->id();
        try {
            BatchInventoryService::reconcileCancelled();
            $orderId = DB::transaction(function () use ($data, $pickup, $userId) {
                PickupSlotService::requireCapacity($pickup);
                // Pilih acak dari kandidat FEFO yang masih layak hingga pickup.
                $candidates = DB::table('produks')->where('stok', '>', 0)
                    ->where('expired_at', '>', now())
                    ->where('expired_at', '<=', now()->addHours(72))
                    ->orderBy('expired_at')->limit(20)->lockForUpdate()->get();
                $needed = (int) $data['jumlah'];
                $basket = [];
                foreach ($candidates as $p) {
                    BatchInventoryService::syncProduct((int) $p->id_produk);
                }
                // Rescue Box diacak dari 12 kandidat terdekat; setiap produk tetap
                // mengambil batch FEFO (kedaluwarsa terdekat) pada saat dialokasi.
                $candidates = $candidates->take(12)->shuffle()->values();
                $remaining = $needed;
                while ($remaining > 0) {
                    $added = false;
                    foreach ($candidates as $p) {
                        $id = (int) $p->id_produk;
                        $used = $basket[$id]['jumlah'] ?? 0;
                        $available = min((int) $p->stok, BatchInventoryService::availableAt($id, $pickup));
                        if ($used >= $available) continue;
                        $price = (float) ($p->harga_diskon ?: $p->harga_asli);
                        if ($price <= 0) continue;
                        if (!isset($basket[$id])) $basket[$id] = ['produk' => $p, 'jumlah' => 0];
                        $basket[$id]['jumlah']++;
                        $remaining--;
                        $added = true;
                        if ($remaining <= 0) break;
                    }
                    if (!$added) break;
                }
                if ($remaining > 0) throw new RuntimeException('Stok Rescue Box tidak cukup. Coba waktu lain.');

                $totalBefore = 0.0;
                $totalAfter = 0.0;
                $details = [];
                foreach ($basket as $id => $item) {
                    $price = (float) ($item['produk']->harga_diskon ?: $item['produk']->harga_asli);
                    $before = round($price * $item['jumlah'], 2);
                    $after = round($before * 0.90, 2); // Diskon ekstra 10% dari harga diskon berjalan.
                    $totalBefore += $before;
                    $totalAfter += $after;
                    $details[] = ['id_produk' => $id, 'jumlah' => $item['jumlah'], 'subtotal' => $after];
                }
                $totalAfter = round($totalAfter, 2);
                if ($totalAfter < 1) throw new RuntimeException('Harga paket tidak valid untuk QRIS.');

                $orderId = DB::table('pesanans')->insertGetId([
                    'id_user' => $userId, 'tanggal_pesan' => now(),
                    'waktu_pengambilan' => $pickup->format('Y-m-d H:i:00'),
                    'kode_pickup' => null, 'total_harga' => $totalAfter,
                    'status' => 'Menunggu Konfirmasi',
                    'payment_expires_at' => now()->addMinutes(10),
                    'stock_restored_at' => null,
                    'created_at' => now(), 'updated_at' => now(),
                ], 'id_pesanan');
                foreach ($details as $d) {
                    DB::table('detail_pesanans')->insert([
                        'id_pesanan' => $orderId, 'id_produk' => $d['id_produk'],
                        'jumlah' => $d['jumlah'], 'subtotal' => $d['subtotal'],
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                    $changed = DB::table('produks')->where('id_produk', $d['id_produk'])
                        ->where('stok', '>=', $d['jumlah'])->decrement('stok', $d['jumlah']);
                    if (!$changed) throw new RuntimeException('Stok berubah. Coba lagi.');
                    BatchInventoryService::allocate((int) $orderId, (int) $d['id_produk'],
                        (int) $d['jumlah'], $pickup);
                }
                DB::table('pembayarans')->insert([
                    'id_pesanan' => $orderId, 'metode' => 'QRIS',
                    'status' => 'Menunggu Pembayaran', 'total_bayar' => $totalAfter,
                    'waktu_bayar' => null, 'created_at' => now(), 'updated_at' => now(),
                ]);
                DB::table('rescue_box_orders')->insert([
                    'id_pesanan' => $orderId, 'jumlah_roti' => $needed,
                    'total_sebelum' => $totalBefore, 'diskon_box' => round($totalBefore - $totalAfter, 2),
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                LoyaltyService::applyVoucher($userId, (int) $orderId, $data['voucher_kode'] ?? null);
                return $orderId;
            }, 3);
            return redirect()->route('pembeli.pembayaran.show', $orderId)
                ->with('success', 'Rescue Box berhasil dipesan. Selesaikan QRIS sebelum 10 menit.');
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
