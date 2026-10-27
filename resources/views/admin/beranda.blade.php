<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Admin - BreadSaver</title>
    <style>
        :root {
            --navy: #23365f; --pink: #ff8fb1; --pink-light: #fff0f5;
            --background: #f8f9fc; --text: #26324b; --muted: #8991a4; --white: #ffffff; --border: #e7eaf1;
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
        .nav-icon { width: 28px; height: 28px; object-fit: contain; }
        .sidebar-bottom { margin-top: auto; }

        .main { margin-left: 240px; min-height: 100vh; padding: 32px; }
        .page-header { margin-bottom: 28px; }
        .page-header h1 { font-size: 27px; margin-bottom: 8px; }
        .page-header p { color: var(--muted); font-size: 14px; }

        .summary-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; margin-bottom: 28px; }
        .summary-card { background: var(--white); border: 1px solid #edf0f6; border-radius: 15px; padding: 22px; }
        .summary-label { color: var(--muted); font-size: 13px; margin-bottom: 10px; }
        .summary-number { color: var(--navy); font-size: 28px; font-weight: bold; }
        .main-footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--border); color: var(--muted); font-size: 12px; text-align: center; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ route('admin.beranda') }}"><img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo"></a>
        </div>
        <p class="nav-title">Admin Panel</p>
        
        <!-- Menu Beranda aktif jika berada di route admin.beranda -->
        <a href="{{ route('admin.beranda') }}" class="nav-link {{ request()->routeIs('admin.beranda') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt=""><span>Beranda</span>
        </a>
        <a href="{{ route('admin.katalog') }}" class="nav-link {{ request()->routeIs('admin.katalog') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt=""><span>Katalog</span>
        </a>
        <a href="{{ route('admin.kelola_akun') }}" class="nav-link {{ request()->routeIs('admin.kelola_akun') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt=""><span>Kelola Akun</span>
        </a>
        <a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-keranjang.png') }}" class="nav-icon" alt=""><span>Laporan</span>
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
            <h1>Beranda Admin</h1>
            <p>Selamat datang kembali, Admin. Berikut ringkasan sistem secara keseluruhan.</p>
        </header>

        <section class="summary-grid">
            <article class="summary-card">
                <p class="summary-label">Total Produk</p>
                <p class="summary-number">{{ $totalProduk ?? 0 }}</p>
            </article>
            <article class="summary-card">
                <p class="summary-label">Total Pesanan</p>
                <p class="summary-number">{{ $totalPesanan ?? 0 }}</p>
            </article>
            <article class="summary-card">
                <p class="summary-label">Total Pengguna</p>
                <p class="summary-number">{{ $totalPengguna ?? 0 }}</p>
            </article>
        </section>

        <footer class="main-footer">© {{ date('Y') }} BreadSaver. Administrator Panel.</footer>
    </main>
</body>
</html>