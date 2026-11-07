<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Produk - BreadSaver</title>

    <style>

        :root {
            --navy: #23365f;
            --pink: #ff8fb1;
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
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        .main {
            max-width: 900px;
            margin: 40px auto;
            padding: 25px;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 27px;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .header p {
            font-size: 14px;
            color: var(--muted);
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            border: 1px solid var(--border);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 9px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            font-family: Arial, sans-serif;
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

        .error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 7px;
        }

        .error-list {
            background: #fff0f0;
            color: #b42318;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            text-decoration: none;
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

        .note {
            color: var(--muted);
            font-size: 12px;
            margin-top: 7px;
        }

        @media(max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .main {
                margin: 15px auto;
                padding: 15px;
            }

            .form-card {
                padding: 20px;
            }
        }

    </style>

</head>

<body>

    <main class="main">

        <header class="header">

            <h1>Tambah Produk</h1>

            <p>
                Masukkan informasi produk baru ke dalam katalog BreadSaver.
            </p>

        </header>


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


            <!-- FORM TAMBAH PRODUK -->
            <form
                action="{{ route('admin.produk.store') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf


                <!-- NAMA PRODUK -->
                <div class="form-group">

                    <label for="nama_produk">
                        Nama Produk
                    </label>

                    <input
                        type="text"
                        id="nama_produk"
                        name="nama_produk"
                        placeholder="Contoh: Roti Cokelat"
                        value="{{ old('nama_produk') }}"
                        required>

                    @error('nama_produk')
                        <div class="error">{{ $message }}</div>
                    @enderror

                </div>


                <!-- HARGA -->
                <div class="form-row">

                    <div class="form-group">

                        <label for="harga_asli">
                            Harga Asli (Rp)
                        </label>

                        <input
                            type="number"
                            id="harga_asli"
                            name="harga_asli"
                            min="0"
                            step="0.01"
                            placeholder="Contoh: 20000"
                            value="{{ old('harga_asli') }}"
                            required>

                        @error('harga_asli')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="harga_diskon">
                            Harga Diskon (Rp)
                        </label>

                        <input
                            type="number"
                            id="harga_diskon"
                            name="harga_diskon"
                            min="0"
                            step="0.01"
                            placeholder="Contoh: 15000"
                            value="{{ old('harga_diskon') }}"
                            required>

                        @error('harga_diskon')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>

                </div>


                <!-- STOK DAN EXPIRED -->
                <div class="form-row">

                    <div class="form-group">

                        <label for="stok">
                            Jumlah Stok
                        </label>

                        <input
                            type="number"
                            id="stok"
                            name="stok"
                            min="0"
                            placeholder="Contoh: 20"
                            value="{{ old('stok') }}"
                            required>

                        @error('stok')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="expired_at">
                            Tanggal Kedaluwarsa
                        </label>

                        <input
                            type="datetime-local"
                            id="expired_at"
                            name="expired_at"
                            value="{{ old('expired_at') }}"
                            required>

                        @error('expired_at')
                            <div class="error">{{ $message }}</div>
                        @enderror

                    </div>

                </div>


                <!-- DESKRIPSI -->
                <div class="form-group">

                    <label for="deskripsi">
                        Deskripsi Produk
                    </label>

                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        placeholder="Masukkan deskripsi produk...">{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')
                        <div class="error">{{ $message }}</div>
                    @enderror

                </div>


                <!-- FOTO -->
                <div class="form-group">

                    <label for="foto">
                        Foto Produk
                    </label>

                    <input
                        type="file"
                        id="foto"
                        name="foto"
                        accept=".jpg,.jpeg,.png,.webp">

                    <p class="note">
                        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                    </p>

                    @error('foto')
                        <div class="error">{{ $message }}</div>
                    @enderror

                </div>


                <!-- BUTTON -->
                <div class="form-actions">

                    <a
                        href="{{ route('admin.katalog') }}"
                        class="btn btn-back">

                        Kembali

                    </a>


                    <button
                        type="submit"
                        class="btn btn-save">

                        Simpan Produk

                    </button>

                </div>

            </form>

        </section>

    </main>

</body>

</html>
