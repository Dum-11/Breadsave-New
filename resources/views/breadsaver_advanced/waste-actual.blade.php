<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Food Waste Aktual - BreadSaver</title>
@include('breadsaver_advanced.style')</head><body><main class="wrap">
<div class="top"><strong>BREADSAVER / Food Waste Aktual</strong><a href="{{ $isAdmin ? route('admin.beranda') : route('pegawai.beranda') }}">Kembali ke Beranda</a></div>
<h1>Laporan Food Waste Aktual</h1><p class="lead">Bandingkan roti yg dikonfirmasi dibuang oleh staf dengan roti yg telah diambil pembeli. Roti diambil adalah proksi terselamatkan, bukan bukti semua roti itu pasti akan terbuang.</p>
@include('breadsaver_advanced.messages')
<form method="GET" class="inline card"><div class="field"><label>Dari</label><input type="date" name="dari" value="{{ $from->format('Y-m-d') }}"></div><div class="field"><label>Sampai</label><input type="date" name="sampai" value="{{ $to->format('Y-m-d') }}"></div><button>Tampilkan</button></form>
<div class="grid">
<div class="card"><div class="muted">Roti dibuang (dikonfirmasi staf)</div><div class="number">{{ $totalWaste }}</div></div>
<div class="card"><div class="muted">Roti diambil (proksi)</div><div class="number">{{ $picked }}</div></div>
<div class="card"><div class="muted">Batch kedaluwarsa otomatis (belum tentu dibuang)</div><div class="number">{{ $autoExpired }}</div></div>
<div class="card"><div class="muted">Estimasi nilai roti dibuang</div><div class="number">Rp{{ number_format($estimatedLoss,0,',','.') }}</div></div>
</div>
<div class="card"><h2>Catat roti rusak / tidak terjual</h2>
<form method="POST" action="{{ route('breadsaver.waste.record') }}" class="inline">@csrf
<div class="field"><label>Batch</label><select name="id_batch" required>@foreach($batches as $b)<option value="{{ $b->id }}">{{ $b->nama_produk }} / {{ $b->kode_batch }} ({{ $b->stok }} stok)</option>@endforeach</select></div>
<div class="field"><label>Jumlah</label><input type="number" name="jumlah" min="1" value="1" required></div>
<div class="field"><label>Alasan</label><select name="alasan"><option value="kedaluwarsa">Kedaluwarsa</option><option value="rusak">Rusak</option><option value="tidak_terjual">Tidak Terjual</option><option value="lainnya">Lainnya</option></select></div>
<button>Catat & Kurangi Stok</button></form>
</div>
<div class="card"><div class="top"><h2>Riwayat Pembuangan</h2>
@if($isAdmin)<div><a class="btn btn-light" href="{{ route('admin.waste-export.xlsx',['dari'=>$from->format('Y-m-d'),'sampai'=>$to->format('Y-m-d')]) }}">Download XLSX</a> <a class="btn btn-light" href="{{ route('admin.waste-export.pdf',['dari'=>$from->format('Y-m-d'),'sampai'=>$to->format('Y-m-d')]) }}">Download PDF</a></div>@endif</div>
<table class="table"><thead><tr><th>Waktu</th><th>Produk</th><th>Batch</th><th>Jumlah</th><th>Alasan</th><th>Sumber</th></tr></thead><tbody>
@forelse($records as $r)<tr><td>{{ $r->tanggal_buang }}</td><td>{{ $r->nama_produk }}</td><td>{{ $r->kode_batch }}</td><td>{{ $r->jumlah }}</td><td>{{ str_replace('_',' ',$r->alasan) }}</td><td>{{ $r->sumber }}</td></tr>
@empty<tr><td colspan="6">Belum ada food waste tercatat pada periode ini.</td></tr>@endforelse</tbody></table></div>
</main></body></html>
