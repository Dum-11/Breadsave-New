<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BreadSaver</title>
    <style>
        :root {
            --green: #365b35;
            --green-dark: #294529;
            --cream: #fff8eb;
            --brown: #69402b;
            --border: #d8c9ae;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        .page-overlay {
            min-height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px 65px;
        }

        .register-card {
            width: 100%;
            max-width: 400px;
            padding: 32px 28px;
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .login-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand-name {
            font-size: 26px;
            font-weight: 700;
            color: var(--green);
        }

        .brand-tagline {
            margin-top: 4px;
            font-size: 11px;
            letter-spacing: 1.5px;
            color: #78644d;
            font-weight: bold;
        }

        .register-card h2 {
            margin-bottom: 6px;
            font-size: 22px;
            font-weight: 700;
            color: var(--brown);
        }

        .subtitle {
            margin-bottom: 22px;
            font-size: 13px;
            line-height: 1.5;
            color: #78644d;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--brown);
        }

        .form-group input {
            display: block;
            width: 100%;
            height: 44px;
            padding: 0 14px;
            border: 1px solid #cbbda5;
            border-radius: 8px;
            background: #ffffff;
            color: #342b22;
            font-family: inherit;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(54, 91, 53, 0.15);
        }

        .error-message {
            margin-bottom: 18px;
            padding: 12px;
            border: 1px solid #fecaca;
            border-radius: 8px;
            background: #fff1f2;
            color: #991b1b;
            font-size: 12px;
            line-height: 1.4;
        }

        .error-message div + div {
            margin-top: 4px;
        }

        .register-button {
            width: 100%;
            height: 44px;
            margin-top: 6px;
            padding: 0 16px;
            border: none;
            border-radius: 8px;
            background: var(--green);
            color: #ffffff;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .register-button:hover {
            background: var(--green-dark);
        }

        .form-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 13px;
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

        .login-link {
            margin-bottom: 8px;
        }

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

        @media (max-width: 600px) {
            body {
                background-attachment: scroll;
            }

            .page-overlay {
                padding: 16px 12px 60px;
            }

            .register-card {
                padding: 24px 20px;
            }

            .page-footer {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="page-overlay">
        <main class="register-card">
            <header class="login-header">
                <h1 class="brand-name">BreadSaver</h1>
                <p class="brand-tagline">SAVE FOOD, SAVE MORE</p>
            </header>

            <h2>Buat akun baru</h2>
            <p class="subtitle">
                Daftar sekarang dan temukan roti favorit dengan harga lebih hemat.
            </p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                @if ($errors->any())
                    <div class="error-message">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

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

                <div class="form-group">
                    <label for="email">Alamat email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="nama@email.com"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="new-password"
                        required
                    >
                </div>

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

                <button type="submit" class="register-button">
                    Register
                </button>
            </form>

            <div class="form-footer">
                <div class="login-link">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Login</a>
                </div>
                <div>
                    <a href="{{ url('/') }}">Kembali ke beranda</a>
                </div>
            </div>
        </main>
    </div>

    <footer class="page-footer">
        BreadSaver Development Team
        &nbsp; | &nbsp;
        Selamatkan Roti, Kurangi Food Waste
    </footer>
</body>
</html>