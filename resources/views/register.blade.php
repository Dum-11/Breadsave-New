<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Mengatur encoding agar karakter tampil dengan benar. -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Menyesuaikan tampilan dengan ukuran layar perangkat. -->

    <title>Register - BreadSaver</title>

    <style>
        /*
        =========================================================
        BAGIAN 1: RESET CSS
        =========================================================

        Menghapus jarak bawaan browser agar tampilan konsisten.
        border-box memudahkan pengaturan ukuran elemen karena
        padding dan border ikut dihitung.
        */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /*
        =========================================================
        BAGIAN 2: WARNA UTAMA
        =========================================================

        Variabel warna digunakan agar perubahan identitas warna
        aplikasi lebih mudah dilakukan dari satu tempat.
        */
        :root {
            --green: #365b35;
            --green-dark: #294529;
            --cream: #fff8eb;
            --brown: #69402b;
            --border: #d8c9ae;
        }

        /*
        =========================================================
        BAGIAN 3: BACKGROUND
        =========================================================

        Ilustrasi digunakan sebagai background dekoratif.
        background-size: cover membuat gambar memenuhi area
        tanpa mengubah proporsinya, meskipun sebagian gambar
        dapat terpotong pada layar dengan rasio berbeda.
        */
        body {
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #e8ddca;
            background-image: url("{{ asset('images/login-bg.png') }}");
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: var(--brown);
        }

        /*
        =========================================================
        BAGIAN 4: SUSUNAN HALAMAN
        =========================================================

        Flexbox digunakan untuk memusatkan kartu register.
        position: relative menjadi acuan penempatan footer
        agar footer dapat berada di bagian bawah halaman.
        Padding bawah menyediakan ruang supaya kartu register
        tidak bertabrakan dengan footer.
        */
        .page-overlay {
            min-height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px 65px;
        }

        /*
        =========================================================
        BAGIAN 5: KARTU REGISTER
        =========================================================

        Warna krem solid dipilih agar form tetap mudah dibaca
        di atas background ilustrasi.
        Lebar maksimal 380px membuat form tidak terlalu lebar
        ketika dibuka melalui komputer.
        */
        .register-card {
            width: 100%;
            max-width: 380px;
            padding: 28px;
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 8px;
        }

        /*
        =========================================================
        BAGIAN 6: IDENTITAS BREADSAVER
        =========================================================

        Nama aplikasi ditampilkan menggunakan teks biasa.
        Tidak menggunakan gambar logo agar tampilan lebih sederhana
        dan tidak membutuhkan file logo.png.
        */
        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-name {
            font-size: 25px;
            font-weight: 700;
            color: var(--green);
        }

        .brand-tagline {
            margin-top: 5px;
            font-size: 10px;
            letter-spacing: 1px;
            color: #78644d;
        }

        /*
        =========================================================
        BAGIAN 7: JUDUL DAN DESKRIPSI
        =========================================================

        Ukuran judul dibuat lebih besar daripada deskripsi
        untuk membedakan tingkat kepentingan teks.
        */
        .register-card h2 {
            margin-bottom: 8px;
            font-size: 23px;
            font-weight: 700;
            color: var(--brown);
        }

        .subtitle {
            margin-bottom: 24px;
            font-size: 13px;
            line-height: 1.6;
            color: #78644d;
        }

        /*
        =========================================================
        BAGIAN 8: INPUT FORM
        =========================================================

        Label dan input dikelompokkan agar jarak antarkolom
        konsisten. Input menggunakan latar putih dan border
        tipis agar mudah dikenali sebagai area untuk mengetik.
        */
        .form-group {
            margin-bottom: 17px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 600;
            color: var(--brown);
        }

        .form-group input {
            display: block;
            width: 100%;
            height: 44px;
            padding: 0 12px;
            border: 1px solid #cbbda5;
            border-radius: 5px;
            background: #ffffff;
            color: #342b22;
            font-family: inherit;
            font-size: 13px;
            outline: none;
        }

        /* Border berubah menjadi hijau ketika input dipilih. */
        .form-group input:focus {
            border-color: var(--green);
        }

        /*
        =========================================================
        BAGIAN 9: PESAN ERROR
        =========================================================

        Pesan validasi menggunakan warna merah agar mudah
        dibedakan dari informasi normal.
        */
        .error-message {
            margin-bottom: 16px;
            padding: 10px 12px;
            border: 1px solid #e0a39a;
            border-radius: 5px;
            background: #fff0ed;
            color: #a52d20;
            font-size: 12px;
            line-height: 1.5;
        }

        /*
        =========================================================
        BAGIAN 10: TOMBOL REGISTER
        =========================================================

        Tombol menggunakan warna hijau solid agar sesuai dengan
        identitas BreadSaver tanpa efek dekoratif berlebihan.
        */
        .register-button {
            width: 100%;
            min-height: 44px;
            margin-top: 8px;
            padding: 11px;
            border: none;
            border-radius: 5px;
            background: var(--green);
            color: #ffffff;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        /* Perubahan warna sederhana saat tombol disorot. */
        .register-button:hover {
            background: var(--green-dark);
        }

        /*
        =========================================================
        BAGIAN 11: TAUTAN KEMBALI
        =========================================================

        Tautan ini memungkinkan pengguna kembali ke beranda
        tanpa mengirimkan data login.
        */
        .form-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #78644d;
        }

        .form-footer a {
            color: var(--green);
            font-weight: 600;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        /*
        =========================================================
        BAGIAN 12: FOOTER COKELAT
        =========================================================

        Footer mempertahankan background cokelat dan tulisan
        identitas aplikasi seperti desain sebelumnya.

        position: absolute menempatkan footer di bagian bawah
        pembungkus halaman. Warna solid dipilih agar tampilannya
        sederhana dan teks tetap mudah dibaca.
        */
        .page-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 12px 15px;
            background: #4c2b1d;
            color: #f8ead5;
            font-size: 11px;
            text-align: center;
            line-height: 1.5;
        }

        /*
        =========================================================
        BAGIAN 13: RESPONSIVE DESIGN
        =========================================================

        Ukuran dan jarak kartu disesuaikan untuk HP.
        Struktur HTML tetap sama sehingga tidak perlu membuat
        halaman register terpisah untuk setiap perangkat.
        */
        @media (max-width: 600px) {
            body {
                background-attachment: scroll;
            }

            .page-overlay {
                padding: 20px 14px 65px;
            }

            .register-card {
                padding: 24px 20px;
            }

            .brand-name {
                font-size: 23px;
            }

            .register-card h2 {
                font-size: 21px;
            }

            .page-footer {
                font-size: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Wadah utama untuk memusatkan kartu register dan footer. -->
    <div class="page-overlay">

        <!-- Kartu utama yang menampung formulir register. -->
        <main class="register-card">

            <!-- Identitas aplikasi ditampilkan sebagai teks tanpa logo. -->
            <header class="login-header">
                <h1 class="brand-name">BreadSaver</h1>
                <p class="brand-tagline">SAVE FOOD, SAVE MORE</p>
            </header>

            <h2>Buat akun baru</h2>

            <p class="subtitle">
                Daftar sekarang dan temukan roti favorit dengan harga lebih hemat.
            </p>

            <!--
            POST mengirim data melalui request body, bukan URL.
            alamat tujuan form menggunakan URL register.
            Route POST harus terhubung ke proses pendaftaran
            di controller Laravel.
            -->
            <form method="POST" action="{{ url('/register') }}">

                <!-- Token CSRF untuk perlindungan form Laravel. -->
                @csrf

                <!-- Menampilkan pesan validasi jika terdapat error. -->
                @if ($errors->any())
                    <div class="error-message">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Input nama lengkap pengguna. -->
                <div class="form-group">
                    <label for="nama">Nama lengkap</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        placeholder="Masukkan nama lengkap"
                        value="{{ old('nama') }}"
                        autocomplete="name"
                        required
                        autofocus
                    >
                </div>

                <!-- Input alamat email pengguna. -->
                <div class="form-group">
                    <label for="email">Alamat email</label>

                    <!-- type=email membantu browser memeriksa format email. -->
                    <!-- old() mempertahankan email jika validasi gagal. -->
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="nama@email.com"
                        value="{{ old('email') }}"
                        autocomplete="username"
                        required
                    >
                </div>

                <!-- Input password pengguna. -->
                <div class="form-group">
                    <label for="password">Password</label>

                    <!-- type=password menyamarkan karakter di layar. -->
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <!-- Input konfirmasi password agar tidak terjadi salah ketik. -->
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <!-- Mengirim data pendaftaran ke server. -->
                <button type="submit" class="register-button">
                    Register
                </button>

            </form>

            <!-- Tautan untuk pengguna yang sudah memiliki akun. -->
            <div class="form-footer">
                Sudah punya akun?
                <a href="{{ url('/login') }}">Login</a>
            </div>

        </main>

    </div>

    <!--
    Footer cokelat di bagian bawah halaman.
    Tulisan identitas tim dan slogan tetap dipertahankan.
    -->
    <footer class="page-footer">
        BreadSaver Development Team
        &nbsp; | &nbsp;
        Selamatkan Roti, Kurangi Food Waste
    </footer>
</body>
</html>