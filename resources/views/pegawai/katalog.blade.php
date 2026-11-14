<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - Pegawai BreadSaver</title>

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
            color: inherit;
            text-decoration: none;
        }

        /* SIDEBAR */
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

        /* MAIN CONTENT */
        .main {
            margin-left: 70px;
            min-height: 100vh;
            padding: 32px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .page-header {
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

        /* INFO CARD */
        .info-card {
            background: var(--white);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid var(--border);
            margin-bottom: 22px;
        }

        .info-card h3 {
            color: var(--navy);
            font-size: 16px;
            margin-bottom: 7px;
        }

        .info-card p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
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
        th:nth-child(2) { width: 20%; }
        th:nth-child(3) { width: 16%; }
        th:nth-child(4) { width: 12%; }
        th:nth-child(5) { width: 20%; }
        th:nth-child(6) { width: 20%; }

        .product-image {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-name {
            font-weight: bold;
            color: var(--navy);
        }

        .price {
            color: #ed6590;
            font-weight: bold;
        }

        .stock {
            display: inline-block;
            padding: 6px 10px;
            background: var(--pink-light);
            color: var(--navy);
            border-radius: 7px;
            font-weight: bold;
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
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
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
               class="nav-link profile-link {{ request()->routeIs('pegawai.profil') ? 'active' : '' }}">

                @if(Auth::user()->foto_profil)

                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}"
                         class="nav-icon"
                         alt="Foto Profil"
                         style="border-radius: 50%; object-fit: cover;">

                @else

                    <div class="nav-icon profile-initial"
                         style="width: 24px; height: 24px; border-radius: 50%; background: var(--pink); color: var(--white); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">
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

                    <img src="{{ asset('images/icon-login.png') }}"
                         class="nav-icon"
                         alt="">

                    <span>Logout</span>

                </button>
            </form>

        </div>

    </aside>

    <!-- KONTEN UTAMA -->
    <main class="main">

        <header class="page-header">
            <h1>Daftar Produk & Stok</h1>
            <p>Informasi produk yang dikelola oleh administrator BreadSaver.</p>
        </header>

        <section class="info-card">

            <h3>Informasi Katalog</h3>

            <p>
                Data produk di halaman ini berasal dari database yang sama
                dengan halaman admin. Setiap perubahan produk oleh admin
                akan ditampilkan ketika halaman ini dimuat ulang.
            </p>

        </section>

        <!-- TABEL PRODUK -->
        <section class="table-card">

            <table>

                <thead>

                    <tr>
                        <th>Foto</th>
                        <th>Nama Produk</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Deskripsi</th>
                        <th>Kedaluwarsa</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse ($produks ?? [] as $produk)

                        <tr>

                            <td>
                                @if ($produk->foto)

                                    <img src="{{ asset('storage/' . $produk->foto) }}"
                                         class="product-image"
                                         alt="{{ $produk->nama_produk }}">

                                @else

                                    <span>-</span>

                                @endif
                            </td>

                            <td class="product-name">
                                {{ $produk->nama_produk }}
                            </td>

                            <td class="price">
                                Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}
                            </td>

                            <td>
                                <span class="stock">
                                    {{ $produk->stok }} Pcs
                                </span>
                            </td>

                            <td>
                                {{ $produk->deskripsi ?: '-' }}
                            </td>

                            <td>
                                {{ $produk->expired_at
                                    ? \Carbon\Carbon::parse($produk->expired_at)->format('d-m-Y')
                                    : '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="empty-data">
                                Belum ada data produk toko.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </section>

        <footer class="main-footer">
            © {{ date('Y') }} BreadSaver. Staff Panel.
        </footer>

    </main>

</body>
</html>
