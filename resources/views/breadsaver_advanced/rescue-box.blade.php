<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Rescue Box - BreadSaver</title>
@include('breadsaver_advanced.style')
</head><body class="bs-buyer-with-sidebar">
@include('components.breadsaver-buyer-sidebar')
<main class="wrap">
<div class="top"><strong>BREADSAVER / Rescue Box</strong><a href="{{ route('pembeli.beranda') }}">Kembali ke Beranda</a></div>
<h1>Rescue Box Roti</h1><p class="lead">Selamatkan paket 3–5 roti mendekati kedaluwarsa. Isi dipilih otomatis saat checkout, diskon ekstra 10% dari harga diskon produk saat itu.</p>
@include('breadsaver_advanced.messages')
<div class="grid">
 <div class="card"><div class="muted">Isi paket</div><div class="number">3–5 roti</div></div>
 <div class="card"><div class="muted">Diskon tambahan</div><div class="number">10%</div></div>
 <div class="card"><div class="muted">Pembayaran</div><div class="number">QRIS</div></div>
</div>
<div class="card"><h2>Roti kandidat Rescue Box</h2>
<p class="muted">Kandidat ditampilkan sebagai gambaran; isi final bergantung pada stok dan masa layak saat pembayaran dibuat.</p>
<table class="table"><thead><tr><th>Nama roti</th><th>Harga sekarang</th><th>Stok</th><th>Kedaluwarsa</th></tr></thead><tbody>
@forelse($products as $p)<tr><td><strong>{{ $p->nama_produk }}</strong></td><td>Rp{{ number_format($p->harga_diskon ?: $p->harga_asli,0,',','.') }}</td><td>{{ $p->stok }}</td><td>{{ \Carbon\Carbon::parse($p->expired_at)->format('d/m/Y H:i') }}</td></tr>
@empty<tr><td colspan="4">Belum ada stok roti kandidat Rescue Box dalam 72 jam ke depan.</td></tr>@endforelse
</tbody></table></div>
<div class="card"><h2>Beli Rescue Box</h2>
<form method="POST" action="{{ route('pembeli.rescue-box.checkout') }}">@csrf
<label>Jumlah roti</label><select name="jumlah"><option value="3">3 roti</option><option value="4">4 roti</option><option value="5">5 roti</option></select>
<label>Waktu pengambilan</label><input type="datetime-local" name="waktu_pengambilan" required value="{{ old('waktu_pengambilan') }}">
<label>Voucher loyalty (opsional)</label><input name="voucher_kode" maxlength="32" placeholder="Contoh: BSLY-XXXXXXXXXX" value="{{ old('voucher_kode') }}">
<p class="muted">Nominal paket dihitung saat checkout dan dikunci pada pesanan. Pembayaran harus diselesaikan dalam 10 menit.</p>
<button type="submit">Pesan Rescue Box</button></form>
</div>
</main>@include('components.breadsaver-advanced-slot-picker')</body></html>
