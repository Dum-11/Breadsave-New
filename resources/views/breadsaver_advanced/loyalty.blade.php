<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Loyalty BreadSaver</title>
@include('breadsaver_advanced.style')</head><body class="bs-buyer-with-sidebar">
@include('components.breadsaver-buyer-sidebar')
<main class="wrap">
<div class="top"><strong>BREADSAVER / Loyalty</strong><a href="{{ route('pembeli.beranda') }}">Beranda Pembeli</a></div>
<h1>Poin Penyelamat Roti</h1><p class="lead">Dapat 1 poin per Rp10.000 transaksi (minimal 1 poin) setelah QR pickup diverifikasi. Tukar 100 poin untuk voucher diskon Rp10.000, berlaku 30 hari.</p>
@include('breadsaver_advanced.messages')
<div class="card"><div class="muted">Saldo poin</div><div class="number">{{ number_format($points,0,',','.') }} poin</div>
<form method="POST" action="{{ route('pembeli.loyalty.exchange') }}">@csrf<button {{ $points<100 ? 'disabled' : '' }}>Tukar 100 poin → Voucher Rp10.000</button></form></div>
<div class="card"><h2>Voucher saya</h2><table class="table"><thead><tr><th>Kode</th><th>Potongan</th><th>Berlaku hingga</th><th>Status</th></tr></thead><tbody>
@forelse($vouchers as $v)<tr><td><code>{{ $v->kode }}</code></td><td>Rp{{ number_format($v->nominal,0,',','.') }}</td><td>{{ \Carbon\Carbon::parse($v->kadaluwarsa_at)->format('d/m/Y') }}</td><td>{{ $v->dipakai_pesanan ? 'Terpakai' : ($v->kadaluwarsa_at<now()?'Kedaluwarsa':'Siap digunakan') }}</td></tr>
@empty<tr><td colspan="4">Belum ada voucher.</td></tr>@endforelse</tbody></table></div>
<div class="card"><h2>Riwayat poin</h2><table class="table"><thead><tr><th>Tanggal</th><th>Keterangan</th><th>Poin</th></tr></thead><tbody>
@forelse($ledger as $r)<tr><td>{{ $r->created_at }}</td><td>{{ $r->keterangan }}</td><td>{{ $r->poin > 0 ? '+' : '' }}{{ $r->poin }}</td></tr>
@empty<tr><td colspan="3">Belum ada riwayat poin.</td></tr>@endforelse</tbody></table></div>
</main></body></html>
