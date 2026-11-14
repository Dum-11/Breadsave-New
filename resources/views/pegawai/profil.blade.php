<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil - BreadSaver</title>

    <style>
        :root {
            --navy: #23365f;
            --pink: #ff8fb1;
            --pink-light: #fff0f5;
            --background: #f8f9fc;
            --text: #26324b;
            --muted: #8991a4;
            --white: #ffffff;
            --border: #e7eaf2;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--background);
            color: var(--text);
            overflow-x: hidden;
        }

        a {
            color: inherit;
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
            width: 70px;
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

        /*
        =========================================================
        PROFIL AKTIF
        =========================================================
        Kotak Profil tetap PINK.
        Hanya lingkaran inisial berubah menjadi NAVY.
        Teks Profil tidak dibuat bold.
        =========================================================
        */

        .profile-link.active {
            background: var(--pink);
            color: var(--navy);
            font-weight: normal;
        }

        .profile-link.active:hover {
            background: var(--pink);
            color: var(--navy);
            font-weight: normal;
        }

        .profile-link.active .profile-initial {
            background: var(--navy);
            color: var(--white);
        }

        /*
        =========================================================
        MENU ICON
        =========================================================
        */

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

        /*
        =========================================================
        PROFILE INITIAL
        =========================================================
        */

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
            line-height: 1;
            flex-shrink: 0;
            transition: background-color 0.2s, color 0.2s;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 20px;
        }

        .main {
            margin-left: 70px;
            min-height: 100vh;
            padding: 32px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /*
        =========================================================
        CONTENT
        =========================================================
        */

        .header {
            margin-bottom: 28px;
        }

        .header h1 {
            font-size: 30px;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .header p {
            color: var(--muted);
            font-size: 14px;
        }

        /*
        =========================================================
        ALERT
        =========================================================
        */

        .alert-success {
            background: #eaf8ee;
            border: 1px solid #b9e5c5;
            color: #26723d;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 22px;
            font-size: 14px;
        }

        .alert-error {
            background: #fff0f0;
            border: 1px solid #f2c0c0;
            color: #a12f2f;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 22px;
            font-size: 14px;
        }

        /*
        =========================================================
        PROFILE GRID
        =========================================================
        */

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
            max-width: 1100px;
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 26px;
            box-shadow: 0 4px 15px rgba(36, 59, 100, 0.06);
        }

        .card h2 {
            font-size: 19px;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .card-description {
            font-size: 13px;
            color: #8a96ac;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #4f5f7c;
            margin-bottom: 7px;
        }

        .form-group input {
            width: 100%;
            border: 1px solid #dce2ec;
            border-radius: 9px;
            padding: 12px 13px;
            font-size: 14px;
            color: var(--navy);
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            border-color: var(--pink);
        }

        .form-group input::placeholder {
            color: #aeb7c7;
        }

        .form-group input[type="file"] {
            padding: 9px 10px;
            cursor: pointer;
        }

        .error-text {
            color: #c0392b;
            font-size: 12px;
            margin-top: 6px;
        }

        .btn {
            border: none;
            border-radius: 9px;
            padding: 11px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--pink);
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #f36f99;
        }

        /*
        =========================================================
        BUTTON HAPUS FOTO
        Dibuat konsisten dengan tombol Pilih Foto
        =========================================================
        */

        .btn-delete-photo {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 8px;
            background: var(--pink-light);
            color: #ed6590;
            border: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
        }

        .btn-delete-photo:hover {
            background: var(--pink);
            color: #ffffff;
        }

        /*
        =========================================================
        PROFILE INFO
        =========================================================
        */

        .profile-info {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #edf0f5;
        }

        .avatar {
            width: 58px;
            height: 58px;
            min-width: 58px;
            border-radius: 50%;
            background: var(--pink);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: bold;
            overflow: hidden;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .profile-info h3 {
            color: var(--navy);
            font-size: 18px;
            margin-bottom: 4px;
        }

        .profile-info p {
            color: #8a96ac;
            font-size: 13px;
        }

        .photo-upload {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .photo-upload-label {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 8px;
            background: var(--pink-light);
            color: #ed6590;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
        }

        .photo-upload-label:hover {
            background: var(--pink);
            color: #ffffff;
        }

        .photo-upload input[type="file"] {
            display: none;
        }

        .photo-info {
            margin-top: 6px;
            color: #8a96ac;
            font-size: 11px;
        }

        /*
        =========================================================
        RESPONSIVE
        =========================================================
        */

        @media (max-width: 900px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

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

            .header h1 {
                font-size: 25px;
            }

            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .main {
                padding: 20px 14px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR WITH HOVER EXPAND -->
    <aside class="sidebar">

        <div class="logo-container">
            <a href="{{ route('pegawai.beranda') }}">
                <span class="logo-small">🍞</span>

                <img src="{{ asset('images/logo.png') }}"
                     class="logo"
                     alt="BreadSaver">
            </a>
        </div>

        <p class="nav-title">Pegawai Panel</p>

        <a href="{{ route('pegawai.beranda') }}"
           class="nav-link {{ request()->routeIs('pegawai.beranda') ? 'active' : '' }}">

            <img src="{{ asset('images/icon-home.png') }}"
                 class="nav-icon"
                 alt="">

            <span>Beranda</span>

        </a>

        <a href="{{ route('pegawai.katalog') }}"
           class="nav-link {{ request()->routeIs('pegawai.katalog') ? 'active' : '' }}">

            <img src="{{ asset('images/icon-product.png') }}"
                 class="nav-icon"
                 alt="">

            <span>Katalog Produk</span>

        </a>

        <a href="{{ route('pegawai.pesanan') }}"
           class="nav-link {{ request()->routeIs('pegawai.pesanan') ? 'active' : '' }}">

            <img src="{{ asset('images/icon-orders.png') }}"
                 class="nav-icon"
                 alt="">

            <span>Pesanan Masuk</span>

        </a>

        <!-- PROFIL + LOGOUT SAMA-SAMA DI SIDEBAR-BOTTOM -->
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


    <!-- KONTEN UTAMA -->
    <main class="main">

        <div class="header">

            <h1>Profil Akun</h1>

            <p>
                Kelola informasi akun BreadSaver kamu.
            </p>

        </div>


        <!-- SUCCESS MESSAGE -->

        @if(session('success'))

            <div class="alert-success">
                {{ session('success') }}
            </div>

        @endif


        <!-- ERROR MESSAGE -->

        @if($errors->any())

            <div class="alert-error">

                <strong>Terjadi kesalahan:</strong>

                <ul style="margin-top: 7px; padding-left: 20px;">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <!-- PROFILE GRID -->

        <div class="profile-grid">


            <!-- INFORMASI PROFIL -->

            <div class="card">

                <div class="profile-info">

                    <div class="avatar">

                        @if(!empty($user->foto_profil))

                            <img src="{{ asset('storage/' . $user->foto_profil) }}"
                                 alt="Foto Profil">

                        @else

                            {{ strtoupper(substr($user->nama, 0, 1)) }}

                        @endif

                    </div>


                    <div>

                        <h3>{{ $user->nama }}</h3>

                        <p>{{ $user->email }}</p>


                        <div class="photo-upload">

                            <!-- PILIH FOTO -->

                            <label for="foto_profil"
                                   class="photo-upload-label">

                                Pilih Foto

                            </label>


                            <!-- HAPUS FOTO -->

                            @if(!empty($user->foto_profil))

                                <form action="{{ route('pegawai.profil.deletePhoto') }}"
                                      method="POST"
                                      style="display: inline;">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn-delete-photo"
                                            onclick="return confirm('Yakin ingin menghapus foto profil?')">

                                        Hapus Foto

                                    </button>

                                </form>

                            @endif

                        </div>

                    </div>

                </div>


                <h2>Informasi Akun</h2>

                <p class="card-description">
                    Perbarui informasi dasar akun kamu.
                </p>


                <!-- FORM UPDATE PROFIL -->

                <form action="{{ route('pegawai.profil.update') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    @method('PUT')


                    <!-- FOTO PROFIL -->

                    <div class="form-group">

                        <label for="foto_profil">
                            Foto Profil
                        </label>

                        <input
                            type="file"
                            id="foto_profil"
                            name="foto_profil"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >

                        <div class="photo-info">
                            Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                        </div>

                        @error('foto_profil')

                            <div class="error-text">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <!-- NAMA -->

                    <div class="form-group">

                        <label for="nama">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            value="{{ old('nama', $user->nama) }}"
                            placeholder="Masukkan nama lengkap"
                            required
                        >

                        @error('nama')

                            <div class="error-text">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <!-- EMAIL -->

                    <div class="form-group">

                        <label for="email">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            placeholder="Masukkan email"
                            required
                        >

                        @error('email')

                            <div class="error-text">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <!-- NOMOR HP -->

                    <div class="form-group">

                        <label for="no_hp">
                            Nomor HP
                        </label>

                        <input
                            type="text"
                            id="no_hp"
                            name="no_hp"
                            value="{{ old('no_hp', $user->no_hp) }}"
                            placeholder="Masukkan nomor HP"
                        >

                        @error('no_hp')

                            <div class="error-text">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                </form>

            </div>


            <!-- PASSWORD -->

            <div class="card">

                <h2>Ubah Password</h2>

                <p class="card-description">
                    Gunakan password baru yang minimal terdiri dari 8 karakter.
                </p>


                <form action="{{ route('pegawai.profil.password') }}"
                      method="POST">

                    @csrf

                    @method('PUT')


                    <!-- PASSWORD LAMA -->

                    <div class="form-group">

                        <label for="password_lama">
                            Password Lama
                        </label>

                        <input
                            type="password"
                            id="password_lama"
                            name="password_lama"
                            placeholder="Masukkan password lama"
                            required
                        >

                        @error('password_lama')

                            <div class="error-text">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <!-- PASSWORD BARU -->

                    <div class="form-group">

                        <label for="password_baru">
                            Password Baru
                        </label>

                        <input
                            type="password"
                            id="password_baru"
                            name="password_baru"
                            placeholder="Masukkan password baru"
                            required
                        >

                        @error('password_baru')

                            <div class="error-text">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <!-- KONFIRMASI PASSWORD -->

                    <div class="form-group">

                        <label for="password_baru_confirmation">
                            Konfirmasi Password Baru
                        </label>

                        <input
                            type="password"
                            id="password_baru_confirmation"
                            name="password_baru_confirmation"
                            placeholder="Ulangi password baru"
                            required
                        >

                    </div>


                    <button type="submit" class="btn btn-primary">
                        Ubah Password
                    </button>

                </form>

            </div>

        </div>

    </main>

</body>
</html>
