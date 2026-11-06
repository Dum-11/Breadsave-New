
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
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
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
            color: white;
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
            color: white;
            font-size: 14px;
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
            width: 25px;
            height: 25px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-bottom {
            margin-top: auto;
        }

        /* KONTEN */
        .main {
            width: calc(100% - 230px);
            min-height: 100vh;
            margin-left: 230px;
            padding: 30px;
        }

        .page-header {
            margin-bottom: 28px;
        }

        .page-header h1 {
            font-size: 26px;
            color: var(--navy);
            margin-bottom: 7px;
        }

        .page-header p {
            color: var(--muted);
            font-size: 14px;
        }

        /* INFO */
        .info-card {
            background: var(--white);
            padding: 20px;
            border-radius: 13px;
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
        }

        /* TABEL */
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
            padding: 14px 10px;
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

            th, td {
                padding: 10px 6px;
                font-size: 11px;
            }

            .product-image {
                width: 50px;
                height: 50px;
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

            .page-header h1 {
                font-size: 21px;
            }

            th, td {
                padding: 8px 4px;
                font-size: 9px;
            }

            th {
                font-size: 8px;
            }

            .product-image {
                width: 38px;
                height: 38px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="logo-container">
            <a href="{{ route('pegawai.beranda') }}">
                <img src="{{ asset('images/logo.png') }}"
                     class="logo"
                     alt="BreadSaver">
            </a>
        </div>

        <p class="nav-title">Pegawai Panel</p>

        <a href="{{ route('pegawai.beranda') }}" class="nav-link">
            <img src="{{ asset('images/icon-home.png') }}"
                 class="nav-icon" alt="">
            <span>Beranda</span>
        </a>

        <a href="{{ route('pegawai.katalog') }}" class="nav-link active">
            <img src="{{ asset('images/icon-product.png') }}"
                 class="nav-icon" alt="">
            <span>Katalog Produk</span>
        </a>

        <a href="{{ route('pegawai.pesanan') }}" class="nav-link">
            <img src="{{ asset('images/icon-orders.png') }}"
                 class="nav-icon" alt="">
            <span>Pesanan Masuk</span>
        </a>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                        class="nav-link"
                        style="width:100%;border:0;background:transparent;cursor:pointer;text-align:left;">

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
                                    <img
                                        src="{{ asset('storage/' . $produk->foto) }}"
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
