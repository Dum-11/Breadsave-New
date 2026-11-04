<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Admin BreadSaver</title>
    <style>
        :root {
            --navy: #23365f; --pink: #ff8fb1; --background: #f8f9fc; --text: #26324b; --muted: #8991a4; --white: #ffffff; --border: #e7eaf1;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: var(--background); color: var(--text); padding: 40px; }
        .card { background: var(--white); max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 15px; border: 1px solid var(--border); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        h2 { margin-bottom: 20px; color: var(--navy); font-size: 24px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; font-size: 13px; color: var(--text); }
        input, textarea { width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; outline: none; }
        input:focus, textarea:focus { border-color: var(--pink); }
        button { background: var(--pink); color: var(--navy); border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 10px; font-size: 14px; }
        button:hover { opacity: 0.9; }
        .back-link { display: block; text-align: center; margin-top: 16px; color: var(--muted); text-decoration: none; font-size: 14px; }
        .back-link:hover { color: var(--navy); }
    </style>
</head>
<body>
    <div class="card">
        <h2>Edit Produk</h2>
        <form action="{{ route('admin.katalog.update', $produk->id_produk) }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" required>
            </div>
            <div class="form-group">
                <label>Harga Asli (Rp)</label>
                <input type="number" name="harga_asli" value="{{ $produk->harga_asli }}" required>
            </div>
            <div class="form-group">
                <label>Harga Diskon (Rp)</label>
                <input type="number" name="harga_diskon" value="{{ $produk->harga_diskon }}" required>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" value="{{ $produk->stok }}" required>
            </div>
            <div class="form-group">
                <label>Tanggal & Waktu Kedaluwarsa (Expired At)</label>
                <input type="datetime-local" name="expired_at" value="{{ $produk->expired_at ? date('Y-m-d\TH:i', strtotime($produk->expired_at)) : '' }}" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="3">{{ $produk->deskripsi }}</textarea>
            </div>
            <button type="submit">Perbarui Produk</button>
        </form>
        <a href="{{ route('admin.katalog') }}" class="back-link">Kembali ke Kelola Katalog</a>
    </div>
</body>
</html>