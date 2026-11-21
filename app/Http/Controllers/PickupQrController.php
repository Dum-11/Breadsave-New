<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PickupQrController extends Controller
{
    private function pastikanPegawai(): void
    {
        abort_unless(
            auth()->check() && in_array(auth()->user()->role, ['pegawai', 'admin'], true),
            403,
            'Hanya pegawai atau admin yang dapat memverifikasi pengambilan.'
        );
    }

    private function kode(Request $request): string
    {
        $request->merge([
            'kode_pickup' => strtoupper(trim((string) $request->input('kode_pickup', ''))),
        ]);

        $validated = $request->validate([
            'kode_pickup' => ['required', 'string', 'regex:/^BS-[A-Z0-9]{6}$/'],
        ]);

        return $validated['kode_pickup'];
    }

    private function kesalahan(Pesanan $pesanan): ?string
    {
        if ($pesanan->status === 'Dibatalkan') {
            return 'Pesanan telah dibatalkan.';
        }
        if (!empty($pesanan->picked_up_at)) {
            return 'QR sudah digunakan. Pesanan ini sudah diambil.';
        }
        if (!$pesanan->pembayaran || $pesanan->pembayaran->status !== 'Berhasil') {
            return 'Pembayaran belum berhasil. Pesanan tidak boleh diserahkan.';
        }
        if ($pesanan->status !== 'Selesai') {
            return 'Roti belum siap diambil. Pegawai harus menandainya Siap Diambil terlebih dahulu.';
        }
        return null;
    }

    public function check(Request $request): JsonResponse
    {
        $this->pastikanPegawai();
        $code = $this->kode($request);

        $kandidat = Pesanan::with(['user', 'pembayaran'])
            ->where('kode_pickup', $code)
            ->limit(2)
            ->get();

        if ($kandidat->count() > 1) {
            return response()->json(['ok' => false, 'message' => 'Kode pickup duplikat. Hubungi admin.'], 409);
        }
        $pesanan = $kandidat->first();
        if (!$pesanan || !hash_equals((string) $pesanan->kode_pickup, $code)) {
            return response()->json(['ok' => false, 'message' => 'Kode pickup tidak ditemukan.'], 404);
        }
        if ($error = $this->kesalahan($pesanan)) {
            return response()->json(['ok' => false, 'message' => $error], 409);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Kode valid. Pesanan siap diambil.',
            'pesanan' => [
                'id_pesanan' => $pesanan->id_pesanan,
                'pembeli' => $pesanan->user->nama ?? $pesanan->user->name ?? 'Pembeli',
                'total' => 'Rp ' . number_format((float) $pesanan->total_harga, 0, ',', '.'),
            ],
        ]);
    }

    public function confirm(Request $request): JsonResponse
    {
        $this->pastikanPegawai();
        $code = $this->kode($request);

        // Kunci baris pesanan agar dua scan bersamaan tidak bisa mencatat pengambilan dua kali.
        return DB::transaction(function () use ($code): JsonResponse {
            $kandidat = Pesanan::where('kode_pickup', $code)
                ->lockForUpdate()
                ->limit(2)
                ->get();

            if ($kandidat->count() > 1) {
                return response()->json(['ok' => false, 'message' => 'Kode pickup duplikat. Hubungi admin.'], 409);
            }
            $pesanan = $kandidat->first();
            if (!$pesanan || !hash_equals((string) $pesanan->kode_pickup, $code)) {
                return response()->json(['ok' => false, 'message' => 'Kode pickup tidak ditemukan.'], 404);
            }

            $pesanan->load(['pembayaran']);
            if ($error = $this->kesalahan($pesanan)) {
                return response()->json(['ok' => false, 'message' => $error], 409);
            }

            $pesanan->picked_up_at = now();
            $pesanan->save();

            return response()->json([
                'ok' => true,
                'message' => "Pesanan #{$pesanan->id_pesanan} sudah diambil. QR tidak bisa digunakan kembali.",
            ]);
        });
    }
}
