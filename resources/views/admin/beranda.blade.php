<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Admin - BreadSaver</title>
    <style>
        :root {
            --navy: #23365f;
            --pink: #ff8fb1;
            --pink-light: #fff0f5;
            --background: #f8f9fc;
            --text: #26324b;
            --muted: #8991a4;
            --white: #ffffff;
            --border: #e7eaf1;
            --green: #2e7d32;
            --green-bg: #e8f5e9;
            --yellow: #f57c00;
            --yellow-bg: #fff3e0;
            --blue: #0288d1;
            --blue-bg: #e1f5fe;
            --red: #d32f2f;
            --red-bg: #ffebee;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        /* SIDEBAR - SAMA DENGAN HALAMAN PESANAN */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 76px;
            height: 100vh;
            padding: 20px 12px;
            background: linear-gradient(180deg, #203761 0%, #172b4e 100%);
            color: var(--white);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            white-space: nowrap;
            transition: width 0.3s ease;
            z-index: 1000;
            box-shadow: 4px 0 18px rgba(0, 0, 0, 0.10);
        }

        .sidebar:hover {
            width: 245px;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 34px;
            height: 48px;
            flex-shrink: 0;
        }

        .logo-container a {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo {
            width: 145px;
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .logo-small {

            width: 40px;

            height: 40px;

            object-fit: contain;

            flex-shrink: 0;

            display: none;

        }

        .sidebar:not(:hover) .logo {
            display: none;
        }

        .sidebar:not(:hover) .logo-small {
            display: block;
        }

        .nav-title {
            color: #c3cee4;
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

        .menu {
            display: flex;
            flex-direction: column;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 50px;
            padding: 12px 14px;
            border-radius: 14px;
            margin-bottom: 8px;
            color: var(--white);
            font-size: 15px;
            text-decoration: none;
            transition: 0.2s ease;
            width: 100%;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.10);
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

        .profile-initial {
            width: 24px;
            height: 24px;
            min-width: 24px;
            border-radius: 50%;
            background: var(--pink);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .profile-link.active {
            background: var(--pink);
            color: var(--navy);
            font-weight: normal;
        }

        .profile-link.active .profile-initial {
            background: var(--navy);
            color: white;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 20px;
        }

        .logout-button {
            border: 0;
            background: transparent;
            cursor: pointer;
            text-align: left;
            font-family: inherit;
        }

        .logout-button:hover {
            background: rgba(255, 255, 255, 0.10);
        }

        /* KONTEN UTAMA */
        .main {
            margin-left: 76px;
            min-height: 100vh;
            padding: 30px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 27px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .page-header p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.5;
        }

        /* SUMMARY GRID (4 CARD) */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: var(--white);
            border: 1px solid #edf0f6;
            border-radius: 15px;
            padding: 23px;
        }

        .summary-label {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 12px;
        }

        .summary-number {
            color: var(--navy);
            font-size: 26px;
            font-weight: bold;
        }

        /* DASHBOARD CONTENT GRID */
        .dashboard-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .panel-card {
            background: var(--white);
            border: 1px solid #edf0f6;
            border-radius: 15px;
            padding: 24px;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .panel-title {
            font-size: 18px;
            font-weight: bold;
            color: var(--navy);
        }

        /* TABLE STYLES */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        .custom-table th {
            background: var(--background);
            color: var(--muted);
            font-weight: 600;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
        }

        .custom-table td {
            padding: 14px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }

        /* BADGES */
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-warning { background: var(--yellow-bg); color: var(--yellow); }
        .badge-info { background: var(--blue-bg); color: var(--blue); }
        .badge-success { background: var(--green-bg); color: var(--green); }
        .badge-danger { background: var(--red-bg); color: var(--red); }

        /* PROGRESS BARS STATUS */
        .status-item {
            margin-bottom: 18px;
        }

        .status-info {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .progress-bar-bg {
            width: 100%;
            height: 8px;
            background: var(--background);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 10px;
        }

        /* FOOTER */
        .main-footer {
            margin-top: 45px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
            text-align: center;
        }

        /* RESPONSIVE */
        @media (max-width: 1100px) {
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .dashboard-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 700px) {
            .sidebar {
                width: 76px;
                padding: 20px 10px;
                align-items: center;
            }

            .logo-container {
                margin-bottom: 30px;
            }

            .nav-title {
                display: none;
            }

            .nav-link {
                justify-content: center;
                padding: 10px 5px;
            }

            .nav-link span {
                display: none;
            }

            .sidebar-bottom {
                width: 100%;
            }

            .main {
                margin-left: 76px;
                padding: 18px 14px;
            }

            .page-header h1 {
                font-size: 24px;
            }

            .summary-grid {
                gap: 12px;
            }

            .summary-card, .panel-card {
                padding: 16px;
            }
        }

        @media (hover: none) and (max-width: 700px) {
            .sidebar:hover {
                width: 76px;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ route('admin.beranda') }}">
                <img src="{{ asset('images/logo-icon.png') }}"
                     onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';"
                     alt="Logo BreadSaver"
                     class="logo-small">
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo BreadSaver">
            </a>
        </div>

        <p class="nav-title">Admin Panel</p>

        <nav class="menu">
            <a href="{{ route('admin.beranda') }}"
               class="nav-link {{ request()->routeIs('admin.beranda') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt="">
                <span>Beranda</span>
            </a>

            <a href="{{ route('admin.katalog') }}"
               class="nav-link {{ request()->routeIs('admin.katalog') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt="">
                <span>Katalog</span>
            </a>

            <a href="{{ route('admin.kelola_akun') }}"
               class="nav-link {{ request()->routeIs('admin.kelola_akun') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt="">
                <span>Kelola Akun</span>
            </a>

            <a href="{{ route('admin.laporan') }}"
               class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-keranjang.png') }}" class="nav-icon" alt="">
                <span>Laporan</span>
            </a>
        </nav>

        <div class="sidebar-bottom">
            <!-- PROFIL -->
            <a href="{{ route('admin.profil') }}"
               class="nav-link profile-link {{ request()->routeIs('admin.profil') ? 'active' : '' }}">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}"
                         class="nav-icon" alt="Foto Profil"
                         style="border-radius: 50%; object-fit: cover;">
                @else
                    <div class="nav-icon profile-initial">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    </div>
                @endif
                <span>Profil</span>
            </a>

            <!-- LOGOUT -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link logout-button">
                    <img src="{{ asset('images/icon-login.png') }}" class="nav-icon" alt="">
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">

        <header class="page-header">
            <h1>Beranda Admin</h1>
            <p>Selamat datang kembali, Admin. Berikut ringkasan sistem secara keseluruhan.</p>
        </header>

        <!-- KARTU STATISTIK (4 GRID) -->
        <section class="summary-grid">

            <article class="summary-card">
                <p class="summary-label">Total Pendapatan</p>
                <p class="summary-number" style="color: var(--green);">
                    Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                </p>
            </article>

            <article class="summary-card">
                <p class="summary-label">Total Pesanan</p>
                <p class="summary-number">
                    {{ $totalPesanan ?? 0 }}
                </p>
            </article>

            <article class="summary-card">
                <p class="summary-label">Total Produk</p>
                <p class="summary-number">
                    {{ $totalProduk ?? 0 }}
                </p>
            </article>

            <article class="summary-card">
                <p class="summary-label">Total Pengguna</p>
                <p class="summary-number">
                    {{ $totalPengguna ?? 0 }}
                </p>
            </article>

        </section>

        <!-- KONTEN UTAMA: TABEL PESANAN & STATUS -->
        <div class="dashboard-content">

            <!-- TABEL PESANAN TERBARU -->
            <section class="panel-card">

                <div class="panel-header">
                    <h2 class="panel-title">Pesanan Terbaru</h2>
                </div>

                <div class="table-responsive">

                    <table class="custom-table">

                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pembeli</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($pesananTerbaru ?? [] as $item)

                                <tr>

                                    <td>
                                        <strong>#{{ $item->id_pesanan }}</strong>
                                    </td>

                                    <td>
                                        {{ $item->nama_pembeli ?? 'Pembeli' }}
                                    </td>

                                    <td>
                                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                    </td>

                                    <td>

                                        @if($item->status == 'Menunggu Konfirmasi')

                                            <span class="badge badge-warning">
                                                Menunggu
                                            </span>

                                        @elseif($item->status == 'Diproses')

                                            <span class="badge badge-info">
                                                Diproses
                                            </span>

                                        @elseif($item->status == 'Selesai')

                                            <span class="badge badge-success">
                                                Selesai
                                            </span>

                                        @else

                                            <span class="badge badge-danger">
                                                Dibatalkan
                                            </span>

                                        @endif

                                    </td>

                                    <td style="color: var(--muted); font-size: 13px;">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5"
                                        style="text-align: center; color: var(--muted); padding: 20px;">

                                        Belum ada transaksi pesanan terbaru.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </section>

            <!-- RINGKASAN STATUS PESANAN -->
            <section class="panel-card">

                <div class="panel-header">
                    <h2 class="panel-title">Ringkasan Status</h2>
                </div>

                @php
                    $total = $totalPesanan > 0 ? $totalPesanan : 1;
                @endphp

                <div class="status-item">

                    <div class="status-info">
                        <span>Menunggu Konfirmasi</span>
                        <strong>{{ $statusCounts['menunggu'] ?? 0 }}</strong>
                    </div>

                    <div class="progress-bar-bg">

                        <div class="progress-bar-fill"
                             style="width: {{ (($statusCounts['menunggu'] ?? 0) / $total) * 100 }}%; background: var(--yellow);">
                        </div>

                    </div>

                </div>

                <div class="status-item">

                    <div class="status-info">
                        <span>Diproses</span>
                        <strong>{{ $statusCounts['diproses'] ?? 0 }}</strong>
                    </div>

                    <div class="progress-bar-bg">

                        <div class="progress-bar-fill"
                             style="width: {{ (($statusCounts['diproses'] ?? 0) / $total) * 100 }}%; background: var(--blue);">
                        </div>

                    </div>

                </div>

                <div class="status-item">

                    <div class="status-info">
                        <span>Selesai</span>
                        <strong>{{ $statusCounts['selesai'] ?? 0 }}</strong>
                    </div>

                    <div class="progress-bar-bg">

                        <div class="progress-bar-fill"
                             style="width: {{ (($statusCounts['selesai'] ?? 0) / $total) * 100 }}%; background: var(--green);">
                        </div>

                    </div>

                </div>

                <div class="status-item">

                    <div class="status-info">
                        <span>Dibatalkan</span>
                        <strong>{{ $statusCounts['dibatalkan'] ?? 0 }}</strong>
                    </div>

                    <div class="progress-bar-bg">

                        <div class="progress-bar-fill"
                             style="width: {{ (($statusCounts['dibatalkan'] ?? 0) / $total) * 100 }}%; background: var(--red);">
                        </div>

                    </div>

                </div>

            </section>

        </div>

        <footer class="main-footer">
            © {{ date('Y') }} BreadSaver. Administrator Panel.
        </footer>

    </main>


@include('components.notifikasi-panel')
@include('components.breadsaver-extra-menu')
@include('components.breadsaver-advanced-menu')
</body>
</html>