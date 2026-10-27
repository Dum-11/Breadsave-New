<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - BreadSaver</title>
    <style>
        :root {
            --navy: #23365f; --pink: #ff8fb1; --background: #f8f9fc;
            --text: #26324b; --muted: #8991a4; --white: #ffffff; --border: #e7eaf1;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: var(--background); color: var(--text); }
        
        .sidebar { position: fixed; top: 0; left: 0; width: 240px; height: 100vh; padding: 28px 22px; background: var(--navy); color: var(--white); display: flex; flex-direction: column; z-index: 1000; }
        .logo-container { display: flex; justify-content: center; margin-bottom: 56px; }
        .logo { width: 180px; object-fit: contain; }
        .nav-title { color: #c4cce0; font-size: 12px; letter-spacing: 1px; margin: 0 10px 18px; text-transform: uppercase; }
        .nav-link { display: flex; align-items: center; gap: 14px; min-height: 58px; padding: 12px 14px; border-radius: 12px; margin-bottom: 8px; color: var(--white); font-size: 15px; text-decoration: none; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.1); }
        .nav-link.active { background: var(--pink); color: var(--navy); font-weight: bold; }
        .nav-icon { width: 24px; height: 24px; object-fit: contain; flex-shrink: 0; }
        .sidebar-bottom { margin-top: auto; }

        .main { margin-left: 240px; min-height: 100vh; padding: 32px; }
        .page-header { margin-bottom: 28px; }
        .page-header h1 { font-size: 27px; margin-bottom: 8px; }
        .page-header p { color: var(--muted); font-size: 14px; }

        .cart-card { background: var(--white); border: 1px solid #edf0f6; border-radius: 15px; overflow: hidden; }
        .cart-header { padding: 24px 26px; border-bottom: 1px solid var(--border); }
        .cart-header h2 { font-size: 20px; margin-bottom: 7px; }
        .cart-header p { color: var(--muted); font-size: 14px; }

        .cart-table { width: 100%; border-collapse: collapse; }
        .cart-table th, .cart-table td { padding: 16px 24px; text-align: left; border-bottom: 1px solid var(--border); font-size: 14px; }
        .cart-table th { background: #fafbfe; color: var(--muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        /* Styling Tombol Kuantitas & Hapus */
        .qty-box { display: flex; align-items: center; gap: 10px; }
        .qty-btn { width: 28px; height: 28px; background: #e2e8f0; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; color: var(--navy); display: flex; align-items: center; justify-content: center; }
        .qty-btn:hover { background: #cbd5e1; }
        
        .btn-delete { background: #fee2e2; color: #991b1b; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: bold; cursor: pointer; }
        .btn-delete:hover { background: #fecaca; }

        .cart-footer-action { padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; background: #fafbfe; }
        .total-price { font-size: 18px; font-weight: bold; color: var(--navy); }
        
        .btn-checkout { padding: 12px 24px; background: #237844; color: white; border: none; border-radius: 9px; font-size: 14px; font-weight: bold; cursor: pointer; text-decoration: none; }
        .btn-checkout:hover { background: #1e663a; }

        .empty-cart { min-height: 270px; padding: 40px 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; }
        .cart-icon { width: 76px; height: 76px; margin-bottom: 18px; opacity: 0.85; object-fit: contain; }
        .empty-cart h3 { font-size: 19px; margin-bottom: 10px; }
        .empty-cart p { color: var(--muted); font-size: 14px; line-height: 1.7; margin-bottom: 22px; }
        
        .btn-catalog { display: inline-block; padding: 12px 22px; background: var(--pink); color: var(--navy); border-radius: 9px; font-size: 14px; font-weight: bold; text-decoration: none; }
        .footer { margin-top: 44px; padding: 22px 10px; border-top: 1px solid var(--border); text-align: center; color: var(--muted); font-size: 13px; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ route('pembeli.beranda') }}"><img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo"></a>
        </div>
        <p class="nav-title">Menu Utama</p>
        
        <a href="{{ route('pembeli.beranda') }}" class="nav-link {{ request()->routeIs('pembeli.beranda') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt=""><span>Beranda</span>
        </a>
        <a href="{{ route('pembeli.katalog') }}" class="nav-link {{ request()->routeIs('pembeli.katalog') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt=""><span>Katalog Produk</span>
        </a>
        <a href="{{ route('pembeli.pesanan') }}" class="nav-link {{ request()->routeIs('pembeli.pesanan') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt=""><span>Pesanan</span>
        </a>
        <a href="{{ route('pembeli.keranjang') }}" class="nav-link {{ request()->routeIs('pembeli.keranjang') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-keranjang.png') }}" class="nav-icon" alt=""><span>Keranjang</span>
        </a>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left;">
                    <img src="{{ asset('images/icon-login.png') }}" class="nav-icon" alt=""><span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="main">
        <header class="page-header">
            <h1>Keranjang Belanja</h1>
            <p>Periksa produk pilihanmu sebelum melanjutkan pembelian.</p>
        </header>

        <section class="cart-card">
            <div class="cart-header">
                <h2>Daftar Keranjang</h2>
                <p>Produk yang kamu pilih akan ditampilkan di sini.</p>
            </div>

            @php $totalSemua = 0; @endphp

            @if(isset($keranjangs) && $keranjangs->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($keranjangs as $item)
                                @php 
                                    $harga = $item->produk->harga_diskon ?? $item->produk->harga_asli ?? 0;
                                    $subtotal = $harga * $item->jumlah;
                                    $totalSemua += $subtotal;
                                @endphp
                                <tr>
                                    <td style="font-weight: bold; color: var(--navy);">{{ $item->produk->nama_produk ?? 'Produk' }}</td>
                                    <td>Rp {{ number_format($harga, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="qty-box">
                                            <!-- Tombol Kurang (-) -->
                                            <form action="{{ route('keranjang.kurangQty', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="qty-btn">-</button>
                                            </form>
                                            <span>{{ $item->jumlah }}</span>
                                            <!-- Tombol Tambah (+) -->
                                            <form action="{{ route('keranjang.tambahQty', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="qty-btn">+</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td style="font-weight: bold;">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    <td>
                                        <!-- Tombol Hapus Item -->
                                        <form action="{{ route('keranjang.hapus', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-delete">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="cart-footer-action">
                    <div class="total-price">
                        Total Pembayaran: Rp {{ number_format($totalSemua, 0, ',', '.') }}
                    </div>
                    <!-- Tombol Checkout untuk mengubah keranjang menjadi Pesanan -->
                    <form action="{{ route('pembeli.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-checkout">Checkout Pesanan Sekarang</button>
                    </form>
                </div>
            @else
                <div class="empty-cart">
                    <img src="{{ asset('images/icon-keranjang.png') }}" class="cart-icon" alt="Keranjang Kosong">
                    <h3>Keranjang Masih Kosong</h3>
                    <p>Kamu belum menambahkan produk ke keranjang.<br>Yuk, pilih roti yang tersedia di katalog.</p>
                    <a href="{{ route('pembeli.katalog') }}" class="btn-catalog">Lihat Katalog Produk</a>
                </div>
            @endif
        </section>

        <footer class="footer">© {{ date('Y') }} BreadSaver. Selamatkan Roti, Kurangi Food Waste.</footer>
    </main>
</body>
</html>