<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wishlist Saya - BreadSaver</title>
    <style>
        *{box-sizing:border-box}
        body{margin:0;background:#f6f7fb;color:#203761;font-family:Arial,Helvetica,sans-serif}
        .bsw-wrap{max-width:1100px;margin:0 auto;padding:36px 30px 60px}
        .bsw-head{display:flex;justify-content:space-between;align-items:flex-start;gap:15px;margin-bottom:24px}
        .bsw-sub{font-size:13px;color:#818da8;line-height:1.6;margin:8px 0}
        h1{font-size:25px;margin:5px 0}
        .bsw-link,.bsw-button{border:1px solid #dbe3f1;border-radius:11px;padding:11px 15px;background:#fff;color:#203761;text-decoration:none;font-size:13px;font-weight:bold;cursor:pointer;display:inline-block}
        .bsw-link:hover,.bsw-button:hover{background:#fff1f6}
        .bsw-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(235px,1fr));gap:18px}
        .bsw-card{background:white;border:1px solid #e1e7f2;border-radius:16px;overflow:hidden;display:flex;flex-direction:column}
        .bsw-photo{height:168px;background:#f4f5f9;display:flex;align-items:center;justify-content:center}
        .bsw-photo img{width:100%;height:100%;object-fit:cover}
        .bsw-content{padding:16px;display:flex;flex-direction:column;gap:8px;flex:1}
        .bsw-name{font-size:17px;font-weight:bold;margin:0;color:#203761}
        .bsw-muted{font-size:12px;color:#71809e;line-height:1.5}
        .bsw-price{font-size:19px;font-weight:800;color:#203761}
        .bsw-old{font-size:12px;text-decoration:line-through;color:#9aa5b7}
        .bsw-status{display:inline-block;font-size:11px;background:#e8f6ed;color:#24774c;padding:6px 9px;border-radius:20px;align-self:start}
        .bsw-status.unavailable{background:#ffe9ef;color:#a3355a}
        .bsw-actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:auto;padding-top:8px}
        .bsw-actions>*{flex:1;text-align:center}
        .bsw-buy{border:0;background:#ff8fb1;color:#203761;border-radius:10px;padding:11px 8px;cursor:pointer;font-weight:bold;width:100%;font-size:12px}
        .bsw-buy:hover{background:#ffabc7}
        .bsw-buy:disabled{background:#e9edf5;color:#8993a7;cursor:not-allowed}
        .bsw-remove{border:1px solid #ffd1de;background:#fff;color:#ac365a;border-radius:10px;padding:11px 8px;cursor:pointer;font-size:12px;font-weight:bold}
        .bsw-remove:hover{background:#fff1f6}
        .bsw-empty{border:1px dashed #d1dbee;background:#fff;border-radius:18px;padding:55px 20px;text-align:center}
        .bsw-empty svg{width:65px;height:65px}
        .bsw-empty h2{margin:12px 0 5px;font-size:19px}
        .bsw-notice{background:#e8f7ed;color:#286d41;padding:12px 15px;border-radius:12px;margin-bottom:20px}
        .bsw-pages{margin-top:25px}
        @media(max-width:700px){.bsw-wrap{padding:24px 15px 35px}.bsw-head{flex-wrap:wrap}h1{font-size:21px}}
    </style>
</head>
<body class="bs-buyer-with-sidebar">
    @include('components.breadsaver-buyer-sidebar')
    <main class="bsw-wrap">
        <div class="bsw-head">
            <div>
                <div class="bsw-sub">BREADSAVER / PEMBELI</div>
                <h1>Wishlist Saya</h1>
                <p class="bsw-sub">Roti favorit tersimpan di sini. Harga dan stok mengikuti kondisi terbaru.</p>
            </div>
            <a class="bsw-link" href="{{ route('pembeli.katalog') }}">Jelajahi Katalog</a>
        </div>

        @if(session('success'))<div class="bsw-notice">{{ session('success') }}</div>@endif
        @if($items->count() > 0)
            <section class="bsw-grid" aria-label="Produk favorit">
                @foreach($items as $item)
                    @php
                        $exists = !is_null($item->nama_produk);
                        $available = $exists && (int) $item->stok > 0
                            && (empty($item->expired_at) || \Carbon\Carbon::parse($item->expired_at)->isFuture());
                        $name = $item->nama_produk ?: 'Produk sudah tidak tersedia';
                        $price = (float) ($item->harga_diskon ?? $item->harga_asli ?? 0);
                        $original = (float) ($item->harga_asli ?? 0);
                        $photo = (string) ($item->foto ?? '');
                        if ($photo === '') {
                            $imageUrl = asset('images/bread.png');
                        } elseif (preg_match('~^https?://~i', $photo)) {
                            $imageUrl = $photo;
                        } elseif (strpos($photo, 'storage/') === 0 || strpos($photo, 'images/') === 0) {
                            $imageUrl = asset($photo);
                        } else {
                            $imageUrl = asset('storage/' . $photo);
                        }
                    @endphp
                    <article class="bsw-card">
                        <div class="bsw-photo">
                            <img src="{{ $imageUrl }}" alt="{{ $name }}"
                                 onerror="this.onerror=null;this.src='{{ asset('images/bread.png') }}';">
                        </div>
                        <div class="bsw-content">
                            <h2 class="bsw-name">{{ $name }}</h2>
                            <span class="bsw-status {{ $available ? '' : 'unavailable' }}">
                                {{ $available ? 'Tersedia' : 'Stok habis / tidak tersedia' }}
                            </span>
                            @if($exists)
                                <div class="bsw-price">Rp{{ number_format($price,0,',','.') }}</div>
                                @if($original > $price)<div class="bsw-old">Rp{{ number_format($original,0,',','.') }}</div>@endif
                                <div class="bsw-muted">Stok: {{ max(0,(int)$item->stok) }}</div>
                                @if($item->expired_at)
                                    <div class="bsw-muted">Kedaluwarsa: {{ \Carbon\Carbon::parse($item->expired_at)->format('d/m/Y H:i') }}</div>
                                @endif
                            @endif
                            <div class="bsw-actions">
                                @if($available)
                                    <form action="{{ route('keranjang.tambah', $item->id_produk) }}" method="POST">
                                        @csrf
                                        <button class="bsw-buy" type="submit">+ Keranjang</button>
                                    </form>
                                @else
                                    <button class="bsw-buy" type="button" disabled>Tidak tersedia</button>
                                @endif
                                <form action="{{ route('pembeli.wishlist.destroy', $item->id_produk) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bsw-remove" type="submit" aria-label="Hapus {{ $name }} dari Wishlist">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
            <div class="bsw-pages">{{ $items->links() }}</div>
        @else
            <div class="bsw-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="#ff8fb1" stroke-width="1.7" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z"/></svg>
                <h2>Wishlist masih kosong</h2>
                <p class="bsw-sub">Klik ikon hati di Beranda atau Katalog untuk menyimpan roti favorit.</p>
                <a class="bsw-link" href="{{ route('pembeli.katalog') }}">Cari Roti Favorit</a>
            </div>
        @endif
    </main>
</body>
</html>
