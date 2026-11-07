
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
            --background: #f8f9fc;
            --text: #26324b;
            --muted: #8991a4;
            --white: #ffffff;
            --border: #e7eaf1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        /* SIDEBAR */

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
            z-index: 1000;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 56px;
        }

        .logo {
            width: 180px;
            object-fit: contain;
        }

        .nav-title {
            color: #c4cce0;
            font-size: 12px;
            letter-spacing: 1px;
            margin: 0 10px 18px;
            text-transform: uppercase;
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
            text-decoration: none;
            border: none;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
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
        }

        .sidebar-bottom {
            margin-top: auto;
        }

        /* MAIN CONTENT */

        .main {
            margin-left: 240px;
            min-height: 100vh;
            padding: 32px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 27px;
            margin-bottom: 8px;
        }

        .page-header p {
            color: var(--muted);
            font-size: 14px;
        }

        /* SUMMARY */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
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
            font-size: 30px;
            font-weight: bold;
        }

        /* STATUS */

        .section-title {
            font-size: 19px;
            margin-bottom: 18px;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .status-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 22px;
        }

        .status-card p {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 12px;
        }

        .status-card h2 {
            color: var(--navy);
            font-size: 27px;
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
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 700px) {
            .sidebar {
                width: 75px;
                padding: 20px 10px;
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

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="logo-container">
            <a href="{{ route('pegawai.beranda') }}">
                <img
                    src="{{ asset('images/logo.png') }}"
                    class="logo"
                    alt="BreadSaver">
            </a>
        </div>

        <p class="nav-title">Pegawai Panel</p>

        <a href="{{ route('pegawai.beranda') }}"
           class="nav-link active">

            <img src="{{ asset('images/icon-home.png') }}"
                 class="nav-icon" alt="">

            <span>Beranda</span>
        </a>

        <a href="{{ route('pegawai.katalog') }}"
           class="nav-link">

            <img src="{{ asset('images/icon-product.png') }}"
                 class="nav-icon" alt="">

            <span>Katalog (Produk)</span>
        </a>

        <a href="{{ route('pegawai.pesanan') }}"
           class="nav-link">

            <img src="{{ asset('images/icon-orders.png') }}"
                 class="nav-icon" alt="">

            <span>Pesanan Masuk</span>
        </a>

        <div class="sidebar-bottom">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                        class="nav-link"
                        style="width:100%; border:0; background:transparent; cursor:pointer; text-align:left;">

                    <img src="{{ asset('images/icon-login.png') }}"
                         class="nav-icon" alt="">

                    <span>Logout</span>

                </button>
            </form>

        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">

        <header class="page-header">

            <h1>Beranda Pegawai Toko</h1>

            <p>
                Selamat bekerja. Berikut informasi produk dan pesanan
                yang tercatat dalam database BreadSaver.
            </p>

        </header>

        <!-- DATA UTAMA -->
        <section class="summary-grid">

            <article class="summary-card">

                <p class="summary-label">
                    Total Produk
                </p>

                <p class="summary-number">
                    {{ $jumlahProduk }}
                </p>

            </article>

            <article class="summary-card">

                <p class="summary-label">
                    Total Pesanan
                </p>

                <p class="summary-number">
                    {{ $jumlahPesanan }}
                </p>

            </article>

            <article class="summary-card">

                <p class="summary-label">
                    Total Pengguna
                </p>

                <p class="summary-number">
                    {{ $jumlahAkun }}
                </p>

            </article>

        </section>

        <!-- STATUS PESANAN -->
        <h2 class="section-title">
            Ringkasan Status Pesanan
        </h2>

        <section class="status-grid">

            <article class="status-card">

                <p>Menunggu Konfirmasi</p>

                <h2>{{ $pesananMenunggu }}</h2>

            </article>

            <article class="status-card">

                <p>Sedang Diproses</p>

                <h2>{{ $pesananDiproses }}</h2>

            </article>

            <article class="status-card">

                <p>Pesanan Selesai</p>

                <h2>{{ $pesananSelesai }}</h2>

            </article>

            <article class="status-card">

                <p>Pesanan Dibatalkan</p>

                <h2>{{ $pesananDibatalkan }}</h2>

            </article>

        </section>

        <footer class="main-footer">
            © {{ date('Y') }} BreadSaver. Staff Panel.
        </footer>

    </main>

</body>
</html>
