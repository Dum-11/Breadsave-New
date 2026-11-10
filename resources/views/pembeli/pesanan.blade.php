<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - BreadSaver</title>
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
            --red: #b53a3a;
            --green: #237844;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--background);
            color: var(--text);
            overflow-x: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* SIDEBAR STANDAR PANEL */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            padding: 28px 22px;
            background: var(--navy);
            color: var(--white);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 1000;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 56px;
        }

        .logo {
            width: 180px;
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .nav-title {
            color: #c4cce0;
            font-size: 12px;
            letter-spacing: 1px;
            margin: 0 10px 18px;
            text-transform: uppercase;
        }

        .menu {
            display: flex;
            flex-direction: column;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 58px;
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 8px;
            color: var(--white);
            font-size: 15px;
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
            width: 28px;
            height: 28px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 28px;
        }

        /* MAIN CONTENT STANDAR PANEL */
        .main {
            margin-left: 240px;
            min-height: 100vh;
            padding: 32px;
        }

        .page-header {
            margin-bottom: 28px;
        }

        .page-header h1 {
            font-size: 30px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .page-header p {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.6;
        }

        /* ALERT STYLING */
        .alert {
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: bold;
        }

        .alert-success {
            background: #e2f7e9;
            color: var(--green);
            border: 1px solid #b7ebc6;
        }

        .alert-error {
            background: #ffe6e6;
            color: var(--red);
            border: 1px solid #fecaca;
        }

        /* KOTAK PERINGATAN BELUM LOGIN */
        .auth-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 50px 30px;
            text-align: center;
            max-width: 600px;
            margin: 40px auto;
        }

        .auth-card h3 {
            color: var(--navy);
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .auth-card p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .btn-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--pink);
            color: var(--navy);
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 14px;
            transition: opacity 0.2s;
        }

        .btn-login:hover {
            opacity: 0.85;
        }

        /* RINGKASAN RINGKAS */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }

        .summary-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
        }

        .summary-label {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 10px;
        }

        .summary-number {
            color: var(--navy);
            font-size: 26px;
            font-weight: bold;
        }

        /* TABEL PESANAN */
        .table-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .table-header {
            padding: 24px 28px;
            border-bottom: 1px solid var(--border);
        }

        .table-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .table-header p {
            color: var(--muted);
            font-size: 14px;
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
            padding: 16px 24px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
            vertical-align: middle;
        }

        th {
            background: #fafbfe;
            color: var(--muted);
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* STATUS STYLING */
        .status {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-menunggu { background: #fff4d6; color: #956500; }
        .status-diproses { background: #e6efff; color: #315ba8; }
        .status-selesai { background: #e2f7e9; color: var(--green); }
        .status-dibatalkan { background: #ffe6e6; color: var(--red); }

        .btn-cancel {
            background: #fee2e2;
            color: var(--red);
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-cancel:hover {
            opacity: 0.85;
        }

        .empty-pesanan {
            padding: 45px 20px;
            text-align: center;
        }

        .empty-pesanan h3 {
            font-size: 20px;
            color: var(--navy);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .empty-pesanan p {
            color: var(--muted);
            font-size: 14px;
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

            .page-header h1 {
                font-size: 23px;
            }

            .table-header {
                padding: 18px 20px;
            }

            th, td {
                padding: 12px 14px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR NAVIGASI -->
    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ route('pembeli.beranda') }}">
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo BreadSaver">
            </a>
        </div>
        <p class="nav-title">Menu Utama</p>

        <nav class="menu">
            <a href="{{ route('pembeli.beranda') }}" class="nav-link {{ request()->routeIs('pembeli.beranda', 'home') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt=""><span>Beranda</span>
            </a>
            <a href="{{ route('pembeli.katalog') }}" class="nav-link {{ request()->routeIs('pembeli.katalog', 'katalog') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt=""><span>Katalog Produk</span>
            </a>
            <a href="{{ route('pembeli.pesanan') }}" class="nav-link {{ request()->routeIs('pembeli.pesanan', 'pesanan') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt=""><span>Pesanan</span>
            </a>
            <a href="{{ route('pembeli.keranjang') }}" class="nav-link {{ request()->routeIs('pembeli.keranjang', 'keranjang') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-keranjang.png') }}" class="nav-icon" alt=""><span>Keranjang</span>
            </a>
        </nav>

        <div class="sidebar-bottom">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link" style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left;">
                        <img src="{{ asset('images/icon-login.png') }}" class="nav-icon" alt=""><span>Logout</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">
                    <img src="{{ asset('images/icon-login.png') }}" class="nav-icon" alt=""><span>Login</span>
                </a>
            @endauth
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="main">
        <header class="page-header">
            <h1>Pesanan</h1>
            <p>Pantau jumlah dan status pesanan di BreadSaver.</p>
        </header>

        @auth
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <!-- TAMPILAN JIKA USER SUDAH LOGIN -->
            <section class="summary-grid">
                <article class="summary-card"><p class="summary-label">Total Pesanan</p><p class="summary-number">{{ $totalPesanan ?? 0 }}</p></article>
                <article class="summary-card"><p class="summary-label">Menunggu Konfirmasi</p><p class="summary-number">{{ $menungguKonfirmasi ?? 0 }}</p></article>
                <article class="summary-card"><p class="summary-label">Sedang Diproses</p><p class="summary-number">{{ $diproses ?? 0 }}</p></article>
                <article class="summary-card"><p class="summary-label">Pesanan Selesai</p><p class="summary-number">{{ $selesai ?? 0 }}</p></article>
                <article class="summary-card"><p class="summary-label">Pesanan Dibatalkan</p><p class="summary-number">{{ $dibatalkan ?? 0 }}</p></article>
            </section>

            <section class="table-card">
                <div class="table-header">
                    <h2>Daftar Pesanan</h2>
                    <p>Data pesanan yang tersimpan di database.</p>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Nomor Pesanan</th>
                                <th>ID Pengguna</th>
                                <th>Tanggal Pesan</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pesanans ?? [] as $pesanan)
                                @php
                                    $pesananId = $pesanan->id_pesanan ?? $pesanan->id;
                                    $statusClasses = [
                                        'Menunggu Konfirmasi' => 'status-menunggu',
                                        'Diproses' => 'status-diproses',
                                        'Selesai' => 'status-selesai',
                                        'Dibatalkan' => 'status-dibatalkan',
                                    ];
                                    $statusClass = $statusClasses[$pesanan->status] ?? 'status-menunggu';
                                @endphp
                                <tr>
                                    <td style="font-weight: bold; color: var(--navy);">#{{ $pesananId }}</td>
                                    <td>{{ $pesanan->id_user }}</td>
                                    <td>{{ $pesanan->tanggal_pesan ? \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d-m-Y H:i') : '-' }}</td>
                                    <td style="font-weight: bold;">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="status {{ $statusClass }}">{{ $pesanan->status }}</span>
                                    </td>
                                    <td>
                                        @if($pesanan->status === 'Menunggu Konfirmasi')
                                            <form action="{{ route('pembeli.pesanan.batal', $pesananId) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn-cancel" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan #{{ $pesananId }}?')">
                                                    Batalkan Pesanan
                                                </button>
                                            </form>
                                        @else
                                            <span style="color: var(--muted); font-size: 12px;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-pesanan">
                                        <h3>Belum Ada Pesanan</h3>
                                        <p>Data pesanan belum tersedia.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <!-- TAMPILAN JIKA USER BELUM LOGIN (GUEST) -->
            <section class="auth-card">
                <h3>Anda Belum Masuk ke Akun</h3>
                <p>Silakan masuk terlebih dahulu untuk melihat dan memantau status pesanan Anda.</p>
                <a href="{{ route('login') }}" class="btn-login">Login Sekarang</a>
            </section>
        @endauth

        <footer class="main-footer">© {{ date('Y') }} BreadSaver. Selamatkan Roti, Kurangi Food Waste.</footer>
    </main>
</body>
</html>