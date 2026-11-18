<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - BreadSaver</title>
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

        /* 
        =========================================================
        SIDEBAR COLLAPSIBLE HOVER (AUTO EXPAND)
        =========================================================
        */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 70px; /* Lebar default saat kursor menjauh */
            height: 100vh;
            padding: 24px 12px;
            background: var(--navy);
            color: var(--white);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            white-space: nowrap;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Saat kursor diarahkan ke sidebar -> Terbuka Penuh */
        .sidebar:hover {
            width: 240px;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
            height: 45px;
        }

        .logo {
            width: 140px;
            max-width: 100%;
            height: auto;
            object-fit: contain;
            transition: opacity 0.2s;
        }

        .logo-small {
            font-size: 24px;
            display: none;
        }

        /* Tampilkan logo kecil saat menguncup, logo penuh saat di-hover */
        .sidebar:not(:hover) .logo {
            display: none;
        }
        .sidebar:not(:hover) .logo-small {
            display: block;
        }

        .nav-title {
            color: #c4cce0;
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
            width: 24px;
            height: 24px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .nav-link span {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .nav-link span {
            opacity: 1;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 20px;
        }

        /* MAIN CONTENT & FORM LAYOUT */
        .main {
            margin-left: 70px;
            min-height: 100vh;
            padding: 40px 32px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Wrapper untuk form agar tetap di tengah */
        .form-container {
            max-width: 850px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 27px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .header p {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.5;
        }

        .form-card {
            background: var(--white);
            padding: 32px;
            border-radius: 15px;
            border: 1px solid var(--border);
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            color: var(--navy);
            margin-bottom: 9px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="datetime-local"],
        .form-group input[type="file"],
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
            background: var(--white);
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--pink);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 110px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .current-photo {
            width: 160px;
            height: 130px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid var(--border);
            margin-bottom: 12px;
        }

        .note {
            color: var(--muted);
            font-size: 12px;
            margin-top: 7px;
            line-height: 1.4;
        }

        .error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 7px;
        }

        .error-list {
            background: #fff0f0;
            color: #b42318;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 13px;
            border: 1px solid #fecdca;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 28px;
        }

        .btn {
            padding: 12px 22px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.2s;
        }

        .btn-back {
            background: #edf0f6;
            color: var(--text);
        }

        .btn-save {
            background: var(--pink);
            color: var(--navy);
        }

        .btn:hover {
            opacity: 0.85;
        }

        /* RESPONSIVE LAYOUT */
        @media(max-width: 700px) {
            .sidebar {
                width: 75px;
                padding: 20px 10px;
                align-items: center;
            }

            .logo { width: 50px; }
            .logo-container { margin-bottom: 30px; }
            .nav-title { display: none; }
            .nav-link { justify-content: center; padding: 10px 5px; }
            .nav-link span { display: none; }
            .sidebar-bottom { width: 100%; }

            .main {
                margin-left: 75px;
                padding: 20px 16px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .form-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR WITH HOVER EXPAND -->
    <aside class="sidebar">
        <div class="logo-container">
            <a href="{{ route('admin.beranda') }}">
                <span class="logo-small">🍞</span>
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo BreadSaver">
            </a>
        </div>
        <p class="nav-title">Admin Panel</p>

        <a href="{{ route('admin.beranda') }}" class="nav-link {{ request()->routeIs('admin.beranda') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-home.png') }}" class="nav-icon" alt="">
            <span>Beranda</span>
        </a>

        <a href="{{ route('admin.katalog') }}" class="nav-link {{ request()->routeIs('admin.katalog') || request()->routeIs('admin.produk.edit') ? 'active' : '' }}">
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
        <div class="form-container">
            
            <!-- HEADER -->
            <header class="header">
                <h1>Edit Produk</h1>
                <p>Ubah informasi produk yang sudah tersimpan di BreadSaver.</p>
            </header>

            <!-- FORM CARD -->
            <section class="form-card">

                <!-- PESAN ERROR -->
                @if($errors->any())
                    <div class="error-list">
                        <strong>Terjadi kesalahan:</strong>
                        <ul style="padding-left:20px;margin-top:8px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM EDIT PRODUK -->
                <form action="{{ route('admin.produk.update', $produk->id_produk ?? $produk->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- NAMA PRODUK -->
                    <div class="form-group">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text"
                               id="nama_produk"
                               name="nama_produk"
                               value="{{ old('nama_produk', $produk->nama_produk) }}"
                               placeholder="Masukkan nama produk"
                               required>
                        @error('nama_produk')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- HARGA -->
                    <div class="form-row">
                        <!-- HARGA ASLI -->
                        <div class="form-group">
                            <label for="harga_asli">Harga Asli (Rp)</label>
                            <input type="number"
                                   id="harga_asli"
                                   name="harga_asli"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('harga_asli', $produk->harga_asli) }}"
                                   required>
                            @error('harga_asli')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- HARGA DISKON -->
                        <div class="form-group">
                            <label for="harga_diskon">Harga Diskon (Rp)</label>
                            <input type="number"
                                   id="harga_diskon"
                                   name="harga_diskon"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('harga_diskon', $produk->harga_diskon) }}"
                                   required>
                            @error('harga_diskon')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- STOK DAN EXPIRED -->
                    <div class="form-row">
                        <!-- STOK -->
                        <div class="form-group">
                            <label for="stok">Jumlah Stok</label>
                            <input type="number"
                                   id="stok"
                                   name="stok"
                                   min="0"
                                   value="{{ old('stok', $produk->stok) }}"
                                   required>
                            @error('stok')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- EXPIRED -->
                        <div class="form-group">
                            <label for="expired_at">Tanggal Kedaluwarsa</label>
                            <input type="datetime-local"
                                   id="expired_at"
                                   name="expired_at"
                                   value="{{ old('expired_at', $produk->expired_at ? \Carbon\Carbon::parse($produk->expired_at)->format('Y-m-d\TH:i') : '') }}"
                                   required>
                            @error('expired_at')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- DESKRIPSI -->
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Produk</label>
                        <textarea id="deskripsi"
                                  name="deskripsi"
                                  placeholder="Masukkan deskripsi produk...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- FOTO LAMA -->
                    <div class="form-group">
                        <label>Foto Produk Saat Ini</label>
                        @if($produk->foto)
                            <div>
                                @php
                                    if (str_starts_with($produk->foto, 'http')) {
                                        $imageUrl = $produk->foto;
                                    } elseif (str_starts_with($produk->foto, 'storage/') || str_starts_with($produk->foto, 'images/')) {
                                        $imageUrl = asset($produk->foto);
                                    } else {
                                        $imageUrl = asset('storage/' . $produk->foto);
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}"
                                     class="current-photo"
                                     alt="{{ $produk->nama_produk }}">
                            </div>
                        @else
                            <p class="note">Produk belum memiliki foto.</p>
                        @endif
                    </div>

                    <!-- FOTO BARU -->
                    <div class="form-group">
                        <label for="foto">Ganti Foto Produk</label>
                        <input type="file"
                               id="foto"
                               name="foto"
                               accept=".jpg,.jpeg,.png,.webp">
                        <p class="note">
                            Kosongkan jika tidak ingin mengganti foto lama. Format JPG, JPEG, PNG, atau WEBP. Ukuran maksimal 2 MB.
                        </p>
                        @error('foto')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- TOMBOL -->
                    <div class="form-actions">
                        <a href="{{ route('admin.katalog') }}" class="btn btn-back">Batal</a>
                        <button type="submit" class="btn btn-save">Simpan Perubahan</button>
                    </div>

                </form>
            </section>
        </div>
    </main>

</body>
</html>