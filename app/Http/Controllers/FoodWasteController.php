<?php

namespace App\Http\Controllers;

use App\Services\SimplePdf;
use App\Services\SimpleXlsx;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FoodWasteController extends Controller
{
    private function onlyAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->role === 'admin', 403);
    }

    private function onlyStaff(): void
    {
        abort_unless(Auth::check() && in_array(Auth::user()->role, ['admin', 'pegawai'], true), 403);
    }

    private function rentang(Request $request): array
    {
        $data = $request->validate([
            'dari' => 'nullable|date_format:Y-m-d',
            'sampai' => 'nullable|date_format:Y-m-d|after_or_equal:dari',
        ]);
        $dari = Carbon::parse($data['dari'] ?? now()->subDays(29)->toDateString())->startOfDay();
        $sampai = Carbon::parse($data['sampai'] ?? now()->toDateString())->endOfDay();
        abort_if($dari->gt($sampai), 422, 'Tanggal akhir harus sama atau setelah tanggal awal.');
        return [$dari, $sampai];
    }

    public function dashboard(Request $request)
    {
        $this->onlyAdmin();
        [$dari, $sampai] = $this->rentang($request);

        $ringkasan = DB::table('pesanans as p')
            ->leftJoin('pembayarans as b', 'b.id_pesanan', '=', 'p.id_pesanan')
            ->whereBetween('p.tanggal_pesan', [$dari, $sampai])
            ->selectRaw('COUNT(DISTINCT p.id_pesanan) as total_pesanan')
            ->selectRaw("COUNT(DISTINCT CASE WHEN b.status = 'Berhasil' THEN p.id_pesanan END) as pesanan_lunas")
            ->selectRaw("COUNT(DISTINCT CASE WHEN p.picked_up_at IS NOT NULL AND b.status = 'Berhasil' THEN p.id_pesanan END) as pesanan_diambil")
            ->selectRaw("COALESCE(SUM(CASE WHEN b.status = 'Berhasil' THEN b.total_bayar ELSE 0 END),0) as pendapatan")
            ->first();

        $terselamatkan = DB::table('pesanans as p')
            ->join('pembayarans as b', 'b.id_pesanan', '=', 'p.id_pesanan')
            ->join('detail_pesanans as d', 'd.id_pesanan', '=', 'p.id_pesanan')
            ->join('produks as pr', 'pr.id_produk', '=', 'd.id_produk')
            ->where('b.status', 'Berhasil')
            ->whereNotNull('p.picked_up_at')
            ->whereBetween('p.tanggal_pesan', [$dari, $sampai])
            ->selectRaw('COALESCE(SUM(d.jumlah), 0) as jumlah_roti')
            ->selectRaw('COALESCE(SUM(CASE WHEN (pr.harga_asli * d.jumlah) > d.subtotal THEN (pr.harga_asli * d.jumlah) - d.subtotal ELSE 0 END), 0) as estimasi_hemat')
            ->first();

        $harian = DB::table('pesanans as p')
            ->leftJoin('pembayarans as b', 'b.id_pesanan', '=', 'p.id_pesanan')
            ->whereBetween('p.tanggal_pesan', [$dari, $sampai])
            ->selectRaw('DATE(p.tanggal_pesan) as tanggal')
            ->selectRaw("COALESCE(SUM(CASE WHEN b.status = 'Berhasil' THEN b.total_bayar ELSE 0 END), 0) as pendapatan")
            ->selectRaw("COUNT(DISTINCT CASE WHEN b.status = 'Berhasil' THEN p.id_pesanan END) as lunas")
            ->groupBy(DB::raw('DATE(p.tanggal_pesan)'))
            ->orderBy('tanggal')
            ->get();

        $maks = max(1, (float) $harian->max('pendapatan'));
        return view('breadsaver_extra.dashboard', compact(
            'dari', 'sampai', 'ringkasan', 'terselamatkan', 'harian', 'maks'
        ));
    }

    public function stockPriority(Request $request)
    {
        $this->onlyStaff();
        $filter = $request->input('filter', 'segera');
        abort_unless(in_array($filter, ['segera', 'semua', 'kedaluwarsa'], true), 422);

        $query = DB::table('produks')->where('stok', '>', 0);
        if ($filter === 'segera') {
            $query->where('expired_at', '>', now())
                ->where('expired_at', '<=', now()->addDays(3));
        } elseif ($filter === 'kedaluwarsa') {
            $query->where('expired_at', '<=', now());
        }
        $produk = $query->orderBy('expired_at')->limit(500)->get();
        $isAdmin = Auth::user()->role === 'admin';
        return view('breadsaver_extra.stock-priority', compact('produk', 'filter', 'isAdmin'));
    }

    private function dataEkspor(Request $request): array
    {
        [$dari, $sampai] = $this->rentang($request);
        $pesanans = DB::table('pesanans as p')
            ->leftJoin('users as u', 'u.id_user', '=', 'p.id_user')
            ->leftJoin('pembayarans as b', 'b.id_pesanan', '=', 'p.id_pesanan')
            ->whereBetween('p.tanggal_pesan', [$dari, $sampai])
            ->orderBy('p.tanggal_pesan')
            ->select('p.id_pesanan', 'p.tanggal_pesan', 'u.nama',
                'p.status as status_pesanan', 'b.status as status_pembayaran',
                'b.metode', 'p.total_harga', 'b.total_bayar', 'p.picked_up_at')
            ->get();
        return [$dari, $sampai, $pesanans];
    }

    public function exportXlsx(Request $request)
    {
        $this->onlyAdmin();
        [$dari, $sampai, $pesanans] = $this->dataEkspor($request);
        $rows = [[
            'ID Pesanan', 'Tanggal Pesan', 'Pembeli', 'Metode',
            'Status Pesanan', 'Status Pembayaran', 'Total Pesanan (Rp)',
            'Pembayaran Berhasil (Rp)', 'Waktu Diambil'
        ]];
        foreach ($pesanans as $p) {
            $rows[] = [
                (int) $p->id_pesanan, (string) $p->tanggal_pesan,
                $p->nama ?: 'Akun dihapus', $p->metode ?: '-',
                $p->status_pesanan, $p->status_pembayaran ?: '-',
                (float) $p->total_harga,
                $p->status_pembayaran === 'Berhasil' ? (float) $p->total_bayar : 0.0,
                $p->picked_up_at ?: '-',
            ];
        }
        $nama = 'breadsaver_laporan_' . $dari->format('Ymd') . '_' . $sampai->format('Ymd') . '.xlsx';
        try {
            $isi = SimpleXlsx::buat($rows);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
        return response($isi, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $nama . '"',
            'Cache-Control' => 'no-store',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $this->onlyAdmin();
        [$dari, $sampai, $pesanans] = $this->dataEkspor($request);
        $baris = [
            'Periode : ' . $dari->format('d/m/Y') . ' - ' . $sampai->format('d/m/Y'),
            'Total transaksi: ' . $pesanans->count(),
            'Pemasukan terkonfirmasi: Rp ' . number_format(
                $pesanans->where('status_pembayaran', 'Berhasil')->sum('total_bayar'), 0, ',', '.'
            ),
            str_repeat('-', 89),
            sprintf('%-6s %-16s %-25s %-12s %12s', 'ID', 'Tanggal', 'Pembeli', 'Bayar', 'Total'),
            str_repeat('-', 89),
        ];
        foreach ($pesanans as $p) {
            $nama = mb_substr_safe($p->nama ?: 'Akun dihapus', 24);
            $baris[] = sprintf('%-6s %-16s %-25s %-12s %12s',
                '#' . $p->id_pesanan,
                Carbon::parse($p->tanggal_pesan)->format('d/m/Y H:i'),
                $nama,
                $p->status_pembayaran ?: '-',
                number_format((float) $p->total_harga, 0, ',', '.')
            );
        }
        $pdf = SimplePdf::buat('LAPORAN PENJUALAN BREADSAVER', $baris);
        $namaFile = 'breadsaver_laporan_' . $dari->format('Ymd') . '_' . $sampai->format('Ymd') . '.pdf';
        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $namaFile . '"',
            'Cache-Control' => 'no-store',
        ]);
    }
}

/** Hindari dependensi mbstring bila belum terpasang pada PHP lokal. */
function mb_substr_safe($string, $max)
{
    $string = (string) $string;
    return function_exists('mb_substr') ? mb_substr($string, 0, $max) : substr($string, 0, $max);
}
