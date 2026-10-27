<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Toko - Pegawai BreadSaver</title>
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
        .nav-link { display: flex; align-items: center; gap: 14px; min-height: 58px; padding: 12px 14px; border-radius: 12px; margin-bottom: 8px; color: var(--white); font-size: 15px; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.1); }
        .nav-link.active { background: var(--pink); color: var(--navy); font-weight: bold; }
        .nav-icon { width: 28px; height: 28px; object-fit: contain; }
        .sidebar-bottom { margin-top: auto; }
        .main { margin-left: 240px; min-height: 100vh; padding: 32px; }
        .page-header { margin-bottom: 28px; }
        .page-header h1 { font-size: 27px; margin-bottom: 6px; }
        .page-header p { color: var(--muted); font-size: 14px; }
        .table-card { background: var(--white); border: 1px solid #edf0f6; border-radius: 15px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        th, td { padding: 16px 20px; text-align: left; border-bottom: 1px solid #edf0f6; font-size: 13px; }
        th { background: #fafbfe; color: var(--muted); font-size: 11px; text-transform: uppercase; }
        .main-footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--border); color: var(--muted); font-size: 12px; text-align: center; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="logo-container"><a href="{{ route('pegawai.beranda') }}"><img src="{{ asset('images/logo.png') }}" class="logo"></a></div>
        <p class="nav-title">Pegawai Panel</p>
        <a href="{{ route('pegawai.beranda') }}" class="nav-link"><img src="{{ asset('images/icon-home.png') }}" class="nav-icon"><span>Beranda</span></a>
        <a href="{{ route('pegawai.katalog') }}" class="nav-link active"><img src="{{ asset('images/icon-product.png') }}" class="nav-icon"><span>Katalog (Produk)</span></a>
        <a href="{{ route('pegawai.pesanan') }}" class="nav-link"><img src="{{ asset('images/icon-orders.png') }}" class="nav-icon"><span>Pesanan Masuk</span></a>
        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left;"><img src="{{ asset('images/icon-login.png') }}" class="nav-icon"><span>Logout</span></button>
            </form>
        </div>
    </aside>

    <main class="main">
        <header class="page-header">
            <h1>Daftar Produk & Stok</h1>
            <p>Perbarui status stok roti harian yang ada di toko.</p>
        </header>

        <section class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Nama Roti</th>
                        <th>Harga Jual</th>
                        <th>Stok Aktif</th>
                        <th>Kedaluwarsa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produks ?? [] as $produk)
                        <tr>
                            <td style="font-weight: bold; color: var(--navy);">{{ $produk->nama_produk }}</td>
                            <td>Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}</td>
                            <td>{{ $produk->stok }} Pcs</td>
                            <td>{{ $produk->expired_at ? \Carbon\Carbon::parse($produk->expired_at)->format('d-m-Y') : '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; padding: 40px; color: var(--muted);">Belum ada data produk toko.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <footer class="main-footer">© {{ date('Y') }} BreadSaver. Staff Panel.</footer>
    </main>
</body>
</html>