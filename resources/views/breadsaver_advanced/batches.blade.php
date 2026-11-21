<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Batch FEFO - BreadSaver</title>
@include('breadsaver_advanced.style')</head><body><main class="wrap">
<div class="top"><strong>BREADSAVER / Batch FEFO</strong><a href="{{ auth()->user()->role==='admin' ? route('admin.beranda') : route('pegawai.beranda') }}">Kembali ke Beranda</a></div>
<h1>Manajemen Batch Stok (FEFO)</h1><p class="lead">Stok tiap produk disimpan per batch. Saat checkout, batch yg tanggal kedaluwarsanya paling dekat dan masih layak hingga pickup dipakai lebih dulu.</p>
@include('breadsaver_advanced.messages')
@if(auth()->user()->role==='admin')<div class="card"><h2>Tambah Batch Produk</h2>
<form method="POST" action="{{ route('admin.batches.store') }}" class="inline">@csrf
<div class="field"><label>Produk</label><select name="id_produk" required>@foreach($products as $p)<option value="{{ $p->id_produk }}">{{ $p->nama_produk }}</option>@endforeach</select></div>
<div class="field"><label>Kode Batch</label><input name="kode_batch" required placeholder="LOT-2026-001" maxlength="80"></div>
<div class="field"><label>Tambahan stok</label><input type="number" name="stok" min="1" max="10000" required value="5"></div>
<div class="field"><label>Kedaluwarsa</label><input type="datetime-local" name="expired_at" required></div>
<button>Tambah Batch</button></form>
<p class="muted">Stok lama diimpor otomatis sebagai batch LEGACY saat scheduler pertama dijalankan. Tambahan batch juga menambah total `produks.stok`.</p>
</div>@endif
<div class="card"><h2>Daftar Batch (FEFO)</h2><table class="table"><thead><tr><th>Produk</th><th>Batch</th><th>Stok tersedia</th><th>Kedaluwarsa</th><th>Status</th></tr></thead><tbody>
@forelse($batches as $b)<tr><td>{{ $b->nama_produk }}</td><td>{{ $b->kode_batch }}</td><td>{{ $b->stok }}</td><td>{{ \Carbon\Carbon::parse($b->expired_at)->format('d/m/Y H:i') }}</td><td><span class="pill">{{ \Carbon\Carbon::parse($b->expired_at)->lte(now()) ? 'Kedaluwarsa' : ($b->stok ? 'Aktif' : 'Habis') }}</span></td></tr>
@empty<tr><td colspan="5">Belum ada batch. Jalankan php artisan breadsaver:advanced terlebih dahulu.</td></tr>@endforelse
</tbody></table></div></main></body></html>
