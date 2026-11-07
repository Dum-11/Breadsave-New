
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

        html, body {
            width: 100%;
            min-height: 100%;
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        body {
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 230px;
            height: 100vh;
            padding: 28px 20px;
            background: var(--navy);
            color: var(--white);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 50px;
        }

        .logo {
            width: 170px;
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
            gap: 12px;
            min-height: 52px;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 8px;
            color: var(--white);
            font-size: 14px;
            width: 100%;
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
            width: 25px;
            height: 25px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-bottom {
            margin-top: auto;
        }

        /* KONTEN UTAMA */
        .main {
            width: calc(100% - 230px);
            min-height: 100vh;
            margin-left: 230px;
            padding: 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 28px;
        }

        .page-header h1 {
            font-size: 26px;
            margin-bottom: 7px;
            color: var(--navy);
        }

        .page-header p {
            color: var(--muted);
            font-size: 14px;
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

        /* TABEL */
        .table-card {
            width: 100%;
            background: var(--white);
            border: 1px solid #edf0f6;
            border-radius: 15px;
            overflow: hidden;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 10px;
            text-align: left;
            border-bottom: 1px solid #edf0f6;
            font-size: 13px;
            overflow-wrap: anywhere;
            vertical-align: middle;
        }

        th {
            background: #fafbfe;
            color: var(--muted);
            font-size: 11px;
            text-transform: uppercase;
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
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
            text-align: center;
        }

        /* TABLET */
        @media screen and (max-width: 900px) {
            .sidebar {
                width: 190px;
                padding: 25px 14px;
            }

            .main {
                width: calc(100% - 190px);
                margin-left: 190px;
                padding: 22px;
            }

            .page-header h1 {
                font-size: 22px;
            }

            th, td {
                padding: 10px 6px;
                font-size: 11px;
            }

            .product-image {
                width: 52px;
                height: 52px;
            }
        }

        /* HANDPHONE */
        @media screen and (max-width: 650px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 15px;
            }

            .logo-container {
                margin-bottom: 20px;
            }

            .logo {
                width: 140px;
            }

            .nav-title {
                margin-bottom: 10px;
            }

            .nav-link {
                display: inline-flex;
                width: auto;
                min-height: 42px;
                padding: 9px;
                font-size: 12px;
                gap: 7px;
            }

            .nav-icon {
                width: 20px;
                height: 20px;
            }

            .sidebar-bottom {
                margin-top: 8px;
            }

            .main {
                width: 100%;
                margin-left: 0;
                padding: 20px 14px;
            }

            .page-header {
                align-items: flex-start;
            }

            .page-header h1 {
                font-size: 21px;
            }

            .page-header p {
                font-size: 12px;
            }

            .btn-add {
                padding: 10px 13px;
                font-size: 12px;
            }

            th, td {
                padding: 8px 4px;
                font-size: 9px;
            }

            th {
                font-size: 8px;
            }

            .product-image {
                width: 40px;
                height: 40px;
            }

            .btn-edit {
                padding: 6px 7px;
                font-size: 9px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="logo-container">
            <a href="{{ route('admin.beranda') }}">
                <img src="{{ asset('images/logo.png') }}"
                     class="logo"
                     alt="Logo BreadSaver">
            </a>
        </div>

        <p class="nav-title">Admin Panel</p>

        <a href="{{ route('admin.beranda') }}"
           class="nav-link {{ request()->routeIs('admin.beranda') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-home.png') }}"
                 class="nav-icon" alt="">
            <span>Beranda</span>
        </a>

        <a href="{{ route('admin.katalog') }}"
           class="nav-link {{ request()->routeIs('admin.katalog') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-product.png') }}"
                 class="nav-icon" alt="">
            <span>Katalog</span>
        </a>

        <a href="{{ route('admin.kelola_akun') }}" class="nav-link">
            <img src="{{ asset('images/icon-orders.png') }}"
                 class="nav-icon" alt="">
            <span>Kelola Akun</span>
        </a>

        <a href="{{ route('admin.laporan') }}" class="nav-link">
            <img src="{{ asset('images/icon-keranjang.png') }}"
                 class="nav-icon" alt="">
            <span>Laporan</span>
        </a>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                        class="nav-link"
                        style="border:0;background:transparent;cursor:pointer;text-align:left;">
                    <img src="{{ asset('images/icon-login.png') }}"
                         class="nav-icon" alt="">
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
                                    <img
                                        src="{{ asset('storage/' . $produk->foto) }}"
                                        alt="{{ $produk->nama_produk }}"
                                        class="product-image"
                                    >
                                @else
                                    <span class="no-image">
                                        Tidak ada foto
                                    </span>
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
                            <td class="discount-price">
                                Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}
                            </td>

                            <!-- STOK -->
                            <td>
                                {{ $produk->stok }}
                            </td>

                            <!-- AKSI -->
                            <td>
                                <a href="{{ route('admin.produk.edit', $produk->id_produk) }}"
                                   class="btn-edit">
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
