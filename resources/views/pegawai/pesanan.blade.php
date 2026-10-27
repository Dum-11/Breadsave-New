<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk - Pegawai BreadSaver</title>
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
        .page-header h1 { font-size: 27px; margin-bottom: 6px; }
        .page-header p { color: var(--muted); font-size: 14px; }
        
        .table-card { background: var(--white); border: 1px solid #edf0f6; border-radius: 15px; overflow: hidden; }
        .table-header { padding: 22px 24px; border-bottom: 1px solid var(--border); }
        .table-header h2 { font-size: 19px; margin-bottom: 6px; }
        .table-header p { color: var(--muted); font-size: 13px; }
        
        .table-wrapper { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        th, td { padding: 16px 20px; text-align: left; border-bottom: 1px solid #edf0f6; font-size: 13px; }
        th { background: #fafbfe; color: var(--muted); font-size: 11px; text-transform: uppercase; }
        
        .status { display: inline-block; padding: 6px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .status-menunggu { background: #fff4d6; color: #956500; }
        .status-diproses { background: #e6efff; color: #315ba8; }
        .status-selesai { background: #e2f7e9; color: #237844; }

        .btn-action {
            background: var(--pink);
            color: var(--navy);
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-action:hover { opacity: 0.85; }

        .alert-success {
            background: #e2f7e9;
            color: #237844;
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        .main-footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--border); color: var(--muted); font-size: 12px; text-align: center; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ route('pegawai.beranda') }}"><img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo"></a>
        </div>
        <p class="nav-title">Pegawai Panel</p>
        
        <a href="{{ route('pegawai.beranda') }}" class="nav-link {{ request()->routeIs('pegawai.beranda') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt=""><span>Beranda</span>
        </a>
        <a href="{{ route('pegawai.katalog') }}" class="nav-link {{ request()->routeIs('pegawai.katalog') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt=""><span>Katalog (Produk)</span>
        </a>
        <a href="{{ route('pegawai.pesanan') }}" class="nav-link {{ request()->routeIs('pegawai.pesanan') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt=""><span>Pesanan Masuk</span>
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
            <h1>Pesanan Masuk</h1>
            <p>Daftar pesanan dari pelanggan yang harus diproses oleh staf toko.</p>
        </header>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <section class="table-card">
            <div class="table-header">
                <h2>Daftar Pesanan</h2>
                <p>Data pesanan yang masuk ke sistem toko.</p>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Waktu Pesan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanans ?? [] as $pesanan)
                            @php
                                $pesananId = $pesanan->id_pesanan ?? $pesanan->id;
                                $status = $pesanan->status ?? 'Menunggu Konfirmasi';
                                
                                // Penentuan kelas warna status
                                $statusClass = 'status-menunggu';
                                if($status == 'Diproses') $statusClass = 'status-diproses';
                                if($status == 'Selesai') $statusClass = 'status-selesai';
                            @endphp
                            <tr>
                                <td style="font-weight: bold; color: var(--navy);">#{{ $pesananId }}</td>
                                <td>{{ $pesanan->id_user ?? 'Pelanggan' }}</td>
                                <td>{{ $pesanan->tanggal_pesan ? \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d-m-Y H:i') : '-' }}</td>
                                <td style="font-weight: bold;">Rp {{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    <span class="status {{ $statusClass }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Tombol Aksi Pegawai -->
                                    @if($status == 'Menunggu Konfirmasi')
                                        <form action="{{ route('pegawai.pesanan.proses', $pesananId) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-action">Konfirmasi & Siapkan</button>
                                        </form>
                                    @elseif($status == 'Diproses')
                                        <form action="{{ route('pegawai.pesanan.selesai', $pesananId) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-action" style="background: #237844; color: #ffffff;">Tandai Selesai</button>
                                        </form>
                                    @else
                                        <span style="color: #237844; font-weight: bold; font-size: 12px;">Selesai Disiapkan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: var(--muted);">Belum ada pesanan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="main-footer">© {{ date('Y') }} BreadSaver. Staff Panel.</footer>
    </main>
</body>
</html>