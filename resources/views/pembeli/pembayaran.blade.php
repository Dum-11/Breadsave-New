<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

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

        * {
            box-sizing: border-box;
        }

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
            box-shadow: 0 10px 35px rgba(35,54,95,0.08);
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            color: var(--navy);
            font-size: 27px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: var(--muted);
            font-size: 14px;
        }

        .amount-box {
            text-align: center;
            padding: 24px;
            background: #f6f8fc;
            border-radius: 14px;
            margin-bottom: 22px;
        }

        .amount-label {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 8px;
        }

        .amount {
            color: var(--navy);
            font-size: 32px;
            font-weight: bold;
        }

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

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--muted);
        }

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

        .qr-section h3 {
            color: var(--navy);
            margin: 0 0 15px;
        }

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

        .success-box h3 {
            margin: 0 0 10px;
        }

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

        .btn:hover {
            opacity: 0.88;
        }

        .btn-primary {
            background: var(--navy);
            color: white;
        }

        .btn-pink {
            background: var(--pink);
            color: var(--navy);
        }

        /* TOMBOL CEK STATUS MIDTRANS */
        .btn-check {
            background: var(--green);
            color: white;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .footer {
            text-align: center;
            color: var(--muted);
            font-size: 12px;
            margin-top: 25px;
        }

        @media (max-width: 600px) {
            .payment-card {
                padding: 20px;
                margin: 10px auto;
            }

            .amount {
                font-size: 27px;
            }

            .header h1 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

<div class="payment-card">

    <!-- HEADER -->
    <div class="header">
        <h1>Pembayaran BreadSaver</h1>
        <p class="subtitle">
            Pesanan #{{ $pesanan->id_pesanan }}
        </p>
    </div>

    <!-- ALERT -->
    @if(session('error'))
        <div class="error-box">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="success-box">
            {{ session('success') }}
        </div>
    @endif

    <!-- TOTAL PEMBAYARAN -->
    <div class="amount-box">
        <div class="amount-label">
            Total Pembayaran
        </div>

        <div class="amount">
            Rp{{ number_format(
                $pesanan->total_harga,
                0, ',', '.'
            ) }}
        </div>
    </div>

    <!-- INFORMASI PESANAN -->
    <div class="info">

        <div class="info-row">
            <span class="info-label">
                Metode Pembayaran
            </span>

            <span class="info-value">
                {{ $pesanan->metode }}
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">
                Status Pembayaran
            </span>

            <span class="info-value">
                {{ $pesanan->status_pembayaran }}
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">
                ID Transaksi
            </span>

            <span class="info-value">
                {{ $pesanan->midtrans_order_id ?? '-' }}
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">
                Jadwal Pickup
            </span>

            <span class="info-value">
                {{ $pesanan->waktu_pengambilan
                    ? \Carbon\Carbon::parse(
                        $pesanan->waktu_pengambilan
                      )->format('d M Y, H:i')
                    : '-' }}
            </span>
        </div>

    </div>

    <!-- STATUS PEMBAYARAN -->

    @if($pesanan->status_pembayaran === 'Berhasil')

        <!-- PEMBAYARAN SUKSES -->
        <div class="success-box">
            <h3>Pembayaran Berhasil</h3>

            <p>
                Terima kasih telah melakukan pembayaran.
            </p>

            <p>Kode Pickup:</p>

            <div class="pickup-code">
                {{ $pesanan->kode_pickup ?? 'Belum tersedia' }}
            </div>

            <p>
                Tunjukkan kode ini kepada pegawai
                saat mengambil pesanan.
            </p>
        </div>

    @elseif(
        $pesanan->status_pesanan === 'Dibatalkan' ||
        $pesanan->status_pembayaran === 'Dibatalkan'
    )

        <!-- PESANAN DIBATALKAN -->
        <div class="error-box">
            Pesanan ini telah dibatalkan.
            Pembayaran tidak dapat dilanjutkan.
        </div>

    @elseif($pesanan->metode === 'QRIS')

        @if(!empty($pesanan->qr_url))

            <!-- QRIS DARI MIDTRANS -->
            <div class="qr-section">
                <h3>Scan QRIS Midtrans</h3>

                <img
                    src="{{ $pesanan->qr_url }}"
                    alt="QRIS Midtrans"
                    class="qr-image"
                >

                <p class="qr-caption">
                    QRIS diterbitkan oleh Midtrans.
                    Pembayaran masih menunggu konfirmasi.
                </p>
            </div>

            <div class="notice">
                Ini adalah transaksi Midtrans Sandbox.
                Gunakan simulator pembayaran Midtrans
                untuk menguji transaksi.
                Jangan menggunakan uang sungguhan.
            </div>

        @else

            <!-- BELUM ADA QRIS -->
            <div class="qr-section">
                <h3>QRIS Belum Dibuat</h3>

                <p class="qr-caption">
                    Klik tombol di bawah untuk membuat
                    transaksi QRIS melalui Midtrans.
                </p>
            </div>

            <!-- FORM BUAT QRIS -->
            @if(empty($pesanan->midtrans_order_id))
                <form
                    action="{{ route(
                        'pembeli.midtrans.qris',
                        $pesanan->id_pesanan
                    ) }}"
                    method="POST"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-pink"
                    >
                        Buat QRIS Midtrans
                    </button>
                </form>
            @else
                <div class="notice">
                    Transaksi Midtrans sudah tercatat,
                    tetapi gambar QRIS belum tersedia.
                    Silakan periksa status transaksi.
                </div>
            @endif

        @endif

        <!-- CEK STATUS PEMBAYARAN MIDTRANS -->
        @if(
            $pesanan->status_pembayaran === 'Menunggu Pembayaran'
            && !empty($pesanan->midtrans_order_id)
        )

            <form
                action="{{ route(
                    'pembeli.midtrans.status',
                    $pesanan->id_pesanan
                ) }}"
                method="POST"
            >
                @csrf

                <button
                    type="submit"
                    class="btn btn-check"
                >
                    Cek Status Pembayaran
                </button>
            </form>

            <p class="qr-caption"
               style="text-align:center; margin-top:12px;">
                Klik tombol setelah menyelesaikan
                pembayaran melalui Midtrans Sandbox.
                Status akan diperiksa langsung
                ke server Midtrans.
            </p>

        @endif

    @else

        <!-- METODE LAIN -->
        <div class="notice">
            Integrasi Midtrans untuk metode
            {{ $pesanan->metode }}
            belum tersedia.

            Saat ini kita sedang menyiapkan
            pembayaran QRIS terlebih dahulu.
        </div>

    @endif

    <!-- KEMBALI -->
    <a
        href="{{ route('pembeli.pesanan') }}"
        class="btn btn-primary"
    >
        Kembali ke Pesanan
    </a>

    <div class="footer">
        BreadSaver Payment Gateway
        <br>
        Powered by Midtrans Sandbox
    </div>

</div>

</body>
</html>
