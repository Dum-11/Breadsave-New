<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - BreadSaver</title>
    <style>
        :root {
            --navy: #23365f; --pink: #ff8fb1; --pink-light: #fff0f5;
            --background: #f8f9fc; --text: #26324b; --muted: #8991a4; --white: #ffffff; --border: #e7eaf2;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: var(--background); color: var(--text); }
        
        /* Sidebar */
        .sidebar { position: fixed; top: 0; left: 0; width: 240px; height: 100vh; padding: 28px 22px; background: var(--navy); color: var(--white); display: flex; flex-direction: column; z-index: 1000; }
        .logo-container { display: flex; justify-content: center; margin-bottom: 56px; }
        .logo { width: 180px; object-fit: contain; }
        .menu-title { color: #c4cce0; font-size: 12px; letter-spacing: 1px; margin: 0 10px 18px; }
        .menu-link { display: flex; align-items: center; gap: 14px; min-height: 58px; padding: 12px 14px; border-radius: 12px; margin-bottom: 8px; color: var(--white); font-size: 15px; text-decoration: none; }
        .menu-link:hover { background: rgba(255, 255, 255, 0.1); }
        .menu-link.active { background: var(--pink); color: var(--navy); font-weight: bold; }
        .menu-icon { width: 28px; height: 28px; object-fit: contain; }
        .sidebar-bottom { margin-top: auto; }

        /* Konten Utama */
        .main { margin-left: 240px; min-height: 100vh; padding: 32px; }
        .page-header { margin-bottom: 28px; }
        .page-header h1 { font-size: 27px; margin-bottom: 6px; }
        .page-header p { color: var(--muted); font-size: 14px; }
        
        /* Kotak Peringatan Login */
        .auth-card { background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 50px 30px; text-align: center; max-width: 600px; margin: 40px auto; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .auth-card h3 { color: var(--navy); font-size: 22px; margin-bottom: 12px; }
        .auth-card p { color: var(--muted); font-size: 14px; margin-bottom: 24px; line-height: 1.5; }
        .btn-login { display: inline-block; background: var(--pink); color: var(--navy); padding: 12px 28px; border-radius: 10px; font-weight: bold; font-size: 14px; text-decoration: none; }
        .btn-login:hover { background: #ff7da5; }

        /* Tabel Data Pesanan (Jika sudah login) */
        .table-card { background: var(--white); border: 1px solid var(--border); border-radius: 15px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        th, td { padding: 16px 20px; text-align: left; border-bottom: 1px solid #edf0f6; font-size: 13px; }
        th { background: #fafbfe; color: var(--muted); font-size: 11px; text-transform: uppercase; }
        
        .footer { margin-top: 44px; padding: 24px 0; border-top: 1px solid var(--border); color: var(--muted); font-size: 13px; text-align: center; }
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
            <a href="{{ route('pesanan') }}" class="menu-link active"><img src="{{ asset('images/icon-orders.png') }}" class="menu-icon"><span>Pesanan</span></a>
            <a href="{{ route('keranjang') }}" class="menu-link"><img src="{{ asset('images/icon-keranjang.png') }}" class="menu-icon"><span>Keranjang</span></a>
        </nav>
        <div class="sidebar-bottom">
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="menu-link" style="width: 100%; border: none; background: transparent; cursor: pointer;">
                        <img src="{{ asset('images/icon-login.png') }}" class="menu-icon"><span>Logout</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="menu-link"><img src="{{ asset('images/icon-login.png') }}" class="menu-icon"><span>Login</span></a>
            @endauth
        </div>
    </aside>

    <!-- Konten Utama -->
    <main class="main">
        <header class="page-header">
            <h1>Pesanan</h1>
            <p>Pantau jumlah dan status pesanan di BreadSaver.</p>
        </header>

        @auth
            <!-- TAMPILAN JIKA SUDAH LOGIN -->
            <section class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>Nomor Pesanan</th>
                            <th>Tanggal Pesan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanans ?? [] as $pesanan)
                            <tr>
                                <td style="font-weight: bold; color: var(--navy);">#{{ $pesanan->id ?? 1 }}</td>
                                <td>{{ $pesanan->created_at ?? '-' }}</td>
                                <td>Rp {{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                                <td><span style="padding: 6px 12px; background: #e6efff; color: #315ba8; border-radius: 20px; font-weight: bold; font-size: 11px;">{{ $pesanan->status ?? 'Diproses' }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px; color: var(--muted);">Belum ada riwayat pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        @else
            <!-- TAMPILAN JIKA BELUM LOGIN (MENGGUNAKAN KARTU PERINGATAN LOGIN) -->
            <div class="auth-card">
                <h3>Anda Belum Masuk ke Akun</h3>
                <p>Silakan masuk terlebih dahulu untuk melihat dan memantau status pesanan Anda.</p>
                <a href="{{ route('login') }}" class="btn-login">Login Sekarang</a>
            </div>
        @endauth

        <footer class="footer">© {{ date('Y') }} BreadSaver. Selamatkan Roti, Kurangi Food Waste.</footer>
    </main>

</body>
</html>