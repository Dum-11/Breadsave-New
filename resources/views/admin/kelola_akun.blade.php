<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akun - Admin BreadSaver</title>
    <style>
        :root {
            --navy: #23365f;
            --pink: #ff8fb1;
            --pink-light: #fff0f5;
            --background: #f8f9fc;
            --text: #26324b;
            --muted: #8991a4;
            --white: #ffffff;
            --border: #e7eaf1;
            --green: #237844;
            --red: #b53a3a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--background);
            color: var(--text);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        /* SIDEBAR STANDAR PANEL */
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
            overflow-y: auto;
            z-index: 1000;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 56px;
        }

        .logo {
            width: 180px;
            max-width: 100%;
            height: auto;
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
            border: none;
            transition: background-color 0.2s, color 0.2s;
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
            flex-shrink: 0;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 28px;
        }

        /* MAIN CONTENT STANDAR PANEL */
        .main {
            margin-left: 240px;
            min-height: 100vh;
            padding: 32px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 27px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .page-header p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.5;
        }

        /* NOTIFIKASI ALERT */
        .alert { padding: 14px 20px; border-radius: 10px; margin-bottom: 24px; font-size: 14px; font-weight: bold; }
        .alert-success { background: #e2f7e9; color: var(--green); border: 1px solid #b7ebc6; }
        .alert-error { background: #ffe6e6; color: var(--red); border: 1px solid #fecaca; }

        /* TABEL AKUN */
        .table-card {
            width: 100%;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 15px;
            overflow: hidden;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        th, td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            vertical-align: middle;
        }

        th {
            background: #fafbfe;
            color: var(--muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* STATUS & BUTTON STYLING */
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .status-active { background: #e2f7e9; color: var(--green); }
        .status-blocked { background: #ffe6e6; color: var(--red); }

        .role-select {
            padding: 6px 10px; border: 1px solid var(--border); border-radius: 8px;
            font-size: 13px; color: var(--text); background: #fafbfe; cursor: pointer;
        }
        .btn-action {
            padding: 6px 12px; border: none; border-radius: 8px; font-size: 12px;
            font-weight: bold; cursor: pointer; transition: opacity 0.2s;
        }
        .btn-action:hover { opacity: 0.85; }
        .btn-save { background: var(--navy); color: white; margin-left: 4px; }
        .btn-block { background: #fee2e2; color: #991b1b; }
        .btn-unblock { background: #e2f7e9; color: var(--green); }

        .empty-data {
            text-align: center;
            padding: 40px;
            color: var(--muted);
        }

        /* FOOTER */
        .main-footer {
            margin-top: 45px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
            text-align: center;
        }

        /* RESPONSIVE */
        @media (max-width: 700px) {
            .sidebar {
                width: 75px;
                padding: 20px 10px;
                align-items: center;
            }

            .logo {
                width: 50px;
            }

            .logo-container {
                margin-bottom: 30px;
            }

            .nav-title {
                display: none;
            }

            .nav-link {
                justify-content: center;
                padding: 10px 5px;
            }

            .nav-link span {
                display: none;
            }

            .sidebar-bottom {
                width: 100%;
            }

            .main {
                margin-left: 75px;
                padding: 20px 14px;
            }

            th, td {
                padding: 12px 14px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ route('admin.beranda') }}">
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="BreadSaver">
            </a>
        </div>
        <p class="nav-title">Admin Panel</p>
        
        <a href="{{ route('admin.beranda') }}" class="nav-link {{ request()->routeIs('admin.beranda') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt="">
            <span>Beranda</span>
        </a>
        <a href="{{ route('admin.katalog') }}" class="nav-link {{ request()->routeIs('admin.katalog') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt="">
            <span>Katalog</span>
        </a>
        <a href="{{ route('admin.kelola_akun') }}" class="nav-link {{ request()->routeIs('admin.kelola_akun') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt="">
            <span>Kelola Akun</span>
        </a>
        <a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-keranjang.png') }}" class="nav-icon" alt="">
            <span>Laporan</span>
        </a>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left;">
                    <img src="{{ asset('images/icon-login.png') }}" class="nav-icon" alt="">
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">
        <header class="page-header">
            <h1>Kelola Akun Pengguna</h1>
            <p>Daftar akun yang terdaftar dalam sistem aplikasi.</p>
        </header>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <section class="table-card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Pengguna</th>
                            <th>Email</th>
                            <th>Peran (Role)</th>
                            <th>Status Akun</th>
                            <th>Aksi Bantuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users ?? [] as $user)
                            @php
                                $userId = $user->id_user ?? $user->id;
                                $isActive = $user->is_active ?? true;
                            @endphp
                            <tr>
                                <td style="font-weight: bold; color: var(--navy);">{{ $user->nama ?? $user->name ?? 'Pengguna' }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <!-- Form Ganti Role -->
                                    <form action="{{ route('admin.kelola_akun.role', $userId) }}" method="POST" style="display: flex; align-items: center; gap: 6px;">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="role-select" {{ $userId === auth()->id() ? 'disabled' : '' }}>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="pegawai" {{ $user->role === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                            <option value="pembeli" {{ $user->role === 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                                        </select>
                                        @if($userId !== auth()->id())
                                            <button type="submit" class="btn-action btn-save">Simpan</button>
                                        @endif
                                    </form>
                                </td>
                                <td>
                                    @if($isActive)
                                        <span class="status-badge status-active">Aktif</span>
                                    @else
                                        <span class="status-badge status-blocked">Diblokir</span>
                                    @endif
                                </td>
                                <td>
                                    @if($userId !== auth()->id())
                                        <!-- Form Toggle Status Aktif / Blokir -->
                                        <form action="{{ route('admin.kelola_akun.status', $userId) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            @if($isActive)
                                                <button type="submit" class="btn-action btn-block" onclick="return confirm('Apakah Anda yakin ingin memblokir akun {{ $user->email }}?')">Blokir Akun</button>
                                            @else
                                                <button type="submit" class="btn-action btn-unblock" onclick="return confirm('Aktifkan kembali akun {{ $user->email }}?')">Aktifkan Akun</button>
                                            @endif
                                        </form>
                                    @else
                                        <span style="color: var(--muted); font-size: 12px;">Akun Anda</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-data">
                                    Belum ada data pengguna.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <footer class="main-footer">
            © {{ date('Y') }} BreadSaver. Administrator Panel.
        </footer>
    </main>

</body>
</html>