<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Rating & Ulasan - BreadSaver</title>

    <style>
        :root {
            --navy: #23365f;
            --pink: #ff8fb1;
            --background: #f6f8fc;
            --text: #26324b;
            --muted: #8991a4;
            --border: #e7eaf2;
            --gold: #f4b400;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 15px;
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        .card {
            max-width: 550px;
            margin: 35px auto;
            padding: 30px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(35,54,95,0.08);
        }

        h1 {
            margin: 0 0 8px;
            color: var(--navy);
            font-size: 26px;
            text-align: center;
        }

        .subtitle {
            color: var(--muted);
            text-align: center;
            font-size: 14px;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label.field-label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
            color: var(--navy);
        }

        select,
        textarea {
            display: block;
            width: 100%;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            background: white;
            color: var(--text);
        }

        select:focus,
        textarea:focus {
            outline: 2px solid var(--pink);
            outline-offset: 1px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
            line-height: 1.6;
        }

        .rating-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .rating-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            cursor: pointer;
        }

        .rating-option:hover {
            background: #fff6f9;
            border-color: var(--pink);
        }

        .rating-option input {
            accent-color: var(--navy);
            width: 17px;
            height: 17px;
        }

        .stars {
            color: var(--gold);
            font-size: 22px;
            letter-spacing: 2px;
        }

        .rating-text {
            font-size: 13px;
            color: var(--muted);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 12px;
        }

        .btn-primary {
            background: var(--navy);
            color: white;
        }

        .btn-pink {
            background: var(--pink);
            color: var(--navy);
        }

        .alert {
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #ffe6e6;
            color: #b53a3a;
        }

        .alert-success {
            background: #e2f7e9;
            color: #237844;
        }

        .empty {
            text-align: center;
            background: #f6f8fc;
            border-radius: 12px;
            padding: 25px;
            line-height: 1.7;
        }

        .help {
            color: var(--muted);
            font-size: 12px;
            margin-top: 8px;
        }

        @media (max-width: 600px) {
            .card {
                padding: 20px;
                margin: 10px auto;
            }

            h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

<div class="card">

    <h1>Rating & Ulasan</h1>

    <p class="subtitle">
        Bagikan pengalaman Anda tentang produk BreadSaver
        <br>
        Pesanan #{{ $pesanan->id_pesanan }}
    </p>

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if($produkBelumDinilai->isEmpty())

        <div class="empty">
            <strong>Semua produk sudah dinilai.</strong>
            <p>
                Terima kasih telah memberikan
                rating dan ulasan Anda!
            </p>
        </div>

    @else

        <form
            action="{{ route(
                'pembeli.rating.store',
                $pesanan->id_pesanan
            ) }}"
            method="POST"
        >
            @csrf

            <!-- PILIH PRODUK -->
            <div class="form-group">
                <label class="field-label"
                       for="id_produk">
                    Pilih Produk
                </label>

                <select
                    name="id_produk"
                    id="id_produk"
                    required
                >
                    <option value="">
                        -- Pilih produk yang ingin dinilai --
                    </option>

                    @foreach($produkBelumDinilai as $produk)
                        <option
                            value="{{ $produk->id_produk }}"
                            {{ old('id_produk') == $produk->id_produk
                                ? 'selected' : '' }}
                        >
                            {{ $produk->nama_produk }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- RATING BINTANG -->
            <div class="form-group">
                <label class="field-label">
                    Berikan Rating
                </label>

                <div class="rating-options">

                    @for($i = 5; $i >= 1; $i--)
                        <label class="rating-option">
                            <input
                                type="radio"
                                name="bintang"
                                value="{{ $i }}"
                                {{ old('bintang', 5) == $i
                                    ? 'checked' : '' }}
                                required
                            >

                            <span class="stars">
                                {{ str_repeat('★', $i) }}
                            </span>

                            <span class="rating-text">
                                {{ $i }} dari 5
                            </span>
                        </label>
                    @endfor

                </div>
            </div>

            <!-- ULASAN -->
            <div class="form-group">
                <label class="field-label"
                       for="ulasan">
                    Tulis Ulasan (Opsional)
                </label>

                <textarea
                    name="ulasan"
                    id="ulasan"
                    maxlength="1000"
                    placeholder="Bagaimana rasa, kualitas, dan kesegaran produknya?"
                >{{ old('ulasan') }}</textarea>

                <p class="help">
                    Maksimal 1000 karakter.
                </p>
            </div>

            <button
                type="submit"
                class="btn btn-pink"
            >
                Kirim Rating & Ulasan
            </button>

        </form>

    @endif

    <a
        href="{{ route('pembeli.pesanan') }}"
        class="btn btn-primary"
    >
        Kembali ke Pesanan
    </a>

</div>

</body>
</html>
