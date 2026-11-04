<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - Pembeli BreadSaver</title>
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

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; background: var(--background); color: var(--text); }
        a { color: inherit; text-decoration: none; }

        /* Sidebar Navigasi Utama */
        .sidebar {
            position: fixed; top: 0; left: 0; width: 240px; height: 100vh;
            padding: 28px 22px; background: var(--navy); color: var(--white);
            display: flex; flex-direction: column; overflow-y: auto; z-index: 1000;
        }
        .logo-container { display: flex; justify-content: center; align-items: center; margin-bottom: 56px; }
        .logo { width: 180px; max-width: 100%; height: auto; object-fit: contain; }
        .menu-title { color: #c4cce0; font-size: 12px; letter-spacing: 1px; margin: 0 10px 18px; }
        .menu { display: flex; flex-direction: column; gap: 8px; }
        .menu-link {
            display: flex; align-items: center; gap: 14px; min-height: 58px;
            padding: 12px 14px; border-radius: 12px; color: var(--white); font-size: 15px;
            transition: background-color 0.2s, color 0.2s;
        }
        .menu-link:hover { background: rgba(255, 255, 255, 0.1); }
        .menu-link.active { background: var(--pink); color: var(--navy); font-weight: bold; }
        .menu-icon { width: 28px; height: 28px; object-fit: contain; flex-shrink: 0; }
        .sidebar-bottom { margin-top: auto; padding-top: 28px; }

        /* Konten Utama */
        .main { margin-left: 240px; min-height: 100vh; padding: 32px; }
        .welcome-title { font-size: 30px; font-weight: 700; color: var(--navy); margin-bottom: 10px; }
        .welcome-subtitle { color: var(--muted); font-size: 15px; line-height: 1.6; margin-bottom: 24px; }
        
        .section-heading { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 22px; }
        .product-count { padding: 10px 16px; border-radius: 30px; background: var(--pink-light); color: #ed6590; font-size: 13px; }

        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 22px; }
        .product-card { overflow: hidden; background: var(--white); border: 1px solid var(--border); border-radius: 16px; transition: 0.2s; }
        .product-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(35, 54, 95, 0.08); }
        
        /* Wadah Gambar Produk agar pas dan tidak gepeng */
        .product-image-container { height: 190px; display: flex; justify-content: center; align-items: center; padding: 20px; background: var(--pink-light); }
        .product-image { max-width: 100%; max-height: 100%; object-fit: contain; }
        
        .product-info { padding: 18px; }
        .product-name { color: var(--navy); font-size: 17px; margin-bottom: 12px; }
        .price-row { display: flex; align-items: center; gap: 8px; margin-bottom: 14px; }
        .price-original { color: var(--muted); font-size: 13px; text-decoration: line-through; }
        .price-discount { color: #ed6590; font-size: 18px; font-weight: bold; }
        .product-detail { display: flex; flex-direction: column; gap: 8px; color: var(--muted); font-size: 13px; margin-bottom: 16px; }
        
        .buy-button {
            display: block; width: 100%; padding: 12px; text-align: center;
            background: var(--pink); color: var(--navy); font-size: 14px; font-weight: bold;
            border: none; border-radius: 9px; cursor: pointer; text-decoration: none;
        }
        .buy-button:hover { background: #ff7da5; }

        .footer { margin-top: 44px; padding: 24px 0; border-top: 1px solid var(--border); color: var(--muted); font-size: 13px; text-align: center; }
    </style>
</head>
<body>

    <!-- Sidebar Navigasi -->
    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" alt="BreadSaver" class="logo"></a>
        </div>
        <p class="menu-title">MENU UTAMA</p>
        <nav class="menu">
            <a href="{{ route('pembeli.beranda') }}" class="menu-link {{ request()->routeIs('pembeli.beranda') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-home.png') }}" class="menu-icon"><span>Beranda</span>
            </a>
            <a href="{{ route('pembeli.katalog') }}" class="menu-link {{ request()->routeIs('pembeli.katalog') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-product.png') }}" class="menu-icon"><span>Katalog Produk</span>
            </a>
            <a href="{{ route('pembeli.pesanan') }}" class="menu-link {{ request()->routeIs('pembeli.pesanan') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-orders.png') }}" class="menu-icon"><span>Pesanan</span>
            </a>
            <a href="{{ route('pembeli.keranjang') }}" class="menu-link {{ request()->routeIs('pembeli.keranjang') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-keranjang.png') }}" class="menu-icon" alt=""><span>Keranjang</span>
            </a>
        </nav>

        <div class="sidebar-bottom">
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="menu-link" style="width: 100%; border: none; background: transparent; cursor: pointer;">
                        <img src="{{ asset('images/icon-login.png') }}" class="menu-icon" alt=""><span>Logout</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="menu-link">
                    <img src="{{ asset('images/icon-login.png') }}" class="menu-icon" alt=""><span>Login</span>
                </a>
            @endauth
        </div>
    </aside>

    <!-- Konten Utama -->
    <main class="main">
        <header>
            <h1 class="welcome-title">Katalog Produk</h1>
            <p class="welcome-subtitle">Pilih dan beli produk roti favorit Anda untuk menyelamatkan makanan.</p>
        </header>

        <section>
            <div class="section-heading">
                <h2 style="color: var(--navy); font-size: 20px;">Daftar Roti Tersedia</h2>
                <span class="product-count">{{ isset($produks) ? $produks->count() : 0 }} Produk</span>
            </div>

            @if(isset($produks) && $produks->count() > 0)
                <div class="product-grid">
                    @foreach($produks as $produk)
                        @php
                            $hargaAsli = (float)($produk->harga_asli ?? 0);
                            $hargaDiskon = (float)($produk->harga_diskon ?? $hargaAsli);
                            $stok = (int)($produk->stok ?? 0);

                            // Logika pemilihan gambar otomatis berdasarkan nama produk
                            $namaProduk = strtolower($produk->nama_produk);
                            if (\Illuminate\Support\Str::contains($namaProduk, ['cokelat', 'coklat', 'chocolate'])) {
                                $gambarProduk = 'images/roti-cokelat.png';
                            } elseif (\Illuminate\Support\Str::contains($namaProduk, ['croissant', 'pastry'])) {
                                $gambarProduk = 'images/croissant.png';
                            } elseif (\Illuminate\Support\Str::contains($namaProduk, ['tawar', 'sandwich'])) {
                                $gambarProduk = 'images/roti-tawar.png';
                            } else {
                                $gambarProduk = 'images/bread.png';
                            }
                        @endphp

                        <article class="product-card">
                            <div class="product-image-container">
                                <img src="{{ asset($gambarProduk) }}" alt="{{ $produk->nama_produk }}" class="product-image">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">{{ $produk->nama_produk }}</h3>
                                <div class="price-row">
                                    @if($hargaAsli > $hargaDiskon)
                                        <span class="price-original">Rp{{ number_format($hargaAsli, 0, ',', '.') }}</span>
                                    @endif
                                    <span class="price-discount">Rp{{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                                </div>
                                <div class="product-detail">
                                    <span>Stok: {{ $stok }}</span>
                                    @if(!empty($produk->expired_at))
                                        <span>Kedaluwarsa: {{ \Carbon\Carbon::parse($produk->expired_at)->format('d-m-Y') }}</span>
                                    @endif
                                </div>

                                <!-- Tombol Aksi Tambah ke Keranjang / Beli -->
                                @auth
                                    <form action="{{ route('keranjang.tambah', $produk->id_produk) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="buy-button">Beli Sekarang</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="buy-button">Login untuk Membeli</a>
                                @endauth

                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div style="background: #fff; padding: 40px; text-align: center; border-radius: 16px; border: 1px solid var(--border);">
                    <h3>Belum Ada Roti Tersedia</h3>
                    <p style="color: var(--muted); margin-top: 8px;">Silakan cek kembali nanti.</p>
                </div>
            @endif
        </section>

        <footer class="footer">© {{ date('Y') }} BreadSaver. Selamatkan Roti, Kurangi Food Waste.</footer>
    </main>
</body>
</html>