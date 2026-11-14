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

        .menu-title {
            color: #c4cce0;
            font-size: 11px;
            letter-spacing: 1px;
            margin: 0 10px 14px;
            text-transform: uppercase;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .menu-title {
            opacity: 1;
        }

        .menu {
            display: flex;
            flex-direction: column;
        }

        .menu-link {
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

        .menu-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .menu-link.active {
            background: var(--pink);
            color: var(--navy);
            font-weight: bold;
        }

        .menu-icon {
            width: 24px;
            height: 24px;
            object-fit: contain;
            flex-shrink: 0;
        }

        /*
        =========================================================
        AVATAR PROFIL SIDEBAR
        =========================================================
        */

        .profile-avatar {
            width: 24px;
            height: 24px;
            min-width: 24px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--pink);
            color: var(--navy);
            font-size: 12px;
            font-weight: bold;
            overflow: hidden;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /*
        =========================================================
        HANYA TEKS MENU YANG DISEMBUNYIKAN SAAT SIDEBAR MENGECIL
        =========================================================
        */

        .menu-link > span:not(.profile-avatar) {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .menu-link > span:not(.profile-avatar) {
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

        /* BANNER */
        .banner {
            width: 100%;
            margin-top: 25px;
            margin-bottom: 32px;
        }

        .banner img {
            display: block;
            width: 100%;
            height: auto;
            border-radius: 16px;
            object-fit: cover;
        }

        /* PRODUK SECTION */
        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .section-subtitle {
            font-size: 14px;
            color: var(--muted);
        }

        .product-count {
            padding: 8px 16px;
            border-radius: 30px;
            background: var(--pink-light);
            color: #ed6590;
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
        }

        /* GRID KATALOG RAPI & SERAGAM */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 24px;
            align-items: stretch;
        }

        .product-card {
            overflow: hidden;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(35, 54, 95, 0.08);
        }

        /* WADAH GAMBAR PRODUK PRESISI */
        .product-image-container {
            height: 190px;
            width: 100%;
            overflow: hidden;
            background: var(--pink-light);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* BODY KARTU DENGAN FLEX-GROW */
        .product-info {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-name {
            color: var(--navy);
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 44px;
            overflow-wrap: anywhere;
        }

        .price-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
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
            gap: 4px;
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 10px;
        }

        .product-description {
            font-size: 13px;
            color: #6c757d;
            line-height: 1.5;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* WADAH TOMBOL DIKUNCI DI DASAR KARTU */
        .product-action {
            margin-top: auto;
            padding-top: 8px;
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
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .buy-button:hover {
            opacity: 0.85;
        }

        .empty-state {
            background: var(--white);
            padding: 40px;
            text-align: center;
            border-radius: 16px;
            border: 1px solid var(--border);
        }

        .empty-state h3 {
            font-size: 20px;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 14px;
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

            .menu-title {
                display: none;
            }

            .menu-link {
                justify-content: center;
                padding: 10px 5px;
            }

            /*
            Hanya teks menu yang disembunyikan.
            Avatar profil tetap terlihat.
            */
            .menu-link > span:not(.profile-avatar) {
                display: none;
            }

            .sidebar-bottom {
                width: 100%;
            }

            .main {
                margin-left: 75px;
                padding: 20px 14px;
            }

            .welcome-title {
                font-size: 23px;
            }

            .welcome-subtitle {
                font-size: 13px;
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

    <!-- SIDEBAR WITH HOVER EXPAND -->
    <aside class="sidebar">

        <div class="logo-container">
            <a href="{{ route('pembeli.beranda') }}">
                <span class="logo-small" style="font-size: 24px;">🍞</span>

                <img src="{{ asset('images/logo.png') }}"
                     alt="BreadSaver"
                     class="logo">
            </a>
        </div>

        <p class="menu-title">MENU UTAMA</p>

        <nav class="menu">

            <a href="{{ route('pembeli.beranda') }}"
               class="menu-link {{ request()->routeIs('pembeli.beranda', 'home') ? 'active' : '' }}">

                <img src="{{ asset('images/icon-home.png') }}"
                     class="menu-icon"
                     alt="">

                <span>Beranda</span>
            </a>


            <a href="{{ route('pembeli.katalog') }}"
               class="menu-link {{ request()->routeIs('pembeli.katalog', 'katalog') ? 'active' : '' }}">

                <img src="{{ asset('images/icon-product.png') }}"
                     class="menu-icon"
                     alt="">

                <span>Katalog Produk</span>
            </a>


            <a href="{{ route('pembeli.pesanan') }}"
               class="menu-link {{ request()->routeIs('pembeli.pesanan', 'pesanan') ? 'active' : '' }}">

                <img src="{{ asset('images/icon-orders.png') }}"
                     class="menu-icon"
                     alt="">

                <span>Pesanan</span>
            </a>


            <a href="{{ route('pembeli.keranjang') }}"
               class="menu-link {{ request()->routeIs('pembeli.keranjang', 'keranjang') ? 'active' : '' }}">

                <img src="{{ asset('images/icon-keranjang.png') }}"
                     class="menu-icon"
                     alt="">

                <span>Keranjang</span>
            </a>

        </nav>


        <div class="sidebar-bottom">

            @auth

                <!-- PROFIL -->
                <a href="{{ route('pembeli.profil') }}"
                   class="menu-link {{ request()->routeIs('pembeli.profil') ? 'active' : '' }}">

                    @if(!empty(Auth::user()->foto_profil))

                        <span class="profile-avatar">
                            <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}"
                                 alt="Foto Profil">
                        </span>

                    @else

                        <span class="profile-avatar">
                            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                        </span>

                    @endif

                    <span>Profil</span>

                </a>


                <!-- LOGOUT -->
                <form action="{{ route('logout') }}" method="POST">

                    @csrf

                    <button type="submit"
                            class="menu-link"
                            style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left;">

                        <img src="{{ asset('images/icon-login.png') }}"
                             class="menu-icon"
                             alt="">

                        <span>Logout</span>

                    </button>

                </form>


            @else

                <a href="{{ route('login') }}"
                   class="menu-link">

                    <img src="{{ asset('images/icon-login.png') }}"
                         class="menu-icon"
                         alt="">

                    <span>Login</span>

                </a>

            @endauth

        </div>

    </aside>


    <!-- KONTEN UTAMA -->
    <main class="main">

        <header>

            <h1 class="welcome-title">
                Selamat Datang!
            </h1>

            <p class="welcome-subtitle">
                Temukan roti favoritmu dengan harga lebih hemat.
            </p>

        </header>


        <section class="banner">

            <img src="{{ asset('images/bread-banner.png') }}"
                 alt="Banner BreadSaver">

        </section>


        <section>

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
                    {{ isset($produks) ? $produks->count() : 0 }} Produk
                </span>

            </div>


            @if(isset($produks) && $produks->count() > 0)

                <div class="product-grid">

                    @foreach($produks as $produk)

                        @php

                            $hargaAsli = (float)($produk->harga_asli ?? 0);

                            $hargaDiskon = (float)($produk->harga_diskon ?? $hargaAsli);

                            $stok = (int)($produk->stok ?? 0);


                            // Pilihan Gambar:
                            // Cek foto dari DB (Storage / Presets)

                            if (!empty($produk->foto)) {

                                if (str_starts_with($produk->foto, 'http')) {

                                    $gambarSrc = $produk->foto;

                                } elseif (
                                    str_starts_with($produk->foto, 'storage/') ||
                                    str_starts_with($produk->foto, 'images/')
                                ) {

                                    $gambarSrc = asset($produk->foto);

                                } else {

                                    $gambarSrc = asset('storage/' . $produk->foto);

                                }

                            } else {

                                $namaLow = strtolower($produk->nama_produk);


                                if (\Illuminate\Support\Str::contains(
                                    $namaLow,
                                    ['cokelat', 'coklat', 'chocolate']
                                )) {

                                    $gambarSrc = asset('images/roti-cokelat.png');

                                } elseif (\Illuminate\Support\Str::contains(
                                    $namaLow,
                                    ['croissant', 'pastry']
                                )) {

                                    $gambarSrc = asset('images/croissant.png');

                                } elseif (\Illuminate\Support\Str::contains(
                                    $namaLow,
                                    ['tawar', 'sandwich']
                                )) {

                                    $gambarSrc = asset('images/roti-tawar.png');

                                } else {

                                    $gambarSrc = asset('images/bread.png');

                                }

                            }

                        @endphp


                        <article class="product-card">

                            <div class="product-image-container">

                                <img src="{{ $gambarSrc }}"
                                     alt="{{ $produk->nama_produk }}"
                                     class="product-image"
                                     onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">

                            </div>


                            <div class="product-info">

                                <h3 class="product-name"
                                    title="{{ $produk->nama_produk }}">

                                    {{ $produk->nama_produk }}

                                </h3>


                                <div class="price-row">

                                    @if($hargaAsli > $hargaDiskon)

                                        <span class="price-original">
                                            Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                        </span>

                                    @endif


                                    <span class="price-discount">
                                        Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                    </span>

                                </div>


                                <div class="product-detail">

                                    <span>
                                        Stok: {{ $stok }}
                                    </span>


                                    @if(!empty($produk->expired_at))

                                        <span>

                                            Kedaluwarsa:
                                            {{ \Carbon\Carbon::parse($produk->expired_at)->format('d-m-Y') }}

                                        </span>

                                    @endif

                                </div>


                                @if(!empty($produk->deskripsi))

                                    <p class="product-description">
                                        {{ $produk->deskripsi }}
                                    </p>

                                @endif


                                <div class="product-action">

                                    @auth

                                        <form action="{{ route('keranjang.tambah', $produk->id_produk ?? $produk->id) }}"
                                              method="POST">

                                            @csrf

                                            <button type="submit"
                                                    class="buy-button">

                                                + Keranjang

                                            </button>

                                        </form>

                                    @else

                                        <a href="{{ route('login') }}"
                                           class="buy-button">

                                            Login untuk Membeli

                                        </a>

                                    @endauth

                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

            @else

                <div class="empty-state">

                    <h3>
                        Belum Ada Roti Tersedia
                    </h3>

                    <p>
                        Silakan cek kembali nanti.
                    </p>

                </div>

            @endif

        </section>


        <footer class="footer">

            © {{ date('Y') }} BreadSaver.
            Selamatkan Roti, Kurangi Food Waste.

        </footer>

    </main>

</body>
</html>
