<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use App\Services\PickupSlotService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PickupSlotController extends Controller
{
    public function list(Request $request)
    {
        abort_unless(auth()->check(), 401);
        $data = $request->validate(['tanggal' => 'required|date_format:Y-m-d']);
        $date = Carbon::createFromFormat('Y-m-d', $data['tanggal'], 'Asia/Jakarta');
        abort_if($date->lt(now()->subDay()) || $date->gt(now()->addDays(7)), 422, 'Tanggal di luar rentang.');
        return response()->json(['slots' => PickupSlotService::day($date)]);
    }

    public function index(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
        $validated = $request->validate(['tanggal' => 'nullable|date_format:Y-m-d']);
        $date = Carbon::parse($validated['tanggal'] ?? now()->toDateString(), 'Asia/Jakarta');
        $slots = PickupSlotService::day($date);
        return view('breadsaver_advanced.pickup-slots', compact('date', 'slots'));
    }

    public function save(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
        $data = $request->validate([
            'waktu' => 'required|date_format:Y-m-d\TH:i',
            'kapasitas' => 'required|integer|between:0,50',
            'aktif' => 'nullable|in:1',
        ]);
        $time = Carbon::createFromFormat('Y-m-d\TH:i', $data['waktu'], 'Asia/Jakarta');
        if (!PickupSlotService::valid($time)) return back()->with('error', 'Slot tidak valid.');
        try {
            DB::transaction(function () use ($data, $time) {
                $slot = PickupSlotService::configured($time);
                $count = PickupSlotService::booked($time);
                $capacity = (int) $data['kapasitas'];
                $enabled = isset($data['aktif']);
                if ((!$enabled || $capacity < $count) && $count > 0) {
                    throw new RuntimeException('Slot sudah memiliki ' . $count . ' pesanan aktif; kapasitas tidak boleh di bawah jumlah ini.');
                }
                DB::table('pickup_slots')->where('id', $slot->id)->update([
                    'kapasitas' => $capacity, 'aktif' => $enabled, 'updated_at' => now(),
                ]);
            }, 3);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
        ActivityLogger::record('atur_slot', 'pickup_slot', null, $data['waktu'] . ' kapasitas ' . $data['kapasitas']);
        return back()->with('success', 'Slot berhasil disimpan.');
    }
}
