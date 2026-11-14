<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Pegawai - BreadSaver</title>
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
            color: inherit;
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
            width: 70px;
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

        /* =========================================================
           PROFIL PEGAWAI
        ========================================================= */

        .profile-link {
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
            transition: background-color 0.2s, color 0.2s;
        }

        .profile-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .profile-link.active {
            background: var(--pink);
            color: var(--navy);
            font-weight: normal;
        }

        .profile-link.active:hover {
            background: var(--pink);
            color: var(--navy);
            font-weight: normal;
        }

        .profile-link span {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .profile-link span {
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
            flex-shrink: 0;
        }

        .profile-link.active .profile-initial {
            background: var(--navy);
            color: var(--white);
        }

        .profile-photo {
            width: 24px;
            height: 24px;
            min-width: 24px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        /* MAIN CONTENT STANDAR PANEL */
        .main {
            margin-left: 70px;
            min-height: 100vh;
            padding: 32px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .page-header {
            margin-bottom: 28px;
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

        /* SUMMARY GRID */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .summary-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 22px;
        }

        .summary-label {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 10px;
        }

        .summary-number {
            color: var(--navy);
            font-size: 28px;
            font-weight: bold;
        }

        /* STATUS GRID */
        .section-title {
            font-size: 19px;
            font-weight: bold;
            color: var(--navy);
            margin-bottom: 16px;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .status-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 20px;
        }

        .status-card p {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .status-card h2 {
            font-size: 24px;
            font-weight: bold;
        }

        .text-warning {
            color: var(--yellow);
        }

        .text-info {
            color: var(--blue);
        }

        .text-success {
            color: var(--green);
        }

        .text-danger {
            color: var(--red);
        }

        /* TABEL PESANAN TERBARU */
        .table-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-header h2 {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
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

        th,
        td {
            padding: 14px 20px;
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

        .badge-status {
            display: inline-block;
            padding: 4px 10px;
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
        @media (max-width: 1000px) {
            .summary-grid,
            .status-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

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

            .nav-link,
            .profile-link {
                justify-content: center;
                padding: 10px 5px;
            }

            .nav-link span,
            .profile-link span {
                display: none;
            }

            .sidebar-bottom {
                width: 100%;
            }

            .main {
                margin-left: 75px;
                padding: 20px 14px;
            }

            .summary-grid,
            .status-grid {
                grid-template-columns: 1fr;
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

        <a href="{{ route('pegawai.beranda') }}"
           class="nav-link {{ request()->routeIs('pegawai.beranda') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt="">
            <span>Beranda</span>
        </a>

        <a href="{{ route('pegawai.katalog') }}"
           class="nav-link {{ request()->routeIs('pegawai.katalog') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt="">
            <span>Katalog Produk</span>
        </a>

        <a href="{{ route('pegawai.pesanan') }}"
           class="nav-link {{ request()->routeIs('pegawai.pesanan') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt="">
            <span>Pesanan Masuk</span>
        </a>

        <div class="sidebar-bottom">

            <!-- PROFIL PEGAWAI -->
            <a href="{{ route('pegawai.profil') }}"
               class="profile-link {{ request()->routeIs('pegawai.profil') ? 'active' : '' }}">

                @if(Auth::user()->foto_profil)

                    <img
                        src="{{ asset('storage/' . Auth::user()->foto_profil) }}"
                        class="profile-photo"
                        alt="Foto Profil"
                    >

                @else

                    <div class="profile-initial">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    </div>

                @endif

                <span>Profil</span>
            </a>

            <!-- LOGOUT -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="nav-link"
                    style="width:100%; border:0; background:transparent; cursor:pointer; text-align:left;"
                >
                    <img
                        src="{{ asset('images/icon-login.png') }}"
                        class="nav-icon"
                        alt=""
                    >

                    <span>Logout</span>
                </button>
            </form>

        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="main">

        <header class="page-header">
            <h1>Beranda Pegawai Toko</h1>

            <p>
                Selamat bekerja. Ringkasan status produk dan pesanan masuk di BreadSaver.
            </p>
        </header>

        <!-- KARTU RINGKASAN UTAMA -->
        <section class="summary-grid">

            <article class="summary-card">
                <p class="summary-label">
                    Total Katalog Produk
                </p>

                <p class="summary-number">
                    {{ $jumlahProduk ?? $totalProduk ?? 0 }}
                </p>
            </article>

            <article class="summary-card">
                <p class="summary-label">
                    Pesanan Perlu Diproses
                </p>

                <p class="summary-number text-warning">
                    {{ $pesananMenunggu ?? $jumlahPesananMenunggu ?? 0 }}
                </p>
            </article>

            <article class="summary-card">
                <p class="summary-label">
                    Total Semua Pesanan
                </p>

                <p class="summary-number">
                    {{ $jumlahPesanan ?? 0 }}
                </p>
            </article>

        </section>

        <!-- KARTU STATUS PESANAN DETIL -->
        <h2 class="section-title">
            Status Pesanan Hari Ini
        </h2>

        <section class="status-grid">

            <article class="status-card">
                <p>Menunggu Konfirmasi</p>

                <h2 class="text-warning">
                    {{ $pesananMenunggu ?? 0 }}
                </h2>
            </article>

            <article class="status-card">
                <p>Sedang Diproses</p>

                <h2 class="text-info">
                    {{ $pesananDiproses ?? 0 }}
                </h2>
            </article>

            <article class="status-card">
                <p>Pesanan Selesai</p>

                <h2 class="text-success">
                    {{ $pesananSelesai ?? 0 }}
                </h2>
            </article>

            <article class="status-card">
                <p>Pesanan Dibatalkan</p>

                <h2 class="text-danger">
                    {{ $pesananDibatalkan ?? 0 }}
                </h2>
            </article>

        </section>

        <footer class="main-footer">
            © {{ date('Y') }} BreadSaver. Pegawai Panel.
        </footer>

    </main>

</body>
</html>
