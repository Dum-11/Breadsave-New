<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - BreadSaver</title>
    <style>
        :root {
            --navy: #23365f; --pink: #ff8fb1; --background: #f8f9fc; --text: #26324b; --muted: #8991a4; --white: #ffffff;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: var(--background); color: var(--text); }
        
        .sidebar { position: fixed; top: 0; left: 0; width: 240px; height: 100vh; padding: 28px 22px; background: var(--navy); color: var(--white); display: flex; flex-direction: column; z-index: 1000; }
        .logo-container { display: flex; justify-content: center; margin-bottom: 56px; }
        .logo { width: 180px; object-fit: contain; }
        .menu-title { color: #c4cce0; font-size: 12px; letter-spacing: 1px; margin: 0 10px 18px; }
        .menu-link { display: flex; align-items: center; gap: 14px; min-height: 58px; padding: 12px 14px; border-radius: 12px; margin-bottom: 8px; color: var(--white); font-size: 15px; text-decoration: none; }
        .menu-link:hover { background: rgba(255, 255, 255, 0.1); }
        .menu-link.active { background: var(--pink); color: var(--navy); font-weight: bold; }
        .menu-icon { width: 28px; height: 28px; object-fit: contain; }
        .sidebar-bottom { margin-top: auto; }

        .main { margin-left: 240px; min-height: 100vh; padding: 32px; }
        .page-header { margin-bottom: 28px; }
        .page-header h1 { font-size: 27px; margin-bottom: 6px; }
        
        /* Kotak Peringatan Login */
        .auth-card { background: var(--white); border: 1px solid #e7eaf2; border-radius: 16px; padding: 50px 30px; text-align: center; max-width: 600px; margin: 40px auto; }
        .auth-card h3 { color: var(--navy); font-size: 22px; margin-bottom: 12px; }
        .auth-card p { color: var(--muted); font-size: 14px; margin-bottom: 24px; }
        .btn-login { display: inline-block; background: var(--pink); color: var(--navy); padding: 12px 28px; border-radius: 10px; font-weight: bold; font-size: 14px; text-decoration: none; }
        .btn-login:hover { background: #ff7da5; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo"></a>
        </div>
        <p class="menu-title">MENU UTAMA</p>
        <nav>
            <a href="{{ url('/') }}" class="menu-link"><img src="{{ asset('images/icon-home.png') }}" class="menu-icon"><span>Beranda</span></a>
            <a href="{{ route('katalog') }}" class="menu-link"><img src="{{ asset('images/icon-product.png') }}" class="menu-icon"><span>Katalog Produk</span></a>
            <a href="{{ route('pesanan') }}" class="menu-link"><img src="{{ asset('images/icon-orders.png') }}" class="menu-icon"><span>Pesanan</span></a>
            <a href="{{ route('keranjang') }}" class="menu-link active"><img src="{{ asset('images/icon-keranjang.png') }}" class="menu-icon"><span>Keranjang</span></a>
        </nav>
        <div class="sidebar-bottom">
            <a href="{{ route('login') }}" class="menu-link"><img src="{{ asset('images/icon-login.png') }}" class="menu-icon"><span>Login</span></a>
        </div>
    </aside>

    <!-- Konten Utama -->
    <main class="main">
        <header class="page-header">
            <h1>Keranjang Belanja</h1>
            <p>Kelola produk roti yang ingin Anda beli.</p>
        </header>

        @auth
            <!-- TAMPILAN JIKA SUDAH LOGIN -->
            <div style="background: var(--white); padding: 30px; border-radius: 16px; border: 1px solid #e7eaf2;">
                <p>Daftar produk di keranjang Anda akan muncul di sini...</p>
            </div>
        @else
            <!-- TAMPILAN JIKA BELUM LOGIN (MENAMPILKAN TOMBOL LOGIN) -->
            <div class="auth-card">
                <h3>Anda Belum Masuk ke Akun</h3>
                <p>Silakan masuk terlebih dahulu untuk melihat dan mengelola isi keranjang belanja Anda.</p>
                <a href="{{ route('login') }}" class="btn-login">Login Sekarang</a>
            </div>
        @endauth
    </main>

</body>
</html>