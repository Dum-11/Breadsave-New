<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - BreadSaver</title>

    <style>
        :root {
            --navy: #23365f;
            --pink: #ff8fb1;
            --background: #f6f8fc;
            --text: #26324b;
            --muted: #8991a4;
            --border: #e7eaf2;
            --green: #237844;
            --red: #b53a3a;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 30px 15px;
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        .payment-card {
            max-width: 520px;
            margin: 35px auto;
            padding: 30px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(35, 54, 95, 0.08);
        }

        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { color: var(--navy); font-size: 27px; margin-bottom: 8px; }
        .subtitle { color: var(--muted); font-size: 14px; }

        .amount-box {
            text-align: center;
            padding: 24px;
            background: #f6f8fc;
            border-radius: 14px;
            margin-bottom: 22px;
        }

        .amount-label { color: var(--muted); font-size: 13px; margin-bottom: 8px; }
        .amount { color: var(--navy); font-size: 32px; font-weight: bold; }

        .info {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 22px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 10px 0;
            font-size: 14px;
            border-bottom: 1px solid var(--border);
        }

        .info-row:last-child { border-bottom: none; }
        .info-label { color: var(--muted); }
        .info-value {
            color: var(--navy);
            font-weight: bold;
            text-align: right;
            overflow-wrap: anywhere;
        }

        .qr-section {
            text-align: center;
            padding: 20px;
            border: 1px solid var(--border);
            border-radius: 14px;
            margin-bottom: 20px;
        }

        .qr-section h3 { color: var(--navy); margin: 0 0 15px; }
        .qr-image {
            width: 260px;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 15px;
        }

        .qr-caption {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .notice {
            background: #fff4d8;
            color: #926000;
            padding: 14px;
            border-radius: 10px;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .success-box {
            background: #e2f7e9;
            color: var(--green);
            padding: 22px;
            text-align: center;
            border-radius: 14px;
            margin-bottom: 20px;
        }

        .success-box h3 { margin: 0 0 10px; }
        .pickup-code {
            font-size: 25px;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 14px 0;
        }

        .error-box {
            background: #ffe6e6;
            color: var(--red);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .btn {
            display: block;
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            margin-top: 12px;
            transition: opacity 0.2s;
        }

        .btn:hover { opacity: 0.88; }
        .btn-primary { background: var(--navy); color: white; }
        .btn-pink { background: var(--pink); color: var(--navy); }
        .btn-check { background: var(--green); color: white; }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; }

        .footer {
            text-align: center;
            color: var(--muted);
            font-size: 12px;
            margin-top: 25px;
        }

        /* COUNTDOWN PEMBAYARAN */
        .payment-timer {
            border: 1px solid #e4e9f4;
            border-radius: 14px;
            background: #f6f8fc;
            color: var(--navy);
            text-align: center;
            padding: 20px;
            margin-bottom: 22px;
        }

        .payment-timer-label { margin: 0 0 8px; font-size: 14px; font-weight: bold; }
        .payment-timer-clock {
            font-size: 40px;
            font-weight: 800;
            letter-spacing: 1px;
            font-variant-numeric: tabular-nums;
        }
        .payment-timer-hint { margin: 8px 0 0; color: #747f99; line-height: 1.6; font-size: 13px; }
        .payment-timer-bar {
            height: 8px;
            background: #e1e6f1;
            border-radius: 999px;
            margin-top: 15px;
            overflow: hidden;
        }
        .payment-timer-fill {
            width: 100%;
            height: 100%;
            background: var(--pink);
            border-radius: inherit;
            transition: width 0.25s linear;
        }
        .payment-timer.urgent .payment-timer-clock { color: #b45309; }
        .payment-timer.expired .payment-timer-clock { color: var(--red); }
        .payment-timer.expired .payment-timer-fill { width: 0; }

        @media (max-width: 600px) {
            .payment-card { padding: 20px; margin: 10px auto; }
            .amount { font-size: 27px; }
            .header h1 { font-size: 23px; }
        }
    </style>
</head>
<body>

@php
    $statusPembayaran = $pesanan->status_pembayaran ?? null;
    $statusPesanan = $pesanan->status_pesanan ?? ($pesanan->status ?? null);
    $menungguPembayaran = $statusPembayaran === 'Menunggu Pembayaran' && $statusPesanan !== 'Dibatalkan';
    $deadlinePembayaran = !empty($pesanan->payment_expires_at)
        ? \Carbon\Carbon::parse($pesanan->payment_expires_at)->timestamp
        : null;
    $tampilkanTimer = $menungguPembayaran && $deadlinePembayaran !== null;
    $punyaTransaksiMidtrans = !empty($pesanan->midtrans_order_id);
@endphp

<div class="payment-card">

    <!-- HEADER -->
    <div class="header">
        <h1>Pembayaran BreadSaver</h1>
        <p class="subtitle">Pesanan #{{ $pesanan->id_pesanan }}</p>
    </div>

    <!-- ALERT -->
    @if(session('error'))
        <div class="error-box">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="success-box">{{ session('success') }}</div>
    @endif

    <!-- COUNTDOWN: hanya muncul untuk pesanan yg masih menunggu pembayaran -->
    @if($tampilkanTimer)
        <section class="payment-timer" id="paymentTimer"
                 data-deadline="{{ $deadlinePembayaran }}"
                 data-server-now="{{ now()->timestamp }}"
                 aria-label="Sisa waktu pembayaran">
            <p class="payment-timer-label">Sisa Waktu Pembayaran</p>
            <div class="payment-timer-clock" id="paymentTimerClock" role="timer">--:--</div>
            <p class="payment-timer-hint" id="paymentTimerHint">
                Selesaikan pembayaran sebelum waktu habis.
            </p>
            <div class="payment-timer-bar" aria-hidden="true">
                <div class="payment-timer-fill" id="paymentTimerFill"></div>
            </div>
        </section>
    @endif

    <!-- TOTAL PEMBAYARAN -->
    <div class="amount-box">
        <div class="amount-label">Total Pembayaran</div>
        <div class="amount">
            Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
        </div>
    </div>

    <!-- INFORMASI PESANAN -->
    <div class="info">
        <div class="info-row">
            <span class="info-label">Metode Pembayaran</span>
            <span class="info-value">{{ $pesanan->metode }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status Pembayaran</span>
            <span class="info-value">{{ $pesanan->status_pembayaran }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">ID Transaksi</span>
            <span class="info-value">{{ $pesanan->midtrans_order_id ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jadwal Pickup</span>
            <span class="info-value">
                {{ $pesanan->waktu_pengambilan
                    ? \Carbon\Carbon::parse($pesanan->waktu_pengambilan)->format('d M Y, H:i')
                    : '-' }}
            </span>
        </div>
    </div>

    <!-- STATUS PEMBAYARAN -->
    @if($statusPembayaran === 'Berhasil')
        <div class="success-box">
            <h3>Pembayaran Berhasil</h3>
            <p>Terima kasih telah melakukan pembayaran.</p>
            <p>Kode Pickup:</p>
            <div class="pickup-code">{{ $pesanan->kode_pickup ?? 'Belum tersedia' }}</div>
            <p>Tunjukkan kode ini kepada pegawai saat mengambil pesanan.</p>
        </div>

    @elseif($statusPesanan === 'Dibatalkan' || $statusPembayaran === 'Dibatalkan')
        <div class="error-box">
            Pesanan ini telah dibatalkan. Pembayaran tidak dapat dilanjutkan.
        </div>

    @elseif($pesanan->metode === 'QRIS')
        @if(!empty($pesanan->qr_url))
            <div class="qr-section">
                <h3>Scan QRIS Midtrans</h3>
                <img src="{{ $pesanan->qr_url }}" alt="QRIS Midtrans" class="qr-image">
                <p class="qr-caption">
                    QRIS diterbitkan oleh Midtrans. Pembayaran masih menunggu konfirmasi.
                </p>
            </div>
            <div class="notice">
                Ini adalah transaksi Midtrans Sandbox. Gunakan simulator pembayaran
                Midtrans untuk menguji transaksi. Jangan menggunakan uang sungguhan.
            </div>
        @else
            <div class="qr-section">
                <h3>QRIS Belum Dibuat</h3>
                <p class="qr-caption">
                    Klik tombol di bawah untuk membuat transaksi QRIS melalui Midtrans.
                </p>
            </div>

            @if(!$punyaTransaksiMidtrans)
                <form action="{{ route('pembeli.midtrans.qris', $pesanan->id_pesanan) }}" method="POST">
                    @csrf
                    <button type="submit" id="btnBuatQris" class="btn btn-pink">
                        Buat QRIS Midtrans
                    </button>
                </form>
            @else
                <div class="notice">
                    Transaksi Midtrans sudah tercatat, tetapi gambar QRIS belum tersedia.
                    Silakan periksa status transaksi.
                </div>
            @endif
        @endif

        <!-- CEK STATUS MANUAL TETAP TERSEDIA -->
        @if($menungguPembayaran && $punyaTransaksiMidtrans)
            <form action="{{ route('pembeli.midtrans.status', $pesanan->id_pesanan) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-check">Cek Status Pembayaran</button>
            </form>
            <p class="qr-caption" style="text-align:center; margin-top:12px;">
                Status akan diperiksa langsung ke server Midtrans.
                Jika belum berubah otomatis, gunakan tombol di atas.
            </p>
        @endif
    @else
        <div class="notice">
            Integrasi Midtrans untuk metode {{ $pesanan->metode }} belum tersedia.
            Saat ini kita sedang menyiapkan pembayaran QRIS terlebih dahulu.
        </div>
    @endif

    <!-- KEMBALI -->
    <a href="{{ route('pembeli.pesanan') }}" class="btn btn-primary">Kembali ke Pesanan</a>

    <div class="footer">
        BreadSaver Payment Gateway<br>
        Powered by Midtrans Sandbox
    </div>
</div>

@if($tampilkanTimer)
<script>
(function () {
    const timer = document.getElementById('paymentTimer');
    if (!timer) return;

    const deadlineMs = Number(timer.dataset.deadline) * 1000;
    const serverStartMs = Number(timer.dataset.serverNow) * 1000;
    const clockStart = performance.now();
    const clock = document.getElementById('paymentTimerClock');
    const hint = document.getElementById('paymentTimerHint');
    const fill = document.getElementById('paymentTimerFill');
    const createQrButton = document.getElementById('btnBuatQris');
    const paymentDuration = 10 * 60;

    function updateCountdown() {
        // Gunakan waktu server, bukan jam laptop pembeli.
        const serverNowMs = serverStartMs + (performance.now() - clockStart);
        const remaining = Math.max(0, Math.ceil((deadlineMs - serverNowMs) / 1000));
        const minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
        const seconds = String(remaining % 60).padStart(2, '0');

        clock.textContent = minutes + ':' + seconds;
        fill.style.width = Math.min(100, remaining / paymentDuration * 100) + '%';
        timer.classList.toggle('urgent', remaining > 0 && remaining <= 120);
        timer.classList.toggle('expired', remaining === 0);

        if (remaining === 0) {
            hint.textContent = 'Batas waktu habis. Sistem sedang memeriksa pembayaran dan memproses pembatalan jika belum dibayar.';
            if (createQrButton) createQrButton.disabled = true;
        } else if (remaining <= 120) {
            hint.textContent = 'Waktu hampir habis. Selesaikan pembayaran sekarang.';
        } else {
            hint.textContent = 'Selesaikan pembayaran sebelum waktu habis.';
        }
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) updateCountdown();
    });
})();
</script>
@endif

{{-- Auto-sync Midtrans bersifat opsional: hanya aktif bila route sudah terpasang. --}}
@if($menungguPembayaran && $punyaTransaksiMidtrans && \Illuminate\Support\Facades\Route::has('pembeli.pembayaran.auto-sync'))
<script>
(function () {
    const syncUrl = @json(route('pembeli.pembayaran.auto-sync', $pesanan->id_pesanan));
    const csrf = @json(csrf_token());
    let checking = false;
    let finished = false;

    async function checkPayment() {
        if (checking || finished || document.hidden) return;
        checking = true;
        try {
            const response = await fetch(syncUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf
                }
            });
            if (!response.ok) return;
            const result = await response.json();
            if (result.status === 'Berhasil' || result.status === 'Dibatalkan') {
                finished = true;
                window.location.reload();
            }
        } catch (error) {
            console.warn('Pemeriksaan Midtrans otomatis belum berhasil:', error);
        } finally {
            checking = false;
        }
    }

    setTimeout(checkPayment, 1500);
    setInterval(checkPayment, 10000);
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) checkPayment();
    });
})();
</script>
@endif


@include('components.notifikasi-panel')
</body>
</html>
