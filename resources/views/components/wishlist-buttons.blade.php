{{-- Tambahkan sekali sebelum </body> pada Beranda dan Katalog Pembeli. --}}
@auth
@if(auth()->user()->role === 'pembeli')
<style>
    .bsw-product-heart{
        position:absolute!important;z-index:9;top:12px;right:12px;width:39px;height:39px;
        display:flex;align-items:center;justify-content:center;border-radius:50%;
        border:1px solid #ffc6d8;background:rgba(255,255,255,.97);color:#e75d91;
        box-shadow:0 2px 11px rgba(0,0,0,.12);cursor:pointer;padding:7px;
        transition:transform .12s ease,background .15s ease;
    }
    .bsw-product-heart:hover{transform:scale(1.08)}
    .bsw-product-heart[aria-pressed="true"]{background:#ff8fb1;border-color:#ff8fb1;color:white}
    .bsw-product-heart svg{display:block;width:21px;height:21px;stroke:currentColor;stroke-width:2;fill:none;stroke-linecap:round;stroke-linejoin:round}
    .bsw-product-heart[aria-pressed="true"] svg{fill:currentColor}
    .bsw-product-heart:disabled{opacity:.7;cursor:wait}
    #bsw-wishlist-toast{position:fixed;bottom:23px;right:22px;z-index:99999;max-width:270px;
        padding:11px 15px;border-radius:10px;background:#203761;color:white;font:13px Arial,sans-serif;
        box-shadow:0 5px 22px rgba(0,0,0,.2)}
    #bsw-wishlist-toast[hidden]{display:none!important}
</style>
<div id="bsw-wishlist-toast" role="status" aria-live="polite" hidden></div>
<script>
(function () {
    'use strict';
    const listing = Array.from(document.querySelectorAll('.product-card'));
    if (!listing.length) return;

    const baseUrl = @json(url('/pembeli/wishlist'));
    const idsUrl = @json(route('pembeli.wishlist.ids'));
    const csrf = @json(csrf_token());
    const toast = document.getElementById('bsw-wishlist-toast');
    const buttons = new Map();
    let toastTimeout;

    function notify(message) {
        if (!toast) return;
        toast.textContent = message;
        toast.hidden = false;
        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => { toast.hidden = true; }, 2500);
    }

    function extractId(card) {
        if (card.dataset.wishlistId && /^\d+$/.test(card.dataset.wishlistId)) {
            return card.dataset.wishlistId;
        }
        // Cadangan bagi kartu lama yang belum punya data-wishlist-id.
        const form = Array.from(card.querySelectorAll('form[action]'))
            .find(f => f.action.includes('keranjang'));
        if (!form) return null;
        const match = new URL(form.action, location.origin).pathname.match(/\/(\d+)\/?$/);
        return match ? match[1] : null;
    }

    function setSelected(button, saved) {
        button.setAttribute('aria-pressed', saved ? 'true' : 'false');
        button.setAttribute('title', saved ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist');
        button.setAttribute('aria-label', saved ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist');
    }

    function makeButton(id, card) {
        const parent = card.querySelector('.product-image-container') || card;
        if (getComputedStyle(parent).position === 'static') parent.style.position = 'relative';
        const button = document.createElement('button');
        button.className = 'bsw-product-heart';
        button.type = 'button';
        button.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z"/></svg>';
        setSelected(button, false);
        button.addEventListener('click', async () => {
            if (button.disabled) return;
            const isSaved = button.getAttribute('aria-pressed') === 'true';
            button.disabled = true;
            try {
                const response = await fetch(baseUrl + '/' + encodeURIComponent(id), {
                    method: isSaved ? 'DELETE' : 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf
                    }
                });
                if (!response.ok) throw new Error('HTTP ' + response.status);
                const result = await response.json();
                // Produk yang sama bisa muncul beberapa kali dalam satu halaman.
                (buttons.get(id) || []).forEach(b => setSelected(b, Boolean(result.saved)));
                notify(result.message || 'Wishlist diperbarui.');
            } catch (error) {
                notify('Gagal menyimpan Wishlist. Coba lagi.');
            } finally {
                button.disabled = false;
            }
        });
        parent.appendChild(button);
        if (!buttons.has(id)) buttons.set(id, []);
        buttons.get(id).push(button);
    }

    listing.forEach(card => {
        if (card.querySelector('.bsw-product-heart')) return;
        const id = extractId(card);
        if (id) makeButton(id, card);
    });
    if (!buttons.size) return;

    fetch(idsUrl, {credentials:'same-origin',headers:{Accept:'application/json'}})
        .then(res => { if (!res.ok) throw new Error('Gagal memuat Wishlist'); return res.json(); })
        .then(data => {
            const selected = new Set((data.ids || []).map(String));
            buttons.forEach((bs, id) => bs.forEach(b => setSelected(b, selected.has(String(id)))));
        })
        .catch(() => notify('Status Wishlist belum bisa dimuat.'));
})();
</script>
@endif
@endauth
