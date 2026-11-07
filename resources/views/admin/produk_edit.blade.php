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
            --background: #f8f9fc;
            --text: #26324b;
            --muted: #8991a4;
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

        <!-- HEADER -->
        <header class="header">

            <h1>Edit Produk</h1>

            <p>
                Ubah informasi produk yang sudah tersimpan di BreadSaver.
            </p>

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
            <form
                action="{{ route('admin.produk.update', $produk->id_produk) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                @method('PUT')


                <!-- NAMA PRODUK -->
                <div class="form-group">

                    <label for="nama_produk">
                        Nama Produk
                    </label>

                    <input
                        type="text"
                        id="nama_produk"
                        name="nama_produk"
                        value="{{ old('nama_produk', $produk->nama_produk) }}"
                        placeholder="Masukkan nama produk"
                        required>

                    @error('nama_produk')

                        <div class="error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <!-- HARGA -->
                <div class="form-row">


                    <!-- HARGA ASLI -->
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
                            value="{{ old('harga_asli', $produk->harga_asli) }}"
                            required>

                        @error('harga_asli')

                            <div class="error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <!-- HARGA DISKON -->
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
                            value="{{ old('harga_diskon', $produk->harga_diskon) }}"
                            required>

                        @error('harga_diskon')

                            <div class="error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                <!-- STOK DAN EXPIRED -->
                <div class="form-row">


                    <!-- STOK -->
                    <div class="form-group">

                        <label for="stok">
                            Jumlah Stok
                        </label>

                        <input
                            type="number"
                            id="stok"
                            name="stok"
                            min="0"
                            value="{{ old('stok', $produk->stok) }}"
                            required>

                        @error('stok')

                            <div class="error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <!-- EXPIRED -->
                    <div class="form-group">

                        <label for="expired_at">
                            Tanggal Kedaluwarsa
                        </label>

                        <input
                            type="datetime-local"
                            id="expired_at"
                            name="expired_at"
                            value="{{ old(
                                'expired_at',
                                $produk->expired_at
                                    ? \Carbon\Carbon::parse($produk->expired_at)->format('Y-m-d\TH:i')
                                    : ''
                            ) }}"
                            required>

                        @error('expired_at')

                            <div class="error">
                                {{ $message }}
                            </div>

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
                        placeholder="Masukkan deskripsi produk...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>

                    @error('deskripsi')

                        <div class="error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <!-- FOTO LAMA -->
                <div class="form-group">

                    <label>
                        Foto Produk Saat Ini
                    </label>

                    @if($produk->foto)

                        <div>

                            <img
                                src="{{ asset('storage/' . $produk->foto) }}"
                                class="current-photo"
                                alt="{{ $produk->nama_produk }}">

                        </div>

                    @else

                        <p class="note">
                            Produk belum memiliki foto.
                        </p>

                    @endif

                </div>


                <!-- FOTO BARU -->
                <div class="form-group">

                    <label for="foto">
                        Ganti Foto Produk
                    </label>

                    <input
                        type="file"
                        id="foto"
                        name="foto"
                        accept=".jpg,.jpeg,.png,.webp">

                    <p class="note">
                        Kosongkan jika tidak ingin mengganti foto lama.
                        Format JPG, JPEG, PNG, atau WEBP.
                        Ukuran maksimal 2 MB.
                    </p>

                    @error('foto')

                        <div class="error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <!-- TOMBOL -->
                <div class="form-actions">

                    <a
                        href="{{ route('admin.katalog') }}"
                        class="btn btn-back">

                        Kembali

                    </a>


                    <button
                        type="submit"
                        class="btn btn-save">

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </section>

    </main>

</body>

</html>
