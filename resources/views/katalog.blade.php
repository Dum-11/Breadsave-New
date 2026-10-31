
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - BreadSaver</title>
    <style>
        :root {
            --navy: #23365f;
            --pink: #ff8fb1;
            --pink-light: #fff0f5;
            --background: #f8f9fc;
            --text: #26324b;
            --muted: #8991a4;
            --white: #ffffff;
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
            z-index: 1000;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 56px;
        }

        .logo {
            width: 180px;
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
            gap: 14px;
            min-height: 58px;
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 8px;
            color: var(--white);
            font-size: 15px;
            text-decoration: none;
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
            width: 28px;
            height: 28px;
            object-fit: contain;
        }

        .sidebar-bottom {
            margin-top: auto;
        }

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
        }

        .catalog-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 24px;
        }

        .search-input {
            width: 100%;
            max-width: 420px;
            height: 43px;
            padding: 0 14px;
            border: 1px solid #e1e5ee;
            border-radius: 9px;
            outline: none;
            font-size: 13px;
        }

        .search-input:focus {
            border-color: var(--navy);
        }

        .product-count {
            background: var(--pink-light);
            color: #d64d7b;
            padding: 9px 13px;
            border-radius: 20px;
            font-size: 12px;
            white-space: nowrap;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .product-card {
            background: white;
            border: 1px solid #edf0f6;
            border-radius: 15px;
            padding: 18px;
        }

        /* Area gambar produk */
        .product-image {
            width: 100%;
            height: 180px;
            background: var(--pink-light);
            border-radius: 12px;
            margin-bottom: 17px;
            overflow: hidden;
        }

        /* Gambar memenuhi seluruh area produk */
        .bread-image {
            width: 100%;
            height: 100%;
            padding: 0;
            object-fit: cover;
            object-position: center;
            display: block;
        }

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

        .product-card h2 {
            font-size: 17px;
            margin-bottom: 12px;
        }

        .price-old {
            color: var(--muted);
            font-size: 13px;
            text-decoration: line-through;
            margin-bottom: 5px;
        }

        .price-new {
            color: var(--navy);
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .product-info {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.9;
            margin-bottom: 15px;
        }

        .buy-btn {
            display: block;
            text-align: center;
            padding: 12px;
            border-radius: 9px;
            background: var(--navy);
            color: white;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
        }

        .buy-btn:hover {
            background: #344b7d;
        }

        .main-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e7eaf1;
            color: var(--muted);
            font-size: 12px;
            text-align: center;
        }

        [hidden] {
            display: none !important;
        }

        @media (max-width: 1000px) {
            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 650px) {
            .sidebar {
                width: 190px;
                padding: 22px 14px;
            }

            .main {
                margin-left: 190px;
                padding: 20px 14px;
            }

            .products {
                grid-template-columns: 1fr;
            }

            .catalog-tools {
                align-items: stretch;
                flex-direction: column;
            }

            .search-input {
                max-width: none;
            }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="BreadSaver">
            </a>
        </div>

        <p class="nav-title">Menu Utama</p>

        <a href="{{ url('/') }}" class="nav-link">
            <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt="">
            <span>Beranda</span>
        </a>

        <a href="{{ route('katalog') }}" class="nav-link active">
            <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt="">
            <span>Katalog Produk</span>
        </a>

        <a href="{{ route('pesanan') }}" class="nav-link">
            <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt="">
            <span>Pesanan</span>
        </a>

        <a href="{{ route('keranjang') }}" class="nav-link">
            <img src="{{ asset('images/icon-keranjang.png') }}" class="nav-icon" alt="">
            <span>Keranjang</span>
        </a>

        <div class="sidebar-bottom">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link" style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left;">
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
            <h1>Katalog Produk</h1>
            <p>Temukan roti yang tersedia dengan harga lebih hemat.</p>
        </header>

        <div class="catalog-tools">
            <input
                type="search"
                id="searchProduct"
                class="search-input"
                placeholder="Cari nama roti..."
            >

            <span class="product-count" id="productCount">
                {{ $produks->count() }} Produk
            </span>
        </div>

        <section class="products" id="productList">
            @forelse ($produks as $produk)
                @php
                    $namaProduk = strtolower($produk->nama_produk);

                    if (
                        \Illuminate\Support\Str::contains(
                            $namaProduk,
                            ['croissant', 'pastry']
                        )
                    ) {
                        $gambarProduk = 'images/croissant.png';
                    } elseif (
                        \Illuminate\Support\Str::contains(
                            $namaProduk,
                            ['tawar', 'sandwich']
                        )
                    ) {
                        $gambarProduk = 'images/roti-tawar.png';
                    } elseif (
                        \Illuminate\Support\Str::contains(
                            $namaProduk,
                            ['cokelat', 'coklat', 'chocolate']
                        )
                    ) {
                        $gambarProduk = 'images/roti-cokelat.png';
                    } else {
                        $gambarProduk = 'images/bread.png';
                    }

                    $diskon = $produk->harga_asli > 0
                        ? round(
                            (($produk->harga_asli - $produk->harga_diskon)
                            / $produk->harga_asli) * 100
                        )
                        : 0;
                @endphp

                <article
                    class="product-card"
                    data-product-name="{{ $namaProduk }}"
                >
                    <div class="product-image">
                        <img
                            src="{{ asset($gambarProduk) }}"
                            class="bread-image"
                            alt="{{ $produk->nama_produk }}"
                            onerror="this.onerror=null; this.src='{{ asset('images/bread.png') }}';"
                        >
                    </div>

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
                        {{ $produk->expired_at ? \Carbon\Carbon::parse($produk->expired_at)->format('d-m-Y') : '-' }}
                    </div>

                    @auth
                        <a href="#" class="buy-btn" style="background: var(--pink); color: var(--navy);">
                            Beli Sekarang
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="buy-btn">
                            Login untuk Membeli
                        </a>
                    @endauth
                </article>
            @empty
                <div class="empty-state" style="grid-column: 1/-1; text-align: center; background: #fff; padding: 30px; border-radius: 15px;">
                    <h2>Belum Ada Roti Tersedia</h2>
                </div>
            @endforelse

            <div class="empty-state" id="noSearchResult" hidden style="grid-column: 1/-1; text-align: center; background: #fff; padding: 30px; border-radius: 15px;">
                <h2>Produk Tidak Ditemukan</h2>
                <p style="color: var(--muted);">Coba gunakan kata kunci nama roti yang berbeda.</p>
            </div>
        </section>

        <footer class="main-footer">
            © {{ date('Y') }} BreadSaver. Selamatkan Roti, Kurangi Food Waste.
        </footer>
    </main>

    <script>
        const searchInput = document.getElementById('searchProduct');
        const productCards = document.querySelectorAll('.product-card[data-product-name]');
        const productCount = document.getElementById('productCount');
        const noSearchResult = document.getElementById('noSearchResult');

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

            productCount.textContent = visibleCount + ' Produk';

            noSearchResult.hidden =
                productCards.length === 0 || visibleCount !== 0;
        });
    </script>

</body>
</html>
