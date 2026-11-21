@auth
@if(auth()->user()->role === 'pembeli')
<script>
(function () {
    const field = document.querySelector('input[name="waktu_pengambilan"]');
    if (!field) return;
    const form = field.closest('form');
    if (!form) return;
    const wrapper = document.createElement('div');
    wrapper.style.cssText = 'margin:12px 0;padding:12px;border:1px solid #e4e8f1;border-radius:10px;background:#fafbff;max-width:420px';
    const label = document.createElement('label');
    label.textContent = 'Pilih Slot Pickup Tersedia';
    label.style.cssText = 'display:block;font-size:12px;font-weight:bold;margin:0 0 6px;color:#253d69';
    const date = document.createElement('input');
    date.type = 'date';
    date.style.cssText = 'display:block;max-width:220px;padding:9px;border:1px solid #d5dfeb;border-radius:8px;margin-bottom:8px';
    const slots = document.createElement('select');
    slots.style.cssText = 'display:block;width:100%;max-width:350px;padding:9px;border:1px solid #d5dfeb;border-radius:8px';
    const hint = document.createElement('small');
    hint.style.cssText = 'display:block;margin-top:7px;color:#79859b';
    hint.textContent = 'Slot maksimal 5 pesanan, setiap 30 menit (WIB).';
    wrapper.append(label,date,slots,hint);
    field.insertAdjacentElement('afterend', wrapper);
    const before = field.value;
    const today = new Intl.DateTimeFormat('en-CA', {
        timeZone:'Asia/Jakarta',year:'numeric',month:'2-digit',day:'2-digit'
    }).format(new Date());
    const inputDate = /^\d{4}-\d{2}-\d{2}/.exec(before);
    date.value = inputDate ? inputDate[0] : today;
    date.min = today;
    const maxDate = new Date(Date.now() + 7 * 86400000);
    date.max = new Intl.DateTimeFormat('en-CA', {
        timeZone:'Asia/Jakarta',year:'numeric',month:'2-digit',day:'2-digit'
    }).format(maxDate);
    field.type = 'hidden';
    async function update() {
        field.value=''; slots.replaceChildren();
        const placeholder=document.createElement('option');placeholder.textContent='Memuat slot...';placeholder.value='';
        slots.appendChild(placeholder);
        try {
            const url = @json(route('pembeli.slots')) + '?tanggal=' + encodeURIComponent(date.value);
            const result=await fetch(url,{credentials:'same-origin',headers:{Accept:'application/json'}});
            if (!result.ok) throw new Error('HTTP '+result.status);
            const data=await result.json();
            slots.replaceChildren();
            for (const s of data.slots || []) {
                if (!s.active || s.remaining < 1) continue;
                const option=document.createElement('option');
                option.value=s.time;option.textContent=s.label+' WIB • sisa '+s.remaining;
                slots.appendChild(option);
            }
            if (!slots.options.length) {
                const empty=document.createElement('option');empty.value='';
                empty.textContent='Slot penuh / tidak tersedia';slots.appendChild(empty);
            }
            const validOld = Array.from(slots.options).some(o => o.value === before);
            if(validOld) slots.value=before;
            field.value=slots.value;
        } catch(e) {
            slots.replaceChildren();
            const error=document.createElement('option');error.value='';error.textContent='Gagal memuat slot';slots.appendChild(error);
        }
    }
    date.addEventListener('change', update);
    slots.addEventListener('change',()=>{field.value=slots.value;});
    form.addEventListener('submit',function(e){
        if (!field.value) {e.preventDefault();alert('Pilih slot pickup yg masih tersedia.');}
    });
    update();
})();
</script>
@endif
@endauth
