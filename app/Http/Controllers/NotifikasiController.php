<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
    public function feed()
    {
        $userId = Auth::id();

        $notifikasi = DB::table('notifikasi')
            ->where('id_user', $userId)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(15)
            ->get();

        $belumDibaca = DB::table('notifikasi')
            ->where('id_user', $userId)
            ->whereNull('dibaca_pada')
            ->count();

        return response()->json([
            'success' => true,
            'belum_dibaca' => $belumDibaca,
            'notifikasi' => $notifikasi,
        ]);
    }

    public function read($id)
    {
        $userId = Auth::id();

        $notifikasi = DB::table('notifikasi')
            ->where('id', $id)
            ->where('id_user', $userId)
            ->first();

        if (!$notifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.',
            ], 404);
        }

        DB::table('notifikasi')
            ->where('id', $id)
            ->where('id_user', $userId)
            ->whereNull('dibaca_pada')
            ->update([
                'dibaca_pada' => now(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi sudah dibaca.',
        ]);
    }

    public function readAll()
    {
        $userId = Auth::id();

        $jumlah = DB::table('notifikasi')
            ->where('id_user', $userId)
            ->whereNull('dibaca_pada')
            ->update([
                'dibaca_pada' => now(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi sudah dibaca.',
            'jumlah' => $jumlah,
        ]);
    }
}
