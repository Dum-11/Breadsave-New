
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - BreadSaver</title>

    <style>
        /*
         * RESET CSS
         * box-sizing: border-box memudahkan pengaturan ukuran elemen
         * karena padding dan border ikut dihitung dalam ukuran elemen.
         */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /*
         * Variabel warna digunakan bersama agar tema halaman konsisten
         * dan perubahan warna dapat dilakukan dari satu tempat.
         */
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

        body {
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        a {
            text-decoration: none;
        }

        /*
         * SIDEBAR
         * Ukuran dan jarak dibuat mengikuti halaman Beranda.
         * position: fixed menjaga sidebar tetap di tempat ketika konten
         * digulir, sedangkan margin-left pada konten menyediakan ruangnya.
         */
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

        /* Logo dipusatkan dengan jarak bawah yang sama seperti Beranda. */
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

        /* Judul navigasi mengikuti Beranda. */
        .nav-title {
            color: #c4cce0;
            font-size: 12px;
            letter-spacing: 1px;
            margin: 0 10px 18px;
            text-transform: uppercase;
        }

        /* Ukuran dan jarak menu disamakan dengan Beranda. */
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

        /* Efek hover dan menu aktif. */
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            background: var(--pink);
            color: var(--navy);
            font-weight: bold;
        }

        /* Ukuran ikon mengikuti Beranda. */
        .nav-icon {
            width: 28px;
            height: 28px;
            object-fit: contain;
            flex-shrink: 0;
        }

        /* Login dan Logout tetap berada di bagian bawah sidebar. */
        .sidebar-bottom {
            margin-top: auto;
            padding-top: 28px;
        }

        /* Konten mengikuti lebar sidebar. */
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

        /*
         * RINGKASAN PESANAN
         * Grid memudahkan penyusunan beberapa kartu dalam kolom seimbang.
         * repeat dan minmax membuat kolom dapat menyesuaikan ruang.
         */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }

        .summary-card {
            background: var(--white);
            border: 1px solid #edf0f6;
            border-radius: 13px;
            padding: 20px;
            min-width: 0;
        }

        .summary-label {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .summary-number {
            color: var(--navy);
            font-size: 26px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        /*
         * TABEL PESANAN
         * Tabel cocok untuk data dengan struktur kolom yang konsisten,
         * seperti nomor pesanan, tanggal, status, dan total harga.
         *
         * Dibandingkan kartu terpisah untuk setiap pesanan, tabel lebih
         * mudah dipindai ketika pengguna perlu membandingkan banyak baris.
         */
        .table-card {
            background: var(--white);
            border: 1px solid #edf0f6;
            border-radius: 15px;
            overflow: hidden;
        }

        .table-header {
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
        }

        .table-header h2 {
            font-size: 19px;
            margin-bottom: 6px;
        }

        .table-header p {
            color: var(--muted);
            font-size: 13px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        th,
        td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid #edf0f6;
            font-size: 13px;
        }

        th {
            background: #fafbfe;
            color: var(--muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            color: var(--text);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #fcfcfe;
        }

        /*
         * STATUS PESANAN
         * Class terpisah digunakan supaya warna status dapat dibedakan
         * tanpa perlu menulis style inline pada setiap baris.
         */
        .status {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-menunggu {
            background: #fff4d6;
            color: #956500;
        }

        .status-diproses {
            background: #e6efff;
            color: #315ba8;
        }

        .status-selesai {
            background: #e2f7e9;
            color: #237844;
        }

        .status-dibatalkan {
            background: #ffe6e6;
            color: #b53a3a;
        }

        .order-number {
            color: var(--navy);
            font-weight: bold;
        }

        .order-total {
            font-weight: bold;
        }

        /*
         * PESAN KOSONG
         * Digunakan ketika tabel belum memiliki data pesanan.
         * colspan membuat pesan membentang sepanjang enam kolom tabel.
         */
        .empty-cell {
            padding: 45px 20px;
            text-align: center;
            white-space: normal;
        }

        .empty-cell h3 {
            font-size: 17px;
            margin-bottom: 9px;
        }

        .empty-cell p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .main-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
            text-align: center;
        }

        /*
         * RESPONSIVE
         * Media query menyesuaikan jumlah kolom ringkasan dan padding
         * berdasarkan lebar layar tanpa perlu membuat halaman berbeda.
         */
        @media (max-width: 1250px) {
            .summary-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .main {
                padding: 25px;
            }
        }

        @media (max-width: 700px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                overflow: visible;
                padding: 18px;
            }

            .logo {
                max-width: 170px;
            }

            .logo-container {
                margin-bottom: 20px;
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
                font-size: 21px;
            }

            .summary-grid {
                gap: 12px;
            }

            .summary-card {
                padding: 15px;
            }

            .summary-number {
                font-size: 22px;
            }

            .table-header {
                padding: 18px;
            }
        }

        @media (max-width: 400px) {
            .summary-grid {
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

        <p class="nav-title">Menu Utama</p>

        <a href="{{ url('/') }}" class="nav-link">
            <img
                src="{{ asset('images/icon-home.png') }}"
                alt=""
                class="nav-icon"
            >
            <span>Beranda</span>
        </a>

        <a href="{{ route('katalog') }}" class="nav-link">
            <img
                src="{{ asset('images/icon-product.png') }}"
                alt=""
                class="nav-icon"
            >
            <span>Katalog Produk</span>
        </a>

        <!-- Menu aktif ditandai melalui class active. -->
        <a href="{{ route('pesanan') }}" class="nav-link active">
            <img
                src="{{ asset('images/icon-orders.png') }}"
                alt=""
                class="nav-icon"
            >
            <span>Pesanan</span>
        </a>

        <!-- Tautan fragment hanya berfungsi jika elemen tujuannya tersedia. -->
        <a href="{{ route('keranjang') }}" class="nav-link">
            <img
                src="{{ asset('images/icon-keranjang.png') }}"
                alt=""
                class="nav-icon"
            >
            <span>Keranjang</span>
        </a>

        <div class="sidebar-bottom">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="nav-link"
                        style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left; font-family: inherit;"
                    >
                        <img
                            src="{{ asset('images/icon-login.png') }}"
                            alt=""
                            class="nav-icon"
                        >
                        <span>Logout</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">
                    <img
                        src="{{ asset('images/icon-login.png') }}"
                        alt=""
                        class="nav-icon"
                    >
                    <span>Login</span>
                </a>
            @endauth
        </div>

    </aside>

    <!-- KONTEN UTAMA -->
    <main class="main">

        <header class="page-header">
            <h1>Pesanan</h1>
            <p>Pantau jumlah dan status pesanan di BreadSaver.</p>
        </header>

        <!-- RINGKASAN PESANAN -->
        <section class="summary-grid">

            <article class="summary-card">
                <p class="summary-label">Total Pesanan</p>
                <p class="summary-number">{{ $totalPesanan }}</p>
            </article>

            <article class="summary-card">
                <p class="summary-label">Menunggu Konfirmasi</p>
                <p class="summary-number">{{ $menungguKonfirmasi }}</p>
            </article>

            <article class="summary-card">
                <p class="summary-label">Sedang Diproses</p>
                <p class="summary-number">{{ $diproses }}</p>
            </article>

            <article class="summary-card">
                <p class="summary-label">Pesanan Selesai</p>
                <p class="summary-number">{{ $selesai }}</p>
            </article>

            <article class="summary-card">
                <p class="summary-label">Pesanan Dibatalkan</p>
                <p class="summary-number">{{ $dibatalkan }}</p>
            </article>

        </section>

        <!-- DAFTAR PESANAN -->
        <section class="table-card">

            <div class="table-header">
                <h2>Daftar Pesanan</h2>
                <p>Data pesanan yang tersimpan di database.</p>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nomor Pesanan</th>
                            <th>ID Pengguna</th>
                            <th>Tanggal Pesan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pesanans as $pesanan)
                            <tr>
                                <td class="order-number">
                                    #{{ $pesanan->id_pesanan }}
                                </td>

                                <td>
                                    {{ $pesanan->id_user }}
                                </td>

                                <td>
                                    {{ $pesanan->tanggal_pesan
                                        ? \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d-m-Y H:i')
                                        : '-' }}
                                </td>

                                <td class="order-total">
                                    Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                </td>

                                <td>
                                    @php
                                        /*
                                         * Menentukan class status berdasarkan nilai
                                         * yang tersimpan di database.
                                         *
                                         * Array mapping lebih mudah dirawat daripada
                                         * rangkaian kondisi berulang ketika jumlah
                                         * status bertambah.
                                         */
                                        $statusClasses = [
                                            'Menunggu Konfirmasi' => 'status-menunggu',
                                            'Diproses' => 'status-diproses',
                                            'Selesai' => 'status-selesai',
                                            'Dibatalkan' => 'status-dibatalkan',
                                        ];

                                        $statusClass = $statusClasses[$pesanan->status]
                                            ?? 'status-menunggu';
                                    @endphp

                                    <span class="status {{ $statusClass }}">
                                        {{ $pesanan->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-cell">
                                    <h3>Belum Ada Pesanan</h3>
                                    <p>
                                        Data pesanan belum tersedia.
                                        Pesanan yang tercatat akan muncul di sini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </section>

        <footer class="main-footer">
            © {{ date('Y') }} BreadSaver.
            Selamatkan Roti, Kurangi Food Waste.
        </footer>

    </main>

</body>
</html>
