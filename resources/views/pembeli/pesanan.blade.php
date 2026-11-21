<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - BreadSaver</title>

    <style>
        :root {
            --navy: #1f3763;
            --navy-2: #29477d;
            --pink: #ff8fb1;
            --pink-soft: #fff2f6;
            --green: #16a34a;
            --green-soft: #eafaf0;
            --orange: #b7791f;
            --orange-soft: #fff7e6;
            --red: #dc2626;
            --red-soft: #ffeaea;
            --blue-soft: #eef4ff;
            --text: #24324b;
            --muted: #7f8aa3;
            --white: #ffffff;
            --bg: #f6f8fc;
            --border: #e8edf5;
            --shadow: 0 10px 30px rgba(25, 44, 77, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* SIDEBAR */
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
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 20px;
        }

        /* MAIN */
        .main {
            margin-left: 76px;
            min-height: 100vh;
            padding: 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 30px;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .page-header p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: bold;
            border: 1px solid transparent;
        }

        .alert-success {
            background: #eafaf0;
            color: #1a7f42;
            border-color: #b9e8c9;
        }

        .alert-error {
            background: #ffeaea;
            color: #c13737;
            border-color: #f4b4b4;
        }

        /* LIST WRAPPER */
        .orders-wrapper {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .order-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 22px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .order-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(180deg, #ffffff 0%, #fbfcff 100%);
        }

        .order-id {
            font-size: 17px;
            font-weight: bold;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .order-date {
            color: var(--muted);
            font-size: 13px;
        }

        .badge-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-order-waiting {
            background: #fff4d6;
            color: #956500;
        }

        .badge-order-process {
            background: #e7f0ff;
            color: #325aa5;
        }

        .badge-order-finished {
            background: #e2f7e9;
            color: #1f7a42;
        }

        .badge-order-cancelled {
            background: #ffe6e6;
            color: #c03f3f;
        }

        .badge-pay-pending {
            background: var(--orange-soft);
            color: var(--orange);
        }

        .badge-pay-success {
            background: var(--green-soft);
            color: var(--green);
        }

        .badge-pay-cancelled {
            background: var(--red-soft);
            color: var(--red);
        }

        .order-body {
            display: grid;
            grid-template-columns: 96px 1fr auto;
            gap: 18px;
            padding: 22px 24px;
            align-items: center;
        }

        .thumb-wrap {
            width: 96px;
            height: 96px;
            border-radius: 18px;
            overflow: hidden;
            background: #f1f5fb;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .thumb-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumb-placeholder {
            font-size: 34px;
        }

        .product-name {
            font-size: 17px;
            font-weight: bold;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .product-sub {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 14px;
        }

        .mini-items {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .mini-chip {
            background: #f5f7fb;
            border: 1px solid var(--border);
            color: #54627b;
            border-radius: 999px;
            padding: 7px 11px;
            font-size: 12px;
        }

        .order-side {
            min-width: 220px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
        }

        .total-box {
            text-align: right;
        }

        .total-label {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 4px;
        }

        .total-value {
            font-size: 24px;
            font-weight: bold;
            color: var(--navy);
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            padding: 0 24px 18px;
        }

        .meta-card {
            background: #fbfcff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 14px 15px;
        }

        .meta-label {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 6px;
        }

        .meta-value {
            color: var(--navy);
            font-size: 14px;
            font-weight: 600;
            line-height: 1.5;
        }

        /* COUNTDOWN PEMBAYARAN: tampil hanya untuk pesanan belum lunas */
        .order-payment-timer {
            margin: 0 24px 18px;
            padding: 17px 20px;
            background: #f7faff;
            border: 1px solid #d9e5f7;
            border-radius: 15px;
        }
        .order-payment-timer-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
        }
        .order-payment-timer-label {
            font-size: 13px;
            font-weight: bold;
            color: var(--navy);
            margin-bottom: 6px;
        }
        .order-payment-timer-hint {
            font-size: 12px;
            line-height: 1.5;
            color: var(--muted);
        }
        .order-payment-timer-clock {
            color: var(--navy);
            font-weight: 800;
            font-size: 25px;
            letter-spacing: 1px;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }
        .order-payment-timer-track {
            height: 8px;
            overflow: hidden;
            background: #e5eaf3;
            border-radius: 999px;
        }
        .order-payment-timer-progress {
            height: 100%;
            width: 100%;
            border-radius: inherit;
            background: var(--pink);
            transition: width 0.75s linear;
        }
        .order-payment-timer.urgent {
            background: #fffaf0;
            border-color: #f5d799;
        }
        .order-payment-timer.urgent .order-payment-timer-clock {
            color: #a16207;
        }
        .order-payment-timer.expired {
            background: #fff6f6;
            border-color: #ffcaca;
        }
        .order-payment-timer.expired .order-payment-timer-clock {
            color: var(--red);
        }
        .order-payment-timer.expired .order-payment-timer-progress {
            width: 0;
        }
        @media (max-width: 600px) {
            .order-payment-timer {
                margin: 0 16px 16px;
                padding: 15px;
            }
            .order-payment-timer-clock { font-size: 22px; }
        }

        /* QR Code untuk pengambilan pesanan yang sudah dibayar */
        .pickup-qr-box {
            display: flex;
            align-items: center;
            gap: 18px;
            margin: 0 24px 20px;
            padding: 18px;
            border: 1px solid #cbdcf5;
            border-radius: 16px;
            background: #f7faff;
        }

        .pickup-qr-image {
            flex: 0 0 auto;
            width: 186px;
            height: 186px;
            display: grid;
            place-items: center;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 6px;
        }

        .pickup-qr-image svg {
            width: 100%;
            height: 100%;
        }

        .pickup-qr-info h3 {
            color: var(--navy);
            font-size: 16px;
            margin-bottom: 9px;
        }

        .pickup-qr-info p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 8px;
        }

        .pickup-qr-code {
            display: inline-block;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            color: var(--navy);
            font-family: Consolas, monospace;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            overflow-wrap: anywhere;
        }

        @media (max-width: 600px) {
            .pickup-qr-box {
                margin-left: 16px;
                margin-right: 16px;
                flex-direction: column;
                align-items: flex-start;
            }
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 0 24px 24px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s ease;
            border: none;
        }

        .btn:hover {
            opacity: 0.88;
        }

        .btn-pay {
            background: var(--navy);
            color: white;
        }
        .btn-pay.payment-expired {
            opacity: 0.55;
            cursor: not-allowed;
            pointer-events: none;
        }

        .btn-detail {
            background: #ebf2ff;
            color: #315ba8;
        }

        .btn-rating {
            background: var(--pink-soft);
            color: var(--navy);
            border: 1px solid var(--pink);
        }

        .btn-cancel {
            background: #ffe7e7;
            color: #c43d3d;
        }

        /* RATING DAN ULASAN PESANAN */
        .reviews-list {
            margin: 0 24px 18px;
            padding: 18px;
            background: #fffafc;
            border: 1px solid #ffe0e9;
            border-radius: 16px;
        }

        .reviews-heading {
            color: var(--navy);
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .review-item {
            padding: 12px 0;
            border-bottom: 1px solid #f1e7ee;
        }

        .review-item:first-of-type {
            padding-top: 0;
        }

        .review-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .review-product {
            color: var(--navy);
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .review-stars {
            color: #e8a317;
            font-size: 19px;
            letter-spacing: 1px;
        }

        .review-score {
            color: var(--muted);
            font-size: 12px;
            margin-left: 5px;
        }

        .review-text {
            margin-top: 8px;
            font-size: 13px;
            line-height: 1.6;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .review-complete {
            display: inline-flex;
            align-items: center;
            padding: 0 14px;
            min-height: 42px;
            border-radius: 12px;
            background: var(--green-soft);
            color: var(--green);
            font-size: 13px;
            font-weight: bold;
        }

        .empty-pesanan {
            background: white;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 44px 24px;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .empty-pesanan h3 {
            font-size: 22px;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .empty-pesanan p {
            color: var(--muted);
            font-size: 14px;
        }

        .main-footer {
            margin-top: 34px;
            padding-top: 18px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
            text-align: center;
        }

        .auth-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 50px 30px;
            text-align: center;
            max-width: 600px;
            margin: 40px auto;
            box-shadow: var(--shadow);
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
            border-radius: 12px;
            font-weight: bold;
            font-size: 14px;
        }

        @media (max-width: 980px) {
            .order-body {
                grid-template-columns: 80px 1fr;
            }

            .order-side {
                grid-column: 1 / -1;
                align-items: flex-start;
                min-width: 100%;
            }

            .total-box {
                text-align: left;
            }
        }

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

            .nav-link span {
                display: none;
            }

            .main {
                margin-left: 76px;
                padding: 18px 14px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-header h1 {
                font-size: 24px;
            }

            .order-top,
            .order-body,
            .meta-grid,
            .actions {
                padding-left: 16px;
                padding-right: 16px;
            }

            .order-top {
                flex-direction: column;
            }

            .badge-group {
                justify-content: flex-start;
            }

            .order-body {
                grid-template-columns: 1fr;
            }

            .thumb-wrap {
                width: 84px;
                height: 84px;
            }

            .reviews-list {
                margin-left: 16px;
                margin-right: 16px;
            }
        }

    /* TAB FILTER PESANAN */
    .order-tabs {
        display: flex;
        align-items: center;
        gap: 8px;
        background: white;
        padding: 12px;
        margin-bottom: 22px;
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow-x: auto;
        scrollbar-width: thin;
    }

    .order-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 17px;
        border: none;
        border-radius: 10px;
        background: transparent;
        color: var(--muted);
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .order-tab:hover {
        background: #f3f6fc;
        color: var(--navy);
    }

    .order-tab.active {
        background: var(--navy);
        color: white;
    }

    .tab-count {
        background: #edf1f7;
        color: var(--navy);
        padding: 3px 8px;
        border-radius: 7px;
        font-size: 11px;
    }

    .order-tab.active .tab-count {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    .filter-empty {
        text-align: center;
        padding: 50px 20px;
        background: white;
        border: 1px solid var(--border);
        border-radius: 18px;
        color: var(--muted);
    }

    .filter-empty[hidden] {
        display: none;
    }

    .filter-empty h3 {
        color: var(--navy);
        margin-bottom: 8px;
    }

    @media (max-width: 700px) {
        .order-tabs {
            padding: 8px;
        }

        .order-tab {
            padding: 10px 12px;
            font-size: 12px;
        }
    }

    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ route('pembeli.beranda') }}">
                <img src="{{ asset('images/logo-icon.png') }}"
                     onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';"
                     alt="Logo BreadSaver"
                     class="logo-small">
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo BreadSaver">
            </a>
        </div>

        <p class="nav-title">Menu Utama</p>

        <nav class="menu">
            <a href="{{ route('pembeli.beranda') }}" class="nav-link {{ request()->routeIs('pembeli.beranda', 'home') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt="">
                <span>Beranda</span>
            </a>

            <a href="{{ route('pembeli.katalog') }}" class="nav-link {{ request()->routeIs('pembeli.katalog', 'katalog') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt="">
                <span>Katalog Produk</span>
            </a>

            <a href="{{ route('pembeli.pesanan') }}" class="nav-link {{ request()->routeIs('pembeli.pesanan', 'pesanan') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt="">
                <span>Pesanan</span>
            </a>

            <a href="{{ route('pembeli.keranjang') }}" class="nav-link {{ request()->routeIs('pembeli.keranjang', 'keranjang') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-keranjang.png') }}" class="nav-icon" alt="">
                <span>Keranjang</span>
            </a>
        </nav>

        <div class="sidebar-bottom">
            @auth
                <a href="{{ route('pembeli.profil') }}" class="nav-link {{ request()->routeIs('pembeli.profil') ? 'active' : '' }}">
                    @if(Auth::user()->foto_profil)
                        <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}"
                             class="nav-icon"
                             alt="Foto Profil"
                             style="border-radius:50%; object-fit:cover;">
                    @else
                        <div class="nav-icon profile-initial">
                            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                        </div>
                    @endif
                    <span>Profil</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="nav-link"
                            style="width:100%; border:0; background:transparent; cursor:pointer; text-align:left;">
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

    <main class="main">
        <header class="page-header">
            <div>
                <h1>Pesanan Saya</h1>
                <p>Lihat status pesanan, pembayaran, dan jadwal pengambilan secara lebih rapi dan modern.</p>
            </div>
        </header>

        @auth
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @php
                // Status yang ditampilkan kepada pembeli mengikuti status pembayaran.
                // Pesanan yang belum lunas tidak dihitung sebagai Menunggu Konfirmasi.
                $kategoriUntukPesanan = static function ($item) {
                    $pembayaran = $item->status_pembayaran ?? null;

                    if ($item->status === 'Dibatalkan' || $pembayaran === 'Dibatalkan') {
                        return 'dibatalkan';
                    }

                    if ($pembayaran !== 'Berhasil') {
                        return 'belum-bayar';
                    }

                    if (!empty($item->picked_up_at)) {
                        return 'selesai';
                    }

                    if (in_array($item->status, ['Selesai', 'Siap Diambil'], true)) {
                        return 'siap';
                    }

                    if ($item->status === 'Diproses') {
                        return 'diproses';
                    }

                    return 'menunggu';
                };

            @endphp

            @if(($pesanans ?? collect())->count())
                @php
                    // Sekali query untuk semua pesanan, bukan query berulang per kartu.
                    $idsPesananRating = collect($pesanans)
                        ->pluck('id_pesanan')
                        ->filter()
                        ->all();

                    $ratingsPerPesanan = \Illuminate\Support\Facades\DB::table('ratings as r')
                        ->leftJoin('produks as pr', 'r.id_produk', '=', 'pr.id_produk')
                        ->whereIn('r.id_pesanan', $idsPesananRating)
                        ->select(
                            'r.id_pesanan',
                            'r.id_produk',
                            'r.bintang',
                            'r.ulasan',
                            'pr.nama_produk'
                        )
                        ->orderBy('r.id_pesanan')
                        ->get()
                        ->groupBy('id_pesanan');

                    $jumlahProdukPerPesanan = \Illuminate\Support\Facades\DB::table('detail_pesanans')
                        ->whereIn('id_pesanan', $idsPesananRating)
                        ->select('id_pesanan')
                        ->selectRaw('COUNT(DISTINCT id_produk) as jumlah_produk')
                        ->groupBy('id_pesanan')
                        ->pluck('jumlah_produk', 'id_pesanan');
                @endphp

                <!-- TAB FILTER PESANAN -->
                <div class="order-tabs" id="orderTabs">

                    <button type="button" class="order-tab active"
                            data-filter="semua">
                        Semua
                        <span class="tab-count" data-count="semua">0</span>
                    </button>

                    <button type="button" class="order-tab"
                            data-filter="belum-bayar">
                        Belum Bayar
                        <span class="tab-count" data-count="belum-bayar">0</span>
                    </button>

                    <button type="button" class="order-tab"
                            data-filter="menunggu">
                        Menunggu Konfirmasi
                        <span class="tab-count" data-count="menunggu">0</span>
                    </button>

                    <button type="button" class="order-tab"
                            data-filter="diproses">
                        Diproses
                        <span class="tab-count" data-count="diproses">0</span>
                    </button>

                    <button type="button" class="order-tab"
                            data-filter="siap">
                        Siap Diambil
                        <span class="tab-count" data-count="siap">0</span>
                    </button>

                    <button type="button" class="order-tab"
                            data-filter="selesai">
                        Selesai
                        <span class="tab-count" data-count="selesai">0</span>
                    </button>

                    <button type="button" class="order-tab"
                            data-filter="dibatalkan">
                        Dibatalkan
                        <span class="tab-count" data-count="dibatalkan">0</span>
                    </button>

                </div>

                <!-- PESAN KETIKA FILTER KOSONG -->
                <div class="filter-empty" id="filterEmpty" hidden>
                    <h3>Belum Ada Pesanan</h3>
                    <p>Tidak ada pesanan pada kategori ini.</p>
                </div>

                <section class="orders-wrapper">
                    @foreach($pesanans as $pesanan)
                        @php
                            $pesananId = $pesanan->id_pesanan ?? $pesanan->id;

                            $ulasanPesanan = $ratingsPerPesanan->get($pesananId, collect());
                            $jumlahProdukPesanan = (int) $jumlahProdukPerPesanan->get($pesananId, 0);
                            $jumlahProdukDinilai = $ulasanPesanan
                                ->pluck('id_produk')
                                ->filter(fn($id) => $id !== null)
                                ->unique()
                                ->count();
                            $masihBisaMenilai = $jumlahProdukPesanan > $jumlahProdukDinilai;

                            $statusPembayaran = $pesanan->status_pembayaran ?? null;
                            $kategoriPesanan = $kategoriUntukPesanan($pesanan);

                            // Satu status utama; status konfirmasi baru terlihat sesudah lunas.
                            $statusLabel = match ($kategoriPesanan) {
                                'belum-bayar' => 'Menunggu Pembayaran',
                                'menunggu' => 'Menunggu Konfirmasi',
                                'diproses' => 'Diproses',
                                'siap' => 'Siap Diambil',
                                'selesai' => 'Sudah Diambil',
                                'dibatalkan' => 'Dibatalkan',
                                default => 'Menunggu Pembayaran',
                            };

                            $statusOrderClass = match ($kategoriPesanan) {
                                'diproses' => 'badge-order-process',
                                'siap', 'selesai' => 'badge-order-finished',
                                'dibatalkan' => 'badge-order-cancelled',
                                default => 'badge-order-waiting',
                            };

                            $fotoUtama = null;
                            if (!empty($pesanan->foto_produk_utama)) {
                                $pathFoto = ltrim($pesanan->foto_produk_utama, '/');

                                if (\Illuminate\Support\Str::startsWith($pathFoto, ['http://', 'https://'])) {
                                    $fotoUtama = $pathFoto;
                                } elseif (\Illuminate\Support\Str::startsWith($pathFoto, ['storage/', 'images/'])) {
                                    $fotoUtama = asset($pathFoto);
                                } else {
                                    $fotoUtama = asset('storage/' . $pathFoto);
                                }
                            }
                        @endphp

                        <article class="order-card"
                                 data-order-id="{{ $pesananId }}"
                                 data-order-state="{{ ($pesanan->status ?? '') . '|' . ($statusPembayaran ?? '') . '|' . ($pesanan->picked_up_at ?? '') }}"
                                 data-category="{{ $kategoriPesanan }}">
                            <div class="order-top">
                                <div>
                                    <p class="order-id">Pesanan #{{ $pesananId }}</p>
                                    <p class="order-date">
                                        Dibuat pada
                                        {{ $pesanan->tanggal_pesan
                                            ? \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d M Y, H:i')
                                            : '-' }}
                                    </p>
                                </div>

                                <div class="badge-group">
                                    <span class="badge {{ $statusOrderClass }}">
                                        {{ $statusLabel }}
                                    </span>

                                    @if($statusPembayaran === 'Berhasil')
                                        <span class="badge badge-pay-success">Pembayaran Berhasil</span>
                                    @endif
                                </div>
                            </div>

                            <div class="order-body">
                                <div class="thumb-wrap">
                                    @if($fotoUtama)
                                        <img src="{{ $fotoUtama }}" alt="Foto Produk">
                                    @else
                                        <div class="thumb-placeholder">🍞</div>
                                    @endif
                                </div>

                                <div>
                                    <div class="product-name">
                                        {{ $pesanan->nama_produk_utama ?? 'Produk tidak ditemukan' }}
                                    </div>

                                    <div class="product-sub">
                                        @if(($pesanan->jumlah_produk ?? 0) > 1)
                                            +{{ ($pesanan->jumlah_produk - 1) }} produk lainnya •
                                        @endif

                                        {{ $pesanan->jumlah_item ?? 0 }} item
                                    </div>

                                    <div class="mini-items">
                                        @foreach(($pesanan->items_preview ?? collect())->take(3) as $item)
                                            <span class="mini-chip">
                                                {{ $item->nama_produk }} x{{ $item->jumlah }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="order-side">
                                    <div class="total-box">
                                        <div class="total-label">Total Belanja</div>
                                        <div class="total-value">
                                            Rp{{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="meta-grid">
                                <div class="meta-card">
                                    <div class="meta-label">Metode Pembayaran</div>
                                    <div class="meta-value">
                                        {{ $pesanan->metode_pembayaran ?? '-' }}
                                    </div>
                                </div>

                                <div class="meta-card">
                                    <div class="meta-label">Status Pembayaran</div>
                                    <div class="meta-value">
                                        {{ $statusPembayaran ?? '-' }}
                                    </div>
                                </div>

                                <div class="meta-card">
                                    <div class="meta-label">Jadwal Pickup</div>
                                    <div class="meta-value">
                                        {{ !empty($pesanan->waktu_pengambilan)
                                            ? \Carbon\Carbon::parse($pesanan->waktu_pengambilan)->format('d M Y, H:i')
                                            : '-' }}
                                    </div>
                                </div>

                                <div class="meta-card">
                                    <div class="meta-label">Kode Pickup</div>
                                    <div class="meta-value">
                                        {{ $pesanan->kode_pickup ?? 'Belum tersedia' }}
                                    </div>
                                </div>
                            </div>

                            {{-- COUNTDOWN: sama persis dengan batas waktu pembayaran saat checkout. --}}
                            @if(
                                $statusPembayaran === 'Menunggu Pembayaran' &&
                                $pesanan->status !== 'Dibatalkan' &&
                                !empty($pesanan->payment_expires_at)
                            )
                                <section class="order-payment-timer"
                                         data-payment-deadline="{{ \Carbon\Carbon::parse($pesanan->payment_expires_at)->timestamp }}"
                                         data-server-time="{{ now()->timestamp }}"
                                         aria-label="Sisa waktu pembayaran pesanan {{ $pesananId }}">
                                    <div class="order-payment-timer-top">
                                        <div>
                                            <p class="order-payment-timer-label">Sisa Waktu Pembayaran</p>
                                            <p class="order-payment-timer-hint" data-timer-hint>
                                                Selesaikan pembayaran sebelum batas waktu habis.
                                            </p>
                                        </div>
                                        <span class="order-payment-timer-clock" data-timer-clock role="timer">--:--</span>
                                    </div>
                                    <div class="order-payment-timer-track" aria-hidden="true">
                                        <div class="order-payment-timer-progress" data-timer-progress></div>
                                    </div>
                                </section>
                            @endif

                            @if(
                                $statusPembayaran === 'Berhasil' &&
                                filled($pesanan->kode_pickup) &&
                                $pesanan->status !== 'Dibatalkan' &&
                                empty($pesanan->picked_up_at)
                            )
                                <section class="pickup-qr-box" aria-label="QR Code Pengambilan Pesanan">
                                    <div class="pickup-qr-image">
                                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(170)->margin(1)->generate($pesanan->kode_pickup) !!}
                                    </div>
                                    <div class="pickup-qr-info">
                                        <h3>QR Code Pengambilan</h3>
                                        <p>QR ini dapat digunakan setelah status pesanan berubah menjadi <strong>Siap Diambil</strong>.</p>
                                        <p>Kode pengambilan:</p>
                                        <span class="pickup-qr-code">{{ $pesanan->kode_pickup }}</span>
                                        <p style="margin-top: 10px;">Jaga kode ini. Pegawai akan memverifikasi pembayaran dan status pengambilan sebelum menyerahkan pesanan.</p>
                                    </div>
                                </section>
                            @elseif(!empty($pesanan->picked_up_at))
                                <section class="pickup-qr-box" aria-label="Status pengambilan">
                                    <div class="pickup-qr-info">
                                        <h3>Pesanan Sudah Diambil</h3>
                                        <p>Pengambilan berhasil diverifikasi pada {{ \Carbon\Carbon::parse($pesanan->picked_up_at)->format('d M Y, H:i') }}.</p>
                                        <p>QR Code pengambilan sudah tidak berlaku.</p>
                                    </div>
                                </section>
                            @endif

                            @if($ulasanPesanan->isNotEmpty())
                                <section class="reviews-list" aria-label="Ulasan pesanan">
                                    <p class="reviews-heading">Ulasan Anda</p>

                                    @foreach($ulasanPesanan as $ulasan)
                                        <div class="review-item">
                                            <p class="review-product">
                                                {{ $ulasan->nama_produk ?? 'Produk' }}
                                            </p>
                                            <div>
                                                <span class="review-stars" aria-label="{{ $ulasan->bintang }} dari 5 bintang">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        {{ $i <= $ulasan->bintang ? '★' : '☆' }}
                                                    @endfor
                                                </span>
                                                <span class="review-score">{{ $ulasan->bintang }}/5</span>
                                            </div>
                                            @if(filled($ulasan->ulasan))
                                                <p class="review-text">{{ $ulasan->ulasan }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </section>
                            @endif

                            <div class="actions">
                                @if(
                                    $statusPembayaran === 'Menunggu Pembayaran' &&
                                    $pesanan->status !== 'Dibatalkan'
                                )
                                    <a href="{{ route('pembeli.pembayaran.show', $pesananId) }}"
                                       class="btn btn-pay">
                                        Bayar Sekarang
                                    </a>
                                @endif

                                @if($statusPembayaran === 'Berhasil')
                                    <a href="{{ route('pembeli.pembayaran.show', $pesananId) }}"
                                       class="btn btn-detail">
                                        Lihat Detail Pembayaran
                                    </a>
                                @endif

                                @if(
                                    $pesanan->status === 'Selesai' &&
                                    $statusPembayaran === 'Berhasil'
                                )
                                    @if($masihBisaMenilai)
                                        <a href="{{ route('pembeli.rating.form', $pesananId) }}"
                                           class="btn btn-rating">
                                            Beri Ulasan
                                        </a>
                                    @elseif($jumlahProdukPesanan > 0)
                                        <span class="review-complete">Sudah Diulas</span>
                                    @endif
                                @endif

                                @if(
                                    $pesanan->status === 'Menunggu Konfirmasi' &&
                                    $statusPembayaran !== 'Berhasil'
                                )
                                    <form action="{{ route('pembeli.pesanan.batal', $pesananId) }}"
                                          method="POST"
                                          style="display:inline;">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-cancel"
                                                onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan #{{ $pesananId }}?')">
                                            Batalkan Pesanan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </section>
            @else
                <section class="empty-pesanan">
                    <h3>Belum Ada Pesanan</h3>
                    <p>Silakan checkout produk terlebih dahulu supaya riwayat pesanan muncul di sini.</p>
                </section>
            @endif

        @else
            <section class="auth-card">
                <h3>Anda Belum Masuk ke Akun</h3>
                <p>Silakan masuk terlebih dahulu untuk melihat dan memantau status pesanan Anda.</p>
                <a href="{{ route('login') }}" class="btn-login">
                    Login Sekarang
                </a>
            </section>
        @endauth

        <footer class="main-footer">
            © {{ date('Y') }} BreadSaver. Selamatkan Roti, Kurangi Food Waste.
        </footer>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const tabs = document.querySelectorAll('.order-tab');
        const cards = document.querySelectorAll(
            '.order-card[data-category]'
        );
        const emptyMessage = document.getElementById('filterEmpty');

        // Hitung jumlah pesanan setiap kategori
        document.querySelectorAll('[data-count]').forEach(function (el) {

            const category = el.dataset.count;

            const total = category === 'semua'
                ? cards.length
                : Array.from(cards).filter(function (card) {
                    return card.dataset.category === category;
                }).length;

            el.textContent = total;
        });

        // Menampilkan pesanan sesuai tab
        function filterPesanan(category) {

            let visible = 0;

            cards.forEach(function (card) {

                const cocok = category === 'semua' ||
                    card.dataset.category === category;

                card.style.display = cocok ? '' : 'none';

                if (cocok) {
                    visible++;
                }
            });

            // Tampilan tab aktif
            tabs.forEach(function (tab) {
                const aktif = tab.dataset.filter === category;

                tab.classList.toggle('active', aktif);
                tab.setAttribute('aria-pressed', aktif ? 'true' : 'false');
            });

            // Pesan ketika tidak ada pesanan
            if (emptyMessage) {
                emptyMessage.hidden = visible > 0;
            }
        }

        // Event klik tab
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                filterPesanan(this.dataset.filter);
            });
        });

        // Default menampilkan semua pesanan
        filterPesanan('semua');

    });
    </script>


    {{-- Auto-sync Midtrans: hanya polling transaksi pembeli yang belum lunas. --}}
    @auth
        @php
            $paymentAutoSyncUrls = \Illuminate\Support\Facades\Route::has('pembeli.pembayaran.auto-sync')
                ? collect($pesanans ?? [])
                    ->filter(fn ($p) =>
                        ($p->status_pembayaran ?? null) === 'Menunggu Pembayaran' &&
                        $p->status !== 'Dibatalkan'
                    )
                    ->map(fn ($p) => route('pembeli.pembayaran.auto-sync', $p->id_pesanan))
                    ->values()
                : collect();
        @endphp

        @if($paymentAutoSyncUrls->isNotEmpty())
            <script>
            (function () {
                const urls = @json($paymentAutoSyncUrls);
                const csrf = @json(csrf_token());
                let sedangMengecek = false;

                async function cekPembayaranOtomatis() {
                    if (sedangMengecek || document.hidden) return;
                    sedangMengecek = true;

                    try {
                        for (const url of urls) {
                            const response = await fetch(url, {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': csrf
                                }
                            });

                            if (!response.ok) continue;
                            const hasil = await response.json();

                            if (hasil.status === 'Berhasil' || hasil.status === 'Dibatalkan') {
                                // Refresh otomatis setelah backend memperbarui database.
                                window.location.reload();
                                return;
                            }
                        }
                    } catch (error) {
                        console.warn('Auto-sync Midtrans belum berhasil:', error);
                    } finally {
                        sedangMengecek = false;
                    }
                }

                // Langsung cek dan ulangi tiap 10 detik selama halaman dibuka.
                setTimeout(cekPembayaranOtomatis, 1500);
                setInterval(cekPembayaranOtomatis, 10000);
                document.addEventListener('visibilitychange', function () {
                    if (!document.hidden) cekPembayaranOtomatis();
                });
            })();
            </script>
        @endif
    @endauth

    {{-- Countdown pembayaran 10 menit, mengikuti jam server dan batas waktu di database. --}}
    <script>
    (function () {
        const timers = Array.from(document.querySelectorAll('.order-payment-timer[data-payment-deadline]'));
        if (!timers.length) return;

        const monotonicStartMs = performance.now();
        const paymentDurationSeconds = 10 * 60;

        function updateTimers() {
            const elapsedMs = performance.now() - monotonicStartMs;

            timers.forEach(function (timer) {
                const deadlineMs = Number(timer.dataset.paymentDeadline) * 1000;
                const serverStartMs = Number(timer.dataset.serverTime) * 1000;
                const nowMs = serverStartMs + elapsedMs;
                const secondsLeft = Math.max(0, Math.ceil((deadlineMs - nowMs) / 1000));

                const clock = timer.querySelector('[data-timer-clock]');
                const hint = timer.querySelector('[data-timer-hint]');
                const progress = timer.querySelector('[data-timer-progress]');

                const minutes = String(Math.floor(secondsLeft / 60)).padStart(2, '0');
                const seconds = String(secondsLeft % 60).padStart(2, '0');
                clock.textContent = minutes + ':' + seconds;
                progress.style.width = Math.min(100, secondsLeft / paymentDurationSeconds * 100) + '%';

                timer.classList.toggle('urgent', secondsLeft > 0 && secondsLeft <= 120);
                timer.classList.toggle('expired', secondsLeft === 0);

                // Setelah tenggat lewat, jangan arahkan pembeli membuat pembayaran baru.
                const payLink = timer.closest('.order-card')?.querySelector('.btn-pay');
                if (payLink) {
                    payLink.classList.toggle('payment-expired', secondsLeft === 0);
                    if (secondsLeft === 0) {
                        payLink.setAttribute('aria-disabled', 'true');
                        payLink.setAttribute('tabindex', '-1');
                    } else {
                        payLink.removeAttribute('aria-disabled');
                        payLink.removeAttribute('tabindex');
                    }
                }

                if (secondsLeft === 0) {
                    hint.textContent = 'Batas waktu habis. Sistem sedang memverifikasi pembayaran dan memproses pembatalan.';
                } else if (secondsLeft <= 120) {
                    hint.textContent = 'Waktu hampir habis. Segera selesaikan pembayaran.';
                } else {
                    hint.textContent = 'Selesaikan pembayaran sebelum batas waktu habis.';
                }
            });
        }

        updateTimers();
        setInterval(updateTimers, 1000);
        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) updateTimers();
        });
    })();
    </script>

    {{-- Pembaruan status read-only, termasuk pembatalan oleh Laravel Scheduler. --}}
    @auth
        @if(collect($pesanans ?? [])->contains(fn ($p) =>
            ($p->status_pembayaran ?? null) === 'Menunggu Pembayaran' &&
            $p->status !== 'Dibatalkan'
        ))
            <script>
            (function () {
                const getState = function (doc) {
                    return Array.from(doc.querySelectorAll('.order-card[data-order-id]'))
                        .map(function (card) {
                            return card.dataset.orderId + ':' + (card.dataset.orderState || '');
                        }).join(';');
                };
                const initialState = getState(document);
                let checking = false;

                async function checkStatusChanges() {
                    if (checking || document.hidden) return;
                    checking = true;
                    try {
                        const response = await fetch(window.location.pathname, {
                            credentials: 'same-origin',
                            headers: { 'Accept': 'text/html' },
                            cache: 'no-store'
                        });
                        if (!response.ok) return;
                        const html = await response.text();
                        const remote = new DOMParser().parseFromString(html, 'text/html');
                        const updatedState = getState(remote);
                        if (updatedState !== initialState) {
                            window.location.reload();
                        }
                    } catch (error) {
                        console.warn('Belum bisa memeriksa pembaruan status pesanan:', error);
                    } finally {
                        checking = false;
                    }
                }

                setInterval(checkStatusChanges, 12000);
                document.addEventListener('visibilitychange', function () {
                    if (!document.hidden) checkStatusChanges();
                });
            })();
            </script>
        @endif
    @endauth


@include('components.notifikasi-panel')
</body>
</html>