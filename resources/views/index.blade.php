
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    {{-- Mengatur lebar halaman mengikuti perangkat.
         Dipakai agar tampilan tidak terlalu lebar di HP.
         Tanpa viewport, browser HP bisa menampilkan versi desktop
         yang diperkecil sehingga teks sulit dibaca. --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BreadSaver - Selamatkan Roti</title>

    <style>
        /* CSS Variables: menyimpan warna yang sering dipakai.
           Keuntungan: kalau warna utama berubah, cukup ubah di sini.
           Dibanding menulis kode warna berulang, cara ini lebih mudah dirawat. */
        :root {
            --navy: #23365f;
            --pink: #ff8fb1;
            --pink-light: #fff0f5;
            --background: #f8f9fc;
            --text: #26324b;
            --muted: #8991a4;
            --white: #ffffff;
            --border: #e7eaf2;
        }

        /* Reset CSS:
           Menghilangkan margin dan padding bawaan browser.
           box-sizing: border-box membuat padding dan border masuk
           dalam perhitungan ukuran elemen.
           Ini lebih mudah daripada menghitung lebar elemen secara manual. */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Pengaturan dasar seluruh halaman.
           Arial dipilih karena sederhana dan tersedia di banyak perangkat.
           Alternatif font lain bisa dipakai, tetapi hasil tampilannya
           dapat berbeda tergantung font yang tersedia. */
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--background);
            color: var(--text);
        }

        /* Menghapus garis bawah tautan dan mengikuti warna teks induknya.
           Cocok untuk tautan navigasi yang tampil seperti menu,
           bukan seperti tautan artikel biasa. */
        a {
            color: inherit;
            text-decoration: none;
        }

        /* Sidebar dibuat fixed agar menu tetap berada di sisi kiri
           ketika halaman utama digulir.
           Kekurangannya: sidebar mengambil ruang layar dan perlu
           penyesuaian khusus pada perangkat kecil. */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            padding: 28px 22px;
            background: var(--navy);
            color: var(--white);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 1000;
        }

        /* Flexbox memusatkan logo secara horizontal dan vertikal.
           margin-bottom memberi jarak antara logo dan menu.
           Lebih fleksibel daripada mengatur posisi dengan margin kiri manual. */
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 56px;
        }

        /* max-width mencegah logo melewati ukuran wadah.
           height: auto mempertahankan rasio gambar agar tidak gepeng.
           object-fit: contain memastikan seluruh logo tetap terlihat. */
        .logo {
            width: 180px;
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        /* Judul kelompok menu dibedakan menggunakan ukuran dan warna.
           Letter-spacing menambah jarak antarkarakter agar label
           terlihat seperti penanda bagian navigasi. */
        .menu-title {
            color: #c4cce0;
            font-size: 12px;
            letter-spacing: 1px;
            margin: 0 10px 18px;
        }

        /* Flexbox menyusun menu dari atas ke bawah.
           gap mengatur jarak antarmenu tanpa perlu margin pada setiap item. */
        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        /* Setiap menu menggunakan flex agar ikon dan teks sejajar.
           min-height menjaga area klik tetap cukup tinggi.
           border-radius membuat sudut menu membulat.
           transition membuat perubahan warna tidak terjadi secara mendadak. */
        .menu-link {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 58px;
            padding: 12px 14px;
            border-radius: 12px;
            color: var(--white);
            font-size: 15px;
            transition: background-color 0.2s, color 0.2s;
        }

        /* Hover hanya mengubah tampilan ketika kursor berada di menu.
           Efek ini membantu pengguna desktop mengetahui elemen yang aktif. */
        .menu-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Class active menandai halaman yang sedang dibuka.
           Pada kode ini, Beranda selalu aktif.
           Jika ingin lebih dinamis, class active dapat ditentukan
           berdasarkan route Laravel yang sedang diakses. */
        .menu-link.active {
            background: var(--pink);
            color: var(--navy);
            font-weight: bold;
        }

        /* Ukuran ikon diseragamkan agar posisi teks konsisten.
           flex-shrink: 0 mencegah ikon mengecil ketika ruang terbatas. */
        .menu-icon {
            width: 28px;
            height: 28px;
            object-fit: contain;
            flex-shrink: 0;
        }

        /* margin-top: auto mendorong bagian login/logout ke bawah
           karena sidebar menggunakan flex-direction: column.
           Cara ini lebih fleksibel daripada memberi posisi top tetap. */
        .sidebar-bottom {
            margin-top: auto;
            padding-top: 28px;
        }

        /* Konten utama digeser sejauh lebar sidebar agar tidak tertutup.
           min-height memastikan area konten minimal setinggi layar.
           Padding memberi ruang antara isi halaman dan tepi layar. */
        .main {
            margin-left: 240px;
            min-height: 100vh;
            padding: 32px;
        }

        /* Header menggunakan flex untuk memisahkan kelompok informasi.
           justify-content: space-between berguna jika nanti ditambahkan
           tombol profil atau notifikasi di sebelah kanan. */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        /* Judul utama diberi ukuran besar agar hierarki informasi jelas. */
        .welcome-title {
            font-size: 30px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 10px;
        }

        /* Teks pendukung dibuat lebih lembut agar tidak mengalahkan judul.
           line-height meningkatkan keterbacaan teks yang lebih panjang. */
        .welcome-subtitle {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.6;
        }

        /* Banner mengambil lebar wadah dan memiliki jarak ke bagian produk.
           Tinggi gambar tidak dipaksakan supaya rasio aslinya tetap terjaga. */
        .banner {
            width: 100%;
            margin-bottom: 32px;
        }

        .banner img {
            display: block;
            width: 100%;
            height: auto;
            border-radius: 16px;
            object-fit: cover;
        }

        /* Flexbox memisahkan judul bagian dan jumlah produk.
           gap menjaga jarak ketika kedua elemen berdekatan. */
        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }

        .section-title {
            font-size: 24px;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .section-subtitle {
            font-size: 14px;
            color: var(--muted);
        }

        /* Label jumlah produk dibuat seperti kapsul.
           Nilainya berasal dari koleksi Laravel, bukan angka statis. */
        .product-count {
            padding: 10px 16px;
            border-radius: 30px;
            background: var(--pink-light);
            color: #ed6590;
            font-size: 13px;
            white-space: nowrap;
        }

        /* CSS Grid mengatur kartu dalam kolom yang menyesuaikan lebar layar.
           repeat(auto-fill, minmax(220px, 1fr)) berarti kolom berukuran
           minimal 220px dan ruang tersisa dibagi secara fleksibel.
           Ini lebih adaptif daripada menentukan empat kolom secara tetap. */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 22px;
        }

        /* Kartu menyembunyikan bagian gambar yang keluar dari sudut kartu.
           Border membedakan kartu dari latar halaman.
           Transition menghaluskan efek hover. */
        .product-card {
            overflow: hidden;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            transition: box-shadow 0.2s, transform 0.2s;
        }

        /* translateY mengangkat kartu sedikit saat kursor diarahkan.
           Box-shadow menambah kedalaman visual.
           Efek ini hanya dekoratif dan tidak mengubah data produk. */
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(35, 54, 95, 0.08);
        }

        /* Wadah gambar memiliki tinggi seragam agar kartu terlihat rapi.
           Flexbox memusatkan gambar yang ukurannya berbeda-beda. */
        .product-image-container {
            height: 190px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: #ffffff;
        }

        /* Gambar dibatasi ukuran maksimalnya tanpa dipaksa memenuhi wadah.
           contain lebih cocok daripada cover untuk gambar produk
           karena seluruh bentuk produk tetap terlihat. */
        .product-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Padding memberi ruang antara isi kartu dan batas kartu. */
        .product-info {
            padding: 18px;
        }

        /* overflow-wrap mencegah nama produk panjang merusak lebar kartu. */
        .product-name {
            color: var(--navy);
            font-size: 17px;
            margin-bottom: 12px;
            overflow-wrap: anywhere;
        }

        /* Harga asli dan diskon bisa berada pada baris yang sama.
           flex-wrap memungkinkan harga pindah baris ketika ruang sempit. */
        .price-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 14px;
        }

        /* Harga asli dicoret untuk membedakannya dari harga diskon.
           Harga asli hanya ditampilkan jika nilainya lebih tinggi. */
        .price-original {
            color: var(--muted);
            font-size: 13px;
            text-decoration: line-through;
        }

        .price-discount {
            color: #ed6590;
            font-size: 18px;
            font-weight: bold;
        }

        /* Detail produk disusun vertikal agar stok dan tanggal
           kedaluwarsa mudah dibaca. */
        .product-detail {
            display: flex;
            flex-direction: column;
            gap: 8px;
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 16px;
        }

        /* Tombol menggunakan display:block dan width:100%
           agar memenuhi lebar area informasi produk.
           cursor:pointer menunjukkan bahwa elemen dapat diklik. */
        .buy-button {
            display: block;
            width: 100%;
            padding: 12px;
            text-align: center;
            background: var(--pink);
            color: var(--navy);
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 9px;
            cursor: pointer;
        }

        .buy-button:hover {
            background: #ff7da5;
        }

        /* Empty state ditampilkan saat belum ada produk.
           Flexbox memusatkan gambar dan pesan supaya ruang kosong
           tidak terlihat seperti halaman yang rusak. */
        .empty-state {
            width: 100%;
            min-height: 295px;
            padding: 35px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .empty-state img {
            width: 110px;
            height: 110px;
            object-fit: contain;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 20px;
            color: var(--navy);
            margin-bottom: 12px;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        /* Footer dipisahkan menggunakan garis atas dan jarak.
           date('Y') pada Blade menghasilkan tahun saat halaman dirender. */
        .footer {
            margin-top: 44px;
            padding: 24px 0;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
            text-align: center;
        }

        /* RESPONSIVE TABLET
           Aturan ini aktif pada layar maksimal 900px.
           Sidebar diperkecil supaya konten utama tetap mendapat ruang.
           Media query lebih tepat daripada membuat halaman terpisah
           khusus untuk setiap ukuran layar. */
        @media (max-width: 900px) {
            .sidebar {
                width: 210px;
                padding: 24px 16px;
            }

            /* margin-left harus sama dengan lebar sidebar.
               Jika tidak, konten bisa tertutup atau memiliki jarak berlebih. */
            .main {
                margin-left: 210px;
                padding: 24px;
            }

            .logo {
                width: 160px;
            }

            .welcome-title {
                font-size: 26px;
            }
        }

        /* RESPONSIVE HP
           Pada layar maksimal 600px, sidebar berubah menjadi ikon saja.
           Cara ini menghemat ruang dibanding mempertahankan menu teks.
           Konsekuensinya, ikon perlu cukup jelas karena label disembunyikan. */
        @media (max-width: 600px) {
            .sidebar {
                width: 76px;
                padding: 22px 10px;
                align-items: center;
            }

            .logo-container {
                margin-bottom: 35px;
            }

            .logo {
                width: 55px;
            }

            .menu-title {
                display: none;
            }

            .menu {
                width: 100%;
                align-items: center;
            }

            .menu-link {
                width: 54px;
                min-height: 54px;
                justify-content: center;
                padding: 10px;
            }

            /* Hanya teks di dalam menu yang disembunyikan.
               Ikon tetap ditampilkan sebagai navigasi ringkas. */
            .menu-link span {
                display: none;
            }

            .menu-icon {
                width: 27px;
                height: 27px;
            }

            .sidebar-bottom {
                width: 100%;
                display: flex;
                justify-content: center;
            }

            /* Margin konten mengikuti lebar sidebar versi HP. */
            .main {
                margin-left: 76px;
                padding: 20px 14px;
            }

            .welcome-title {
                font-size: 23px;
            }

            .welcome-subtitle {
                font-size: 13px;
            }

            /* Judul dan jumlah produk tidak dipaksa sejajar
               ketika ruang horizontal pada HP terbatas. */
            .section-heading {
                align-items: flex-start;
            }

            .section-title {
                font-size: 20px;
            }

            /* Satu kartu per baris membuat kartu tetap mudah dibaca.
               Jika dipaksa dua kolom, gambar dan informasi bisa terlalu sempit. */
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    {{-- SIDEBAR: navigasi utama BreadSaver.
         <aside> dipakai karena menu ini merupakan bagian pendamping
         konten utama, sedangkan konten utama berada di dalam <main>. --}}
    <aside class="sidebar">

        <div class="logo-container">
            {{-- url('/') menghasilkan URL halaman utama Laravel.
                 asset() menghasilkan URL file statis di folder public.
                 Keduanya berbeda: url untuk alamat halaman,
                 asset untuk gambar, CSS, atau file publik. --}}
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}"
                     alt="BreadSaver" class="logo">
            </a>
        </div>

        <p class="menu-title">MENU UTAMA</p>

        <nav class="menu">
            {{-- url('/') digunakan untuk menuju halaman utama.
                 Class active menandai menu Beranda sebagai aktif. --}}
            <a href="{{ url('/') }}" class="menu-link active">
                <img src="{{ asset('images/icon-home.png') }}"
                     alt="" class="menu-icon">
                <span>Beranda</span>
            </a>

            {{-- route('katalog') memakai nama route Laravel.
                 Named route lebih mudah dirawat daripada menulis URL
                 secara manual karena URL dapat berubah tanpa harus
                 mengganti semua tautan yang menggunakannya. --}}
            <a href="{{ route('katalog') }}" class="menu-link">
                <img src="{{ asset('images/icon-product.png') }}"
                     alt="" class="menu-icon">
                <span>Katalog Produk</span>
            </a>

            {{-- url('/pesanan') memakai alamat langsung.
                 Alternatifnya adalah route('pesanan') jika route tersebut
                 sudah diberi nama di file routes/web.php. --}}
            <a href="{{ url('/pesanan') }}" class="menu-link">
                <img src="{{ asset('images/icon-orders.png') }}"
                     alt="" class="menu-icon">
                <span>Pesanan</span>
            </a>

            <a href="{{ url('/keranjang') }}" class="menu-link">
                <img src="{{ asset('images/icon-keranjang.png') }}"
                     alt="" class="menu-icon">
                <span>Keranjang</span>
            </a>
        </nav>

        <div class="sidebar-bottom">

            {{-- @guest hanya menampilkan elemen untuk pengunjung
                 yang belum login.
                 Ini adalah directive Blade yang menggunakan status
                 autentikasi Laravel, bukan pengecekan manual JavaScript. --}}
            @guest
                <a href="{{ route('login') }}" class="menu-link">
                    <img src="{{ asset('images/icon-login.png') }}"
                         alt="" class="menu-icon">
                    <span>Login</span>
                </a>
            @endguest

            {{-- @auth hanya menampilkan bagian ini ketika pengguna login.
                 Form POST dipakai untuk logout karena logout mengubah
                 status autentikasi dan tidak sebaiknya dilakukan
                 melalui tautan GET biasa. --}}
            @auth
                <form action="{{ route('logout') }}" method="POST">

                    {{-- @csrf menyertakan token perlindungan CSRF Laravel.
                         Token membantu mencegah permintaan palsu dari situs lain.
                         Jangan dihapus hanya demi menghemat satu baris. --}}
                    @csrf

                    <button type="submit" class="menu-link"
                            style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left;">
                        <img src="{{ asset('images/icon-login.png') }}"
                             alt="" class="menu-icon">
                        <span>Logout</span>
                    </button>
                </form>
            @endauth

        </div>
    </aside>

    {{-- MAIN: isi utama halaman, terpisah dari sidebar. --}}
    <main class="main">

        <header class="topbar">
            <div>
                <h1 class="welcome-title">Selamat Datang!</h1>
                <p class="welcome-subtitle">
                    Temukan roti favoritmu dengan harga lebih hemat.
                </p>
            </div>
        </header>

        {{-- asset() digunakan agar banner diambil dari folder public/images.
             Pastikan file bread-banner.png benar-benar tersedia di sana. --}}
        <section class="banner">
            <img src="{{ asset('images/bread-banner.png') }}"
                 alt="Banner BreadSaver">
        </section>

        <section class="products-section">
            <div class="section-heading">
                <div>
                    <h2 class="section-title">Roti Diskon Hari Ini</h2>
                    <p class="section-subtitle">
                        Pilihan roti yang tersedia untuk kamu.
                    </p>
                </div>

                {{-- isset() memeriksa apakah variabel tersedia.
                     count() menghitung jumlah item koleksi.
                     Operator ternary ?: memilih hasil berdasarkan kondisi.
                     Jika $produks tidak tersedia, jumlah yang ditampilkan 0.
                     Namun, controller tetap sebaiknya selalu mengirim
                     variabel produks agar struktur data konsisten. --}}
                <span class="product-count">
                    {{ isset($produks) ? $produks->count() : 0 }} Produk
                </span>
            </div>

            {{-- @if menentukan tampilan berdasarkan kondisi.
                 Pemeriksaan isset mencegah akses variabel yang belum tersedia.
                 count() > 0 memastikan kartu hanya dibuat jika ada produk. --}}
            @if(isset($produks) && $produks->count() > 0)

                <div class="product-grid">

                    {{-- @foreach mengulang setiap produk dari koleksi.
                         Dibanding menulis kartu satu per satu, perulangan
                         membuat halaman otomatis mengikuti isi database. --}}
                    @foreach($produks as $produk)

                        @php
                            /*
                             * Mengubah nilai harga menjadi float agar operasi
                             * perbandingan harga dilakukan sebagai angka.
                             *
                             * ?? adalah null coalescing operator.
                             * Jika atribut tidak tersedia atau bernilai null,
                             * nilai pengganti setelah ?? akan digunakan.
                             *
                             * Harga diskon memakai harga asli sebagai default.
                             * Ini mencegah harga menjadi 0 hanya karena harga
                             * diskon belum diisi, tetapi aturan bisnis yang
                             * sebenarnya tetap perlu ditentukan di backend.
                             *
                             * Stok diubah menjadi integer karena jumlah barang
                             * biasanya dihitung dalam bilangan bulat.
                             */
                            $hargaAsli = (float) ($produk->harga_asli ?? 0);
                            $hargaDiskon = (float) ($produk->harga_diskon ?? $hargaAsli);
                            $stok = (int) ($produk->stok ?? 0);
                        @endphp

                        <article class="product-card">

                            <div class="product-image-container">
                                {{-- Saat ini semua produk menggunakan gambar
                                     bread.png yang sama.
                                     Jika setiap produk memiliki gambar sendiri,
                                     gunakan atribut gambar dari database.
                                     alt berisi nama produk untuk aksesibilitas. --}}
                                <img
                                    src="{{ asset('images/bread.png') }}"
                                    alt="{{ $produk->nama_produk }}"
                                    class="product-image"
                                >
                            </div>

                            <div class="product-info">

                                {{-- Blade {{ }} melakukan escaping output HTML
                                     untuk membantu mencegah XSS.
                                     Lebih aman daripada mencetak input pengguna
                                     menggunakan {!! !!} tanpa sanitasi. --}}
                                <h3 class="product-name">
                                    {{ $produk->nama_produk }}
                                </h3>

                                <div class="price-row">

                                    {{-- Harga asli hanya ditampilkan jika
                                         lebih besar daripada harga diskon.
                                         Perbandingan ini menghindari coretan
                                         harga ketika tidak ada potongan. --}}
                                    @if($hargaAsli > $hargaDiskon)
                                        <span class="price-original">
                                            Rp{{ number_format($hargaAsli, 0, ',', '.') }}
                                        </span>
                                    @endif

                                    {{-- number_format memformat angka menjadi
                                         format Rupiah tanpa angka desimal.
                                         Parameter: jumlah desimal, pemisah
                                         desimal, dan pemisah ribuan. --}}
                                    <span class="price-discount">
                                        Rp{{ number_format($hargaDiskon, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="product-detail">

                                    {{-- Stok sudah disimpan dalam variabel
                                         integer agar mudah digunakan kembali. --}}
                                    <span>Stok: {{ $stok }}</span>

                                    {{-- empty() memeriksa apakah nilai kosong.
                                         Tanggal hanya ditampilkan jika tersedia.
                                         Carbon::parse mengubah nilai tanggal
                                         menjadi objek tanggal yang bisa diformat.
                                         format('d-m-Y') menghasilkan hari-bulan-tahun.
                                         Jika data tanggal tidak valid, parse()
                                         dapat menimbulkan error sehingga validasi
                                         tanggal sebaiknya dilakukan di backend. --}}
                                    @if(!empty($produk->expired_at))
                                        <span>
                                            Kedaluwarsa:
                                            {{ \Carbon\Carbon::parse($produk->expired_at)->format('d-m-Y') }}
                                        </span>
                                    @endif

                                </div>

                                {{-- Tombol ini mengarahkan pengguna ke login.
                                     Ini bukan proses pembelian sebenarnya.
                                     Untuk pembelian setelah login, tautan dapat
                                     diganti dengan route detail produk atau
                                     endpoint tambah ke keranjang yang sesuai. --}}
                                <a href="{{ route('login') }}" class="buy-button">
                                    Login untuk Membeli
                                </a>

                            </div>
                        </article>

                    @endforeach
                </div>

            @else

                {{-- Empty state muncul ketika koleksi kosong atau tidak tersedia.
                     Memberi pesan yang jelas lebih baik daripada membiarkan
                     bagian produk kosong tanpa penjelasan. --}}
                <div class="empty-state">
                    <img src="{{ asset('images/bread.png') }}" alt="Roti">
                    <h3>Belum Ada Roti Tersedia</h3>
                    <p>
                        Saat ini belum ada produk roti yang dijual.
                        Silakan cek kembali nanti.
                    </p>
                </div>

            @endif
        </section>

        <footer class="footer">
            {{-- date('Y') mengambil tahun saat halaman dirender.
                 Jadi tahun tidak perlu diganti secara manual setiap tahun. --}}
            © {{ date('Y') }} BreadSaver. Selamatkan Roti, Kurangi Food Waste.
        </footer>

    </main>

</body>
</html>
