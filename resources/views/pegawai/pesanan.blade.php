<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk - Pegawai BreadSaver</title>
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
            --green: #237844;
            --yellow: #956500;
            --blue: #315ba8;
            --red: #b53a3a;
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
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

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

        /* Tampilkan ikon kecil saat menguncup, logo penuh saat di-hover */
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

        .table-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 15px;
            overflow: hidden;
        }

        .table-header {
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
        }

        .table-header h2 {
            font-size: 19px;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .table-header p {
            color: var(--muted);
            font-size: 13px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        th, td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            vertical-align: middle;
        }

        th {
            background: #fafbfe;
            color: var(--muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* BADGE STATUS */
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-menunggu {
            background: #fff4d6;
            color: var(--yellow);
        }

        .status-diproses {
            background: #e6efff;
            color: var(--blue);
        }

        .status-selesai {
            background: #e2f7e9;
            color: var(--green);
        }

        .status-dibatalkan {
            background: #ffe6e6;
            color: var(--red);
        }

        /* BUTTON STYLING */
        .btn-action {
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-right: 4px;
        }

        .btn-action:hover {
            opacity: 0.85;
        }

        .btn-konfirmasi {
            background: var(--pink);
            color: var(--navy);
        }

        .btn-selesai {
            background: var(--green);
            color: #ffffff;
        }

        .btn-batal {
            background: #fee2e2;
            color: var(--red);
        }

        .alert-success {
            background: #e2f7e9;
            color: var(--green);
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .alert-error {
            background: #ffe6e6;
            color: var(--red);
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: bold;
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
        @media (max-width: 700px) {
            .sidebar {
                width: 75px;
                padding: 20px 10px;
                align-items: center;
            }

            .logo {
                width: 50px;
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
                margin-left: 75px;
                padding: 20px 14px;
            }

            th, td {
                padding: 10px 12px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <!-- SIDEBAR WITH HOVER EXPAND -->
    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ route('pegawai.beranda') }}">
                <span class="logo-small">🍞</span>
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="BreadSaver">
            </a>
        </div>
        <p class="nav-title">Pegawai Panel</p>
        
        <a href="{{ route('pegawai.beranda') }}" class="nav-link {{ request()->routeIs('pegawai.beranda') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt=""><span>Beranda</span>
        </a>
        <a href="{{ route('pegawai.katalog') }}" class="nav-link {{ request()->routeIs('pegawai.katalog') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt=""><span>Katalog Produk</span>
        </a>
        <a href="{{ route('pegawai.pesanan') }}" class="nav-link {{ request()->routeIs('pegawai.pesanan') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt=""><span>Pesanan Masuk</span>
        </a>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left;">
                    <img src="{{ asset('images/icon-login.png') }}" class="nav-icon" alt=""><span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">
        <header class="page-header">
            <h1>Pesanan Masuk</h1>
            <p>Daftar pesanan dari pelanggan yang harus diproses oleh staf toko.</p>
        </header>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        <section class="table-card">
            <div class="table-header">
                <h2>Daftar Pesanan</h2>
                <p>Data pesanan yang masuk ke sistem toko.</p>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Waktu Pesan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi Pegawai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanans ?? [] as $pesanan)
                            @php
                                $pesananId = $pesanan->id_pesanan ?? $pesanan->id;
                                $status = $pesanan->status ?? 'Menunggu Konfirmasi';
                                
                                $statusClass = 'status-menunggu';
                                if($status == 'Diproses') $statusClass = 'status-diproses';
                                elseif($status == 'Selesai') $statusClass = 'status-selesai';
                                elseif($status == 'Dibatalkan') $statusClass = 'status-dibatalkan';
                            @endphp
                            <tr>
                                <td style="font-weight: bold; color: var(--navy);">#{{ $pesananId }}</td>
                                <td>{{ $pesanan->user->nama ?? $pesanan->user->name ?? 'User #'.$pesanan->id_user }}</td>
                                <td>{{ $pesanan->created_at ? \Carbon\Carbon::parse($pesanan->created_at)->format('d-m-Y H:i') : ($pesanan->tanggal_pesan ? \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d-m-Y H:i') : '-') }}</td>
                                <td style="font-weight: bold;">Rp {{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    <span class="status {{ $statusClass }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td>
                                    @if($status == 'Menunggu Konfirmasi')
                                        <!-- Tombol Konfirmasi -->
                                        <form action="{{ route('pegawai.pesanan.proses', $pesananId) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-konfirmasi">Konfirmasi & Siapkan</button>
                                        </form>
                                        <!-- Tombol Batalkan -->
                                        <form action="{{ route('pegawai.pesanan.batal', $pesananId) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-batal" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan #{{ $pesananId }}?')">Batalkan</button>
                                        </form>
                                    @elseif($status == 'Diproses')
                                        <!-- Tombol Selesai -->
                                        <form action="{{ route('pegawai.pesanan.selesai', $pesananId) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-selesai">Tandai Selesai</button>
                                        </form>
                                        <!-- Tombol Batalkan -->
                                        <form action="{{ route('pegawai.pesanan.batal', $pesananId) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-batal" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan #{{ $pesananId }}?')">Batalkan</button>
                                        </form>
                                    @elseif($status == 'Selesai')
                                        <span style="color: var(--green); font-weight: bold; font-size: 12px;">Selesai Disiapkan</span>
                                    @elseif($status == 'Dibatalkan')
                                        <span style="color: var(--red); font-weight: bold; font-size: 12px;">Pesanan Dibatalkan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: var(--muted);">Belum ada pesanan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="main-footer">© {{ date('Y') }} BreadSaver. Staff Panel.</footer>
    </main>
</body>
</html>