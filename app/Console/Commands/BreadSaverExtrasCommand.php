<?php

namespace App\Console\Commands;

use App\Services\HargaDinamisService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/** Satu scheduler untuk harga dinamis, peringatan stok, dan pengingat pickup. */
class BreadSaverExtrasCommand extends Command
{
    protected $signature = 'breadsaver:extras {--dry-run : Simulasi tanpa mengubah database}';
    protected $description = 'Sinkronkan harga mendekati kedaluwarsa dan kirim pengingat pickup/stok';

    public function handle()
    {
        $dryRun = (bool) $this->option('dry-run');
        $harga = HargaDinamisService::sinkronkan($dryRun);
        $this->info("Harga diperiksa: {$harga['diperiksa']}; perlu update: {$harga['diubah']}.");

        $pickup = $this->pengingatPengambilan($dryRun);
        $produk = $this->peringatanStok($dryRun);
        $this->info("Pengingat pickup: {$pickup}; peringatan produk: {$produk}." . ($dryRun ? ' (simulasi)' : ''));
        return 0;
    }

    private function pengingatPengambilan(bool $dryRun): int
    {
        $pesanans = DB::table('pesanans as p')
            ->join('pembayarans as b', 'b.id_pesanan', '=', 'p.id_pesanan')
            ->where('p.status', 'Selesai') // BreadSaver: Selesai = siap diambil
            ->where('b.status', 'Berhasil')
            ->whereNull('p.picked_up_at')
            ->whereNotNull('p.waktu_pengambilan')
            ->where('p.waktu_pengambilan', '<=', now()->addHour())
            ->select('p.id_pesanan', 'p.id_user', 'p.waktu_pengambilan')
            ->limit(2000)
            ->get();

        $terkirim = 0;
        foreach ($pesanans as $p) {
            $jadwal = Carbon::parse($p->waktu_pengambilan);
            $isTerlambat = $jadwal->lte(now()->subMinutes(30));
            $event = $isTerlambat ? 'pickup-late-30' : 'pickup-soon-60';

            // Remind mendekati waktu hanya dalam jendela 60 menit sebelum pickup.
            if (!$isTerlambat && $jadwal->lt(now())) {
                continue;
            }
            $pesan = $isTerlambat
                ? "Pesanan #{$p->id_pesanan} belum tercatat diambil. Hubungi toko jika jadwal berubah."
                : "Pesanan #{$p->id_pesanan} siap diambil pukul " . $jadwal->format('H:i') . '. Tunjukkan QR pickup di toko.';

            $terkirim += $this->kirimSekali(
                (int) $p->id_user, (int) $p->id_pesanan,
                'pesanan:' . $p->id_pesanan . ':' . $event,
                'pengambilan', $isTerlambat ? 'Pengambilan Belum Tercatat' : 'Pengingat Ambil Pesanan',
                $pesan, route('pembeli.pesanan'), $dryRun
            );
        }
        return $terkirim;
    }

    private function peringatanStok(bool $dryRun): int
    {
        $produk = DB::table('produks')
            ->where('stok', '>', 0)
            ->where('expired_at', '>', now())
            ->where('expired_at', '<=', now()->addDay())
            ->orderBy('expired_at')
            ->limit(2000)
            ->get(['id_produk', 'nama_produk', 'stok', 'expired_at']);
        if ($produk->isEmpty()) {
            return 0;
        }

        $staf = DB::table('users')->whereIn('role', ['pegawai', 'admin'])
            ->where('is_active', 1)
            ->get(['id_user', 'role']);
        $terkirim = 0;
        foreach ($produk as $p) {
            foreach ($staf as $u) {
                $tujuan = $u->role === 'admin'
                    ? route('admin.stock-priority')
                    : route('pegawai.stock-priority');
                $terkirim += $this->kirimSekali(
                    (int) $u->id_user, null,
                    'produk:' . $p->id_produk . ':expired-24h',
                    'stok', 'Prioritas Jual Roti',
                    $p->nama_produk . ' tersisa ' . $p->stok . ' buah; kedaluwarsa ' .
                        Carbon::parse($p->expired_at)->format('d/m/Y H:i') . '.',
                    $tujuan, $dryRun
                );
            }
        }
        return $terkirim;
    }

    private function kirimSekali(int $userId, ?int $pesananId, string $key, string $jenis,
        string $judul, string $pesan, string $tautan, bool $dryRun): int
    {
        $sudahAda = DB::table('notifikasi')->where('id_user', $userId)
            ->where('event_key', $key)->exists();
        if ($sudahAda) {
            return 0;
        }
        if ($dryRun) {
            return 1;
        }
        return DB::table('notifikasi')->insertOrIgnore([
            'id_user' => $userId,
            'id_pesanan' => $pesananId,
            'jenis' => $jenis,
            'judul' => $judul,
            'pesan' => $pesan,
            'tautan' => $tautan,
            'event_key' => $key,
            'dibaca_pada' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]) ? 1 : 0;
    }
}
