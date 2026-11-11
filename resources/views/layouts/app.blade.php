<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'BreadSaver' }}</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            min-height: 100vh;
        }

        /*
        =========================================================
        SIDEBAR COLLAPSIBLE HOVER (GLOBAL)
        =========================================================
        */
        .sidebar {
            width: 70px;
            height: 100vh;
            background-color: #1e293b;
            color: #ffffff;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow: hidden;
            white-space: nowrap;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Terbuka Penuh Saat Kursor Mengarah ke Sidebar */
        .sidebar:hover {
            width: 240px;
        }

        .sidebar-header {
            padding: 20px 18px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid #334155;
        }

        .sidebar-header .logo-icon {
            font-size: 22px;
            min-width: 34px;
            text-align: center;
        }

        .sidebar-header .logo-text {
            font-size: 18px;
            font-weight: 700;
            color: #f43f5e;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .sidebar-header .logo-text {
            opacity: 1;
        }

        .sidebar-menu-title {
            padding: 14px 20px 6px;
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 1px;
            font-weight: 700;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .sidebar-menu-title {
            opacity: 1;
        }

        .sidebar-menu {
            list-style: none;
            padding: 5px 0;
            flex-grow: 1;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 18px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #f43f5e;
            color: #ffffff;
        }

        .sidebar-menu a .icon {
            font-size: 18px;
            min-width: 34px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .sidebar-menu a .text {
            margin-left: 12px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .sidebar-menu a .text {
            opacity: 1;
        }

        .sidebar-footer {
            padding: 10px 0 20px;
            border-top: 1px solid #334155;
        }

        /* Area Konten Utama menyesuaikan lebar sidebar default */
        .main-content {
            margin-left: 70px;
            padding: 24px;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body>

    <!-- SIDEBAR NAVIGASI GLOBAL -->
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">
                <span class="logo-icon">🍞</span>
                <span class="logo-text">BreadSaver</span>
            </div>

            <!-- DETEKSI MENU SESUAI ROLE USER SAAT INI -->
            @auth
                @if(auth()->user()->role === 'admin')
                    <!-- MENU ADMIN -->
                    <div class="sidebar-menu-title">PANEL ADMIN</div>
                    <ul class="sidebar-menu">
                        <li><a href="{{ route('admin.beranda') }}" class="{{ request()->routeIs('admin.beranda') ? 'active' : '' }}"><span class="icon">📊</span><span class="text">Beranda</span></a></li>
                        <li><a href="{{ route('admin.katalog') }}" class="{{ request()->routeIs('admin.katalog*') ? 'active' : '' }}"><span class="icon">🥖</span><span class="text">Katalog Roti</span></a></li>
                        <li><a href="{{ route('admin.kelola_akun') }}" class="{{ request()->routeIs('admin.kelola_akun') ? 'active' : '' }}"><span class="icon">👥</span><span class="text">Kelola Akun</span></a></li>
                        <li><a href="{{ route('admin.laporan') }}" class="{{ request()->routeIs('admin.laporan') ? 'active' : '' }}"><span class="icon">📑</span><span class="text">Laporan</span></a></li>
                    </ul>
                @elseif(auth()->user()->role === 'pegawai')
                    <!-- MENU PEGAWAI -->
                    <div class="sidebar-menu-title">PANEL PEGAWAI</div>
                    <ul class="sidebar-menu">
                        <li><a href="{{ route('pegawai.beranda') }}" class="{{ request()->routeIs('pegawai.beranda') ? 'active' : '' }}"><span class="icon">📊</span><span class="text">Beranda</span></a></li>
                        <li><a href="{{ route('pegawai.katalog') }}" class="{{ request()->routeIs('pegawai.katalog') ? 'active' : '' }}"><span class="icon">🥖</span><span class="text">Katalog Stok</span></a></li>
                        <li><a href="{{ route('pegawai.pesanan') }}" class="{{ request()->routeIs('pegawai.pesanan') ? 'active' : '' }}"><span class="icon">📦</span><span class="text">Pesanan Masuk</span></a></li>
                    </ul>
                @else
                    <!-- MENU PEMBELI (LOGIN) -->
                    <div class="sidebar-menu-title">MENU PEMBELI</div>
                    <ul class="sidebar-menu">
                        <li><a href="{{ route('pembeli.beranda') }}" class="{{ request()->routeIs('pembeli.beranda') ? 'active' : '' }}"><span class="icon">🏠</span><span class="text">Beranda</span></a></li>
                        <li><a href="{{ route('pembeli.katalog') }}" class="{{ request()->routeIs('pembeli.katalog') ? 'active' : '' }}"><span class="icon">🥖</span><span class="text">Katalog Roti</span></a></li>
                        <li><a href="{{ route('pembeli.pesanan') }}" class="{{ request()->routeIs('pembeli.pesanan') ? 'active' : '' }}"><span class="icon">📋</span><span class="text">Pesanan Saya</span></a></li>
                        <li><a href="{{ route('pembeli.keranjang') }}" class="{{ request()->routeIs('pembeli.keranjang') ? 'active' : '' }}"><span class="icon">🛒</span><span class="text">Keranjang</span></a></li>
                    </ul>
                @endif
            @else
                <!-- MENU GUEST (BELUM LOGIN) -->
                <div class="sidebar-menu-title">MENU UTAMA</div>
                <ul class="sidebar-menu">
                    <li><a href="{{ route('pembeli.beranda') }}" class="{{ request()->routeIs('pembeli.beranda') ? 'active' : '' }}"><span class="icon">🏠</span><span class="text">Beranda</span></a></li>
                    <li><a href="{{ route('pembeli.katalog') }}" class="{{ request()->routeIs('pembeli.katalog') ? 'active' : '' }}"><span class="icon">🥖</span><span class="text">Katalog Roti</span></a></li>
                    <li><a href="{{ route('pembeli.pesanan') }}" class="{{ request()->routeIs('pembeli.pesanan') ? 'active' : '' }}"><span class="icon">📋</span><span class="text">Pesanan</span></a></li>
                    <li><a href="{{ route('pembeli.keranjang') }}" class="{{ request()->routeIs('pembeli.keranjang') ? 'active' : '' }}"><span class="icon">🛒</span><span class="text">Keranjang</span></a></li>
                </ul>
            @endauth
        </div>

        <!-- FOOTER LOGOUT / LOGIN -->
        <div class="sidebar-footer">
            <ul class="sidebar-menu">
                @auth
                    <li>
                        <form action="{{ route('logout') }}" method="POST" id="global-logout-form" style="display: none;">@csrf</form>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('global-logout-form').submit();">
                            <span class="icon">🚪</span>
                            <span class="text">Keluar</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login') }}">
                            <span class="icon">🔑</span>
                            <span class="text">Masuk</span>
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </aside>

    <!-- CONTAINER KONTEN UTAMA HALAMAN -->
    <main class="main-content">
        {{ $slot }}
    </main>

</body>
</html>