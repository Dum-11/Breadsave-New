<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk - Pegawai BreadSaver</title>
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
            --yellow: #956500;
            --blue: #315ba8;
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

        /* SIDEBAR - SERAGAM DENGAN HALAMAN PESANAN */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 76px;
            height: 100vh;
            padding: 20px 12px;
            background: linear-gradient(180deg, #203761 0%, #172b4e 100%);
            color: var(--white);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            white-space: nowrap;
            transition: width 0.3s ease;
            z-index: 1000;
            box-shadow: 4px 0 18px rgba(0, 0, 0, 0.10);
        }

        .sidebar:hover {
            width: 245px;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 34px;
            height: 48px;
            flex-shrink: 0;
        }

        .logo-container a {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo {
            width: 145px;
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .logo-small {

            width: 40px;

            height: 40px;

            object-fit: contain;

            flex-shrink: 0;

            display: none;

        }

        .sidebar:not(:hover) .logo {
            display: none;
        }

        .sidebar:not(:hover) .logo-small {
            display: block;
        }

        .nav-title {
            color: #c3cee4;
            font-size: 11px;
            letter-spacing: 1px;
            margin: 0 10px 14px;
            text-transform: uppercase;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .nav-title {
            opacity: 1;
        }

        .nav-link,
        .profile-link {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 50px;
            padding: 12px 14px;
            border-radius: 14px;
            margin-bottom: 8px;
            color: var(--white);
            font-size: 15px;
            text-decoration: none;
            transition: 0.2s ease;
            width: 100%;
        }

        .nav-link:hover,
        .profile-link:hover {
            background: rgba(255, 255, 255, 0.10);
        }

        .nav-link.active {
            background: var(--pink);
            color: var(--navy);
            font-weight: bold;
        }

        .profile-link.active,
        .profile-link.active:hover {
            background: var(--pink);
            color: var(--navy);
            font-weight: normal;
        }

        .nav-icon,
        .profile-photo {
            width: 24px;
            height: 24px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .profile-photo {
            border-radius: 50%;
            object-fit: cover;
        }

        .nav-link span,
        .profile-link span {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .nav-link span,
        .sidebar:hover .profile-link span {
            opacity: 1;
        }

        .profile-initial {
            width: 24px;
            height: 24px;
            min-width: 24px;
            border-radius: 50%;
            background: var(--pink);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .profile-link.active .profile-initial {
            background: var(--navy);
            color: var(--white);
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 20px;
        }

        .sidebar form button.nav-link {
            font-family: inherit;
        }

        /* MAIN CONTENT STANDAR PANEL */
        .main {
            margin-left: 76px;
            min-height: 100vh;
            padding: 30px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

        .table-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 15px;
            overflow: hidden;
        }

        .table-header {
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
        }

        .table-header h2 {
            font-size: 19px;
            color: var(--navy);
            margin-bottom: 6px;
        }

        .table-header p {
            color: var(--muted);
            font-size: 13px;
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

        /* BADGE STATUS */
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-menunggu {
            background: #fff4d6;
            color: var(--yellow);
        }

        .status-diproses {
            background: #e6efff;
            color: var(--blue);
        }

        .status-selesai {
            background: #e2f7e9;
            color: var(--green);
        }

        .status-dibatalkan {
            background: #ffe6e6;
            color: var(--red);
        }

        /* BUTTON STYLING */
        .btn-action {
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-right: 4px;
        }

        .btn-action:hover {
            opacity: 0.85;
        }

        .btn-konfirmasi {
            background: var(--pink);
            color: var(--navy);
        }

        .btn-selesai {
            background: var(--green);
            color: #ffffff;
        }

        .btn-batal {
            background: #fee2e2;
            color: var(--red);
        }

        .alert-success {
            background: #e2f7e9;
            color: var(--green);
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .alert-error {
            background: #ffe6e6;
            color: var(--red);
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: bold;
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
                width: 76px;
                padding: 20px 10px;
                align-items: center;
            }

            .logo-container {
                margin-bottom: 30px;
            }

            .nav-title {
                display: none;
            }

            .nav-link,
            .profile-link {
                justify-content: center;
                padding: 10px 5px;
            }

            .nav-link span,
            .profile-link span {
                display: none;
            }

            .sidebar-bottom {
                width: 100%;
            }

            .main {
                margin-left: 76px;
                padding: 20px 14px;
            }

            th, td {
                padding: 10px 12px;
                font-size: 11px;
            }
        }

        @media (hover: none) and (max-width: 700px) {
            .sidebar:hover {
                width: 76px;
            }
        }

        /* SCANNER QR PICKUP */
        .pickup-scanner-card {
            margin: 0 0 24px;
            padding: 24px;
            border-radius: 15px;
            border: 1px solid var(--border);
            background: var(--white);
        }
        .pickup-scanner-head h2 {
            font-size: 19px;
            color: var(--navy);
            margin-bottom: 7px;
        }
        .pickup-scanner-head p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }
        .pickup-scanner-grid {
            display: grid;
            grid-template-columns: minmax(240px, 360px) 1fr;
            gap: 24px;
            margin-top: 18px;
            align-items: start;
        }
        .pickup-camera-frame {
            min-height: 240px;
            border-radius: 12px;
            border: 1px dashed #c8d2e4;
            background: #f6f8fd;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        #pickup-qr-reader {
            width: 100%;
            min-height: 240px;
        }
        .pickup-scanner-controls {
            display: flex;
            gap: 9px;
            flex-wrap: wrap;
            margin-top: 12px;
        }
        .pickup-button {
            padding: 11px 15px;
            border-radius: 9px;
            border: 0;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            background: var(--navy);
            color: #ffffff;
        }
        .pickup-button:disabled {
            opacity: .55;
            cursor: not-allowed;
        }
        .pickup-button-pink { background: var(--pink); color: var(--navy); }
        .pickup-button-muted { background: #e8edf6; color: var(--navy); }
        .pickup-button-green { background: var(--green); color: #ffffff; }
        .pickup-field-label {
            display: block;
            color: var(--navy);
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .pickup-input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid var(--border);
            border-radius: 9px;
            font-size: 14px;
            color: var(--navy);
            text-transform: uppercase;
            outline: none;
        }
        .pickup-input:focus { border-color: var(--pink); }
        .pickup-help {
            margin-top: 8px;
            font-size: 12px;
            color: var(--muted);
            line-height: 1.5;
        }
        .pickup-response {
            margin-top: 16px;
            padding: 13px;
            font-size: 13px;
            line-height: 1.6;
            border-radius: 9px;
            background: #edf1f9;
            color: var(--navy);
        }
        .pickup-response.error { background: #ffe6e6; color: #a32727; }
        .pickup-response.success { background: #e2f7e9; color: #176337; }
        .pickup-confirmation {
            padding: 18px;
            border: 1px solid #b6dbc5;
            background: #f2fbf5;
            border-radius: 10px;
            margin-top: 16px;
        }
        .pickup-confirmation h3 { font-size: 16px; color: var(--green); margin-bottom: 10px; }
        .pickup-confirmation p { font-size: 13px; margin-bottom: 7px; line-height: 1.5; }
        .pickup-confirmation .pickup-button { margin-top: 10px; }
        @media (max-width: 850px) {
            .pickup-scanner-grid { grid-template-columns: 1fr; }
            .pickup-scanner-card { padding: 18px; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR WITH HOVER EXPAND -->
    <aside class="sidebar">

        <div class="logo-container">
            <a href="{{ route('pegawai.beranda') }}">
                <img src="{{ asset('images/logo-icon.png') }}"
                     onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';"
                     alt="Logo BreadSaver"
                     class="logo-small">
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="BreadSaver">
            </a>
        </div>

        <p class="nav-title">Pegawai Panel</p>

        <a href="{{ route('pegawai.beranda') }}"
           class="nav-link {{ request()->routeIs('pegawai.beranda') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt="">
            <span>Beranda</span>
        </a>

        <a href="{{ route('pegawai.katalog') }}"
           class="nav-link {{ request()->routeIs('pegawai.katalog') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-product.png') }}" class="nav-icon" alt="">
            <span>Katalog Produk</span>
        </a>

        <a href="{{ route('pegawai.pesanan') }}"
           class="nav-link {{ request()->routeIs('pegawai.pesanan') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-orders.png') }}" class="nav-icon" alt="">
            <span>Pesanan Masuk</span>
        </a>

        <div class="sidebar-bottom">

            <!-- PROFIL -->
            <a href="{{ route('pegawai.profil') }}"
               class="nav-link profile-link {{ request()->routeIs('pegawai.profil') ? 'active' : '' }}">

                @if(Auth::user()->foto_profil)

                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}"
                         class="nav-icon"
                         alt="Foto Profil"
                         style="border-radius: 50%; object-fit: cover;">

                @else

                    <div class="nav-icon profile-initial">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    </div>

                @endif

                <span>Profil</span>

            </a>

            <!-- LOGOUT -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                        class="nav-link"
                        style="width: 100%; border: 0; background: transparent; cursor: pointer; text-align: left;">

                    <img src="{{ asset('images/icon-login.png') }}"
                         class="nav-icon"
                         alt="">

                    <span>Logout</span>

                </button>
            </form>

        </div>

    </aside>

    <!-- MAIN CONTENT -->
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

        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif


        <!-- Verifikasi pengambilan melalui QR; hasil scan tidak langsung menyerahkan pesanan -->
        @if(Auth::check() && in_array(Auth::user()->role, ['pegawai', 'admin'], true))
        <section class="pickup-scanner-card" aria-labelledby="pickup-title">
            <div class="pickup-scanner-head">
                <h2 id="pickup-title">Scanner QR Pengambilan</h2>
                <p>Scan QR dari pembeli atau masukkan kode pickup secara manual. Sistem memeriksa pembayaran berhasil dan roti sudah siap. Pengambilan baru dicatat setelah Anda mengonfirmasi.</p>
            </div>
            <div class="pickup-scanner-grid">
                <div>
                    <div class="pickup-camera-frame">
                        <div id="pickup-qr-reader" aria-label="Tampilan kamera pemindai QR"></div>
                    </div>
                    <div class="pickup-scanner-controls">
                        <button type="button" class="pickup-button pickup-button-pink" id="pickup-start-camera">Mulai Scan Kamera</button>
                        <button type="button" class="pickup-button pickup-button-muted" id="pickup-stop-camera" disabled>Matikan Kamera</button>
                    </div>
                    <p class="pickup-help">Untuk kamera HP lewat jaringan lokal, gunakan HTTPS. Di localhost PC, kamera bisa dipakai jika browser mengizinkan akses.</p>
                </div>
                <div>
                    <label class="pickup-field-label" for="pickup-code-input">Kode Pickup</label>
                    <input type="text" id="pickup-code-input" class="pickup-input"
                           placeholder="Contoh: BS-YYBP5P" maxlength="9" autocomplete="off" spellcheck="false">
                    <p class="pickup-help">Kode akan terisi otomatis setelah QR terbaca. Bisa juga diketik langsung.</p>
                    <div class="pickup-scanner-controls">
                        <button type="button" class="pickup-button" id="pickup-check-button">Periksa Kode</button>
                        <button type="button" class="pickup-button pickup-button-muted" id="pickup-reset-button">Reset</button>
                    </div>
                    <div id="pickup-result" class="pickup-response" role="status" aria-live="polite" hidden></div>
                    <div id="pickup-confirmation" class="pickup-confirmation" hidden>
                        <h3>QR Valid — Pesanan Siap Diambil</h3>
                        <p><strong>No. Pesanan:</strong> <span id="pickup-order-id"></span></p>
                        <p><strong>Pembeli:</strong> <span id="pickup-customer"></span></p>
                        <p><strong>Total:</strong> <span id="pickup-total"></span></p>
                        <p>Pastikan roti sudah diserahkan ke pembeli sebelum menekan tombol di bawah.</p>
                        <button type="button" class="pickup-button pickup-button-green" id="pickup-confirm-button">
                            Konfirmasi Sudah Diambil
                        </button>
                    </div>
                </div>
            </div>
        </section>
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
                            <th>Aksi Pegawai</th>
                        </tr>
                    </thead>

                    <tbody id="pegawai-pesanan-tbody">

                        @forelse ($pesanans ?? [] as $pesanan)

                            @php
                                $pesananId = $pesanan->id_pesanan ?? $pesanan->id;
                                $status = $pesanan->status ?? 'Menunggu Konfirmasi';
                                $sudahDiambil = !empty($pesanan->picked_up_at);
                                // ID pesanan yang sudah dibayar disiapkan oleh route pegawai.pesanan.
                                $pembayaranBerhasil = in_array((string) $pesananId, $idPesananLunas ?? [], true);
                                $statusLabel = $sudahDiambil ? 'Sudah Diambil' : ($status === 'Selesai' ? 'Siap Diambil' : $status);

                                $statusClass = 'status-menunggu';

                                if ($status == 'Diproses') {
                                    $statusClass = 'status-diproses';
                                } elseif ($status == 'Selesai') {
                                    $statusClass = 'status-selesai';
                                } elseif ($status == 'Dibatalkan') {
                                    $statusClass = 'status-dibatalkan';
                                }
                            @endphp

                            <tr data-pesanan-id="{{ $pesananId }}">

                                <td style="font-weight: bold; color: var(--navy);">
                                    #{{ $pesananId }}
                                </td>

                                <td>
                                    {{ $pesanan->user->nama ?? $pesanan->user->name ?? 'User #'.$pesanan->id_user }}
                                </td>

                                <td>
                                    {{ $pesanan->created_at
                                        ? \Carbon\Carbon::parse($pesanan->created_at)->format('d-m-Y H:i')
                                        : ($pesanan->tanggal_pesan
                                            ? \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d-m-Y H:i')
                                            : '-') }}
                                </td>

                                <td style="font-weight: bold;">
                                    Rp {{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="status {{ $statusClass }}" data-pesanan-status>
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <td data-pesanan-actions>

                                    @if($status == 'Menunggu Konfirmasi')

                                        @if($pembayaranBerhasil)
                                        <form action="{{ route('pegawai.pesanan.proses', $pesananId) }}"
                                              method="POST"
                                              style="display: inline;">

                                            @csrf

                                            <button type="submit"
                                                    class="btn-action btn-konfirmasi">
                                                Konfirmasi & Siapkan
                                            </button>

                                        </form>
                                        @else
                                            <span style="display: inline-block; color: #a55d12; background: #fff6dd; border-radius: 8px; padding: 9px 12px; font-size: 12px; font-weight: 700; margin-right: 8px;">
                                                Belum dibayar — belum bisa diproses
                                            </span>
                                        @endif

                                        <form action="{{ route('pegawai.pesanan.batal', $pesananId) }}"
                                              method="POST"
                                              style="display: inline;">

                                            @csrf

                                            <button type="submit"
                                                    class="btn-action btn-batal"
                                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan #{{ $pesananId }}?')">
                                                Batalkan
                                            </button>

                                        </form>

                                    @elseif($status == 'Diproses')

                                        @if($pembayaranBerhasil)
                                        <form action="{{ route('pegawai.pesanan.selesai', $pesananId) }}"
                                              method="POST"
                                              style="display: inline;">

                                            @csrf

                                            <button type="submit"
                                                    class="btn-action btn-selesai">
                                                Tandai Siap Diambil
                                            </button>

                                        </form>
                                        @else
                                            <span style="display: inline-block; color: #a55d12; background: #fff6dd; border-radius: 8px; padding: 9px 12px; font-size: 12px; font-weight: 700; margin-right: 8px;">
                                                Belum dibayar — belum bisa diproses
                                            </span>
                                        @endif

                                        <form action="{{ route('pegawai.pesanan.batal', $pesananId) }}"
                                              method="POST"
                                              style="display: inline;">

                                            @csrf

                                            <button type="submit"
                                                    class="btn-action btn-batal"
                                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan #{{ $pesananId }}?')">
                                                Batalkan
                                            </button>

                                        </form>

                                    @elseif($status == 'Selesai')

                                        <span style="color: var(--green); font-weight: bold; font-size: 12px;">
                                            {{ $sudahDiambil ? 'Sudah Diambil (QR terpakai)' : 'Siap Diambil — tunggu scan QR' }}
                                        </span>

                                    @elseif($status == 'Dibatalkan')

                                        <span style="color: var(--red); font-weight: bold; font-size: 12px;">
                                            Pesanan Dibatalkan
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    style="text-align: center; padding: 40px; color: var(--muted);">
                                    Belum ada pesanan masuk.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

        <footer class="main-footer">
            © {{ date('Y') }} BreadSaver. Staff Panel.
        </footer>

    </main>


    @if(Auth::check() && in_array(Auth::user()->role, ['pegawai', 'admin'], true))
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        (() => {
            'use strict';
            const checkUrl = @json(route('pegawai.pickup.check'));
            const confirmUrl = @json(route('pegawai.pickup.confirm'));
            const csrfToken = @json(csrf_token());
            const ordersUrl = @json(route('pegawai.pesanan'));
            const ordersBody = document.getElementById('pegawai-pesanan-tbody');

            const input = document.getElementById('pickup-code-input');
            const result = document.getElementById('pickup-result');
            const review = document.getElementById('pickup-confirmation');
            const startButton = document.getElementById('pickup-start-camera');
            const stopButton = document.getElementById('pickup-stop-camera');
            const checkButton = document.getElementById('pickup-check-button');
            const confirmButton = document.getElementById('pickup-confirm-button');
            const resetButton = document.getElementById('pickup-reset-button');

            let camera = null;
            let busy = false;
            let approvedCode = null;
            let approvedOrderId = null;
            let syncingOrders = false;

            function showMessage(message, type = '') {
                result.hidden = false;
                result.className = 'pickup-response' + (type ? ' ' + type : '');
                result.textContent = message;
            }
            function clearReview() {
                approvedCode = null;
                approvedOrderId = null;
                review.hidden = true;
                confirmButton.disabled = false;
            }
            function setBusy(value) {
                busy = value;
                checkButton.disabled = value;
                confirmButton.disabled = value;
                resetButton.disabled = value;
            }
            async function send(url, code) {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({kode_pickup: code}),
                });
                let data;
                try {
                    data = await response.json();
                } catch (_) {
                    throw new Error('Server tidak mengembalikan JSON. Cek login dan route Laravel.');
                }
                if (!response.ok || !data.ok) {
                    const issue = data.message || 'Kode tidak dapat diverifikasi.';
                    throw new Error(issue);
                }
                return data;
            }
            async function verify() {
                if (busy) return;
                clearReview();
                const code = input.value.trim().toUpperCase();
                input.value = code;
                if (!/^BS-[A-Z0-9]{6}$/.test(code)) {
                    showMessage('Format kode salah. Contoh: BS-YYBP5P.', 'error');
                    return;
                }
                setBusy(true);
                showMessage('Memeriksa pembayaran dan status pesanan...');
                try {
                    const data = await send(checkUrl, code);
                    approvedCode = code;
                    approvedOrderId = data.pesanan.id_pesanan;
                    document.getElementById('pickup-order-id').textContent = '#' + data.pesanan.id_pesanan;
                    document.getElementById('pickup-customer').textContent = data.pesanan.pembeli;
                    document.getElementById('pickup-total').textContent = data.pesanan.total;
                    review.hidden = false;
                    showMessage('Kode valid. Periksa detail pesanan, kemudian konfirmasi setelah roti diserahkan.', 'success');
                } catch (err) {
                    showMessage(err.message || 'Gagal memverifikasi kode.', 'error');
                } finally {
                    setBusy(false);
                }
            }
            // Langsung perbarui baris pesanan yang baru dikonfirmasi,
            // tanpa perlu reload halaman atau menunggu polling.
            function tandaiSudahDiambil(idPesanan) {
                if (!ordersBody || !idPesanan) return;
                const row = Array.from(ordersBody.querySelectorAll('tr[data-pesanan-id]'))
                    .find(item => item.dataset.pesananId === String(idPesanan));
                if (!row) return;

                const status = row.querySelector('[data-pesanan-status]');
                if (status) {
                    status.className = 'status status-selesai';
                    status.textContent = 'Sudah Diambil';
                }
                const actions = row.querySelector('[data-pesanan-actions]');
                if (actions) {
                    const label = document.createElement('span');
                    label.style.cssText = 'color:var(--green);font-weight:bold;font-size:12px;';
                    label.textContent = 'Sudah Diambil (QR terpakai)';
                    actions.replaceChildren(label);
                }
            }

            // Sinkronisasi daftar pesanan lewat GET halaman yang sama.
            // Hanya isi tabel yang diganti; kamera, input dan pesan scanner tetap utuh.
            async function sinkronkanPesanan() {
                if (syncingOrders || document.hidden || busy || !ordersBody) return;
                syncingOrders = true;
                try {
                    const response = await fetch(ordersUrl, {
                        method: 'GET',
                        credentials: 'same-origin',
                        cache: 'no-store',
                        headers: {
                            'Accept': 'text/html',
                            'Cache-Control': 'no-cache',
                        },
                    });
                    if (!response.ok || response.redirected) {
                        throw new Error('Tidak dapat memuat daftar pesanan terbaru.');
                    }
                    const html = await response.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const freshBody = doc.getElementById('pegawai-pesanan-tbody');
                    if (!freshBody) throw new Error('Tabel pesanan tidak ditemukan dalam respons.');
                    if (ordersBody.innerHTML !== freshBody.innerHTML) {
                        ordersBody.innerHTML = freshBody.innerHTML;
                    }
                } catch (error) {
                    // Gagal sinkron otomatis tidak boleh membatalkan konfirmasi pickup
                    // yang sudah berhasil disimpan di database.
                    console.warn('Sinkronisasi pesanan gagal:', error);
                } finally {
                    syncingOrders = false;
                }
            }

            async function confirmPickup() {
                if (busy || !approvedCode) return;
                if (!window.confirm('Roti sudah benar-benar diserahkan ke pembeli? Pengambilan akan dicatat dan QR tidak bisa dipakai lagi.')) return;
                setBusy(true);
                try {
                    const completedOrderId = approvedOrderId;
                    const data = await send(confirmUrl, approvedCode);
                    tandaiSudahDiambil(completedOrderId);
                    clearReview();
                    input.value = '';
                    showMessage(data.message || 'Pengambilan berhasil. QR tidak dapat digunakan kembali.', 'success');
                    // Konfirmasi sudah tercatat; sinkronkan ulang dari database tanpa reload.
                    // Dipanggil setelah setBusy(false) di finally agar tidak dilewati.
                    window.requestAnimationFrame(() => { void sinkronkanPesanan(); });
                } catch (err) {
                    clearReview();
                    showMessage(err.message || 'Gagal mengonfirmasi pengambilan.', 'error');
                } finally {
                    setBusy(false);
                }
            }
            async function stopCamera() {
                const active = camera;
                camera = null;
                stopButton.disabled = true;
                startButton.disabled = false;
                if (!active) return;
                try { if (active.isScanning) await active.stop(); } catch (_) {}
                try { await active.clear(); } catch (_) {}
            }
            async function startCamera() {
                if (camera) return;
                if (!window.isSecureContext) {
                    showMessage('Kamera memerlukan HTTPS atau localhost. Gunakan input manual jika membuka lewat IP HTTP.', 'error');
                    return;
                }
                if (typeof Html5Qrcode === 'undefined') {
                    showMessage('Library pemindai gagal dimuat. Periksa koneksi internet atau gunakan input manual.', 'error');
                    return;
                }
                clearReview();
                result.hidden = true;
                startButton.disabled = true;
                const qr = new Html5Qrcode('pickup-qr-reader');
                camera = qr;
                const onScanSuccess = async (text) => {
                    if (busy || !camera) return;
                    input.value = String(text).trim().toUpperCase();
                    await stopCamera();
                    await verify();
                };
                const options = {fps: 10, qrbox: {width: 200, height: 200}};
                try {
                    try {
                        await qr.start({facingMode: 'environment'}, options, onScanSuccess);
                    } catch (firstError) {
                        const cameras = await Html5Qrcode.getCameras();
                        if (!cameras.length) throw firstError;
                        await qr.start(cameras[0].id, options, onScanSuccess);
                    }
                    stopButton.disabled = false;
                    showMessage('Kamera aktif. Arahkan ke QR Code pembeli.');
                } catch (err) {
                    await stopCamera();
                    showMessage('Kamera tidak bisa dibuka. Izinkan akses kamera atau ketik kode secara manual. ' + (err.message || ''), 'error');
                }
            }

            startButton.addEventListener('click', startCamera);
            stopButton.addEventListener('click', stopCamera);
            checkButton.addEventListener('click', async () => { await stopCamera(); await verify(); });
            confirmButton.addEventListener('click', confirmPickup);
            resetButton.addEventListener('click', async () => {
                await stopCamera(); clearReview(); input.value = ''; result.hidden = true;
            });
            input.addEventListener('input', () => { clearReview(); result.hidden = true; });
            input.addEventListener('keydown', async (event) => {
                if (event.key === 'Enter') { event.preventDefault(); await stopCamera(); await verify(); }
            });
            // Perubahan dari tab/perangkat lain ikut muncul otomatis (maks. ~5 detik).
            const ordersInterval = window.setInterval(() => {
                if (!document.hidden) void sinkronkanPesanan();
            }, 5000);
            window.addEventListener('focus', () => { void sinkronkanPesanan(); });
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) void sinkronkanPesanan();
            });
            window.addEventListener('pagehide', () => {
                window.clearInterval(ordersInterval);
                if (camera) stopCamera();
            });
        })();
    </script>
    @endif

@include('components.notifikasi-panel')
</body>
</html>