<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PickupSlotService
{
    public static function valid(Carbon $time): bool
    {
        $time = $time->copy()->second(0);
        return in_array((int) $time->minute, [0, 30], true)
            && $time->format('H:i') >= '08:00' && $time->format('H:i') <= '20:00'
            && $time->gte(now()->addMinutes(30)) && $time->lte(now()->addDays(7));
    }

    public static function configured(Carbon $time)
    {
        $key = $time->format('Y-m-d H:i:00');
        DB::table('pickup_slots')->insertOrIgnore([
            'slot_start' => $key, 'kapasitas' => 5, 'aktif' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return DB::table('pickup_slots')->where('slot_start', $key)->lockForUpdate()->first();
    }

    public static function booked(Carbon $time): int
    {
        return (int) DB::table('pesanans')->where('waktu_pengambilan', $time->format('Y-m-d H:i:00'))
            ->where('status', '!=', 'Dibatalkan')->count();
    }

    /** Call inside DB::transaction; row lock serializes concurrent checkouts. */
    public static function requireCapacity(Carbon $time): void
    {
        if (!self::valid($time)) throw new RuntimeException('Pilih slot 30 menit dalam jam 08.00–20.00, maksimal 7 hari ke depan.');
        $slot = self::configured($time);
        if (!$slot->aktif || self::booked($time) >= (int) $slot->kapasitas) {
            throw new RuntimeException('Slot pengambilan sudah penuh atau ditutup. Pilih jam lain.');
        }
    }

    public static function day(Carbon $date): array
    {
        $results = [];
        for ($minutes = 8 * 60; $minutes <= 20 * 60; $minutes += 30) {
            $time = $date->copy()->startOfDay()->addMinutes($minutes);
            if (!self::valid($time)) continue;
            $key = $time->format('Y-m-d H:i:00');
            $row = DB::table('pickup_slots')->where('slot_start', $key)->first();
            $capacity = $row ? (int) $row->kapasitas : 5;
            $booked = self::booked($time);
            $results[] = [
                'time' => $time->format('Y-m-d\TH:i'), 'label' => $time->format('H:i'),
                'capacity' => $capacity, 'booked' => $booked,
                'remaining' => max(0, $capacity - $booked),
                'active' => $row ? (bool) $row->aktif : true,
            ];
        }
        return $results;
    }
}
