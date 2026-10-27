<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - BreadSaver</title>
    <style>
        :root {
            --navy: #23365f; --pink: #ff8fb1; --pink-light: #fff0f5;
            --background: #f8f9fc; --text: #26324b; --muted: #8991a4; --white: #ffffff; --border: #e7eaf1;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: var(--background); color: var(--text); }
        
        /* Sidebar Styling */
        .sidebar { position: fixed; top: 0; left: 0; width: 240px; height: 100vh; padding: 28px 22px; background: var(--navy); color: var(--white); display: flex; flex-direction: column; z-index: 1000; }
        .logo-container { display: flex; justify-content: center; margin-bottom: 56px; }
        .logo { width: 180px; object-fit: contain; }
        .nav-title { color: #c4cce0; font-size: 12px; letter-spacing: 1px; margin: 0 10px 18px; text-transform: uppercase; }

        /* PENATAAN UTAMA AGAR IKON & TEKS SEJAJAR DAN TIDAK KEMANA-MANA */
        .nav-link { 
            display: flex; 
            align-items: center; 
            gap: 14px; 
            min-height: 50px; 
            padding: 10px 14px; 
            border-radius: 12px; 
            margin-bottom: 8px; 
            color: var(--white); 
            font-size: 15px; 
            text-decoration: none; 
        }
        .nav-link:hover { background: rgba(255, 255, 255, 0.1); }
        .nav-link.active { background: var(--pink); color: var(--navy); font-weight: bold; }

        /* UKURAN TETAP IKON (Mencegah gambar membesar sendiri) */
        .nav-icon { 
            width: 22px; 
            height: 22px; 
            object-fit: contain; 
            flex-shrink: 0; 
        }

        .sidebar-bottom { margin-top: auto; }

        .main { margin-left: 240px; min-height: 100vh; padding: 32px; }
        .page-header { margin-bottom: 28px; }
        .page-header h1 { font-size: 27px; margin-bottom: 8px; }
        .page-header p { color: var(--muted); font-size: 14px; }

        .summary-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 16px; margin-bottom: 28px; }
        .summary-card { background: var(--white); border: 1px solid #edf0f6; border-radius: 13px; padding: 20px; }
        .summary-label { color: var(--muted); font-size: 12px; margin-bottom: 12px; }
        .summary-number { color: var(--navy); font-size: 26px; font-weight: bold; }

        .table-card { background: var(--white); border: 1px solid #edf0f6; border-radius: 15px; overflow: hidden; }
        .table-header { padding: 22px 24px; border-bottom: 1px solid var(--border); }
        .table-header h2 { font-size: 19px; margin-bottom: 6px; }
        .table-header p { color: var(--muted); font-size: 13px; }
        
        .table-wrapper { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        th, td { padding: 16px 20px; text-align: left; border-bottom: 1px solid #edf0f6; font-size: 13px; }
        th { background: #fafbfe; color: var(--muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .status { display: inline-block; padding: 7px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .status-menunggu { background: #fff4d6; color: #956500; }
        .status-diproses { background: #e6efff; color: #315ba8; }
        .status-selesai { background: #e2f7e9; color: #237844; }
        .status-dibatalkan { background: #ffe6e6; color: #b53a3a; }

        .main-footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--border); color: var(--muted); font-size: 12px; text-align: center; }
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
        <!-- Diperbaiki menggunakan class nav-link dan route pembeli.keranjang agar rapi -->
        <a href="{{ route('pembeli.keranjang') }}" class="nav-link {{ request()->routeIs('pembeli.keranjang') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-keranjang.png') }}" class="nav-icon" alt=""><span>Keranjang</span>
        </a>

        <div class="sidebar-bottom">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link" style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left;">
                        <img src="{{ asset('images/icon-login.png') }}" class="nav-icon" alt=""><span>Logout</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link"><img src="{{ asset('images/icon-login.png') }}" class="nav-icon" alt=""><span>Login</span></a>
            @endauth
        </div>
    </aside>

    <main class="main">
        <header class="page-header">
            <h1>Pesanan</h1>
            <p>Pantau jumlah dan status pesanan di BreadSaver.</p>
        </header>

        <section class="summary-grid">
            <article class="summary-card"><p class="summary-label">Total Pesanan</p><p class="summary-number">{{ $totalPesanan ?? 0 }}</p></article>
            <article class="summary-card"><p class="summary-label">Menunggu Konfirmasi</p><p class="summary-number">{{ $menungguKonfirmasi ?? 0 }}</p></article>
            <article class="summary-card"><p class="summary-label">Sedang Diproses</p><p class="summary-number">{{ $diproses ?? 0 }}</p></article>
            <article class="summary-card"><p class="summary-label">Pesanan Selesai</p><p class="summary-number">{{ $selesai ?? 0 }}</p></article>
            <article class="summary-card"><p class="summary-label">Pesanan Dibatalkan</p><p class="summary-number">{{ $dibatalkan ?? 0 }}</p></article>
        </section>

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
                        @forelse ($pesanans ?? [] as $pesanan)
                            <tr>
                                <td style="font-weight: bold; color: var(--navy);">#{{ $pesanan->id_pesanan ?? $pesanan->id }}</td>
                                <td>{{ $pesanan->id_user }}</td>
                                <td>{{ $pesanan->tanggal_pesan ? \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d-m-Y H:i') : '-' }}</td>
                                <td style="font-weight: bold;">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'Menunggu Konfirmasi' => 'status-menunggu',
                                            'Diproses' => 'status-diproses',
                                            'Selesai' => 'status-selesai',
                                            'Dibatalkan' => 'status-dibatalkan',
                                        ];
                                        $statusClass = $statusClasses[$pesanan->status] ?? 'status-menunggu';
                                    @endphp
                                    <span class="status {{ $statusClass }}">{{ $pesanan->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 45px 20px; text-align: center;">
                                    <h3>Belum Ada Pesanan</h3>
                                    <p style="color: var(--muted); margin-top: 5px;">Data pesanan belum tersedia.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="main-footer">© {{ date('Y') }} BreadSaver. Selamatkan Roti, Kurangi Food Waste.</footer>
    </main>
</body>
</html>