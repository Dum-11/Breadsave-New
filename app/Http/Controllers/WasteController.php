<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use App\Services\BatchInventoryService;
use App\Services\SimplePdf;
use App\Services\SimpleXlsx;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WasteController extends Controller
{
    private function staff(): void
    {
        abort_unless(auth()->check() && in_array(auth()->user()->role, ['admin','pegawai'], true), 403);
    }

    private function period(Request $request): array
    {
        $data = $request->validate([
            'dari' => 'nullable|date_format:Y-m-d',
            'sampai' => 'nullable|date_format:Y-m-d|after_or_equal:dari',
        ]);
        $from = Carbon::parse($data['dari'] ?? now()->subDays(29)->toDateString())->startOfDay();
        $to = Carbon::parse($data['sampai'] ?? now()->toDateString())->endOfDay();
        abort_if($to->lt($from), 422, 'Periode tidak valid.');
        return [$from, $to];
    }

    private function data(Request $request): array
    {
        [$from, $to] = $this->period($request);
        $records = DB::table('food_waste_records as w')
            ->join('produks as p', 'p.id_produk', '=', 'w.id_produk')
            ->leftJoin('produk_batches as b', 'b.id', '=', 'w.id_batch')
            ->whereBetween('w.tanggal_buang', [$from, $to])
            ->orderByDesc('w.tanggal_buang')
            ->select('w.*', 'p.nama_produk', 'p.harga_asli', 'b.kode_batch')->get();
        $picked = (int) DB::table('pesanans as p')
            ->join('pembayarans as pay', 'pay.id_pesanan', '=', 'p.id_pesanan')
            ->join('detail_pesanans as d', 'd.id_pesanan', '=', 'p.id_pesanan')
            ->where('pay.status', 'Berhasil')->whereNotNull('p.picked_up_at')
            ->whereBetween('p.picked_up_at', [$from, $to])->sum('d.jumlah');
        return [$from, $to, $records, $picked];
    }

    public function index(Request $request)
    {
        $this->staff();
        [$from, $to, $records, $picked] = $this->data($request);
        // Waste aktual hanya yg dikonfirmasi pegawai; batch expired otomatis dihitung terpisah.
        $manual = $records->where('sumber', 'manual');
        $totalWaste = (int) $manual->sum('jumlah');
        $autoExpired = (int) $records->where('sumber', 'otomatis')->sum('jumlah');
        $estimatedLoss = (float) $manual->sum(function ($row) {
            return (float) $row->harga_asli * (int) $row->jumlah;
        });
        $batches = DB::table('produk_batches as b')
            ->join('produks as p', 'p.id_produk', '=', 'b.id_produk')
            ->where('b.stok', '>', 0)->orderBy('b.expired_at')
            ->get(['b.id', 'b.kode_batch', 'b.stok', 'b.expired_at', 'p.nama_produk']);
        $isAdmin = auth()->user()->role === 'admin';
        return view('breadsaver_advanced.waste-actual', compact(
            'from','to','records','picked','totalWaste','autoExpired','estimatedLoss','batches','isAdmin'
        ));
    }

    public function record(Request $request)
    {
        $this->staff();
        $data = $request->validate([
            'id_batch' => 'required|integer|exists:produk_batches,id',
            'jumlah' => 'required|integer|between:1,10000',
            'alasan' => 'required|in:kedaluwarsa,rusak,tidak_terjual,lainnya',
        ]);
        try {
            $productId = DB::transaction(function () use ($data) {
                $batch = DB::table('produk_batches')->where('id', $data['id_batch'])->lockForUpdate()->first();
                if (!$batch) throw new RuntimeException('Batch tidak ditemukan.');
                $product = DB::table('produks')->where('id_produk', $batch->id_produk)->lockForUpdate()->first();
                $qty = (int) $data['jumlah'];
                if (!$product || $qty > (int) $batch->stok || $qty > (int) $product->stok) {
                    throw new RuntimeException('Jumlah dibuang melebihi stok tersedia.');
                }
                DB::table('produk_batches')->where('id', $batch->id)->decrement('stok', $qty);
                DB::table('produks')->where('id_produk', $batch->id_produk)->decrement('stok', $qty);
                DB::table('food_waste_records')->insert([
                    'id_produk' => $batch->id_produk, 'id_batch' => $batch->id,
                    'jumlah' => $qty, 'alasan' => $data['alasan'],
                    'sumber' => 'manual', 'created_by' => auth()->id(),
                    'tanggal_buang' => now(), 'event_key' => null,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                BatchInventoryService::refreshExpiry((int) $batch->id_produk);
                return (int) $batch->id_produk;
            }, 3);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
        ActivityLogger::record('catat_food_waste', 'produk', $productId,
            $data['alasan'] . ' sebanyak ' . $data['jumlah'] . ' roti (batch #' . $data['id_batch'] . ')');
        return back()->with('success', 'Food waste aktual dicatat dan stok diperbarui.');
    }

    public function exportXlsx(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
        [$from,$to,$records,$picked] = $this->data($request);
        $rows = [['Tanggal','Produk','Batch','Alasan','Jumlah','Harga Asli/Unit (Rp)','Estimasi Nilai Limbah (Rp)','Sumber']];
        foreach ($records as $r) $rows[] = [
            (string) $r->tanggal_buang, (string) $r->nama_produk, (string) $r->kode_batch,
            (string) $r->alasan, (int) $r->jumlah, (float) $r->harga_asli,
            round($r->jumlah * $r->harga_asli,2), (string) $r->sumber,
        ];
        $rows[] = [];
        $rows[] = ['Roti dibuang dikonfirmasi (manual)', (int) $records->where('sumber','manual')->sum('jumlah')];
        $rows[] = ['Batch kedaluwarsa ditandai otomatis', (int) $records->where('sumber','otomatis')->sum('jumlah')];
        $rows[] = ['Roti diambil pembeli (proksi)', (int) $picked];
        $file = 'breadsaver_food_waste_' . $from->format('Ymd') . '_' . $to->format('Ymd') . '.xlsx';
        return response(SimpleXlsx::buat($rows), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $file . '"', 'Cache-Control' => 'no-store',
        ]);
    }

    public function exportPdf(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
        [$from,$to,$records,$picked] = $this->data($request);
        $lines = [
            'Periode: ' . $from->format('d/m/Y') . ' - ' . $to->format('d/m/Y'),
            'Roti dibuang (terkonfirmasi): ' . $records->where('sumber','manual')->sum('jumlah'),
            'Batch expired otomatis (belum dikonfirmasi dibuang): ' . $records->where('sumber','otomatis')->sum('jumlah'),
            'Roti diambil pembeli (proksi): ' . $picked,
            str_repeat('-', 72),
        ];
        foreach ($records as $r) {
            $lines[] = Carbon::parse($r->tanggal_buang)->format('d/m/Y H:i') . ' | ' .
                substr($r->nama_produk, 0, 23) . ' | ' . $r->alasan . ' | ' . $r->jumlah . ' unit';
        }
        $file = 'breadsaver_food_waste_' . $from->format('Ymd') . '_' . $to->format('Ymd') . '.pdf';
        return response(SimplePdf::buat('LAPORAN FOOD WASTE AKTUAL BREADSAVER', $lines), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $file . '"', 'Cache-Control' => 'no-store',
        ]);
    }
}
