<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Admin BreadSaver</title>
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
        button { background: var(--navy); color: var(--white); border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 10px; font-size: 14px; }
        button:hover { background: #1a2a4e; }
        .back-link { display: block; text-align: center; margin-top: 16px; color: var(--muted); text-decoration: none; font-size: 14px; }
        .back-link:hover { color: var(--navy); }
    </style>
</head>
<body>
    <div class="card">
        <h2>Tambah Produk Baru</h2>
        <form action="{{ route('admin.katalog.simpan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" required placeholder="Contoh: Roti Coklat Spesial">
            </div>
            <div class="form-group">
                <label>Harga Asli (Rp)</label>
                <input type="number" name="harga_asli" required placeholder="Contoh: 15000">
            </div>
            <div class="form-group">
                <label>Harga Diskon (Rp)</label>
                <input type="number" name="harga_diskon" required placeholder="Contoh: 10000">
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" required placeholder="Contoh: 20">
            </div>
            <div class="form-group">
                <label>Tanggal & Waktu Kedaluwarsa (Expired At)</label>
                <input type="datetime-local" name="expired_at" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat produk..."></textarea>
            </div>
            <button type="submit">Simpan Produk</button>
        </form>
        <a href="{{ route('admin.katalog') }}" class="back-link">Kembali ke Kelola Katalog</a>
    </div>
</body>
</html>