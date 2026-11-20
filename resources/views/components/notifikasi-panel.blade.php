@auth
<style>
.bsn-root{position:fixed;top:16px;right:20px;z-index:9999;font-family:Arial,sans-serif}
.bsn-toggle{position:relative;width:38px;height:38px;display:flex;align-items:center;justify-content:center;border-radius:50%;border:2px solid white;background:#23365f;color:white;cursor:pointer;box-shadow:0 5px 18px rgba(35,54,95,.19)}
.bsn-toggle:hover{background:#344b78}
.bsn-toggle svg{width:19px;height:19px}
.bsn-badge{position:absolute;top:-6px;right:-7px;min-width:18px;height:18px;padding:0 4px;border-radius:12px;background:#ff8fb1;color:#23365f;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:bold}
.bsn-badge[hidden],.bsn-panel[hidden]{display:none!important}
.bsn-panel{position:absolute;top:46px;right:0;width:300px;max-width:calc(100vw - 24px);max-height:360px;background:#fff;border:1px solid #e7eaf1;border-radius:14px;box-shadow:0 15px 45px rgba(35,54,95,.18);overflow:hidden;color:#26324b}
.bsn-header{padding:12px 14px;display:flex;align-items:center;justify-content:space-between;gap:8px;border-bottom:1px solid #e7eaf1}
.bsn-header h3{font-size:14px;margin:0;color:#23365f}
.bsn-read-all{border:0;background:transparent;color:#ad5374;font-size:11px;font-weight:bold;cursor:pointer}
.bsn-list{max-height:300px;overflow:auto}
.bsn-item{display:block;width:100%;padding:11px 14px;border:0;border-bottom:1px solid #edf0f5;background:white;color:#26324b;text-align:left;cursor:pointer}
.bsn-item:hover{background:#f8f9fc}
.bsn-item.unread{background:#fff0f5}
.bsn-item-title{font-size:13px;font-weight:bold;color:#23365f;margin-bottom:5px}
.bsn-item-message{font-size:11px;color:#667085;white-space:normal;line-height:1.5;overflow-wrap:anywhere}
.bsn-item-date{margin-top:7px;font-size:10px;color:#8991a4}
.bsn-empty{padding:24px 14px;font-size:12px;color:#8991a4;text-align:center}
@media(max-width:600px){.bsn-root{top:12px;right:12px}.bsn-panel{width:min(300px,calc(100vw - 24px))}}
</style>

<div class="bsn-root" id="bsnRoot">
    <button type="button" class="bsn-toggle" id="bsnToggle" aria-label="Buka notifikasi" aria-controls="bsnPanel" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
            <path d="M10 21h4"/>
        </svg>
        <span class="bsn-badge" id="bsnBadge" hidden>0</span>
    </button>

    <div class="bsn-panel" id="bsnPanel" hidden>
        <div class="bsn-header">
            <h3>Notifikasi</h3>
            <button type="button" id="bsnReadAll" class="bsn-read-all">Tandai Semua Dibaca</button>
        </div>
        <div class="bsn-list" id="bsnList">
            <div class="bsn-empty">Memuat notifikasi...</div>
        </div>
    </div>
</div>

<script>
(() => {
    const root = document.getElementById('bsnRoot');
    if (!root || root.dataset.initialized === '1') return;
    root.dataset.initialized = '1';

    const toggle = document.getElementById('bsnToggle');
    const panel = document.getElementById('bsnPanel');
    const badge = document.getElementById('bsnBadge');
    const list = document.getElementById('bsnList');
    const readAll = document.getElementById('bsnReadAll');
    const feedUrl = @json(route('notifikasi.feed'));
    const readAllUrl = @json(route('notifikasi.read-all'));
    const readUrlPrefix = @json(url('/notifikasi'));
    const csrf = @json(csrf_token());
    let busy = false;

    async function api(url, method = 'GET') {
        const res = await fetch(url, {
            method,
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf
            }
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    }

    function line(tag, cls, value) {
        const el = document.createElement(tag);
        el.className = cls;
        el.textContent = String(value || '');
        return el;
    }

    function localUrl(value) {
        if (typeof value !== 'string' || !value) return null;
        try {
            const dest = new URL(value, location.origin);
            return dest.origin === location.origin ? dest.href : null;
        } catch (e) { return null; }
    }

    function render(data) {
        const count = Math.max(0, Number(data.belum_dibaca || 0));
        badge.textContent = count > 99 ? '99+' : String(count);
        badge.hidden = !count;
        list.replaceChildren();

        const items = Array.isArray(data.notifikasi) ? data.notifikasi : [];
        if (items.length === 0) {
            list.append(line('div', 'bsn-empty', 'Belum ada notifikasi.'));
            return;
        }

        for (const item of items) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'bsn-item' + (item.dibaca_pada ? '' : ' unread');
            btn.append(line('div', 'bsn-item-title', item.judul));
            btn.append(line('div', 'bsn-item-message', item.pesan));
            btn.append(line('div', 'bsn-item-date', item.created_at));
            btn.addEventListener('click', async () => {
                if (btn.disabled) return;
                btn.disabled = true;
                try {
                    await api(readUrlPrefix + '/' + encodeURIComponent(item.id) + '/read', 'POST');
                    const destination = localUrl(item.tautan);
                    if (destination) window.location.assign(destination);
                    else await load();
                } catch (e) {
                    btn.disabled = false;
                    alert('Gagal membuka notifikasi.');
                }
            });
            list.append(btn);
        }
    }

    async function load() {
        if (busy) return;
        busy = true;
        try { render(await api(feedUrl)); }
        catch (e) { console.warn('Notifikasi belum bisa dimuat:', e); }
        finally { busy = false; }
    }

    toggle.addEventListener('click', () => {
        panel.hidden = !panel.hidden;
        toggle.setAttribute('aria-expanded', String(!panel.hidden));
        if (!panel.hidden) load();
    });

    readAll.addEventListener('click', async () => {
        readAll.disabled = true;
        try { await api(readAllUrl, 'POST'); await load(); }
        catch (e) { alert('Gagal menandai semua notifikasi.'); }
        finally { readAll.disabled = false; }
    });

    document.addEventListener('click', event => {
        if (!root.contains(event.target)) {
            panel.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) load();
    });
    setInterval(() => { if (!document.hidden) load(); }, 15000);
    load();
})();
</script>
@endauth
