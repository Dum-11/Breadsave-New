{{-- Sidebar mandiri untuk halaman Rescue Box dan Poin & Voucher.
     Halaman Pembeli lama sudah punya sidebar sendiri; jangan include di sana. --}}
@auth
<style>
body.bs-buyer-with-sidebar { padding-left: 76px; }
body.bs-buyer-with-sidebar main.wrap { margin-left: auto; margin-right: auto; }
.bs-buyer-side {
    position: fixed; inset: 0 auto 0 0; width: 76px; z-index: 1000;
    box-sizing: border-box; padding: 20px 12px;
    background: linear-gradient(180deg,#203761 0%,#172b4e 100%);
    color: #fff; display: flex; flex-direction: column;
    overflow-x: hidden; overflow-y: auto; white-space: nowrap;
    box-shadow: 4px 0 18px rgba(0,0,0,.1);
    transition: width .3s ease;
}
.bs-buyer-side:hover { width: 245px; }
.bs-buyer-side * { box-sizing: border-box; }
.bs-buyer-side-logo { display:flex;justify-content:center;align-items:center;min-height:48px;margin-bottom:34px; }
.bs-buyer-side-logo img { display:block;max-width:100%;object-fit:contain; }
.bs-buyer-side-logo .bs-mini { width:40px;height:40px; }
.bs-buyer-side-logo .bs-wide { display:none;width:145px;height:auto; }
.bs-buyer-side:hover .bs-mini { display:none; }
.bs-buyer-side:hover .bs-wide { display:block; }
.bs-buyer-side-heading { font-size:11px;letter-spacing:1px;margin:0 10px 14px;color:#c3cee4;opacity:0; }
.bs-buyer-side:hover .bs-buyer-side-heading { opacity:1; }
.bs-buyer-side-nav { display:flex;flex-direction:column; }
.bs-buyer-side-link {
    display:flex;align-items:center;gap:14px;min-height:50px;
    padding:12px 14px;border-radius:14px;margin-bottom:8px;
    color:#fff !important;text-decoration:none !important;font-size:15px;
    transition:background .2s ease;
}
.bs-buyer-side-link:hover { background:rgba(255,255,255,.10); }
.bs-buyer-side-link.active { background:#ff8fb1; color:#172b4e !important;font-weight:bold; }
.bs-buyer-side-link img { display:block;width:24px;height:24px;object-fit:contain;flex-shrink:0; }
/* Samakan avatar Profil dengan sidebar Pesanan/Beranda lama. */
.bs-buyer-side-profile-avatar {
    display:flex;align-items:center;justify-content:center;
    width:24px;height:24px;min-width:24px;flex-shrink:0;
    border-radius:50%;background:#ff8fb1;color:#fff;
    font-size:12px;font-weight:700;overflow:hidden;
}
.bs-buyer-side-link .bs-buyer-side-profile-avatar img {
    width:100%;height:100%;object-fit:cover;
}
.bs-buyer-side-link span { opacity:0;overflow:hidden;transition:opacity .2s ease; }
.bs-buyer-side:hover .bs-buyer-side-link span { opacity:1; }
.bs-buyer-side-bottom { margin-top:auto;padding-top:20px; }
.bs-buyer-side-logout { font-family:inherit;border:none;background:transparent;width:100%;text-align:left;cursor:pointer; }
.bs-buyer-side-logout:hover { background:rgba(255,255,255,.10); }
@media (max-width:700px) {
    .bs-buyer-side { padding:20px 10px; }
    .bs-buyer-side-heading { display:none; }
    .bs-buyer-side-link { justify-content:center;padding:10px 5px; }
    .bs-buyer-side:hover .bs-buyer-side-link { justify-content:flex-start;padding:10px 14px; }
}

/* Foto profil transparan, tanpa lingkaran pink */
.profile-avatar:has(img),
.bs-buyer-side-profile-avatar:has(img) {
    background: transparent !important;
    border-radius: 0 !important;
    overflow: visible !important;
}

/* Pertahankan seluruh gambar tanpa terpotong */
.profile-avatar img,
.bs-buyer-side-profile-avatar img {
    background: transparent !important;
    object-fit: contain !important;
}

</style>
<aside class="bs-buyer-side" aria-label="Navigasi Pembeli">
    <a class="bs-buyer-side-logo" href="{{ route('pembeli.beranda') }}">
        <img class="bs-mini" src="{{ asset('images/logo-icon.png') }}" alt="BreadSaver">
        <img class="bs-wide" src="{{ asset('images/logo.png') }}" alt="BreadSaver">
    </a>
    <div class="bs-buyer-side-heading">MENU UTAMA</div>
    <nav class="bs-buyer-side-nav">
        <a class="bs-buyer-side-link {{ request()->routeIs('pembeli.beranda') ? 'active' : '' }}" href="{{ route('pembeli.beranda') }}" title="Beranda"><img src="{{ asset('images/icon-home.png') }}" alt=""><span>Beranda</span></a>
        <a class="bs-buyer-side-link {{ request()->routeIs('pembeli.katalog') ? 'active' : '' }}" href="{{ route('pembeli.katalog') }}" title="Katalog Produk"><img src="{{ asset('images/icon-product.png') }}" alt=""><span>Katalog Produk</span></a>
                <a class="bs-buyer-side-link {{ request()->routeIs('pembeli.wishlist*') ? 'active' : '' }}" href="{{ route('pembeli.wishlist') }}" title="Wishlist Saya"><img src="{{ asset('images/icon-wishlist.svg') }}" alt=""><span>Wishlist Saya</span></a>
<a class="bs-buyer-side-link {{ request()->routeIs('pembeli.rescue-box*') ? 'active' : '' }}" href="{{ route('pembeli.rescue-box') }}" title="Bread Rescue Box"><img src="{{ asset('images/icon-rescue-box.png') }}" alt=""><span>Bread Rescue Box</span></a>
        <a class="bs-buyer-side-link {{ request()->routeIs('pembeli.pesanan') ? 'active' : '' }}" href="{{ route('pembeli.pesanan') }}" title="Pesanan"><img src="{{ asset('images/icon-orders.png') }}" alt=""><span>Pesanan</span></a>
        <a class="bs-buyer-side-link {{ request()->routeIs('pembeli.keranjang') ? 'active' : '' }}" href="{{ route('pembeli.keranjang') }}" title="Keranjang"><img src="{{ asset('images/icon-keranjang.png') }}" alt=""><span>Keranjang</span></a>
        <a class="bs-buyer-side-link {{ request()->routeIs('pembeli.loyalty*') ? 'active' : '' }}" href="{{ route('pembeli.loyalty') }}" title="Poin &amp; Voucher"><img src="{{ asset('images/icon-poin-voucher.png') }}" alt=""><span>Poin &amp; Voucher</span></a>
    </nav>
    <div class="bs-buyer-side-bottom">
        <a class="bs-buyer-side-link {{ request()->routeIs('pembeli.profil') ? 'active' : '' }}" href="{{ route('pembeli.profil') }}" title="Profil">
            <div class="bs-buyer-side-profile-avatar">
                @if(!empty(Auth::user()->foto_profil))
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Foto Profil">
                @else
                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                @endif
            </div>
            <span>Profil</span>
        </a>
        <form action="{{ route('logout') }}" method="POST">@csrf
            <button class="bs-buyer-side-link bs-buyer-side-logout" type="submit" title="Logout"><img src="{{ asset('images/icon-login.png') }}" alt=""><span>Logout</span></button>
        </form>
    </div>
</aside>
@endauth
