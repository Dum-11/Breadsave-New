<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Audit Pegawai BreadSaver</title>
@include('breadsaver_advanced.style')</head><body><main class="wrap">
<div class="top"><strong>BREADSAVER / Aktivitas Pegawai</strong><a href="{{ route('admin.beranda') }}">Beranda Admin</a></div>
<h1>Riwayat Aktivitas Pegawai</h1><p class="lead">Jejak perubahan status pesanan, pengambilan QR, perubahan produk, stok, dan catatan waste.</p>
<form method="GET" class="inline card"><div class="field"><label>Tanggal</label><input type="date" name="tanggal" value="{{ $data['tanggal'] ?? '' }}"></div><button>Tampilkan</button><a class="btn btn-light" href="{{ route('admin.activity') }}">Semua</a></form>
<div class="card"><table class="table"><thead><tr><th>Waktu</th><th>Pegawai</th><th>Aksi</th><th>Objek</th><th>Detail</th></tr></thead><tbody>
@forelse($logs as $l)<tr><td>{{ $l->created_at }}</td><td>{{ $l->nama_pegawai ?: 'Akun dihapus' }} (#{{ $l->id_user }})</td><td>{{ str_replace('_',' ',$l->aksi) }}</td><td>{{ $l->entitas }} #{{ $l->id_entitas ?: '-' }}</td><td>{{ $l->ringkasan }}</td></tr>
@empty<tr><td colspan="5">Belum ada aktivitas tercatat.</td></tr>@endforelse</tbody></table></div>
</main></body></html>
