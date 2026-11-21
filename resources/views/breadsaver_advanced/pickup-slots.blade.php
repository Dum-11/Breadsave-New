<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Slot Pickup - BreadSaver</title>
@include('breadsaver_advanced.style')</head><body><main class="wrap">
<div class="top"><strong>BREADSAVER / Slot Pengambilan</strong><a href="{{ route('admin.beranda') }}">Beranda Admin</a></div>
<h1>Pengaturan Slot Pickup</h1><p class="lead">Default maksimal 5 pesanan tiap 30 menit, pukul 08.00–20.00 WIB. Slot yg sudah dipesan tidak bisa ditutup atau dikurangi kapasitasnya di bawah jumlah pesanan aktif.</p>
@include('breadsaver_advanced.messages')
<form class="inline card" method="GET"><div class="field"><label>Tanggal</label><input type="date" name="tanggal" value="{{ $date->format('Y-m-d') }}"></div><button>Tampilkan</button></form>
<div class="card"><table class="table"><thead><tr><th>Jam</th><th>Terisi</th><th>Sisa</th><th>Atur Kapasitas</th></tr></thead><tbody>
@forelse($slots as $s)<tr><td><strong>{{ $s['label'] }}</strong></td><td>{{ $s['booked'] }}</td><td><span class="pill">{{ $s['remaining'] }}</span></td><td>
<form class="inline" method="POST" action="{{ route('admin.slots.save') }}">@csrf
<input type="hidden" name="waktu" value="{{ $s['time'] }}">
<div class="field"><input type="number" name="kapasitas" min="0" max="50" value="{{ $s['capacity'] }}" style="width:90px"></div>
<label style="margin:0"><input style="width:auto" type="checkbox" name="aktif" value="1" {{ $s['active'] ? 'checked' : '' }}> Aktif</label>
<button>Simpan</button></form>
</td></tr>@empty<tr><td colspan="4">Tidak ada slot pada tanggal tersebut.</td></tr>@endforelse
</tbody></table></div></main></body></html>
