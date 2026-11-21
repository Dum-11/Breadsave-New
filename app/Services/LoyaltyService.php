<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class LoyaltyService
{
    public static function points(int $userId): int
    {
        return max(0, (int) DB::table('loyalty_ledger')->where('id_user', $userId)->sum('poin'));
    }

    public static function rewardPickedUp(int $limit = 250): int
    {
        $orders = DB::table('pesanans as p')
            ->join('pembayarans as b', 'b.id_pesanan', '=', 'p.id_pesanan')
            ->leftJoin('loyalty_ledger as l', function ($join) {
                $join->on('l.id_pesanan', '=', 'p.id_pesanan')->where('l.event_key', 'like', 'pickup:%');
            })
            ->whereNotNull('p.picked_up_at')->where('b.status', 'Berhasil')
            ->whereNull('l.id')->orderBy('p.id_pesanan')->limit($limit)
            ->get(['p.id_pesanan', 'p.id_user', 'p.total_harga']);
        $count = 0;
        foreach ($orders as $order) {
            $points = max(1, (int) floor((float) $order->total_harga / 10000));
            $count += DB::table('loyalty_ledger')->insertOrIgnore([
                'id_user' => $order->id_user, 'id_pesanan' => $order->id_pesanan,
                'poin' => $points, 'event_key' => 'pickup:' . $order->id_pesanan,
                'keterangan' => 'Poin dari pengambilan pesanan #' . $order->id_pesanan,
                'created_at' => now(), 'updated_at' => now(),
            ]) ? 1 : 0;
        }
        return $count;
    }

    /** Call in transaction. Voucher changes totals BEFORE QRIS is generated. */
    public static function applyVoucher(int $userId, int $orderId, ?string $code): float
    {
        $code = trim((string) $code);
        if ($code === '') return 0.0;
        $v = DB::table('loyalty_vouchers')->where('id_user', $userId)
            ->where('kode', strtoupper($code))->lockForUpdate()->first();
        if (!$v || $v->dipakai_pesanan || now()->gte($v->kadaluwarsa_at)) {
            throw new RuntimeException('Voucher tidak aktif, bukan milik akun ini, atau sudah digunakan.');
        }
        $order = DB::table('pesanans')->where('id_pesanan', $orderId)
            ->where('id_user', $userId)->lockForUpdate()->first();
        if (!$order) throw new RuntimeException('Pesanan tidak ditemukan.');
        // Midtrans QRIS mengharuskan nominal minimal Rp1.
        $discount = min((float) $v->nominal, max(0.0, (float) $order->total_harga - 1.0));
        if ($discount <= 0) throw new RuntimeException('Total pesanan tidak mencukupi untuk voucher.');
        $newTotal = round((float) $order->total_harga - $discount, 2);
        DB::table('pesanans')->where('id_pesanan', $orderId)->update([
            'total_harga' => $newTotal, 'voucher_discount' => $discount, 'updated_at' => now(),
        ]);
        DB::table('pembayarans')->where('id_pesanan', $orderId)->update([
            'total_bayar' => $newTotal, 'updated_at' => now(),
        ]);
        DB::table('loyalty_vouchers')->where('id', $v->id)->update([
            'dipakai_pesanan' => $orderId, 'updated_at' => now(),
        ]);
        return $discount;
    }

    public static function exchange(int $userId): string
    {
        return DB::transaction(function () use ($userId) {
            DB::table('users')->where('id_user', $userId)->lockForUpdate()->first();
            if (self::points($userId) < 100) {
                throw new RuntimeException('Minimal 100 poin untuk menukar voucher Rp10.000.');
            }
            $code = 'BSLY-' . strtoupper(Str::random(10));
            $id = DB::table('loyalty_vouchers')->insertGetId([
                'id_user' => $userId, 'kode' => $code, 'nominal' => 10000,
                'poin_dipakai' => 100, 'kadaluwarsa_at' => now()->addDays(30),
                'created_at' => now(), 'updated_at' => now(),
            ]);
            DB::table('loyalty_ledger')->insert([
                'id_user' => $userId, 'id_pesanan' => null,
                'poin' => -100, 'event_key' => 'exchange:' . $id,
                'keterangan' => 'Tukar 100 poin untuk voucher Rp10.000',
                'created_at' => now(), 'updated_at' => now(),
            ]);
            return $code;
        }, 3);
    }

    public static function releaseCancelledVouchers(): int
    {
        $vouchers = DB::table('loyalty_vouchers as v')
            ->join('pesanans as p', 'p.id_pesanan', '=', 'v.dipakai_pesanan')
            ->where('p.status', 'Dibatalkan')->whereNotNull('p.stock_restored_at')
            ->get(['v.id', 'v.dipakai_pesanan']);
        $count = 0;
        foreach ($vouchers as $v) {
            $count += DB::table('loyalty_vouchers')->where('id', $v->id)
                ->where('dipakai_pesanan', $v->dipakai_pesanan)
                ->update(['dipakai_pesanan' => null, 'updated_at' => now()]);
        }
        return $count;
    }
}
