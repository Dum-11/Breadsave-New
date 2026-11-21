<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivityLogger
{
    public static function record(string $action, string $entity, ?int $entityId, string $summary): void
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['pegawai', 'admin'], true)) return;
        try {
            DB::table('employee_activity_logs')->insert([
                'id_user' => (int) auth()->id(), 'aksi' => substr($action, 0, 70),
                'entitas' => substr($entity, 0, 70), 'id_entitas' => $entityId,
                'ringkasan' => substr($summary, 0, 1000),
                'created_at' => now(), 'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Audit log tidak boleh menggagalkan pembayaran/pickup yang berhasil.
            Log::warning('Gagal menyimpan aktivitas pegawai', ['message' => $e->getMessage()]);
        }
    }
}
