<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Stok Prioritas — BreadSaver</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#f7f8fc;color:#243657;font:14px/1.5 Arial,sans-serif}
.wrap{max-width:1150px;margin:0 auto;padding:28px 20px}h1{margin:7px 0;font-size:26px;color:#23375f}p{color:#71809b}
a{color:#23375f;text-decoration:none}.nav{display:flex;gap:15px;align-items:center;justify-content:space-between;flex-wrap:wrap;margin-bottom:24px}
.back,.tab{display:inline-block;padding:10px 14px;border:1px solid #d9dfeb;border-radius:9px;background:white}
.tabs{display:flex;gap:8px;flex-wrap:wrap;margin:22px 0}.tab.current{background:#233b66;color:white;border-color:#233b66}
.card{background:white;border:1px solid #e3e7f0;border-radius:15px;overflow:auto}
table{width:100%;border-collapse:collapse;min-width:750px}th,td{border-bottom:1px solid #edf0f4;text-align:left;padding:14px}
th{font-size:12px;color:#71809b;background:#fafbfe}td strong{display:block}.small{font-size:11px;color:#7c879a}.pill{font-size:11px;padding:5px 8px;border-radius:20px;font-weight:bold;background:#f0f1f5}
.pill.danger{color:#a82243;background:#ffe7ef}.pill.warning{color:#aa681b;background:#fff2da}.pill.good{color:#39644f;background:#e5f5e9}
.tips{margin-top:18px;padding:14px;border-left:3px solid #e790af;background:#fff7fb;color:#63718a}
</style>
</head>
<body>
<main class="wrap">
  <nav class="nav">
    <div><strong>BREADSAVER</strong> <span class="small">/ Stok Prioritas</span></div>
    <a class="back" href="{{ $isAdmin ? route('admin.beranda') : route('pegawai.beranda') }}">Kembali ke Beranda</a>
  </nav>
  <h1>Manajemen Produk Mendekati Kedaluwarsa</h1>
  <p>Urutan produk berdasarkan waktu kedaluwarsa terdekat. Stok tetap tersimpan meski produk sudah lewat masa layaknya.</p>
  <div class="tabs">
    <a class="tab {{ $filter === 'segera' ? 'current' : '' }}" href="?filter=segera">≤ 72 jam</a>
    <a class="tab {{ $filter === 'kedaluwarsa' ? 'current' : '' }}" href="?filter=kedaluwarsa">Sudah kedaluwarsa</a>
    <a class="tab {{ $filter === 'semua' ? 'current' : '' }}" href="?filter=semua">Semua stok aktif</a>
  </div>
  <section class="card">
    <table>
      <thead><tr><th>Produk</th><th>Stok</th><th>Kedaluwarsa</th><th>Prioritas</th><th>Harga Asli</th><th>Harga Jual Saat Ini</th>@if($isAdmin)<th>Aksi</th>@endif</tr></thead>
      <tbody>
      @forelse($produk as $p)
        @php
          $menit = $p->expired_at ? now()->diffInMinutes(\Carbon\Carbon::parse($p->expired_at), false) : 1000000;
          $expired = $menit <= 0;
          $tingkat = $expired ? 'Kedaluwarsa' : ($menit <= 1440 ? 'Kritis (24 jam)' : ($menit <= 4320 ? 'Prioritas (72 jam)' : 'Normal'));
          $warna = $expired || $menit <= 1440 ? 'danger' : ($menit <= 4320 ? 'warning' : 'good');
        @endphp
        <tr>
          <td><strong>{{ $p->nama_produk }}</strong><span class="small">ID #{{ $p->id_produk }}</span></td>
          <td>{{ number_format($p->stok, 0, ',', '.') }}</td>
          <td>{{ $p->expired_at ? \Carbon\Carbon::parse($p->expired_at)->format('d/m/Y H:i') : '-' }}</td>
          <td><span class="pill {{ $warna }}">{{ $tingkat }}</span>@if(!$expired && $menit < 1440)<div class="small">{{ max(1, ceil($menit / 60)) }} jam lagi</div>@endif</td>
          <td>Rp{{ number_format($p->harga_asli, 0, ',', '.') }}</td>
          <td>Rp{{ number_format($p->harga_diskon, 0, ',', '.') }}</td>
          @if($isAdmin)<td><a href="{{ route('admin.produk.edit', ['id' => $p->id_produk]) }}">Edit produk</a></td>@endif
        </tr>
      @empty
        <tr><td colspan="{{ $isAdmin ? 7 : 6 }}" style="text-align:center;padding:30px;color:#768197">Tidak ada produk untuk filter ini.</td></tr>
      @endforelse
      </tbody>
    </table>
  </section>
  <div class="tips">Aturan diskon otomatis: 72–48 jam sebelum kedaluwarsa minimal 30%; 48–24 jam minimal 50%; kurang dari 24 jam minimal 70%. Diskon manual yg lebih besar tetap dipertahankan. Produk kedaluwarsa ditolak saat ditambahkan ke keranjang atau checkout.</div>
</main>
</body>
</html>
