{{--
    BreadSaver - Countdown Pembayaran
    Pasang dengan @include('pembeli.partials.countdown-pembayaran')
    Membutuhkan $pesanan dari halaman pembayaran.
    Hanya menampilkan waktu; pembatalan dan stok tetap ditangani server.
--}}
@php
    $statusPembayaranCountdown = $pesanan->status_pembayaran ?? null;
    $statusPesananCountdown = $pesanan->status_pesanan ?? ($pesanan->status ?? null);
    $deadlineCountdown = !empty($pesanan->payment_expires_at)
        ? \Carbon\Carbon::parse($pesanan->payment_expires_at)->timestamp
        : null;
    $tampilkanCountdown = $statusPembayaranCountdown === 'Menunggu Pembayaran'
        && $statusPesananCountdown !== 'Dibatalkan'
        && $deadlineCountdown !== null;
@endphp

@if($tampilkanCountdown)
    <style>
        .bs-payment-timer {
            border: 1px solid #e7eaf2;
            background: #f6f8fc;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 22px;
            text-align: center;
            color: #23365f;
        }
        .bs-payment-timer__label {
            margin: 0 0 8px;
            font-weight: 700;
            font-size: 14px;
        }
        .bs-payment-timer__clock {
            font-variant-numeric: tabular-nums;
            font-weight: 800;
            font-size: 38px;
            line-height: 1.25;
            letter-spacing: 1px;
        }
        .bs-payment-timer__hint {
            margin: 8px 0 0;
            font-size: 13px;
            line-height: 1.6;
            color: #747f99;
        }
        .bs-payment-timer__bar {
            height: 7px;
            border-radius: 999px;
            background: #e1e6f1;
            overflow: hidden;
            margin-top: 15px;
        }
        .bs-payment-timer__fill {
            height: 100%;
            background: #ff8fb1;
            width: 100%;
            border-radius: inherit;
            transition: width 0.3s linear;
        }
        .bs-payment-timer.is-urgent .bs-payment-timer__clock {
            color: #b45309;
        }
        .bs-payment-timer.is-expired .bs-payment-timer__clock {
            color: #b53a3a;
        }
        .bs-payment-timer.is-expired .bs-payment-timer__fill {
            width: 0;
        }
    </style>

    <section
        id="bs-payment-timer-{{ $pesanan->id_pesanan }}"
        class="bs-payment-timer"
        data-deadline="{{ $deadlineCountdown }}"
        data-server-now="{{ now()->timestamp }}"
        aria-label="Sisa waktu pembayaran"
    >
        <p class="bs-payment-timer__label">Sisa Waktu Pembayaran</p>
        <div class="bs-payment-timer__clock" data-timer-clock>--:--</div>
        <p class="bs-payment-timer__hint" data-timer-hint>
            Selesaikan pembayaran sebelum batas waktu habis.
        </p>
        <div class="bs-payment-timer__bar" aria-hidden="true">
            <div class="bs-payment-timer__fill" data-timer-fill></div>
        </div>
    </section>

    <script>
    (function () {
        const timer = document.getElementById(@json('bs-payment-timer-' . $pesanan->id_pesanan));
        if (!timer) return;

        const deadlineMs = Number(timer.dataset.deadline) * 1000;
        const serverNowAtLoadMs = Number(timer.dataset.serverNow) * 1000;
        const performanceAtLoadMs = performance.now();
        const clock = timer.querySelector('[data-timer-clock]');
        const hint = timer.querySelector('[data-timer-hint]');
        const fill = timer.querySelector('[data-timer-fill]');
        const standardDurationSeconds = 10 * 60;
        let intervalId = null;

        function refreshTimer() {
            // Jam browser tidak dipakai agar countdown tidak meleset jika jam PC salah.
            const currentServerMs = serverNowAtLoadMs + (performance.now() - performanceAtLoadMs);
            const secondsLeft = Math.max(0, Math.ceil((deadlineMs - currentServerMs) / 1000));
            const minutes = String(Math.floor(secondsLeft / 60)).padStart(2, '0');
            const seconds = String(secondsLeft % 60).padStart(2, '0');

            clock.textContent = minutes + ':' + seconds;
            fill.style.width = Math.min(100, secondsLeft / standardDurationSeconds * 100) + '%';
            timer.classList.toggle('is-urgent', secondsLeft > 0 && secondsLeft <= 120);

            if (secondsLeft === 0) {
                timer.classList.add('is-expired');
                hint.textContent = 'Batas pembayaran telah habis. Sistem sedang memverifikasi status transaksi dan pembatalan pesanan.';
                if (intervalId !== null) clearInterval(intervalId);
            }
        }

        refreshTimer();
        if (!timer.classList.contains('is-expired')) {
            intervalId = setInterval(refreshTimer, 1000);
        }
        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) refreshTimer();
        });
    })();
    </script>
@endif
