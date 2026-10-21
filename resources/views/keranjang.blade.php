
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - BreadSaver</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --navy: #23365f;
            --pink: #ff8fb1;
            --background: #f8f9fc;
            --text: #26324b;
            --muted: #8991a4;
            --white: #ffffff;
            --border: #e7eaf1;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

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

        .main {
            margin-left: 240px;
            min-height: 100vh;
            padding: 32px;
        }

        .page-header {
            margin-bottom: 28px;
        }

        .page-header h1 {
            font-size: 27px;
            margin-bottom: 8px;
        }

        .page-header p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .cart-card {
            background: var(--white);
            border: 1px solid #edf0f6;
            border-radius: 15px;
            overflow: hidden;
        }

        .cart-header {
            padding: 24px 26px;
            border-bottom: 1px solid var(--border);
        }

        .cart-header h2 {
            font-size: 20px;
            margin-bottom: 7px;
        }

        .cart-header p {
            color: var(--muted);
            font-size: 14px;
        }

        .empty-cart {
            min-height: 270px;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .cart-icon {
            width: 76px;
            height: 76px;
            margin-bottom: 18px;
            object-fit: contain;
            opacity: 0.85;
        }

        .empty-cart h3 {
            font-size: 19px;
            margin-bottom: 10px;
        }

        .empty-cart p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 22px;
        }

        .btn-catalog {
            display: inline-block;
            padding: 12px 22px;
            background: var(--pink);
            color: var(--navy);
            border-radius: 9px;
            font-size: 14px;
            font-weight: bold;
            transition: opacity 0.2s;
        }

        .btn-catalog:hover {
            opacity: 0.85;
        }

        .footer {
            margin-top: 44px;
            padding: 22px 10px;
            border-top: 1px solid var(--border);
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }

        @media (max-width: 900px) {
            .main {
                padding: 24px;
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                overflow: visible;
                padding: 18px;
            }

            .logo-container {
                margin-bottom: 20px;
            }

            .logo {
                max-width: 170px;
            }

            .nav-title {
                margin-top: 15px;
            }

            .sidebar-bottom {
                margin-top: 10px;
            }

            .main {
                margin-left: 0;
                padding: 20px 15px;
            }

            .page-header h1 {
                font-size: 23px;
            }

            .cart-header {
                padding: 20px;
            }

            .empty-cart {
                min-height: 240px;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">

        <div class="logo-container">
            <a href="{{ url('/') }}">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="BreadSaver"
                    class="logo"
                >
            </a>
        </div>

        <p class="nav-title">Menu Utama</p>

        <a href="{{ url('/') }}" class="nav-link">
            <img src="{{ asset('images/icon-home.png') }}"
                 alt="" class="nav-icon">
            <span>Beranda</span>
        </a>

        <a href="{{ route('katalog') }}" class="nav-link">
            <img src="{{ asset('images/icon-product.png') }}"
                 alt="" class="nav-icon">
            <span>Katalog Produk</span>
        </a>

        <a href="{{ route('pesanan') }}" class="nav-link">
            <img src="{{ asset('images/icon-orders.png') }}"
                 alt="" class="nav-icon">
            <span>Pesanan</span>
        </a>

        <a href="{{ route('keranjang') }}" class="nav-link active">
            <img src="{{ asset('images/icon-keranjang.png') }}"
                 alt="" class="nav-icon">
            <span>Keranjang</span>
        </a>

        <div class="sidebar-bottom">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="nav-link"
                        style="width:100%;border:0;background:transparent;cursor:pointer;text-align:left;font-family:inherit;"
                    >
                        <img src="{{ asset('images/icon-login.png') }}"
                             alt="" class="nav-icon">
                        <span>Logout</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">
                    <img src="{{ asset('images/icon-login.png') }}"
                         alt="" class="nav-icon">
                    <span>Login</span>
                </a>
            @endauth
        </div>

    </aside>

    <main class="main">

        <header class="page-header">
            <h1>Keranjang Belanja</h1>
            <p>Periksa produk pilihanmu sebelum melanjutkan pembelian.</p>
        </header>

        <section class="cart-card">

            <div class="cart-header">
                <h2>Daftar Keranjang</h2>
                <p>Produk yang kamu pilih akan ditampilkan di sini.</p>
            </div>

            <div class="empty-cart">
                <img
                    src="{{ asset('images/icon-keranjang.png') }}"
                    alt=""
                    class="cart-icon"
                >

                <h3>Keranjang Masih Kosong</h3>

                <p>
                    Kamu belum menambahkan produk ke keranjang.<br>
                    Yuk, pilih roti yang tersedia di katalog.
                </p>

                <a href="{{ route('katalog') }}" class="btn-catalog">
                    Lihat Katalog Produk
                </a>
            </div>

        </section>

        <footer class="footer">
            © {{ date('Y') }} BreadSaver. Selamatkan Roti, Kurangi Food Waste.
        </footer>

    </main>

</body>
</html>
