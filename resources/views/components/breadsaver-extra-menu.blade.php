@auth
@if(in_array(auth()->user()->role, ['admin', 'pegawai'], true))
<style>
.bs-extra-menu{position:fixed;bottom:22px;right:22px;z-index:9800;font:13px Arial,sans-serif}
.bs-extra-button{background:#253d69;color:#fff;border:1px solid #fff;border-radius:24px;padding:11px 15px;cursor:pointer;box-shadow:0 4px 16px rgba(20,40,80,.24);font-weight:bold}
.bs-extra-panel{position:absolute;bottom:49px;right:0;width:230px;padding:8px;background:#fff;box-shadow:0 8px 30px rgba(20,40,80,.2);border:1px solid #e5e8f0;border-radius:13px}
.bs-extra-panel a{display:block;color:#243960;padding:11px 13px;text-decoration:none;border-radius:7px;font-weight:600}
.bs-extra-panel a:hover{background:#fff0f5}
.bs-extra-panel[hidden]{display:none!important}
</style>
<div class="bs-extra-menu" id="bs-extra-menu">
    <button class="bs-extra-button" type="button" id="bs-extra-open" aria-expanded="false" aria-controls="bs-extra-panel">Fitur Food Waste ☰</button>
    <nav class="bs-extra-panel" id="bs-extra-panel" hidden>
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.foodwaste') }}">Dashboard Food Waste</a>
            <a href="{{ route('admin.stock-priority') }}">Stok Prioritas</a>
            <a href="{{ route('admin.report-export.xlsx') }}">Download Laporan Excel</a>
            <a href="{{ route('admin.report-export.pdf') }}">Download Laporan PDF</a>
        @else
            <a href="{{ route('pegawai.stock-priority') }}">Produk Hampir Kedaluwarsa</a>
        @endif
    </nav>
</div>
<script>
(function(){
 var root=document.getElementById('bs-extra-menu'),btn=document.getElementById('bs-extra-open'),panel=document.getElementById('bs-extra-panel');
 if(!root||!btn||!panel)return;
 btn.addEventListener('click',function(){panel.hidden=!panel.hidden;btn.setAttribute('aria-expanded',String(!panel.hidden));});
 document.addEventListener('click',function(ev){if(!root.contains(ev.target)){panel.hidden=true;btn.setAttribute('aria-expanded','false');}});
})();
</script>
@endif
@endauth
