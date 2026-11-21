@auth
@if(auth()->user()->role === 'pembeli')
@php
$voucherOptions = \Illuminate\Support\Facades\DB::table('loyalty_vouchers')
    ->where('id_user', auth()->id())
    ->whereNull('dipakai_pesanan')
    ->where('kadaluwarsa_at', '>', now())
    ->get(['kode', 'nominal'])->toArray();
@endphp
<script>
(function(){
    const form=document.querySelector('form[action*="/pembeli/checkout"]');
    if(!form || form.querySelector('[name="voucher_kode"]'))return;
    const codes = @json($voucherOptions);
    if(!codes.length)return;
    const box=document.createElement('div');box.style.cssText='padding:10px;background:#fff7fb;border:1px solid #efdae5;border-radius:9px;margin:10px 0';
    const label=document.createElement('label');label.textContent='Pakai voucher Loyalty (opsional)';
    label.style.cssText='display:block;font-size:12px;font-weight:bold;margin-bottom:6px;color:#304573';
    const select=document.createElement('select');select.name='voucher_kode';
    select.style.cssText='width:100%;max-width:370px;padding:9px;border:1px solid #dce2ee;border-radius:8px';
    const blank=document.createElement('option');blank.value='';blank.textContent='Tanpa voucher';select.appendChild(blank);
    codes.forEach(v=>{const option=document.createElement('option');option.value=v.kode;option.textContent=v.kode+' – Diskon Rp'+Number(v.nominal).toLocaleString('id-ID');select.appendChild(option);});
    box.append(label,select);form.appendChild(box);
})();
</script>
@endif
@endauth
