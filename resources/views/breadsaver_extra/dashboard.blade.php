<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Food Waste — BreadSaver</title>
    <style>
        *{box-sizing:border-box} body{margin:0;background:#f6f7fb;color:#243455;font:14px/1.55 Arial,sans-serif}
        a{color:#243e72;text-decoration:none} .wrap{max-width:1160px;margin:0 auto;padding:30px 22px}
        .nav{display:flex;gap:12px;align-items:center;justify-content:space-between;flex-wrap:wrap;margin-bottom:24px}
        .links{display:flex;gap:12px;flex-wrap:wrap}.links a{background:#fff;border:1px solid #dde2ed;border-radius:10px;padding:9px 12px}
        h1{font-size:27px;line-height:1.2;margin:6px 0 8px;color:#21365d} p{margin:4px 0 14px;color:#64718b}
        .box,.kpi{border:1px solid #e5e8f0;background:#fff;border-radius:16px;padding:20px}
        .filter{display:flex;align-items:end;gap:12px;flex-wrap:wrap}.filter label{display:grid;gap:5px;font-weight:bold;font-size:12px}
        input{border:1px solid #cfd6e2;border-radius:8px;padding:11px;color:#21365d}button,.button{border:0;background:#253e6e;color:white;border-radius:9px;padding:11px 16px;cursor:pointer;font-weight:bold;display:inline-flex}
        .button.light{background:#fff;border:1px solid #d7ddea;color:#253e6e}
        .kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin:20px 0}
        .kpi small{display:block;color:#71809a;font-size:12px}.kpi strong{display:block;font-size:28px;letter-spacing:-.5px;margin-top:9px;color:#22385f}
        .kpi.pink{border-color:#f9c7d7;background:#fff7fa}.kpi.pink strong{color:#ba4671}
        .title-row{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin:25px 0 12px}h2{font-size:17px;margin:0}
        .chart{display:flex;align-items:end;gap:10px;overflow-x:auto;height:235px;padding:24px 8px 0;border-bottom:1px solid #ccd3e0}
        .day{min-width:45px;display:flex;flex:1;flex-direction:column;justify-content:end;align-items:center;height:100%;gap:5px}
        .bar{background:linear-gradient(180deg,#fb91b5,#d45583);width:25px;min-height:3px;border-radius:6px 6px 0 0}
        .day span{font-size:10px;color:#8a95a6;white-space:nowrap}.muted{font-size:12px;color:#778397}
        .actions{display:flex;gap:10px;flex-wrap:wrap}.note{border-left:3px solid #d5a0b4;padding:12px 15px;background:#fff9fb;font-size:12px;color:#68768c;margin-top:16px}
        table{width:100%;border-collapse:collapse}th,td{text-align:left;padding:10px 12px;border-bottom:1px solid #edf0f5;font-size:13px}th{color:#63718b}
        @media(max-width:650px){.wrap{padding:18px 12px}.kpi strong{font-size:24px}.chart{height:185px}}
    </style>
</head>
<body>
<main class="wrap">
    <nav class="nav">
        <div><strong>BREADSAVER</strong> <span class="muted">/ Statistik Food Waste</span></div>
        <div class="links">
            <a href="{{ route('admin.beranda') }}">Beranda Admin</a>
            <a href="{{ route('admin.stock-priority') }}">Stok Prioritas</a>
            <a href="{{ route('admin.laporan') }}">Laporan Lama</a>
        </div>
    </nav>
    <h1>Dashboard Food Waste</h1>
    <p>Pantau penjualan, pesanan yg sudah diambil, dan dampak estimasi penghematan.</p>

    <section class="box">
        <form action="{{ route('admin.foodwaste') }}" method="GET" class="filter">
            <label>Dari tanggal<input name="dari" type="date" value="{{ $dari->toDateString() }}" required></label>
            <label>Sampai tanggal<input name="sampai" type="date" value="{{ $sampai->toDateString() }}" required></label>
            <button type="submit">Tampilkan</button>
        </form>
    </section>

    <section class="kpis">
        <article class="kpi"><small>Total Pesanan</small><strong>{{ number_format($ringkasan->total_pesanan ?? 0, 0, ',', '.') }}</strong></article>
        <article class="kpi"><small>Pesanan Lunas</small><strong>{{ number_format($ringkasan->pesanan_lunas ?? 0, 0, ',', '.') }}</strong></article>
        <article class="kpi pink"><small>Roti Terambil</small><strong>{{ number_format($terselamatkan->jumlah_roti ?? 0, 0, ',', '.') }}</strong></article>
        <article class="kpi"><small>Pendapatan Lunas</small><strong>Rp{{ number_format($ringkasan->pendapatan ?? 0, 0, ',', '.') }}</strong></article>
        <article class="kpi"><small>Estimasi Hemat Pembeli</small><strong>Rp{{ number_format($terselamatkan->estimasi_hemat ?? 0, 0, ',', '.') }}</strong></article>
    </section>

    <div class="title-row"><h2>Pendapatan per Hari</h2><span class="muted">Dihitung dari pembayaran berstatus Berhasil</span></div>
    <section class="box">
        @if($harian->isEmpty())
            <p>Belum ada transaksi pada rentang tanggal ini.</p>
        @else
            <div class="chart">
                @foreach($harian as $h)
                    <div class="day" title="{{ $h->tanggal }}: Rp{{ number_format($h->pendapatan, 0, ',', '.') }}">
                        <span>Rp{{ number_format($h->pendapatan / 1000, 0, ',', '.') }}rb</span>
                        <div class="bar" style="height:{{ max(2, round((float)$h->pendapatan / $maks * 175)) }}px"></div>
                        <span>{{ \Carbon\Carbon::parse($h->tanggal)->format('d/m') }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <div class="title-row">
        <h2>Ekspor Laporan Penjualan</h2>
        <div class="actions">
            <a class="button" href="{{ route('admin.report-export.xlsx', ['dari' => $dari->toDateString(), 'sampai' => $sampai->toDateString()]) }}">Download Excel (.xlsx)</a>
            <a class="button light" href="{{ route('admin.report-export.pdf', ['dari' => $dari->toDateString(), 'sampai' => $sampai->toDateString()]) }}">Download PDF (.pdf)</a>
        </div>
    </div>
    <section class="box">
        <table>
            <thead><tr><th>Tanggal</th><th>Pesanan Lunas</th><th>Pendapatan</th></tr></thead>
            <tbody>
            @forelse($harian as $h)
                <tr><td>{{ \Carbon\Carbon::parse($h->tanggal)->format('d/m/Y') }}</td><td>{{ $h->lunas }}</td><td>Rp{{ number_format($h->pendapatan, 0, ',', '.') }}</td></tr>
            @empty
                <tr><td colspan="3">Belum ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="note">Angka “roti terambil” menghitung jumlah produk pada pesanan lunas yg QR-nya sudah diverifikasi. Ini indikator roti tersalurkan, bukan bukti ilmiah seluruhnya pasti menjadi limbah. Estimasi hemat memakai harga asli produk saat ini (riwayat harga asli belum disimpan per transaksi).</div>
    </section>
</main>
</body>
</html>
