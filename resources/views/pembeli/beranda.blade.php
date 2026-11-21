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

        /* SIDEBAR - SERAGAM DENGAN HALAMAN PESANAN */
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
            transition: 0.2s ease;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.10);
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

        /* Avatar profil tetap terlihat meskipun sidebar mengecil */
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
            color: var(--white);
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

        .nav-link > span:not(.profile-avatar) {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .nav-link > span:not(.profile-avatar) {
            opacity: 1;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 20px;
        }

        /* MAIN - SELARAS DENGAN LEBAR SIDEBAR */
        .main {
            margin-left: 76px;
            min-height: 100vh;
            padding: 30px;
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


        /* PENCARIAN & FILTER PRODUK */
        .bread-filter-bar {
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 12px;
            padding: 18px;
            margin-bottom: 24px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .bread-filter-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1 1 290px;
            min-width: 0;
        }

        .bread-filter-field--sort {
            flex: 0 1 250px;
        }

        .bread-filter-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
        }

        .bread-filter-input,
        .bread-filter-select {
            width: 100%;
            height: 44px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0 14px;
            background: var(--white);
            color: var(--text);
            font: inherit;
            font-size: 14px;
            outline: none;
        }

        .bread-filter-input:focus,
        .bread-filter-select:focus {
            border-color: var(--pink);
            box-shadow: 0 0 0 3px rgba(255, 143, 177, 0.16);
        }

        .bread-filter-reset {
            height: 44px;
            padding: 0 17px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #f3f5f9;
            color: var(--navy);
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .bread-filter-reset:hover {
            background: #e9edf5;
        }

        .product-card[hidden],
        .product-grid[hidden],
        .bread-filter-empty[hidden] {
            display: none !important;
        }

        .bread-filter-empty {
            padding: 38px 20px;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: var(--white);
            text-align: center;
            color: var(--muted);
            font-size: 14px;
        }

        .bread-filter-empty strong {
            display: block;
            margin-bottom: 8px;
            color: var(--navy);
            font-size: 18px;
        }

        @media (max-width: 700px) {
            .bread-filter-field,
            .bread-filter-field--sort {
                flex-basis: 100%;
            }
            .bread-filter-reset {
                width: 100%;
            }
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

        /*
        =========================================================
        POPUP IKLAN
        =========================================================
        */

        .ad-overlay {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.62);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 9999;
        }

        .ad-popup {
            position: relative;
            width: min(900px, 85vw);

            /*
            Menjaga rasio popup.
            Bagian putih dari gambar akan terpotong
            karena tinggi popup dibatasi.
            */

            aspect-ratio: 16 / 9;

            overflow: hidden;

            background: transparent;
        }

        .ad-image {
            display: block;
            width: 100%;
            height: 100%;

            object-fit: cover;
            object-position: center top;
        }

        /*
        =========================================================
        TOMBOL X
        =========================================================
        */

        .ad-close {
            position: absolute;
            top: 12px;
            right: 12px;

            width: 38px;
            height: 38px;

            border: none;
            border-radius: 50%;

            background: rgba(0, 0, 0, 0.65);
            color: #ffffff;

            font-size: 28px;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: normal;

            line-height: 36px;
            text-align: center;

            padding: 0;
            margin: 0;

            cursor: pointer;

            z-index: 20;

            transition:
                background-color 0.2s,
                transform 0.2s;
        }

        .ad-close:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: scale(1.05);
        }

        .ad-close:active {
            transform: scale(0.95);
        }

        /*
        =========================================================
        RESPONSIVE
        =========================================================
        */

        @media (max-width: 700px) {

            .sidebar {
                width: 76px;
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

            /*
            Hanya teks menu yang disembunyikan.
            Avatar profil tetap terlihat.
            */

            .nav-link > span:not(.profile-avatar) {
                display: none;
            }

            .sidebar-bottom {
                width: 100%;
            }

            .main {
                margin-left: 76px;
                padding: 18px 14px;
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

            /*
            POPUP MOBILE
            */

            .ad-overlay {
                padding: 10px;
            }

            .ad-popup {
                width: 94vw;
                aspect-ratio: 16 / 9;
            }

            .ad-image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center top;
            }

            .ad-close {
                top: 8px;
                right: 8px;
                width: 34px;
                height: 34px;
                font-size: 25px;
                line-height: 32px;
            }
        }
    
        /* RATING & ULASAN PRODUK */
        .bread-rating-line {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 12px;
            font-size: 12px;
            color: #7f8aa3;
        }

        .bread-rating-stars {
            color: #e8a317;
            font-size: 18px;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        .bread-rating-score {
            font-size: 13px;
            color: #23365f;
            font-weight: bold;
        }

        .bread-rating-count {
            font-size: 12px;
            color: #7f8aa3;
        }

        .bread-review-preview {
            padding: 10px 11px;
            background: #f6f8fc;
            border: 1px solid #e8edf5;
            border-radius: 10px;
            font-size: 12px;
            color: #5d6981;
            line-height: 1.5;
            margin-bottom: 10px;
            overflow-wrap: anywhere;
        }

        .bread-review-details {
            margin: 0 0 13px;
            font-size: 12px;
        }

        .bread-review-details summary {
            cursor: pointer;
            color: #29477d;
            font-weight: bold;
            list-style-position: inside;
            padding: 4px 0;
        }

        .bread-review-list {
            margin-top: 8px;
            max-height: 220px;
            overflow-y: auto;
            border: 1px solid #e8edf5;
            border-radius: 10px;
            background: white;
            padding: 0 10px;
        }

        .bread-review-item {
            padding: 10px 0;
            border-bottom: 1px solid #e8edf5;
            color: #5d6981;
            line-height: 1.5;
        }

        .bread-review-item:last-child {
            border-bottom: none;
        }

        .bread-review-item .bread-review-stars {
            color: #e8a317;
            letter-spacing: 1px;
        }

        .bread-review-item p {
            margin: 5px 0 0;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        /* Foto profil transparan, tanpa lingkaran pink */
        .profile-avatar:has(img),
        .bs-buyer-side-profile-avatar:has(img) {
            background: transparent !important;
            border-radius: 0 !important;
            overflow: visible !important;
        }

        /* Pertahankan seluruh gambar tanpa terpotong */
        .profile-avatar img,
        .bs-buyer-side-profile-avatar img {
            background: transparent !important;
            object-fit: contain !important;
        }

    </style>
</head>

<body>

    <!-- SIDEBAR WITH HOVER EXPAND -->
    <aside class="sidebar">

        <div class="logo-container">

            <a href="{{ route('pembeli.beranda') }}">

                <img src="{{ asset('images/logo-icon.png') }}"
                     onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';"
                     alt="Logo BreadSaver"
                     class="logo-small">

                <img src="{{ asset('images/logo.png') }}"
                     alt="BreadSaver"
                     class="logo">

            </a>

        </div>


        <p class="nav-title">
            MENU UTAMA
        </p>


        <nav class="menu">

            <a href="{{ route('pembeli.beranda') }}"
               class="nav-link {{ request()->routeIs('pembeli.beranda', 'home') ? 'active' : '' }}">

                <img src="{{ asset('images/icon-home.png') }}"
                     class="nav-icon"
                     alt="">

                <span>
                    Beranda
                </span>

            </a>


            <a href="{{ route('pembeli.katalog') }}"
               class="nav-link {{ request()->routeIs('pembeli.katalog', 'katalog') ? 'active' : '' }}">

                <img src="{{ asset('images/icon-product.png') }}"
                     class="nav-icon"
                     alt="">

                <span>
                    Katalog Produk
                </span>

            </a>


                                    <a href="{{ route('pembeli.wishlist') }}"
               class="nav-link {{ request()->routeIs('pembeli.wishlist*') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-wishlist.svg') }}" class="nav-icon" alt="">
                <span>Wishlist Saya</span>
            </a>
<a href="{{ route('pembeli.rescue-box') }}"
               class="nav-link {{ request()->routeIs('pembeli.rescue-box*') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-rescue-box.png') }}" class="nav-icon" alt="">
                <span>Bread Rescue Box</span>
            </a>
<a href="{{ route('pembeli.pesanan') }}"
               class="nav-link {{ request()->routeIs('pembeli.pesanan', 'pesanan') ? 'active' : '' }}">

                <img src="{{ asset('images/icon-orders.png') }}"
                     class="nav-icon"
                     alt="">

                <span>
                    Pesanan
                </span>

            </a>


            <a href="{{ route('pembeli.keranjang') }}"
               class="nav-link {{ request()->routeIs('pembeli.keranjang', 'keranjang') ? 'active' : '' }}">

                <img src="{{ asset('images/icon-keranjang.png') }}"
                     class="nav-icon"
                     alt="">

                <span>
                    Keranjang
                </span>

            </a>
            <a href="{{ route('pembeli.loyalty') }}"
               class="nav-link {{ request()->routeIs('pembeli.loyalty*') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-poin-voucher.png') }}" class="nav-icon" alt="">
                <span>Poin &amp; Voucher</span>
            </a>


        </nav>


        <div class="sidebar-bottom">

            @auth

                <!-- PROFIL -->
                <a href="{{ route('pembeli.profil') }}"
                   class="nav-link {{ request()->routeIs('pembeli.profil') ? 'active' : '' }}">

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


                    <span>
                        Profil
                    </span>

                </a>


                <!-- LOGOUT -->
                <form action="{{ route('logout') }}"
                      method="POST">

                    @csrf

                    <button type="submit"
                            class="nav-link"
                            style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left;">

                        <img src="{{ asset('images/icon-login.png') }}"
                             class="nav-icon"
                             alt="">

                        <span>
                            Logout
                        </span>

                    </button>

                </form>


            @else

                <a href="{{ route('login') }}"
                   class="nav-link">

                    <img src="{{ asset('images/icon-login.png') }}"
                         class="nav-icon"
                         alt="">

                    <span>
                        Login
                    </span>

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


                <span class="product-count" id="breadProductCount" aria-live="polite">
                    {{ isset($produks) ? $produks->count() : 0 }} Produk
                </span>

            </div>


            @if(isset($produks) && $produks->count() > 0)

                <!-- Pencarian dan pengurutan hanya memproses produk yang tampil di halaman ini -->
                <div class="bread-filter-bar" role="search" aria-label="Cari dan urutkan roti">
                    <div class="bread-filter-field">
                        <label class="bread-filter-label" for="breadSearch">Cari Nama Roti</label>
                        <input class="bread-filter-input" type="search" id="breadSearch"
                               placeholder="Contoh: roti cokelat" autocomplete="off">
                    </div>

                    <div class="bread-filter-field bread-filter-field--sort">
                        <label class="bread-filter-label" for="breadSort">Urutkan Produk</label>
                        <select class="bread-filter-select" id="breadSort">
                            <option value="default">Urutan Awal</option>
                            <option value="price_asc">Harga Termurah</option>
                            <option value="discount_desc">Diskon Terbesar (%)</option>
                            <option value="rating_desc">Rating Tertinggi</option>
                        </select>
                    </div>

                    <button class="bread-filter-reset" type="button" id="breadReset">Reset</button>
                </div>



                @php
                    // Ambil rating untuk seluruh produk pada halaman sekaligus.
                    $idsProdukRating = $produks->map(function ($produkItem) {
                        return $produkItem->id_produk ?? $produkItem->id;
                    })->all();

                    $statistikRatingProduk = \Illuminate\Support\Facades\DB::table('ratings')
                        ->select('id_produk')
                        ->selectRaw('COUNT(*) AS jumlah_rating, AVG(bintang) AS rata_rating')
                        ->whereIn('id_produk', $idsProdukRating)
                        ->groupBy('id_produk')
                        ->get()
                        ->keyBy('id_produk');

                    $ulasanPerProduk = \Illuminate\Support\Facades\DB::table('ratings')
                        ->select('id_produk', 'bintang', 'ulasan', 'created_at')
                        ->whereIn('id_produk', $idsProdukRating)
                        ->whereNotNull('ulasan')
                        ->where('ulasan', '<>', '')
                        ->orderByDesc('created_at')
                        ->get()
                        ->groupBy('id_produk');
                @endphp


                <div class="product-grid" id="breadProductGrid">

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


                        @php
                            $idSortProduk = $produk->id_produk ?? $produk->id;
                            $statSortRating = $statistikRatingProduk->get($idSortProduk);
                            $nilaiSortRating = (float) ($statSortRating->rata_rating ?? 0);
                            $persenSortDiskon = $hargaAsli > 0
                                ? max(0, ($hargaAsli - $hargaDiskon) / $hargaAsli * 100)
                                : 0;
                        @endphp

                        <article class="product-card" data-wishlist-id="{{ $produk->id_produk ?? $produk->id }}"
                                 data-nama="{{ $produk->nama_produk }}"
                                 data-harga="{{ $hargaDiskon }}"
                                 data-diskon="{{ $persenSortDiskon }}"
                                 data-rating="{{ $nilaiSortRating }}">

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


                                
                                <!-- Rating dan ulasan dari pembeli -->
                                @php
                                    $idProdukRating = $produk->id_produk ?? $produk->id;
                                    $dataRating = $statistikRatingProduk->get($idProdukRating);
                                    $jumlahRating = (int) ($dataRating->jumlah_rating ?? 0);
                                    $rataRating = $jumlahRating > 0
                                        ? (float) $dataRating->rata_rating
                                        : 0;
                                    $bintangBulat = max(0, min(5, (int) round($rataRating)));
                                    $ulasanProduk = $ulasanPerProduk->get($idProdukRating, collect());
                                @endphp

                                <div class="bread-rating-line">
                                    @if($jumlahRating > 0)
                                        <span class="bread-rating-stars" aria-label="Rating {{ number_format($rataRating, 1, ',', '.') }} dari 5">
                                            {{ str_repeat('★', $bintangBulat) }}{{ str_repeat('☆', 5 - $bintangBulat) }}
                                        </span>
                                        <span class="bread-rating-score">
                                            {{ number_format($rataRating, 1, ',', '.') }} / 5
                                        </span>
                                        <span class="bread-rating-count">
                                            ({{ $jumlahRating }} penilaian)
                                        </span>
                                    @else
                                        <span class="bread-rating-stars" aria-hidden="true">☆☆☆☆☆</span>
                                        <span class="bread-rating-count">Belum ada rating</span>
                                    @endif
                                </div>

                                @if($ulasanProduk->isNotEmpty())
                                    <div class="bread-review-preview">
                                        <strong>Ulasan terbaru:</strong>
                                        {{ \Illuminate\Support\Str::limit($ulasanProduk->first()->ulasan, 110) }}
                                    </div>

                                    <details class="bread-review-details">
                                        <summary>Lihat ulasan pembeli ({{ $ulasanProduk->count() }})</summary>
                                        <div class="bread-review-list">
                                            @foreach($ulasanProduk->take(5) as $ulasan)
                                                @php
                                                    $bintangUlasan = max(1, min(5, (int) $ulasan->bintang));
                                                @endphp
                                                <div class="bread-review-item">
                                                    <span class="bread-review-stars">
                                                        {{ str_repeat('★', $bintangUlasan) }}{{ str_repeat('☆', 5 - $bintangUlasan) }}
                                                    </span>
                                                    <p>{{ $ulasan->ulasan }}</p>
                                                </div>
                                            @endforeach
                                            @if($ulasanProduk->count() > 5)
                                                <div class="bread-review-item">
                                                    Menampilkan 5 ulasan terbaru.
                                                </div>
                                            @endif
                                        </div>
                                    </details>
                                @endif

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

                <div class="bread-filter-empty" id="breadFilterEmpty" role="status" hidden>
                    <strong>Roti tidak ditemukan</strong>
                    Coba kata kunci lain atau klik Reset untuk melihat semua produk.
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


    <!-- =========================================================
         POPUP IKLAN
         ========================================================= -->

    <div id="adPopup"
         class="ad-overlay">

        <div class="ad-popup">

            <!-- TOMBOL X -->
            <button type="button"
                    class="ad-close"
                    onclick="closeAd()"
                    aria-label="Tutup iklan">
                ×
            </button>


            <!-- GAMBAR IKLAN -->
            <a href="https://www.patreon.com/cw/nohara51"
               target="_blank"
               rel="noopener noreferrer">

                <img src="{{ asset('images/iklan.png') }}"
                     alt="Iklan"
                     class="ad-image">

            </a>

        </div>

    </div>


    <!-- =========================================================
         JAVASCRIPT POPUP
         ========================================================= -->

    <script>
        function closeAd() {
            const adPopup = document.getElementById('adPopup');

            if (adPopup) {
                adPopup.style.display = 'none';
            }
        }
    </script>


    <!-- Pencarian dan filter roti: tanpa mengubah proses checkout/keranjang -->
    <script>
        (() => {
            const grid = document.getElementById('breadProductGrid');
            if (!grid) return;

            const search = document.getElementById('breadSearch');
            const sort = document.getElementById('breadSort');
            const reset = document.getElementById('breadReset');
            const count = document.getElementById('breadProductCount');
            const empty = document.getElementById('breadFilterEmpty');
            const cards = Array.from(grid.querySelectorAll('.product-card'));
            const originalIndex = new Map(cards.map((card, index) => [card, index]));

            function asNumber(value) {
                const parsed = Number(value);
                return Number.isFinite(parsed) ? parsed : 0;
            }

            function applyBreadFilter() {
                const term = search.value.trim().toLocaleLowerCase('id-ID');
                const filtered = cards.filter((card) =>
                    (card.dataset.nama || '').toLocaleLowerCase('id-ID').includes(term)
                );

                const sortValue = sort.value;
                filtered.sort((a, b) => {
                    let difference = 0;
                    if (sortValue === 'price_asc') {
                        difference = asNumber(a.dataset.harga) - asNumber(b.dataset.harga);
                    } else if (sortValue === 'discount_desc') {
                        difference = asNumber(b.dataset.diskon) - asNumber(a.dataset.diskon);
                    } else if (sortValue === 'rating_desc') {
                        difference = asNumber(b.dataset.rating) - asNumber(a.dataset.rating);
                    }
                    return difference || originalIndex.get(a) - originalIndex.get(b);
                });

                const matchedSet = new Set(filtered);
                const fragment = document.createDocumentFragment();
                filtered.forEach((card) => {
                    card.hidden = false;
                    fragment.appendChild(card);
                });
                cards.forEach((card) => {
                    if (!matchedSet.has(card)) {
                        card.hidden = true;
                        fragment.appendChild(card);
                    }
                });
                grid.appendChild(fragment);

                count.textContent = `${filtered.length} Produk`;
                empty.hidden = filtered.length !== 0;
                grid.hidden = filtered.length === 0;
            }

            search.addEventListener('input', applyBreadFilter);
            sort.addEventListener('change', applyBreadFilter);
            reset.addEventListener('click', () => {
                search.value = '';
                sort.value = 'default';
                applyBreadFilter();
                search.focus();
            });
            applyBreadFilter();
        })();
    </script>

@include('components.notifikasi-panel')


@include('components.wishlist-buttons')
</body>
</html>
