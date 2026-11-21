{{--
    BreadSaver - tampilan Keranjang modern (revisi)
    - Header teks saja, tanpa ikon keranjang tambahan.
    - Thumbnail diambil dari tabel produks.foto, memakai aturan URL yg sama seperti Katalog.
    - Tidak mengubah route, controller, form checkout, pilihan slot, voucher, CSRF, atau event JS lama.
--}}
@php
    $bsCartItems = collect($keranjangs ?? [])->values();
    $bsCartPhotoUrls = [];
    // IDs item keranjang dan subtotal asli untuk pilihan checkout.
    $bsCartSelectionRows = $bsCartItems->map(function ($item) {
        $price = (float) (($item->harga_diskon ?? 0) > 0
            ? $item->harga_diskon
            : ($item->harga_asli ?? 0));
        return [
            'id' => (int) ($item->id ?? 0),
            'qty' => (int) ($item->jumlah ?? 0),
            'subtotal' => round($price * (int) ($item->jumlah ?? 0)),
        ];
    })->values()->all();

    if ($bsCartItems->isNotEmpty()) {
        $bsCartProductIds = $bsCartItems->pluck('id_produk')->filter(function ($id) {
            return $id !== null && $id !== '';
        })->unique()->values()->all();
        $bsCartProductNames = $bsCartItems->pluck('nama_produk')->filter()->unique()->values()->all();

        $bsProductRowsQuery = \Illuminate\Support\Facades\DB::table('produks')
            ->select('id_produk', 'nama_produk', 'foto');

        $bsProductRows = $bsProductRowsQuery->where(function ($query) use ($bsCartProductIds, $bsCartProductNames) {
            if (!empty($bsCartProductIds)) {
                $query->whereIn('id_produk', $bsCartProductIds);
            }
            if (!empty($bsCartProductNames)) {
                if (!empty($bsCartProductIds)) {
                    $query->orWhereIn('nama_produk', $bsCartProductNames);
                } else {
                    $query->whereIn('nama_produk', $bsCartProductNames);
                }
            }
        })->get();

        $bsProductRowsById = $bsProductRows->keyBy('id_produk');
        $bsProductRowsByName = $bsProductRows->keyBy('nama_produk');

        foreach ($bsCartItems as $bsCartItem) {
            $bsProduct = null;
            if (isset($bsCartItem->id_produk)) {
                $bsProduct = $bsProductRowsById->get($bsCartItem->id_produk);
            }
            if (!$bsProduct && !empty($bsCartItem->nama_produk)) {
                $bsProduct = $bsProductRowsByName->get($bsCartItem->nama_produk);
            }

            $bsPhoto = trim((string) ($bsProduct->foto ?? ''));
            if ($bsPhoto !== '') {
                // Meniru resolusi foto dari Katalog Produk.
                if (preg_match('~^https?://~i', $bsPhoto)) {
                    $bsUrl = $bsPhoto;
                } elseif (str_starts_with($bsPhoto, 'storage/') || str_starts_with($bsPhoto, 'images/')) {
                    $bsUrl = asset($bsPhoto);
                } else {
                    $bsUrl = asset('storage/' . ltrim($bsPhoto, '/'));
                }
            } else {
                // Fallback foto persis seperti Katalog, bukan ilustrasi SVG generik.
                $bsName = strtolower((string) ($bsProduct->nama_produk ?? $bsCartItem->nama_produk ?? ''));
                if (str_contains($bsName, 'cokelat') || str_contains($bsName, 'coklat') || str_contains($bsName, 'chocolate')) {
                    $bsUrl = asset('images/roti-cokelat.png');
                } elseif (str_contains($bsName, 'croissant') || str_contains($bsName, 'pastry')) {
                    $bsUrl = asset('images/croissant.png');
                } elseif (str_contains($bsName, 'tawar') || str_contains($bsName, 'sandwich')) {
                    $bsUrl = asset('images/roti-tawar.png');
                } else {
                    $bsUrl = asset('images/bread.png');
                }
            }
            $bsCartPhotoUrls[] = $bsUrl;
        }
    }
