<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Admin BreadSaver</title>
    <style>
        :root {
            --navy: #23365f;
            --pink: #ff8fb1;
            --pink-light: #fff0f5;
            --background: #f8f9fc;
            --text: #26324b;
            --muted: #8991a4;
            --white: #ffffff;
            --border: #e7eaf2;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; background: var(--background); color: var(--text); overflow-x: hidden; }
        a { color: inherit; text-decoration: none; }

        /* 
        =========================================================
        SIDEBAR COLLAPSIBLE HOVER (AUTO EXPAND)
        =========================================================
        */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 70px; /* Lebar default menguncup saat kursor menjauh */
            height: 100vh;
            padding: 24px 12px;
            background: var(--navy);
            color: var(--white);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            white-space: nowrap;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Saat kursor diarahkan ke sidebar -> Terbuka Penuh */
        .sidebar:hover {
            width: 240px;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
            height: 45px;
        }

        .logo {
            width: 140px;
            max-width: 100%;
            height: auto;
            object-fit: contain;
            transition: opacity 0.2s;
        }

        .logo-small {
            font-size: 24px;
            display: none;
        }

        /* Tampilkan logo kecil saat menguncup, logo penuh saat di-hover */
        .sidebar:not(:hover) .logo {
            display: none;
        }
        .sidebar:not(:hover) .logo-small {
            display: block;
        }

        .nav-title {
            color: #c4cce0;
            font-size: 11px;
            letter-spacing: 1px;
            margin: 0 10px 14px;
            text-transform: uppercase;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .nav-title {
            opacity: 1;
        }

        .menu { display: flex; flex-direction: column; }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 50px;
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 8px;
            color: var(--white);
            font-size: 15px;
            text-decoration: none;
            border: none;
            transition: background-color 0.2s, color 0.2s;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            background: var(--pink);
            color: var(--navy);
            font-weight: bold;
        }

        .nav-icon {
            width: 24px;
            height: 24px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .nav-link span {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .nav-link span {
            opacity: 1;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 20px;
        }

        /* MAIN CONTENT STANDAR PANEL */
        .main {
            margin-left: 70px;
            min-height: 100vh;
            padding: 32px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .page-header { margin-bottom: 24px; }
        .page-header h1 { font-size: 30px; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
        .page-header p { color: var(--muted); font-size: 15px; line-height: 1.6; }

        /* SUMMARY CARDS */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }
        .summary-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
        }
        .summary-label { color: var(--muted); font-size: 13px; margin-bottom: 8px; }
        .summary-value { color: var(--navy); font-size: 24px; font-weight: bold; }

        /* FILTER & ACTION BAR */
        .filter-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 18px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .btn-print {
            padding: 10px 20px;
            background: var(--navy);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-print:hover { opacity: 0.9; }

        /* TABEL LAPORAN */
        .table-card { background: var(--white); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
        .table-wrapper { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        th, td { padding: 16px 24px; text-align: left; border-bottom: 1px solid var(--border); font-size: 14px; vertical-align: middle; }
        th { background: #fafbfe; color: var(--muted); font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        
        /* STATUS STYLING */
        .status { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .status-menunggu { background: #fff4d6; color: #956500; }
        .status-diproses { background: #e6efff; color: #315ba8; }
        .status-selesai { background: #e2f7e9; color: #237844; }
        .status-dibatalkan { background: #ffe6e6; color: #b53a3a; }

        .empty-data { text-align: center; padding: 50px 20px; color: var(--muted); }
        .footer { margin-top: 45px; padding-top: 20px; border-top: 1px solid var(--border); color: var(--muted); font-size: 12px; text-align: center; }

        @media print {
            .sidebar, .btn-print { display: none !important; }
            .main { margin-left: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR WITH HOVER EXPAND -->
    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ route('admin.beranda') }}">
                <span class="logo-small">🍞</span>
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo BreadSaver">
            </a>
        </div>
        <p class="nav-title">ADMIN PANEL</p>

        <nav class="menu">
            <a href="{{ route('admin.beranda') }}" class="nav-link {{ request()->routeIs('admin.beranda') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt=""><span>Beranda</span>
            </a>
            <a href="{{ route('admin.katalog') }}" class="nav-link {{ request()->routeIs('admin.katalog') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt=""><span>Katalog</span>
            </a>
            <a href="{{ route('admin.kelola_akun') }}" class="nav-link {{ request()->routeIs('admin.kelola_akun') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt=""><span>Kelola Akun</span>
            </a>
            <a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-keranjang.png') }}" class="nav-icon" alt=""><span>Laporan</span>
            </a>
        </nav>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left;">
                    <img src="{{ asset('images/icon-login.png') }}" class="nav-icon" alt=""><span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="main">
        <header class="page-header">
            <h1>Laporan Penjualan</h1>
            <p>Rekapitulasi transaksi sukses dan penyelamatan food waste.</p>
        </header>

        @php
            $totalPendapatan = 0;
            $pesananSelesaiCount = 0;

            if(isset($pesanans)) {
                foreach($pesanans as $p) {
                    if(($p->status ?? '') === 'Selesai') {
                        $totalPendapatan += (float)($p->total_harga ?? 0);
                        $pesananSelesaiCount++;
                    }
                }
            }
        @endphp

        <!-- RINGKASAN STATISTIK -->
        <section class="summary-grid">
            <article class="summary-card">
                <p class="summary-label">Total Pendapatan (Selesai)</p>
                <p class="summary-value">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </article>
            <article class="summary-card">
                <p class="summary-label">Transaksi Berhasil</p>
                <p class="summary-value">{{ $pesananSelesaiCount }} Pesanan</p>
            </article>
            <article class="summary-card">
                <p class="summary-label">Total Semua Transaksi</p>
                <p class="summary-value">{{ isset($pesanans) ? $pesanans->count() : 0 }} Pesanan</p>
            </article>
        </section>

        <!-- FITUR CETAK -->
        <div class="filter-card">
            <div>
                <strong style="color: var(--navy); font-size: 15px;">Rekap Data Transaksi</strong>
                <p style="color: var(--muted); font-size: 13px; margin-top: 2px;">Cetak atau simpan data laporan keuangan.</p>
            </div>
            <button onclick="window.print()" class="btn-print">Cetak Laporan</button>
        </div>

        <!-- TABEL DATA LAPORAN -->
        <section class="table-card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID TRANSAKSI</th>
                            <th>TANGGAL</th>
                            <th>PELANGGAN</th>
                            <th>TOTAL PENDAPATAN</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanans ?? [] as $pesanan)
                            <tr>
                                <td style="font-weight: bold; color: var(--navy);">#{{ $pesanan->id_pesanan ?? $pesanan->id }}</td>
                                <td>{{ $pesanan->created_at ? \Carbon\Carbon::parse($pesanan->created_at)->format('d-m-Y H:i') : ($pesanan->tanggal_pesan ?? '-') }}</td>
                                <td>{{ $pesanan->user->nama ?? $pesanan->user->name ?? 'User #'.$pesanan->id_user }}</td>
                                <td style="font-weight: bold;">Rp{{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'Menunggu Konfirmasi' => 'status-menunggu',
                                            'Diproses' => 'status-diproses',
                                            'Selesai' => 'status-selesai',
                                            'Dibatalkan' => 'status-dibatalkan',
                                        ];
                                        $statusClass = $statusClasses[$pesanan->status ?? ''] ?? 'status-menunggu';
                                    @endphp
                                    <span class="status {{ $statusClass }}">{{ $pesanan->status ?? 'Draft' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-data">
                                    <h3 style="font-size: 18px; color: var(--navy); margin-bottom: 6px;">Belum ada laporan transaksi.</h3>
                                    <p>Data transaksi pembelian yang diselesaikan akan otomatis muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="footer">© {{ date('Y') }} BreadSaver. Administrator Panel.</footer>
    </main>
</body>
</html>