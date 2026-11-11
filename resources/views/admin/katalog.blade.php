<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Katalog - Admin BreadSaver</title>

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

        /* Tampilkan logo kecil saat menguncup, logo penuh saat di-hover */
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
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

        .btn-add {
            display: inline-block;
            background: var(--pink);
            color: var(--navy);
            padding: 12px 18px;
            border-radius: 9px;
            font-weight: bold;
            font-size: 13px;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        .btn-add:hover {
            opacity: 0.85;
        }

        /* NOTIFIKASI */
        .alert-success {
            padding: 13px 16px;
            background: #e8f8ed;
            color: #237a3b;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* TABEL PRODUK */
        .table-card {
            width: 100%;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 15px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            overflow-wrap: anywhere;
            vertical-align: middle;
        }

        th {
            background: #fafbfe;
            color: var(--muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        th:nth-child(1) { width: 12%; }
        th:nth-child(2) { width: 23%; }
        th:nth-child(3) { width: 17%; }
        th:nth-child(4) { width: 17%; }
        th:nth-child(5) { width: 10%; }
        th:nth-child(6) { width: 21%; }

        .product-name {
            font-weight: bold;
            color: var(--navy);
        }

        .discount-price {
            color: #ed6590;
            font-weight: bold;
        }

        .product-image {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 10px;
            display: block;
            background-color: var(--pink-light);
        }

        .no-image {
            color: var(--muted);
            font-size: 11px;
        }

        .btn-edit {
            display: inline-block;
            padding: 8px 13px;
            background: var(--pink-light);
            color: var(--navy);
            border-radius: 7px;
            font-size: 12px;
            font-weight: bold;
            transition: background-color 0.2s;
        }

        .btn-edit:hover {
            background: var(--pink);
        }

        .empty-data {
            text-align: center;
            padding: 35px;
            color: var(--muted);
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
        @media (max-width: 900px) {
            th, td {
                padding: 10px 8px;
                font-size: 12px;
            }

            .product-image {
                width: 52px;
                height: 52px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR WITH HOVER EXPAND -->
    <aside class="sidebar">

        <div class="logo-container">
            <a href="{{ route('admin.beranda') }}">
                <span class="logo-small">🍞</span>
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo BreadSaver">
            </a>
        </div>

        <p class="nav-title">Admin Panel</p>

        <a href="{{ route('admin.beranda') }}" class="nav-link {{ request()->routeIs('admin.beranda') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt="">
            <span>Beranda</span>
        </a>

        <a href="{{ route('admin.katalog') }}" class="nav-link {{ request()->routeIs('admin.katalog') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt="">
            <span>Katalog</span>
        </a>

        <a href="{{ route('admin.kelola_akun') }}" class="nav-link {{ request()->routeIs('admin.kelola_akun') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt="">
            <span>Kelola Akun</span>
        </a>

        <a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-keranjang.png') }}" class="nav-icon" alt="">
            <span>Laporan</span>
        </a>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left;">
                    <img src="{{ asset('images/icon-login.png') }}" class="nav-icon" alt="">
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- KONTEN UTAMA -->
    <main class="main">

        <header class="page-header">
            <div>
                <h1>Kelola Katalog Produk</h1>
                <p>Tambah, ubah, atau hapus daftar roti diskon.</p>
            </div>

            <a href="{{ route('admin.produk.create') }}" class="btn-add">
                + Tambah Produk
            </a>
        </header>

        <!-- NOTIFIKASI -->
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- TABEL PRODUK -->
        <section class="table-card">

            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Produk</th>
                        <th>Harga Asli</th>
                        <th>Harga Diskon</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($produks ?? [] as $produk)

                        <tr>
                            <!-- FOTO PRODUK -->
                            <td>
                                @if ($produk->foto)
                                    @php
                                        if (str_starts_with($produk->foto, 'http')) {
                                            $imageUrl = $produk->foto;
                                        } elseif (str_starts_with($produk->foto, 'storage/') || str_starts_with($produk->foto, 'images/')) {
                                            $imageUrl = asset($produk->foto);
                                        } else {
                                            $imageUrl = asset('storage/' . $produk->foto);
                                        }
                                    @endphp
                                    <img src="{{ $imageUrl }}" 
                                         alt="{{ $produk->nama_produk }}" 
                                         class="product-image"
                                         onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';">
                                @else
                                    <span class="no-image">Tidak ada foto</span>
                                @endif
                            </td>

                            <!-- NAMA -->
                            <td class="product-name">
                                {{ $produk->nama_produk }}
                            </td>

                            <!-- HARGA ASLI -->
                            <td>
                                Rp {{ number_format($produk->harga_asli, 0, ',', '.') }}
                            </td>

                            <!-- HARGA DISKON -->
                            <td>
                                <span class="discount-price">
                                    Rp {{ number_format($produk->harga_diskon ?? $produk->harga_asli, 0, ',', '.') }}
                                </span>
                            </td>

                            <!-- STOK -->
                            <td>
                                {{ $produk->stok }}
                            </td>

                            <!-- AKSI -->
                            <td>
                                <a href="{{ route('admin.produk.edit', $produk->id_produk ?? $produk->id) }}" class="btn-edit">
                                    Edit
                                </a>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="empty-data">
                                Belum ada data produk.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </section>

        <footer class="main-footer">
            © {{ date('Y') }} BreadSaver. Administrator Panel.
        </footer>

    </main>

</body>
</html>