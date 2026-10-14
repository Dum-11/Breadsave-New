<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BreadSaver - Selamatkan Roti, Kurangi Food Waste</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .card { border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; border-radius: 8px; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .btn { padding: 10px 15px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🥖 BreadSaver</h1>
        <a href="{{ route('login') }}" class="btn">Login</a>
    </div>
    <hr>
    <h2>Daftar Roti Diskon Hari Ini</h2>

    @if($produks->isEmpty())
        <p>Belum ada produk roti yang dijual hari ini.</p>
    @else
        @foreach($produks as $produk)
            <div class="card">
                <h3>{{ $produk->nama_produk }}</h3>
                <p>Stok: {{ $produk->stok }} | Kedaluwarsa: {{ $produk->expired_at }}</p>
                <p><s>Rp {{ number_format($produk->harga_asli, 0, ',', '.') }}</s> 
                <b>Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}</b></p>
            </div>
        @endforeach
    @endif
</body>
</html>