@endphp
<style>
    /* Semua aturan dibatasi untuk halaman Keranjang. */
    .bs-modern-cart-page {
        --bs-navy: #203760;
        --bs-navy-dark: #14294d;
        --bs-pink: #f65292;
        --bs-pink-soft: #fff0f6;
        --bs-ink: #263957;
        --bs-muted: #7a87a0;
        --bs-line: #e6eaf3;
        --bs-green: #148346;
        --bs-green-dark: #0a6636;
        background: radial-gradient(circle at 94% 2%, rgba(255, 143, 177, .13), transparent 23%), #f7f8fc;
        min-height: 100vh;
        color: var(--bs-ink);
        padding-top: 35px;
    }
    .bs-modern-cart-page .page-header {
        display: block;
        position: relative;
        isolation: isolate;
        margin: 0 0 26px;
        padding: 8px 0 18px;
        min-height: 86px;
    }
    .bs-modern-cart-page .page-header::after {
        content: '';
        position: absolute;
        z-index: -1;
        right: 4%; top: -22px;
        width: 230px; height: 110px;
        border-radius: 100% 40% 65% 40%;
        transform: rotate(-8deg);
        background: linear-gradient(105deg, rgba(255, 202, 220, .36), rgba(255, 154, 189, .09));
        pointer-events: none;
    }
    .bs-modern-cart-page .page-header h1 {
        color: var(--bs-navy-dark); font-size: clamp(25px, 2.45vw, 37px);
        line-height: 1.16; margin: 0 0 8px; letter-spacing: -.7px;
    }
    .bs-modern-cart-page .page-header p { color: var(--bs-muted); font-size: 14px; margin: 0; }
    .bs-modern-cart-page .bs-cart-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.48fr) minmax(310px, 1fr);
        gap: 22px;
        align-items: start;
    }
    .bs-modern-cart-page .cart-card,
    .bs-modern-cart-page .bs-checkout-summary {
        min-width: 0;
        background: #fff;
        border: 1px solid var(--bs-line);
        border-radius: 22px;
        box-shadow: 0 13px 35px rgba(33, 55, 95, .058);
        overflow: hidden;
    }
    .bs-modern-cart-page .cart-card { min-height: 510px; }
    .bs-modern-cart-page .cart-header {
        display: flex; align-items: center; flex-wrap: wrap;
        justify-content: space-between; gap: 12px;
        padding: 24px 25px 17px;
        border-bottom: 0;
    }
    .bs-modern-cart-page .cart-header h2 {
        font-size: 22px; color: var(--bs-navy-dark);
        line-height: 1.25; letter-spacing: -.4px; margin-bottom: 5px;
    }
    .bs-modern-cart-page .cart-header p { font-size: 13px; color: var(--bs-muted); }
    .bs-modern-cart-page .bs-cart-count {
        padding: 10px 13px; background: var(--bs-pink-soft);
        border-radius: 12px; font-size: 12px; color: #be3168;
        font-weight: 700; white-space: nowrap;
    }
    .bs-modern-cart-page .cart-card > div:has(.cart-table) { margin: 0 20px; }
    .bs-modern-cart-page .cart-table {
        width: 100%; border-collapse: separate; border-spacing: 0;
    }
    .bs-modern-cart-page .cart-table th {
        background: #f6f7fb; color: #78859f;
        padding: 13px 12px; font-size: 10px;
        font-weight: 800; letter-spacing: .7px; border: 0;
        white-space: nowrap;
    }
    /* Pilih produk tanpa mengubah form tombol jumlah dan hapus lama. */
    .bs-modern-cart-page .bs-cart-select-toolbar {
        display:flex; justify-content:space-between; align-items:center;
        padding: 0 0 13px; gap:12px; flex-wrap:wrap;
        font-size:13px; color:var(--bs-navy-dark);
    }
    .bs-modern-cart-page .bs-cart-select-toolbar label {
        display:inline-flex; gap:9px; align-items:center;
        font-weight:700; cursor:pointer;
    }
    .bs-modern-cart-page .bs-cart-select-toolbar .bs-cart-select-status {
        color:#8790a5; font-size:12px;
    }
    .bs-modern-cart-page input.bs-cart-checkbox {
        width:18px; height:18px; accent-color:#ef5b93;
        cursor:pointer; vertical-align:middle;
    }
    .bs-modern-cart-page .cart-table td.bs-cart-checkbox-cell,
    .bs-modern-cart-page .cart-table th.bs-cart-checkbox-head {
        padding-left:12px; padding-right:6px; min-width:38px;
        width:38px; text-align:center;
    }
    .bs-modern-cart-page .cart-table tr.bs-cart-not-selected td:not(.bs-cart-checkbox-cell) { opacity:.50; }
    .bs-modern-cart-page .bs-cart-select-error {
        color:#b42353; font-weight:700; font-size:12px;
        margin:10px 0 0;
    }
    .bs-modern-cart-page .bs-cart-select-error[hidden] { display:none !important; }
    .bs-modern-cart-page .btn-checkout:disabled { opacity:.55; cursor:not-allowed; }
    .bs-modern-cart-page .cart-table th:first-child { border-radius: 12px 0 0 12px; }
    .bs-modern-cart-page .cart-table th:last-child { border-radius: 0 12px 12px 0; }
    .bs-modern-cart-page .cart-table td {
        padding: 20px 12px; border-bottom: 1px solid var(--bs-line);
        font-size: 13px; color: var(--bs-ink);
        line-height: 1.5; white-space: nowrap;
    }
    .bs-modern-cart-page .cart-table td:nth-child(2) { min-width: 170px; white-space: normal; }
    .bs-modern-cart-page .cart-table td:nth-child(5) { font-weight: 800 !important; color: var(--bs-navy-dark); }
    .bs-modern-cart-page .bs-cart-product {
        display: flex; align-items: center; gap: 11px;
    }
    .bs-modern-cart-page .bs-cart-product-image {
        display: grid; place-items: center; flex: 0 0 60px;
        width: 60px; height: 60px; overflow: hidden;
        background: #f8f9fc; border-radius: 12px;
        border: 1px solid #e9edf3;
    }
    .bs-modern-cart-page .bs-cart-product-image img {
        width: 100%; height: 100%; object-fit: cover;
        display: block;
    }
    .bs-modern-cart-page .bs-cart-product-image .bs-photo-unavailable {
        font-size: 10px; line-height: 1.2; color: #768299; text-align: center;
        padding: 4px; white-space: normal;
    }
    .bs-modern-cart-page .bs-cart-product-name { font-weight: 800; color: var(--bs-navy-dark); }
    .bs-modern-cart-page .qty-box { gap: 8px; white-space: nowrap; }
    .bs-modern-cart-page .qty-btn {
        background: #eef0f6; color: var(--bs-navy-dark);
        width: 31px; height: 32px; border-radius: 9px;
    }
    .bs-modern-cart-page .qty-btn:hover { background: #fbe2ec; }
    .bs-modern-cart-page .btn-delete {
        display: inline-flex; align-items: center; justify-content: center;
        background: #ffe9ef; border-radius: 10px;
        color: #d12d69; min-width: 55px; padding: 9px;
    }
    .bs-modern-cart-page .btn-delete:hover { background: #ffd8e6; }
    .bs-modern-cart-page .cart-footer-action { display: none !important; }
    .bs-modern-cart-page .bs-cart-benefits {
        margin: 38px 20px 24px;
        padding: 19px;
        display: grid; grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        background: linear-gradient(110deg, #fff0f7, #fcfaff);
        border-radius: 15px;
    }
    .bs-modern-cart-page .bs-cart-benefits > div:not(:last-child) { border-right: 1px solid #f5cee0; padding-right: 12px; }
    .bs-modern-cart-page .bs-cart-benefits strong { display:block; font-size: 12px; color: var(--bs-navy-dark); margin-bottom: 5px; }
    .bs-modern-cart-page .bs-cart-benefits small { display:block; color:#7d859a; font-size: 11px; line-height: 1.48; }
    .bs-modern-cart-page .bs-checkout-summary { padding: 23px; }
    .bs-modern-cart-page .bs-summary-title {
        display: flex; align-items: center; gap: 11px;
        padding-bottom: 20px; margin-bottom: 21px;
        border-bottom: 1px solid var(--bs-line);
    }
    .bs-modern-cart-page .bs-summary-icon {
        display:grid; place-items:center; width: 42px; height:42px;
        border-radius: 12px; background: #ffe5ef; color:#cf3678;
    }
    .bs-modern-cart-page .bs-summary-title h2 { margin:0; font-size:20px; color:var(--bs-navy-dark); }
    .bs-modern-cart-page .bs-summary-form label {
        display: block !important; font-weight: 800 !important;
        color: var(--bs-navy-dark) !important; font-size: 13px;
        margin-bottom: 9px !important;
    }
    .bs-modern-cart-page .bs-summary-form select,
    .bs-modern-cart-page .bs-summary-form input:not([type='hidden']) {
        width: 100% !important; max-width: 100% !important;
        min-height: 42px; background: white;
        color: #354563; border: 1px solid #dce2ef !important;
        border-radius: 10px !important; font-size: 13px !important;
        padding: 10px 12px !important;
        margin-bottom: 12px;
    }
    .bs-modern-cart-page .bs-summary-form select:focus,
    .bs-modern-cart-page .bs-summary-form input:focus { outline: 2px solid #fbc9de; outline-offset: 1px; }
    .bs-modern-cart-page .bs-summary-form #waktu_pengambilan:not([type='hidden']) { margin-bottom: 11px; }
    .bs-modern-cart-page .bs-summary-form small,
    .bs-modern-cart-page .bs-summary-form p { line-height: 1.55; }
    .bs-modern-cart-page .bs-summary-form div:has(> input[type='date']):not(.bs-summary-title) {
        max-width: none !important;
    }
    .bs-modern-cart-page .bs-summary-form [style*='max-width:420px'] { max-width: 100% !important; }
    .bs-modern-cart-page .bs-summary-total {
        display:flex; align-items:center; justify-content:space-between;
        gap:10px; padding:22px 0 16px; margin-top: 17px;
        border-top: 1px solid var(--bs-line);
        font-size: 14px; font-weight: 800; color:var(--bs-navy-dark);
    }
    .bs-modern-cart-page .bs-summary-amount { font-size: clamp(19px, 2vw, 26px); color: #e83d83; white-space: nowrap; }
    .bs-modern-cart-page .btn-checkout {
        width:100% !important; display:flex; align-items:center;
        justify-content:center; min-height:49px;
        background: linear-gradient(110deg, var(--bs-green), var(--bs-green-dark));
        border-radius: 12px; font-size:14px; box-shadow:0 9px 19px rgba(20,131,70,.16);
        color:white; border:0; font-weight:800; cursor:pointer; padding: 12px 14px;
    }
    .bs-modern-cart-page .btn-checkout:hover { filter:brightness(1.06); }
    .bs-modern-cart-page .btn-checkout:disabled { opacity: .65; cursor: wait; }
    .bs-modern-cart-page .bs-cart-security {
        display:flex; align-items:center; justify-content:center;
        margin-top:12px; gap:7px; color:#818da0; font-size:11px;
    }
    .bs-modern-cart-page .empty-cart { min-height:340px; }
    .bs-modern-cart-page .footer { margin-top: 25px; border: 0; }
    @media (max-width: 1180px) {
        .bs-modern-cart-page .bs-cart-grid { grid-template-columns: minmax(0, 1fr); }
        .bs-modern-cart-page .cart-card { min-height: 0; }
    }
    @media (max-width: 640px) {
        .bs-modern-cart-page { padding: 19px 13px; }
        .bs-modern-cart-page .page-header h1 { font-size: 23px; }
        .bs-modern-cart-page .page-header p { font-size: 12px; }
        .bs-modern-cart-page .cart-header { padding:19px 17px 14px; }
        .bs-modern-cart-page .cart-card > div:has(.cart-table) { margin: 0 8px; }
        .bs-modern-cart-page .cart-table th, .bs-modern-cart-page .cart-table td { padding: 11px 8px; }
        .bs-modern-cart-page .bs-checkout-summary { padding: 18px; }
        .bs-modern-cart-page .bs-cart-benefits { margin:18px 12px; grid-template-columns:1fr; }
        .bs-modern-cart-page .bs-cart-benefits > div:not(:last-child) { border-right:0; border-bottom: 1px solid #f5cee0; padding-bottom:12px; }
    }
</style>
<script>
(function () {
    'use strict';

    // Urutan URL foto mengikuti baris produk pada tabel Keranjang.
    var bsCartPhotoUrls = @json($bsCartPhotoUrls);
    var bsCartSelectionRows = @json($bsCartSelectionRows);
    var bsSelectionStorageKey = 'breadsaver-cart-selected-v1';
    function loadPreviousSelection() {
        try {
            var raw = sessionStorage.getItem(bsSelectionStorageKey);
            if (!raw) return null;
            var parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed.map(String) : null;
        } catch (e) { return null; }
    }
    function saveSelection(values) {
        try { sessionStorage.setItem(bsSelectionStorageKey, JSON.stringify(values)); }
        catch (e) { /* storage bisa dinonaktifkan oleh browser */ }
    }
    function formatRupiah(n) {
        return 'Rp' + Math.round(n).toLocaleString('id-ID');
    }


    function decorate() {
        var main = document.querySelector('main.main');
        if (!main || main.classList.contains('bs-modern-cart-page')) return;
        var cart = main.querySelector('.cart-card');
        var header = main.querySelector('.page-header');
        if (!cart || !header) return;

        var table = cart.querySelector('.cart-table');
        var form = cart.querySelector('form[action*="checkout"]');
        var footer = cart.querySelector('.cart-footer-action');
        var total = cart.querySelector('.total-price');
        // Jika struktur keranjang berubah, hindari mengubah sebagian halaman.
        if (!table || !form || !footer || !total) return;

        main.classList.add('bs-modern-cart-page');

        var rows = Array.prototype.slice.call(table.querySelectorAll('tbody tr'));
        var qty = rows.reduce(function(sum, tr) {
            var box = tr.querySelector('.qty-box');
            if (!box) return sum + 1;
            var textNodes = Array.prototype.filter.call(box.childNodes, function(n) { return n.nodeType === 3 || (n.nodeType === 1 && n.tagName === 'SPAN'); });
            var number = textNodes.map(function(n) { return n.textContent.trim(); }).find(function(t) { return /^\d+$/.test(t); });
            return sum + (number ? Number(number) : 1);
        }, 0);
        var cartHead = cart.querySelector('.cart-header');
        if (cartHead) {
            var badge = document.createElement('span');
            badge.className = 'bs-cart-count';
            badge.textContent = qty + ' roti di keranjang';
            cartHead.appendChild(badge);
        }

        // Checkbox setiap produk + Pilih Semua. Tidak mengubah route lama.
        var savedIds = loadPreviousSelection();
        var knownIds = bsCartSelectionRows.map(function(item) { return String(item.id); });
        var previouslyKnown = savedIds || [];
        // Item baru otomatis terpilih; pilihan yg sengaja dimatikan tetap tersimpan setelah +/- reload.
        var rememberKey = bsSelectionStorageKey + ':known';
        var oldKnown = [];
        try { oldKnown = JSON.parse(sessionStorage.getItem(rememberKey) || '[]'); }
        catch (e) { oldKnown = []; }
        oldKnown = Array.isArray(oldKnown) ? oldKnown.map(String) : [];
        var selectedIds = new Set(knownIds.filter(function(id) {
            return savedIds === null || savedIds.indexOf(id) !== -1 || oldKnown.indexOf(id) === -1;
        }));

        var tableWrapper = table.parentNode;
        var toolbar = document.createElement('div');
        toolbar.className = 'bs-cart-select-toolbar';
        var allLabel = document.createElement('label');
        var selectAll = document.createElement('input');
        selectAll.type = 'checkbox';
        selectAll.className = 'bs-cart-checkbox';
        selectAll.setAttribute('aria-label', 'Pilih semua produk');
        allLabel.appendChild(selectAll);
        allLabel.appendChild(document.createTextNode('Pilih Semua'));
        toolbar.appendChild(allLabel);
        var status = document.createElement('span');
        status.className = 'bs-cart-select-status';
        toolbar.appendChild(status);
        tableWrapper.insertBefore(toolbar, table);

        var headerRow = table.querySelector('thead tr');
        if (headerRow) {
            var th = document.createElement('th');
            th.className = 'bs-cart-checkbox-head';
            th.textContent = '';
            headerRow.insertBefore(th, headerRow.firstChild);
        }
        var cartChecks = [];
        rows.forEach(function(tr, idx) {
            var data = bsCartSelectionRows[idx];
            if (!data || !data.id) return;
            var td = document.createElement('td');
            td.className = 'bs-cart-checkbox-cell';
            var check = document.createElement('input');
            check.type = 'checkbox';
            check.className = 'bs-cart-checkbox';
            check.value = String(data.id);
            check.checked = selectedIds.has(check.value);
            check.setAttribute('aria-label', 'Pilih produk pada baris ' + (idx + 1));
            td.appendChild(check);
            tr.insertBefore(td, tr.firstChild);
            cartChecks.push(check);
        });

        rows.forEach(function(tr, index) {
            var cell = tr.querySelector('td:not(.bs-cart-checkbox-cell)');
            if (!cell || cell.querySelector('.bs-cart-product')) return;

            var container = document.createElement('div');
            container.className = 'bs-cart-product';
            var thumb = document.createElement('span');
            thumb.className = 'bs-cart-product-image';

            // Gunakan foto yg sama dengan Katalog berdasarkan ID produk.
            var url = bsCartPhotoUrls[index];
            var img = document.createElement('img');
            img.src = url || '';
            img.alt = 'Foto produk ' + cell.textContent.trim();
            img.loading = 'lazy';
            img.decoding = 'async';
            img.addEventListener('error', function() {
                img.remove();
                var errorText = document.createElement('span');
                errorText.className = 'bs-photo-unavailable';
                errorText.textContent = 'Foto tidak tersedia';
                thumb.appendChild(errorText);
            }, {once: true});

            var oldImg = cell.querySelector('img');
            if (!url && oldImg) {
                thumb.appendChild(oldImg);
            } else if (url) {
                thumb.appendChild(img);
            } else {
                var empty = document.createElement('span');
                empty.className = 'bs-photo-unavailable';
                empty.textContent = 'Foto tidak tersedia';
                thumb.appendChild(empty);
            }

            var label = document.createElement('div');
            label.className = 'bs-cart-product-name';
            while (cell.firstChild) label.appendChild(cell.firstChild);
            container.appendChild(thumb);
            container.appendChild(label);
            cell.appendChild(container);
        });

        var layout = document.createElement('div');
        layout.className = 'bs-cart-grid';
        cart.parentNode.insertBefore(layout, cart);
        layout.appendChild(cart);

        var benefits = document.createElement('div');
        benefits.className = 'bs-cart-benefits';
        benefits.innerHTML = '<div><strong>🌿 Kurangi Food Waste</strong><small>Selamatkan roti yg masih layak konsumsi.</small></div>' +
            '<div><strong>✨ Lebih Hemat</strong><small>Nikmati roti berkualitas dengan harga terjangkau.</small></div>' +
            '<div><strong>♡ Dukung Toko Lokal</strong><small>Bantu toko mengurangi makanan terbuang.</small></div>';
        cart.appendChild(benefits);

        var summary = document.createElement('section');
        summary.className = 'bs-checkout-summary';
        summary.setAttribute('aria-label', 'Ringkasan pembelian');
        var title = document.createElement('div');
        title.className = 'bs-summary-title';
        title.innerHTML = '<span class="bs-summary-icon" aria-hidden="true"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3h10l3 3v15H4V3h3"/><path d="M8 9h8m-8 4h8m-8 4h6"/></svg></span><h2>Ringkasan Pembelian</h2>';
        summary.appendChild(title);
        form.classList.add('bs-summary-form');
        form.style.width = '100%';
        form.style.maxWidth = 'none';
        summary.appendChild(form); // moving existing form preserves CSRF, fields, events, and routes

        // Hidden fields di form checkout. Checkbox di tabel berada di LUAR form,
        // sehingga input dipindahkan menjadi hidden untuk dikirim ke Laravel.
        var hiddenHolder = document.createElement('div');
        hiddenHolder.id = 'bs-selected-items-holder';
        form.appendChild(hiddenHolder);


        var price = document.createElement('div');
        price.className = 'bs-summary-total';
        var label = document.createElement('span');
        label.textContent = 'Total Pembayaran';
        var amount = document.createElement('span');
        amount.className = 'bs-summary-amount';
        amount.textContent = (total.textContent || '').replace(/^\s*Total Pembayaran:\s*/i,'').trim();
        price.appendChild(label); price.appendChild(amount);
        var checkoutButton = form.querySelector('.btn-checkout');
        if (checkoutButton) {
            checkoutButton.parentNode.insertBefore(price, checkoutButton);
        } else {
            form.appendChild(price);
        }
        var security = document.createElement('div');
        security.className = 'bs-cart-security';
        security.textContent = '🔒 Pesanan diproses melalui checkout BreadSaver';
        summary.appendChild(security);
        var error = document.createElement('p');
        error.className = 'bs-cart-select-error';
        error.hidden = true;
        error.setAttribute('role', 'alert');
        error.textContent = 'Pilih minimal satu roti sebelum melanjutkan pembayaran.';
        var checkoutTarget = form.querySelector('.btn-checkout');
        if (checkoutTarget) checkoutTarget.insertAdjacentElement('afterend', error);

        function syncSelected() {
            hiddenHolder.replaceChildren();
            var picked = [], count = 0, selectedTotal = 0;
            cartChecks.forEach(function(check, idx) {
                var info = bsCartSelectionRows[idx];
                if (!info) return;
                var tr = check.closest('tr');
                if (tr) tr.classList.toggle('bs-cart-not-selected', !check.checked);
                if (check.checked) {
                    picked.push(String(info.id));
                    count += Number(info.qty || 0);
                    selectedTotal += Number(info.subtotal || 0);
                    var hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'selected_items[]';
                    hidden.value = String(info.id);
                    hiddenHolder.appendChild(hidden);
                }
            });
            saveSelection(picked);
            try { sessionStorage.setItem(rememberKey, JSON.stringify(knownIds)); } catch (e) {}
            selectAll.checked = cartChecks.length > 0 && cartChecks.every(function(c) { return c.checked; });
            selectAll.indeterminate = !selectAll.checked && cartChecks.some(function(c) { return c.checked; });
            status.textContent = picked.length + ' dari ' + cartChecks.length + ' produk dipilih';
            amount.textContent = formatRupiah(selectedTotal);
            if (checkoutTarget) checkoutTarget.disabled = picked.length === 0;
            error.hidden = picked.length > 0;
            // Badge header tetap merepresentasikan jumlah seluruh roti di keranjang.
        }
        cartChecks.forEach(function(check) {
            check.addEventListener('change', syncSelected);
        });
        selectAll.addEventListener('change', function() {
            cartChecks.forEach(function(check) { check.checked = selectAll.checked; });
            syncSelected();
        });
        form.addEventListener('submit', function(event) {
            syncSelected();
            if (!hiddenHolder.querySelector('input[name="selected_items[]"]')) {
                event.preventDefault();
                error.hidden = false;
            }
        });
        syncSelected();
        layout.appendChild(summary);
    }

    // Komponen ini di-include tepat sebelum </body>, jadi struktur tabel dan form sudah ada.
    // Jalankan langsung, TANPA menunggu 'load' seluruh gambar/aset eksternal.
    // Dengan begitu layout modern langsung aktif setelah form +/- me-reload halaman.
    decorate();
    if (!document.querySelector('main.bs-modern-cart-page')) {
        document.addEventListener('DOMContentLoaded', decorate, { once: true });
    }
})();
</script>
