
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"> <!-- Mengatur encoding agar karakter tampil dengan benar -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Membuat tampilan menyesuaikan ukuran layar -->
    <title>BreadSaver - Selamatkan Roti</title> <!-- Judul pada tab browser -->

    <style>
        /* Reset CSS agar setiap browser memulai dengan jarak yang konsisten */
        * {
            margin: 0; /* Menghapus jarak luar bawaan */
            padding: 0; /* Menghapus jarak dalam bawaan */
            box-sizing: border-box; /* Padding dan border ikut dihitung dalam ukuran elemen */
        }

        /* Variabel warna agar warna desain mudah diubah dari satu tempat */
        :root {
            --navy: #23365f; /* Warna utama biru navy */
            --pink: #ff8fb1; /* Warna pink utama */
            --pink-light: #fff0f5; /* Pink muda untuk latar */
            --background: #f8f9fc; /* Warna latar halaman */
            --text: #26324b; /* Warna teks utama */
            --muted: #8991a4; /* Warna teks sekunder */
            --white: #ffffff; /* Warna putih */
        }

        /* Mengatur tampilan dasar seluruh halaman */
        body {
            font-family: Arial, sans-serif; /* Jenis huruf halaman */
            background: var(--background); /* Menggunakan warna latar dari variabel */
            color: var(--text); /* Mengatur warna teks utama */
        }

        /* Menghapus garis bawah pada tautan */
        a {
            text-decoration: none;
        }

        /* Membuat layout utama: sidebar di kiri dan konten di kanan */
        .layout {
            display: flex; /* Menyusun sidebar dan konten secara horizontal */
            min-height: 100vh; /* Tinggi minimal mengikuti tinggi layar */
        }

        /* Mengatur panel navigasi di sebelah kiri */
        .sidebar {
            width: 240px; /* Menetapkan lebar sidebar */
            background: var(--navy); /* Memberikan latar navy */
            color: white; /* Warna teks sidebar */
            padding: 25px 18px; /* Jarak dalam atas-bawah dan kiri-kanan */
            flex-shrink: 0; /* Mencegah sidebar mengecil */
        }

        /* Mengatur posisi logo di dalam sidebar */
        .logo-container {
            text-align: center; /* Memusatkan logo */
            margin-bottom: 35px; /* Memberi jarak di bawah logo */
        }

        /* Mengatur ukuran gambar logo PNG */
        .logo {
            display: block; /* Membuat gambar menjadi elemen blok */
            width: 100%; /* Memakai lebar wadah yang tersedia */
            max-width: 195px; /* Membatasi ukuran maksimum logo */
            height: auto; /* Menjaga proporsi gambar */
            margin: auto; /* Memusatkan gambar */
        }

        /* Mengatur judul kelompok menu sidebar */
        .nav-title {
            color: #aab5d0; /* Warna teks judul menu */
            font-size: 11px; /* Ukuran tulisan kecil */
            margin: 22px 10px 12px; /* Jarak atas, samping, dan bawah */
            text-transform: uppercase; /* Mengubah tulisan menjadi huruf kapital */
            letter-spacing: 1px; /* Memberi jarak antarhuruf */
        }

        /* Mengatur bentuk dan posisi setiap tautan navigasi */
        .nav-link {
            display: flex; /* Menyejajarkan ikon dan teks */
            align-items: center; /* Memusatkan ikon dan teks secara vertikal */
            gap: 12px; /* Jarak antara ikon dan teks */
            color: #e2e7f2; /* Warna teks menu */
            padding: 13px 12px; /* Ruang di dalam tombol menu */
            border-radius: 10px; /* Membulatkan sudut tombol */
            margin-bottom: 7px; /* Jarak antar-menu */
            font-size: 14px; /* Ukuran teks menu */
            transition: 0.2s; /* Menghaluskan perubahan tampilan */
        }

        /* Memberikan efek ketika pointer berada di atas menu */
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1); /* Latar putih transparan */
        }

        /* Menandai menu yang sedang aktif */
        .nav-link.active {
            background: var(--pink); /* Latar pink untuk menu aktif */
            color: var(--navy); /* Teks menu aktif berwarna navy */
            font-weight: bold; /* Menebalkan tulisan menu aktif */
        }

        /* Mengatur ukuran ikon PNG pada menu */
        .nav-icon {
            width: 27px; /* Lebar ikon */
            height: 27px; /* Tinggi ikon */
            object-fit: contain; /* Menjaga gambar utuh tanpa terpotong */
            flex-shrink: 0; /* Mencegah ikon mengecil */
        }

        /* Mengatur area konten utama di sebelah sidebar */
        .main {
            flex: 1; /* Mengisi sisa ruang halaman */
            min-width: 0; /* Mencegah konten memaksa layout melebar */
            padding: 32px; /* Jarak konten dari tepi */
        }

        /* Mengatur bagian judul halaman dan tombol login */
        .topbar {
            display: flex; /* Menyusun teks dan tombol dalam satu baris */
            justify-content: space-between; /* Memisahkan teks dan tombol */
            align-items: center; /* Menyejajarkan keduanya secara vertikal */
            gap: 15px; /* Jarak minimal antarelemen */
            margin-bottom: 28px; /* Jarak ke bagian berikutnya */
        }

        /* Mengatur judul sambutan */
        .topbar h1 {
            font-size: 27px; /* Ukuran judul */
            margin-bottom: 8px; /* Jarak judul ke deskripsi */
        }

        /* Mengatur deskripsi di bawah judul sambutan */
        .topbar p {
            color: var(--muted); /* Warna teks sekunder */
            font-size: 14px; /* Ukuran deskripsi */
            line-height: 1.6; /* Jarak antarbaris */
        }

        /* Mengatur tampilan tombol login */
        .login-btn {
            display: inline-block; /* Memungkinkan padding diterapkan dengan baik */
            background: var(--navy); /* Latar tombol navy */
            color: white; /* Teks tombol putih */
            padding: 12px 20px; /* Ruang dalam tombol */
            border-radius: 9px; /* Membulatkan sudut tombol */
            font-size: 14px; /* Ukuran tulisan */
            font-weight: bold; /* Menebalkan tulisan */
            white-space: nowrap; /* Mencegah teks tombol turun baris */
        }

        /* Membuat kotak informasi tentang BreadSaver */
        .welcome {
            background: white; /* Latar kotak putih */
            padding: 24px; /* Jarak dalam kotak */
            border-radius: 16px; /* Membulatkan sudut kotak */
            margin-bottom: 25px; /* Jarak ke banner */
            border: 1px solid #edf0f6; /* Garis tepi tipis */
        }

        /* Mengatur judul kotak informasi */
        .welcome h2 {
            font-size: 21px; /* Ukuran judul */
            margin-bottom: 10px; /* Jarak ke paragraf */
        }

        /* Mengatur paragraf kotak informasi */
        .welcome p {
            color: var(--muted); /* Warna teks sekunder */
            line-height: 1.7; /* Jarak antarbaris */
            font-size: 14px; /* Ukuran teks */
        }

        /* Mengatur wadah banner promosi */
        .banner {
            margin-bottom: 35px; /* Jarak banner ke daftar produk */
            border-radius: 16px; /* Membulatkan sudut banner */
            overflow: hidden; /* Memotong bagian gambar yang keluar dari sudut wadah */
            background: var(--pink-light); /* Warna latar jika gambar transparan */
        }

        /* Membuat gambar banner memenuhi lebar wadah */
        .banner-image {
            display: block; /* Menghilangkan ruang kosong bawaan gambar inline */
            width: 100%; /* Gambar memenuhi lebar banner */
            height: auto; /* Mempertahankan rasio gambar */
        }

        /* Mengatur judul bagian daftar produk */
        .section-header {
            display: flex; /* Menyusun judul dan jumlah produk dalam satu baris */
            justify-content: space-between; /* Memisahkan judul dan jumlah produk */
            align-items: center; /* Menyejajarkan elemen secara vertikal */
            gap: 15px; /* Jarak antarelemen */
            margin-bottom: 20px; /* Jarak ke kartu produk */
        }

        .section-header h2 {
            font-size: 22px; /* Ukuran judul daftar produk */
        }

        .section-header p {
            color: var(--muted); /* Warna deskripsi */
            font-size: 13px; /* Ukuran deskripsi */
            line-height: 1.6; /* Jarak antarbaris */
            margin-top: 7px; /* Jarak dari judul */
        }

        /* Membuat label jumlah produk */
        .product-count {
            background: var(--pink-light); /* Latar pink muda */
            color: #d64d7b; /* Warna teks pink tua */
            padding: 9px 13px; /* Ruang dalam label */
            border-radius: 20px; /* Membuat bentuk kapsul */
            font-size: 12px; /* Ukuran teks label */
            white-space: nowrap; /* Mencegah label turun baris */
        }

        /* Menyusun kartu produk dalam tiga kolom */
        .products {
            display: grid; /* Menggunakan CSS Grid */
            grid-template-columns: repeat(3, minmax(0, 1fr)); /* Tiga kolom dengan lebar seimbang */
            gap: 20px; /* Jarak horizontal dan vertikal antarkartu */
        }

        /* Mengatur tampilan setiap kartu produk */
        .product-card {
            background: white; /* Latar kartu putih */
            border: 1px solid #edf0f6; /* Garis tepi kartu */
            border-radius: 15px; /* Membulatkan sudut kartu */
            padding: 18px; /* Ruang dalam kartu */
            transition: transform 0.2s, box-shadow 0.2s; /* Menghaluskan animasi kartu */
        }

        /* Memberikan efek ketika pointer berada di atas kartu */
        .product-card:hover {
            transform: translateY(-4px); /* Mengangkat kartu sedikit ke atas */
            box-shadow: 0 8px 25px rgba(35, 54, 95, 0.08); /* Menambahkan bayangan halus */
        }

        /* Mengatur kotak tempat gambar roti */
        .product-image {
            height: 160px; /* Tinggi wadah gambar */
            background: var(--pink-light); /* Latar pink muda */
            border-radius: 12px; /* Membulatkan sudut wadah */
            display: flex; /* Memudahkan posisi gambar */
            justify-content: center; /* Memusatkan gambar secara horizontal */
            align-items: center; /* Memusatkan gambar secara vertikal */
            margin-bottom: 17px; /* Jarak ke informasi produk */
            overflow: hidden; /* Menahan gambar agar tidak keluar wadah */
        }

        /* Menjaga gambar roti tetap utuh di dalam kotak */
        .bread-image {
            width: 90%; /* Lebar gambar relatif terhadap wadah */
            height: 90%; /* Tinggi gambar relatif terhadap wadah */
            object-fit: contain; /* Mempertahankan proporsi gambar */
        }

        /* Mengatur label persentase diskon */
        .discount-label {
            display: inline-block; /* Membuat label mengikuti ukuran teks */
            background: #ffe4ec; /* Latar label pink pucat */
            color: #d64d7b; /* Warna tulisan diskon */
            font-size: 11px; /* Ukuran tulisan */
            font-weight: bold; /* Menebalkan tulisan */
            padding: 6px 10px; /* Ruang dalam label */
            border-radius: 20px; /* Membentuk label kapsul */
            margin-bottom: 12px; /* Jarak ke nama produk */
        }

        /* Mengatur nama produk */
        .product-card h3 {
            font-size: 17px; /* Ukuran nama produk */
            margin-bottom: 12px; /* Jarak ke harga */
            overflow-wrap: anywhere; /* Memungkinkan nama panjang turun baris */
        }

        /* Mengatur harga sebelum diskon */
        .price-old {
            color: var(--muted); /* Warna harga lama dibuat lebih redup */
            font-size: 13px; /* Ukuran harga lama */
            text-decoration: line-through; /* Memberikan garis coret */
            margin-bottom: 5px; /* Jarak ke harga diskon */
        }

        /* Mengatur harga setelah diskon */
        .price-new {
            color: var(--navy); /* Warna harga diskon navy */
            font-size: 20px; /* Ukuran harga diskon */
            font-weight: bold; /* Menegaskan harga yang dibayar */
            margin-bottom: 15px; /* Jarak ke informasi stok */
        }

        /* Mengatur informasi stok dan kedaluwarsa */
        .product-info {
            color: var(--muted); /* Warna informasi sekunder */
            font-size: 12px; /* Ukuran teks informasi */
            line-height: 1.9; /* Jarak antarbaris */
            margin-bottom: 15px; /* Jarak ke tombol pembelian */
        }

        /* Mengatur tombol login pada setiap kartu produk */
        .buy-btn {
            display: block; /* Membuat tombol memenuhi lebar kartu */
            text-align: center; /* Memusatkan teks tombol */
            padding: 12px; /* Ruang dalam tombol */
            border-radius: 9px; /* Membulatkan sudut tombol */
            background: var(--navy); /* Latar tombol navy */
            color: white; /* Warna teks putih */
            font-size: 13px; /* Ukuran teks */
            font-weight: bold; /* Menebalkan teks */
            transition: 0.2s; /* Menghaluskan perubahan warna */
        }

        /* Mengubah warna tombol saat pointer berada di atasnya */
        .buy-btn:hover,
        .login-btn:hover {
            background: #344b7d; /* Navy yang sedikit lebih terang */
        }

        /* Mengatur tampilan ketika database belum memiliki produk */
        .empty-state {
            background: white; /* Latar putih */
            padding: 35px 20px; /* Ruang dalam kotak */
            border: 1px solid #edf0f6; /* Garis tepi */
            border-radius: 15px; /* Sudut membulat */
            text-align: center; /* Memusatkan isi */
            grid-column: 1 / -1; /* Memakai seluruh kolom grid */
        }

        /* Mengatur gambar roti pada kondisi daftar kosong */
        .empty-image {
            display: block; /* Membuat gambar menjadi blok */
            width: 130px; /* Lebar gambar */
            height: 130px; /* Tinggi gambar */
            object-fit: contain; /* Menjaga gambar tidak terpotong */
            margin: 0 auto 15px; /* Memusatkan gambar dan memberi jarak bawah */
        }

        .empty-state h3 {
            margin-bottom: 10px; /* Jarak judul ke deskripsi */
        }

        .empty-state p {
            color: var(--muted); /* Warna deskripsi */
            font-size: 14px; /* Ukuran deskripsi */
            line-height: 1.7; /* Jarak antarbaris */
        }

        /* Mengatur bagian footer di bawah konten */
        footer {
            margin-top: 40px; /* Jarak dari konten di atas */
            padding-top: 20px; /* Jarak teks dari garis atas */
            border-top: 1px solid #e7eaf1; /* Garis pemisah footer */
            color: var(--muted); /* Warna teks footer */
            font-size: 12px; /* Ukuran tulisan footer */
            text-align: center; /* Memusatkan teks */
        }

        /* Penyesuaian layout untuk layar dengan lebar maksimal 1100px */
        @media (max-width: 1100px) {
            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr)); /* Mengubah produk menjadi dua kolom */
            }

            .main {
                padding: 25px; /* Mengurangi ruang konten */
            }
        }

        /* Penyesuaian tampilan untuk tablet dan HP */
        @media (max-width: 700px) {
            .layout {
                flex-direction: column; /* Menyusun sidebar di atas konten */
            }

            .sidebar {
                width: 100%; /* Sidebar memenuhi lebar layar */
                padding: 18px; /* Mengurangi ruang dalam sidebar */
            }

            .logo {
                max-width: 170px; /* Mengecilkan logo di layar kecil */
            }

            .logo-container {
                margin-bottom: 20px; /* Mengurangi jarak bawah logo */
            }

            .nav-title {
                margin-top: 15px; /* Mengurangi jarak atas judul menu */
            }

            .main {
                padding: 20px 15px; /* Mengurangi padding konten */
            }

            .topbar h1 {
                font-size: 21px; /* Mengecilkan judul halaman */
            }

            .topbar p {
                font-size: 12px; /* Mengecilkan deskripsi */
            }

            .welcome {
                padding: 20px; /* Mengurangi ruang kotak informasi */
            }

            .welcome h2 {
                font-size: 19px; /* Mengecilkan judul informasi */
            }

            .products {
                grid-template-columns: 1fr; /* Menampilkan satu produk per baris */
            }

            .product-image {
                height: 190px; /* Menambah tinggi gambar pada kartu HP */
            }

            .section-header h2 {
                font-size: 19px; /* Mengecilkan judul daftar produk */
            }
        }

        /* Penyesuaian tambahan untuk layar yang sangat kecil */
        @media (max-width: 400px) {
            .topbar {
                align-items: flex-start; /* Menyejajarkan elemen dari bagian atas */
            }

            .login-btn {
                padding: 10px 13px; /* Mengecilkan tombol login */
                font-size: 12px; /* Mengecilkan teks tombol */
            }

            .banner {
                border-radius: 10px; /* Mengurangi pembulatan banner */
            }
        }
    </style>
