
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BreadSaver - Selamatkan Roti</title>

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

        .menu-title {
            color: #c4cce0;
            font-size: 12px;
            letter-spacing: 1px;
            margin: 0 10px 18px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 58px;
            padding: 12px 14px;
            border-radius: 12px;
            color: var(--white);
            font-size: 15px;
            transition: background-color 0.2s, color 0.2s;
        }

        .menu-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .menu-link.active {
            background: var(--pink);
            color: var(--navy);
            font-weight: bold;
        }

        .menu-icon {
            width: 28px;
            height: 28px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 28px;
        }

        .sidebar-bottom .menu-link {
            margin-bottom: 8px;
        }

        .main {
            margin-left: 240px;
            min-height: 100vh;
            padding: 32px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .welcome-title {
            font-size: 30px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 10px;
        }

        .welcome-subtitle {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.6;
        }

        .register-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 22px;
            border-radius: 9px;
            background: var(--pink);
            color: var(--navy);
            font-size: 14px;
            font-weight: bold;
            white-space: nowrap;
            transition: background-color 0.2s;
        }

        .register-button:hover {
            background: #ff7da5;
        }

        .banner {
            width: 100%;
            margin-bottom: 32px;
        }

        .banner img {
            display: block;
            width: 100%;
            height: auto;
            border-radius: 16px;
            object-fit: cover;
        }

        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }

        .section-title {
            font-size: 24px;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .section-subtitle {
            font-size: 14px;
            color: var(--muted);
        }

        .product-count {
            padding: 10px 16px;
            border-radius: 30px;
            background: var(--pink-light);
            color: #ed6590;
            font-size: 13px;
            white-space: nowrap;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 22px;
        }

        .product-card {
            overflow: hidden;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(35, 54, 95, 0.08);
        }

        .product-image-container {
            width: 100%;
            height: 190px;
            overflow: hidden;
            background: var(--pink-light);
        }

        .product-image {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .product-info {
            padding: 18px;
        }

        .product-name {
            color: var(--navy);
            font-size: 17px;
            margin-bottom: 12px;
            overflow-wrap: anywhere;
        }

        .price-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 14px;
        }

        .price-original {
            color: var(--muted);
            font-size: 13px;
            text-decoration: line-through;
        }

        .price-discount {
            color: #ed6590;
            font-size: 18px;
            font-weight: bold;
        }

        .product-detail {
            display: flex;
            flex-direction: column;
            gap: 8px;
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 16px;
        }

        .buy-button {
            display: block;
            width: 100%;
            padding: 12px;
            text-align: center;
            background: var(--pink);
            color: var(--navy);
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 9px;
            cursor: pointer;
        }

        .buy-button:hover {
            background: #ff7da5;
        }

        .buy-button:disabled {
            background: #d5d8df;
            color: #737987;
            cursor: not-allowed;
        }

        .empty-state {
            width: 100%;
            min-height: 295px;
            padding: 35px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .empty-state img {
            width: 110px;
            height: 110px;
            object-fit: contain;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 20px;
            color: var(--navy);
            margin-bottom: 12px;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .footer {
            margin-top: 44px;
            padding: 24px 0;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
            text-align: center;
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 210px;
                padding: 24px 16px;
            }

            .main {
                margin-left: 210px;
                padding: 24px;
            }

            .logo {
                width: 160px;
            }

            .welcome-title {
                font-size: 26px;
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                width: 76px;
                padding: 22px 10px;
                align-items: center;
            }

            .logo-container {
                margin-bottom: 35px;
            }

            .logo {
                width: 55px;
            }

            .menu-title {
                display: none;
            }

            .menu {
                width: 100%;
                align-items: center;
            }

            .menu-link {
                width: 54px;
                min-height: 54px;
                justify-content: center;
                padding: 10px;
            }

            .menu-link span {
                display: none;
            }

            .menu-icon {
                width: 27px;
                height: 27px;
            }

            .sidebar-bottom {
                width: 100%;
                display: flex;
                justify-content: center;
            }

            .main {
                margin-left: 76px;
                padding: 20px 14px;
            }

            .welcome-title {
                font-size: 23px;
            }

            .welcome-subtitle {
                font-size: 13px;
            }

            .section-heading {
                align-items: flex-start;
            }

            .section-title {
                font-size: 20px;
            }

            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
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

        <p class="menu-title">MENU UTAMA</p>

        <nav class="menu">

            <a
                href="{{ url('/') }}"
                class="menu-link {{ request()->routeIs('home', 'pembeli.beranda') ? 'active' : '' }}"
            >
                <img
                    src="{{ asset('images/icon-home.png') }}"
                    alt=""
                    class="menu-icon"
                >
                <span>Beranda</span>
            </a>

            <a href="{{ route('katalog') }}" class="menu-link">
                <img
                    src="{{ asset('images/icon-product.png') }}"
                    alt=""
                    class="menu-icon"
                >
                <span>Katalog Produk</span>
            </a>

            <a href="{{ url('/pesanan') }}" class="menu-link">
                <img
                    src="{{ asset('images/icon-orders.png') }}"
                    alt=""
                    class="menu-icon"
                >
                <span>Pesanan</span>
            </a>

            <a href="{{ url('/keranjang') }}" class="menu-link">
                <img
                    src="{{ asset('images/icon-keranjang.png') }}"
                    alt=""
                    class="menu-icon"
                >
                <span>Keranjang</span>
            </a>

        </nav>

        <div class="sidebar-bottom">

            @guest
                <a href="{{ route('login') }}" class="menu-link">
                    <img
                        src="{{ asset('images/icon-login.png') }}"
                        alt=""
                        class="menu-icon"
                    >
                    <span>Login</span>
                </a>
            @endguest

            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button
                        type="submit"
                        class="menu-link"
                        style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left;"
                    >
                        <img
                            src="{{ asset('images/icon-login.png') }}"
                            alt=""
                            class="menu-icon"
                        >
                        <span>Logout</span>
                    </button>
                </form>
            @endauth

        </div>
    </aside>


    <!-- KONTEN UTAMA -->
    <main class="main">

        <header class="topbar">

            <div>
                <h1 class="welcome-title">Selamat Datang!</h1>

                <p class="welcome-subtitle">
                    Temukan roti favoritmu dengan harga lebih hemat.
                </p>
            </div>

            @guest
                <a href="{{ route('register') }}" class="register-button">
                    Register
                </a>
            @endguest

        </header>


        <!-- BANNER -->
        <section class="banner">
            <img
                src="{{ asset('images/bread-banner.png') }}"
                alt="Banner BreadSaver"
            >
        </section>


        <!-- PRODUK DARI DATABASE -->
        <section class="products-section">

            <div class="section-heading">

                <div>
                    <h2 class="section-title">
                        Roti Diskon Hari Ini
                    </h2>

                    <p class="section-subtitle">
                        Pilihan roti yang tersedia untuk kamu.
                    </p>
                </div>

                <span class="product-count">
                    {{ $produks->count() }} Produk
                </span>

            </div>


            @if($produks->count() > 0)

                <div class="product-grid">

                    @foreach($produks as $produk)

                        @php
                            $hargaAsli = (float) $produk->harga_asli;
                            $hargaDiskon = (float) $produk->harga_diskon;
                            $stok = (int) $produk->stok;

                            // Foto diambil dari kolom foto database.
                            // Jika kosong, gunakan gambar bawaan.
                            $gambarProduk = $produk->foto
                                ? asset('storage/' . $produk->foto)
                                : asset('images/bread.png');
                        @endphp


                        <article class="product-card">

                            <!-- FOTO PRODUK -->
                            <div class="product-image-container">

                                <img
                                    src="{{ $gambarProduk }}"
                                    alt="{{ $produk->nama_produk }}"
                                    class="product-image"
                                    onerror="this.onerror=null; this.src='{{ asset('images/bread.png') }}';"
                                >

                            </div>


                            <!-- INFORMASI PRODUK -->
                            <div class="product-info">

                                <h3 class="product-name">
                                    {{ $produk->nama_produk }}
                                </h3>


                                <!-- HARGA PRODUK -->
                                <div class="price-row">

                                    @if($hargaAsli > $hargaDiskon)

                                        <span class="price-original">
                                            Rp{{ number_format($hargaAsli, 0, ',', '.') }}
                                        </span>

                                    @endif

                                    <span class="price-discount">
                                        Rp{{ number_format($hargaDiskon, 0, ',', '.') }}
                                    </span>

                                </div>


                                <!-- DETAIL PRODUK -->
                                <div class="product-detail">

                                    <span>
                                        Stok: {{ $stok }}
                                    </span>

                                    @if($produk->expired_at)

                                        <span>
                                            Kedaluwarsa:
                                            {{ $produk->expired_at->format('d-m-Y') }}
                                        </span>

                                    @endif

                                    @if($produk->deskripsi)

                                        <span>
                                            {{ $produk->deskripsi }}
                                        </span>

                                    @endif

                                </div>


                                <!-- TOMBOL PEMBELIAN -->
                                @if($stok > 0)

                                    @auth

                                        <form
                                            action="{{ route('keranjang.tambah', $produk->id_produk) }}"
                                            method="POST"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="buy-button"
                                            >
                                                Tambah ke Keranjang
                                            </button>
                                        </form>

                                    @else

                                        <a
                                            href="{{ route('login') }}"
                                            class="buy-button"
                                        >
                                            Login untuk Membeli
                                        </a>

                                    @endauth

                                @else

                                    <button
                                        type="button"
                                        class="buy-button"
                                        disabled
                                    >
                                        Stok Habis
                                    </button>

                                @endif

                            </div>

                        </article>

                    @endforeach

                </div>

            @else

                <!-- JIKA BELUM ADA PRODUK -->
                <div class="empty-state">

                    <img
                        src="{{ asset('images/bread.png') }}"
                        alt="Roti"
                    >

                    <h3>Belum Ada Roti Tersedia</h3>

                    <p>
                        Saat ini belum ada produk roti yang dijual.
                        Silakan cek kembali nanti.
                    </p>

                </div>

            @endif

        </section>


        <!-- FOOTER -->
        <footer class="footer">
            © {{ date('Y') }} BreadSaver.
            Selamatkan Roti, Kurangi Food Waste.
        </footer>

    </main>

</body>
</html>
    
