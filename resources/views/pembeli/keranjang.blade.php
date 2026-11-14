<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - BreadSaver</title>
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

        .menu {
            display: flex;
            flex-direction: column;
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

        /* PROFIL INITIAL */
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
            text-transform: uppercase;
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

        /* CART CARD & TABEL */
        .cart-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .cart-header {
            padding: 24px 28px;
            border-bottom: 1px solid var(--border);
        }

        .cart-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .cart-header p {
            color: var(--muted);
            font-size: 14px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table th,
        .cart-table td {
            padding: 16px 24px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
            vertical-align: middle;
        }

        .cart-table th {
            background: #fafbfe;
            color: var(--muted);
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* KUANTITAS & TOMBOL ACTION */
        .qty-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            background: #e2e8f0;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            color: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: background-color 0.2s;
        }

        .qty-btn:hover {
            background: #cbd5e1;
        }

        .btn-delete {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-delete:hover {
            background: #fecaca;
        }

        .cart-footer-action {
            padding: 24px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fafbfe;
            flex-wrap: wrap;
            gap: 16px;
        }

        .total-price {
            font-size: 18px;
            font-weight: bold;
            color: var(--navy);
        }

        .btn-checkout {
            padding: 12px 24px;
            background: #237844;
            color: white;
            border: none;
            border-radius: 9px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .btn-checkout:hover {
            opacity: 0.9;
        }

        .empty-cart {
            min-height: 290px;
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
            opacity: 0.85;
            object-fit: contain;
        }

        .empty-cart h3 {
            font-size: 20px;
            color: var(--navy);
            margin-bottom: 10px;
            font-weight: 700;
        }

        .empty-cart p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .btn-catalog {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            background: var(--pink);
            color: var(--navy);
            border-radius: 9px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .btn-catalog:hover {
            opacity: 0.85;
        }

        /* FOOTER */
        .footer {
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

            .cart-header {
                padding: 18px 20px;
            }

            .cart-table th,
            .cart-table td {
                padding: 12px 14px;
                font-size: 13px;
            }

            .cart-footer-action {
                padding: 18px 20px;
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-checkout {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR WITH HOVER EXPAND -->
    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ route('pembeli.beranda') }}">
                <span class="logo-small" style="font-size: 24px;">🍞</span>
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

                <!-- PROFIL -->
                <a href="{{ route('pembeli.profil') }}"
                   class="nav-link {{ request()->routeIs('pembeli.profil') ? 'active' : '' }}">

                    @if(Auth::user()->foto_profil)
                        <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}"
                             class="nav-icon"
                             alt="Foto Profil"
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
                    <button type="submit"
                            class="nav-link"
                            style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left;">
                        <img src="{{ asset('images/icon-login.png') }}" class="nav-icon" alt="">
                        <span>Logout</span>
                    </button>
                </form>

            @else
                <a href="{{ route('login') }}" class="nav-link">
                    <img src="{{ asset('images/icon-login.png') }}" class="nav-icon" alt="">
                    <span>Login</span>
                </a>
            @endauth
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
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

            @php $totalSemua = 0; @endphp

            @if(isset($keranjangs) && $keranjangs->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($keranjangs as $item)
                                @php
                                    $harga = $item->harga_diskon > 0 ? $item->harga_diskon : $item->harga_asli;
                                    $subtotal = $harga * $item->jumlah;
                                    $totalSemua += $subtotal;
                                @endphp

                                <tr>
                                    <td style="font-weight: bold; color: var(--navy);">
                                        {{ $item->nama_produk ?? 'Produk' }}
                                    </td>

                                    <td>
                                        Rp{{ number_format($harga, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        <div class="qty-box">

                                            <!-- Tombol Kurang (-) -->
                                            <form action="{{ route('keranjang.kurangQty', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="qty-btn">-</button>
                                            </form>

                                            <span>{{ $item->jumlah }}</span>

                                            <!-- Tombol Tambah (+) -->
                                            <form action="{{ route('keranjang.tambahQty', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="qty-btn">+</button>
                                            </form>

                                        </div>
                                    </td>

                                    <td style="font-weight: bold;">
                                        Rp{{ number_format($subtotal, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        <!-- Tombol Hapus Item -->
                                        <form action="{{ route('keranjang.hapus', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-delete">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="cart-footer-action">
                    <div class="total-price">
                        Total Pembayaran: Rp{{ number_format($totalSemua, 0, ',', '.') }}
                    </div>

                    <!-- Tombol Checkout -->
                    <form action="{{ route('pembeli.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-checkout">
                            Checkout Pesanan Sekarang
                        </button>
                    </form>
                </div>

            @else

                <div class="empty-cart">
                    <img src="{{ asset('images/icon-keranjang.png') }}"
                         class="cart-icon"
                         alt="Keranjang Kosong">

                    <h3>Keranjang Masih Kosong</h3>

                    <p>
                        Kamu belum menambahkan produk ke keranjang.<br>
                        Yuk, pilih roti yang tersedia di katalog.
                    </p>

                    <a href="{{ route('pembeli.katalog') }}" class="btn-catalog">
                        Lihat Katalog Produk
                    </a>
                </div>

            @endif
        </section>

        <footer class="footer">
            © {{ date('Y') }} BreadSaver. Selamatkan Roti, Kurangi Food Waste.
        </footer>
    </main>

</body>
</html>