</head>

<body>
    <!-- Layout utama yang membungkus sidebar dan konten -->
    <div class="layout">

        <!-- Sidebar berisi logo dan menu navigasi -->
        <aside class="sidebar">

            <!-- Logo mengarah kembali ke halaman beranda -->
            <div class="logo-container">
                <a href="/">
                    <img
                        src="{{ asset('images/logo.png') }}" 
                        alt="BreadSaver" 
                        class="logo"
                    >
                </a>
            </div>

            <!-- Kelompok menu utama -->
            <p class="nav-title">Menu Utama</p>

            <!-- Tautan menuju halaman beranda -->
            <a href="/" class="nav-link active">
                <img
                    src="{{ asset('images/icon-home.png') }}"
                    alt=""
                    class="nav-icon"
                >
                <span>Beranda</span>
            </a>

            <!-- Tautan menuju bagian daftar produk pada halaman ini -->
            <a href="#produk" class="nav-link">
                <img
                    src="{{ asset('images/icon-product.png') }}"
                    alt=""
                    class="nav-icon"
                >
                <span>Produk Roti</span>
            </a>

            <!-- Tautan menuju halaman login Laravel -->
            <a href="{{ route('login') }}" class="nav-link">
                <img
                    src="{{ asset('images/icon-login.png') }}"
                    alt=""
                    class="nav-icon"
                >
                <span>Login</span>
            </a>

            <!-- Kelompok menu informasi -->
            <p class="nav-title">Tentang Kami</p>

            <!-- Tautan menuju kotak informasi BreadSaver -->
            <a href="#tentang" class="nav-link">
                <img
                    src="{{ asset('images/icon-product.png') }}"
                    alt=""
                    class="nav-icon"
                >
                <span>Tentang BreadSaver</span>
            </a>

        </aside>

        <!-- Area konten utama website -->
        <main class="main">

            <!-- Bagian sambutan dan tombol login -->
            <header class="topbar">
                <div>
                    <h1>Selamat Datang!</h1>
                    <p>Temukan roti favoritmu dengan harga lebih hemat.</p>
                </div>

                <!-- route('login') mengambil URL dari route bernama login -->
                <a href="{{ route('login') }}" class="login-btn">
                    Login
                </a>
            </header>

            <!-- Informasi singkat mengenai tujuan BreadSaver -->
            <section class="welcome" id="tentang">
                <h2>Selamatkan Roti, Kurangi Food Waste</h2>
                <p>
                    BreadSaver membantu kamu mendapatkan roti dengan harga
                    diskon sekaligus mengurangi pemborosan makanan.
                    Temukan pilihan roti yang tersedia hari ini!
                </p>
            </section>

            <!-- Banner promosi menggunakan gambar PNG yang sudah dibuat -->
            <section class="banner">
                <img
                    src="{{ asset('images/bread-banner.png') }}"
                    alt="Flash Sale Roti BreadSaver"
                    class="banner-image"
                >
            </section>

            <!-- Bagian daftar produk dari database -->
            <section id="produk">

                <!-- Judul bagian dan jumlah produk yang tersedia -->
                <div class="section-header">
                    <div>
                        <h2>Roti Diskon Hari Ini</h2>
                        <p>Pilihan roti yang tersedia untuk kamu.</p>
                    </div>

                    <!-- count() menghitung jumlah produk dalam koleksi -->
                    <span class="product-count">
                        {{ $produks->count() }} Produk
                    </span>
                </div>

                <!-- Wadah grid untuk seluruh kartu produk -->
                <div class="products">

                    {{-- Mengulang produk; jika koleksi kosong, bagian @empty akan ditampilkan --}}
                    @forelse ($produks as $produk)

                        <!-- Satu kartu untuk satu produk -->
                        <article class="product-card">

                            <!-- Ilustrasi PNG untuk gambar produk -->
                            <div class="product-image">
                                <img
                                    src="{{ asset('images/bread.png') }}"
                                    alt="Roti {{ $produk->nama_produk }}"
                                    class="bread-image"
                                >
                            </div>

                            {{-- Menghitung persentase diskon dan mencegah pembagian dengan nol --}}
                            @php
                                $diskon = $produk->harga_asli > 0
                                    ? round(
                                        (($produk->harga_asli - $produk->harga_diskon)
                                        / $produk->harga_asli) * 100
                                    )
                                    : 0;
                            @endphp

                            <!-- Label persentase diskon -->
                            <span class="discount-label">
                                Hemat {{ max(0, $diskon) }}%
                            </span>

                            <!-- Nama produk diambil dari database -->
                            <h3>{{ $produk->nama_produk }}</h3>

                            <!-- Harga sebelum diskon dengan format rupiah -->
                            <p class="price-old">
                                Rp {{ number_format($produk->harga_asli, 0, ',', '.') }}
                            </p>

                            <!-- Harga setelah diskon dengan format rupiah -->
                            <p class="price-new">
                                Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}
                            </p>

                            <!-- Informasi stok dan tanggal kedaluwarsa -->
                            <div class="product-info">
                                Stok: {{ $produk->stok }}<br>

                                Kedaluwarsa:
                                {{ $produk->expired_at
                                    ? \Carbon\Carbon::parse($produk->expired_at)->format('d-m-Y')
                                    : '-' }}
                            </div>

                            <!-- Pengguna diarahkan ke login sebelum melanjutkan pembelian -->
                            <a href="{{ route('login') }}" class="buy-btn">
                                Login untuk Membeli
                            </a>

                        </article>

                    {{-- Ditampilkan jika database tidak memiliki produk --}}
                    @empty

                        <div class="empty-state">

                            <!-- Gambar pengganti saat produk belum tersedia -->
                            <img
                                src="{{ asset('images/bread.png') }}"
                                alt="Roti"
                                class="empty-image"
                            >

                            <h3>Belum Ada Roti Tersedia</h3>

                            <p>
                                Saat ini belum ada produk roti yang dijual.
                                Silakan cek kembali nanti.
                            </p>

                        </div>

                    {{-- Menutup blok pengulangan Blade --}}
                    @endforelse

                </div>
            </section>

            <!-- Footer menampilkan tahun saat ini dan slogan website -->
            <footer>
                © {{ date('Y') }} BreadSaver.
                Selamatkan Roti, Kurangi Food Waste.
            </footer>

        </main>
    </div>
</body>
</html>
