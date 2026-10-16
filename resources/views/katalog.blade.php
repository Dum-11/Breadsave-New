
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Mengatur encoding agar teks berbahasa Indonesia tampil dengan benar. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Menyesuaikan ukuran halaman dengan layar komputer, tablet, dan HP. -->
    <title>Katalog Produk - BreadSaver</title>

    <style>
        /* Menyamakan aturan dasar ukuran seluruh elemen. */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Warna mengikuti identitas visual halaman utama BreadSaver. */
        :root {
            --navy: #23365f;
            --pink: #ff8fb1;
            --pink-light: #fff0f5;
            --background: #f8f9fc;
            --text: #26324b;
            --muted: #8991a4;
            --white: #ffffff;
        }

        /* Mengatur font dan latar dasar halaman katalog. */
        body {
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        /* Menghilangkan garis bawah pada tautan. */
        a {
            text-decoration: none;
        }

        /* Sidebar mengikuti ukuran dan susunan Beranda. */
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

        /* Judul halaman dan keterangan katalog. */
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

        /* Wadah pencarian dan jumlah hasil katalog. */
        .catalog-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 24px;
        }

        /* Kotak pencarian produk. */
        .search-input {
            width: 100%;
            max-width: 420px;
            height: 43px;
            padding: 0 14px;
            border: 1px solid #e1e5ee;
            border-radius: 9px;
            background: white;
            color: var(--text);
            font-family: inherit;
            font-size: 13px;
            outline: none;
        }

        /* Menandai kotak pencarian ketika digunakan. */
        .search-input:focus {
            border-color: var(--navy);
        }

        /* Label jumlah produk yang ditemukan. */
        .product-count {
            background: var(--pink-light);
            color: #d64d7b;
            padding: 9px 13px;
            border-radius: 20px;
            font-size: 12px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* Menyusun produk dalam tiga kolom. */
        .products {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        /* Tampilan kartu setiap produk. */
        .product-card {
            background: white;
            border: 1px solid #edf0f6;
            border-radius: 15px;
            padding: 18px;
        }

        /* Area gambar roti pada kartu produk. */
        .product-image {
            height: 160px;
            background: var(--pink-light);
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 17px;
            overflow: hidden;
        }

        /* Menjaga gambar roti tetap utuh. */
        .bread-image {
            width: 90%;
            height: 90%;
            object-fit: contain;
        }

        /* Label persentase diskon produk. */
        .discount-label {
            display: inline-block;
            background: #ffe4ec;
            color: #d64d7b;
            font-size: 11px;
            font-weight: bold;
            padding: 6px 10px;
            border-radius: 20px;
            margin-bottom: 12px;
        }

        /* Nama produk dari database. */
        .product-card h2 {
            font-size: 17px;
            margin-bottom: 12px;
            overflow-wrap: anywhere;
        }

        /* Harga sebelum diskon. */
        .price-old {
            color: var(--muted);
            font-size: 13px;
            text-decoration: line-through;
            margin-bottom: 5px;
        }

        /* Harga setelah diskon. */
        .price-new {
            color: var(--navy);
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        /* Informasi stok dan tanggal kedaluwarsa. */
        .product-info {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.9;
            margin-bottom: 15px;
        }

        /* Tombol menuju halaman login untuk melanjutkan pembelian. */
        .buy-btn {
            display: block;
            text-align: center;
            padding: 12px;
            border-radius: 9px;
            background: var(--navy);
            color: white;
            font-size: 13px;
            font-weight: bold;
        }

        /* Mengubah warna tombol saat diarahkan pointer. */
        .buy-btn:hover {
            background: #344b7d;
        }

        /* Tampilan saat tidak ada produk yang sesuai pencarian. */
        .empty-state {
            background: white;
            padding: 35px 20px;
            border: 1px solid #edf0f6;
            border-radius: 15px;
            text-align: center;
            grid-column: 1 / -1;
        }

        /* Mengatur ukuran gambar pada kondisi kosong. */
        .empty-image {
            display: block;
            width: 130px;
            height: 130px;
            object-fit: contain;
            margin: 0 auto 15px;
        }

        .empty-state h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        /* Footer halaman katalog. */
        .main-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e7eaf1;
            color: var(--muted);
            font-size: 12px;
            text-align: center;
        }

        /* Mengubah grid menjadi dua kolom pada layar menengah. */
        @media (max-width: 1100px) {
            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .main {
                padding: 25px;
            }
        }

        /* Menyesuaikan halaman untuk layar HP. */
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

            .page-header p {
                font-size: 12px;
            }

            .catalog-tools {
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .search-input {
                max-width: 100%;
            }

            .products {
                grid-template-columns: 1fr;
            }

            .product-image {
                height: 190px;
            }
        }

        /* Menyesuaikan jarak untuk layar yang sangat kecil. */
        @media (max-width: 400px) {
            .product-count {
                padding: 8px 10px;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar menggunakan identitas dan ikon yang sama dengan beranda. -->
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

        <!-- Menu Beranda membuka halaman utama. -->
        <a href="{{ url('/') }}" class="nav-link">
            <img
                src="{{ asset('images/icon-home.png') }}"
                alt=""
                class="nav-icon"
            >
            <span>Beranda</span>
        </a>

        <!-- Menu katalog ditandai aktif karena halaman ini sedang dibuka. -->
        <a href="{{ route('katalog') }}" class="nav-link active">
            <img
                src="{{ asset('images/icon-product.png') }}"
                alt=""
                class="nav-icon"
            >
            <span>Katalog Produk</span>
        </a>

        <!-- Menu Pesanan menggunakan ikon PNG yang telah disiapkan. -->
        <a href="#pesanan" class="nav-link">
            <img
                src="{{ asset('images/icon-orders.png') }}"
                alt=""
                class="nav-icon"
            >
            <span>Pesanan</span>
        </a>

        <!-- Menu Keranjang menggunakan ikon PNG yang telah disiapkan. -->
        <a href="#keranjang" class="nav-link">
            <img
                src="{{ asset('images/icon-keranjang.png') }}"
                alt=""
                class="nav-icon"
            >
            <span>Keranjang</span>
        </a>

        <!-- Login ditampilkan untuk pengguna yang belum masuk.
             Logout ditampilkan jika autentikasi Laravel aktif. -->
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

    <!-- Konten utama katalog produk. -->
    <main class="main">

        <header class="page-header">
            <h1>Katalog Produk</h1>
            <p>Temukan roti yang tersedia dengan harga lebih hemat.</p>
        </header>

        <!-- Kolom pencarian memfilter produk yang sudah dimuat dari database. -->
        <div class="catalog-tools">
            <input
                type="search"
                id="searchProduct"
                class="search-input"
                placeholder="Cari nama roti..."
                aria-label="Cari nama roti"
            >

            <span class="product-count" id="productCount">
                {{ $produks->count() }} Produk
            </span>
        </div>

        <!-- Daftar produk diisi oleh data dari model Produk. -->
        <section class="products" id="productList">

            @forelse ($produks as $produk)

                <article
                    class="product-card"
                    data-product-name="{{ strtolower($produk->nama_produk) }}"
                >

                    <div class="product-image">
                        <img
                            src="{{ asset('images/bread.png') }}"
                            alt="Roti {{ $produk->nama_produk }}"
                            class="bread-image"
                        >
                    </div>

                    <!-- Menghitung diskon berdasarkan harga asli dan harga diskon. -->
                    @php
                        $diskon = $produk->harga_asli > 0
                            ? round(
                                (
                                    ($produk->harga_asli - $produk->harga_diskon)
                                    / $produk->harga_asli
                                ) * 100
                            )
                            : 0;
                    @endphp

                    <span class="discount-label">
                        Hemat {{ max(0, $diskon) }}%
                    </span>

                    <h2>{{ $produk->nama_produk }}</h2>

                    <p class="price-old">
                        Rp {{ number_format($produk->harga_asli, 0, ',', '.') }}
                    </p>

                    <p class="price-new">
                        Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}
                    </p>

                    <div class="product-info">
                        Stok: {{ $produk->stok }}<br>
                        Kedaluwarsa:
                        {{ $produk->expired_at
                            ? \Carbon\Carbon::parse($produk->expired_at)->format('d-m-Y')
                            : '-' }}
                    </div>

                    <!-- Pembelian tetap diarahkan ke login seperti di beranda. -->
                    <a href="{{ route('login') }}" class="buy-btn">
                        Login untuk Membeli
                    </a>

                </article>

            @empty

                <!-- Pesan ini ditampilkan jika database belum memiliki produk. -->
                <div class="empty-state" id="emptyState">
                    <img
                        src="{{ asset('images/bread.png') }}"
                        alt="Roti"
                        class="empty-image"
                    >

                    <h2>Belum Ada Roti Tersedia</h2>

                    <p>
                        Saat ini belum ada produk roti yang dijual.
                        Silakan cek kembali nanti.
                    </p>
                </div>

            @endforelse

            <!-- Pesan untuk hasil pencarian yang tidak ditemukan. -->
            <div class="empty-state" id="noSearchResult" hidden>
                <h2>Produk Tidak Ditemukan</h2>
                <p>Coba gunakan nama roti yang berbeda.</p>
            </div>

        </section>

        <footer class="main-footer">
            © {{ date('Y') }} BreadSaver.
            Selamatkan Roti, Kurangi Food Waste.
        </footer>

    </main>

    <script>
        /*
         * Pencarian berjalan di browser dengan mencocokkan teks nama produk.
         * Tidak perlu mengirim permintaan baru ke server setiap kali mengetik.
         */
        const searchInput = document.getElementById('searchProduct');
        const productCards = document.querySelectorAll(
            '.product-card[data-product-name]'
        );
        const productCount = document.getElementById('productCount');
        const noSearchResult = document.getElementById('noSearchResult');

        // Memfilter kartu produk setiap kali isi pencarian berubah.
        searchInput.addEventListener('input', function () {
            const keyword = this.value.trim().toLowerCase();
            let visibleCount = 0;

            productCards.forEach(function (card) {
                const productName = card.dataset.productName || '';
                const matches = productName.includes(keyword);

                card.hidden = !matches;

                if (matches) {
                    visibleCount++;
                }
            });

            // Menampilkan jumlah produk yang cocok dengan pencarian.
            productCount.textContent = visibleCount + ' Produk';

            // Menampilkan pesan jika pencarian tidak menghasilkan produk.
            noSearchResult.hidden =
                productCards.length === 0 || visibleCount !== 0;
        });
    </script>

</body>
</html>
