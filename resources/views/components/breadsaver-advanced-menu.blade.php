@auth
<style>
.bs-advanced-menu{position:fixed;right:18px;bottom:77px;z-index:9750;font:12px Arial,sans-serif}
.bs-advanced-menu summary{cursor:pointer;background:#294470;color:#fff;padding:10px 14px;border-radius:23px;box-shadow:0 5px 17px #1d335545;list-style:none;font-weight:bold}
.bs-advanced-menu summary::-webkit-details-marker{display:none}
.bs-advanced-menu nav{position:absolute;bottom:44px;right:0;width:224px;background:#fff;border:1px solid #dbe3f1;border-radius:12px;box-shadow:0 8px 30px #20375333;padding:8px}
.bs-advanced-menu nav a{display:block;color:#25436f;text-decoration:none;padding:10px;border-radius:7px;font-weight:600}
.bs-advanced-menu nav a:hover{background:#fce9f0}
</style>
<details class="bs-advanced-menu"><summary>Fitur Lanjutan ☰</summary><nav>
@if(auth()->user()->role === 'pembeli')
<a href="{{ route('pembeli.rescue-box') }}">Bread Rescue Box</a>
<a href="{{ route('pembeli.loyalty') }}">Poin & Voucher</a>
@elseif(auth()->user()->role === 'admin')
<a href="{{ route('admin.batches') }}">Batch Stok FEFO</a>
<a href="{{ route('admin.slots') }}">Slot Pengambilan</a>
<a href="{{ route('admin.activity') }}">Aktivitas Pegawai</a>
<a href="{{ route('admin.waste-actual') }}">Food Waste Aktual</a>
@elseif(auth()->user()->role === 'pegawai')
<a href="{{ route('pegawai.batches') }}">Lihat Batch FEFO</a>
<a href="{{ route('pegawai.waste-actual') }}">Catat Food Waste</a>
@endif
</nav></details>
@endauth
