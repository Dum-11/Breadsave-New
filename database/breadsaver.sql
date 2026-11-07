-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Okt 2026 pada 07.32
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `breadsaver`
--

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `customer`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `customer` (
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanans`
--

CREATE TABLE `detail_pesanans` (
  `id_detail` bigint(20) UNSIGNED NOT NULL,
  `id_pesanan` bigint(20) UNSIGNED NOT NULL,
  `id_produk` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `jual`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `jual` (
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang_items`
--

CREATE TABLE `keranjang_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_produk` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `keranjang_items`
--

INSERT INTO `keranjang_items` (`id`, `id_user`, `id_produk`, `jumlah`, `created_at`, `updated_at`) VALUES
(5, 3, 2, 1, '2026-10-27 23:32:08', '2026-10-27 23:32:08');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `memberi_nilai`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `memberi_nilai` (
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2026_10_14_071732_create_breadsaver_tables', 1),
(5, '2026_10_27_160433_create_keranjang_items_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanans`
--

CREATE TABLE `pesanans` (
  `id_pesanan` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `tanggal_pesan` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('Menunggu Konfirmasi','Diproses','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu Konfirmasi',
  `total_harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pesanans`
--

INSERT INTO `pesanans` (`id_pesanan`, `id_user`, `tanggal_pesan`, `status`, `total_harga`, `created_at`, `updated_at`) VALUES
(1, 3, '2026-10-21 15:41:29', 'Diproses', 12000.00, '2026-10-21 01:41:29', '2026-10-27 07:38:07'),
(2, 3, '2026-10-28 06:12:24', 'Diproses', 10000.00, '2026-10-27 23:12:24', '2026-10-27 23:12:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produks`
--

CREATE TABLE `produks` (
  `id_produk` bigint(20) UNSIGNED NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga_asli` decimal(10,2) NOT NULL,
  `harga_diskon` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `expired_at` datetime NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `produks`
--

INSERT INTO `produks` (`id_produk`, `nama_produk`, `harga_asli`, `harga_diskon`, `stok`, `deskripsi`, `expired_at`, `foto`, `created_at`, `updated_at`) VALUES
(2, 'Roti Coklat Spesial', 15000.00, 10000.00, 20, NULL, '2026-10-21 09:58:13', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `produk_view`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `produk_view` (
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ratings`
--

CREATE TABLE `ratings` (
  `id_rating` bigint(20) UNSIGNED NOT NULL,
  `id_pesanan` bigint(20) UNSIGNED NOT NULL,
  `bintang` tinyint(4) NOT NULL,
  `ulasan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `staff`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `staff` (
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('pembeli','pegawai','admin') NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `role`, `no_hp`, `created_at`, `updated_at`) VALUES
(1, 'Admin Toko', 'admin@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, NULL, NULL),
(2, 'Pegawai Toko', 'pegawai@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pegawai', NULL, '2026-10-21 08:02:13', '2026-10-21 08:02:13'),
(3, 'Pembeli Setia', 'pembeli@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pembeli', NULL, '2026-10-21 08:02:13', '2026-10-21 08:02:13');

-- --------------------------------------------------------

--
-- Struktur untuk view `customer`
--
DROP TABLE IF EXISTS `customer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `customer`  AS SELECT `user`.`id_user` AS `id_customer`, `user`.`nama` AS `nama`, `user`.`email` AS `email`, `user`.`password` AS `password`, `user`.`no_hp` AS `no_hp`, 'Yogyakarta' AS `alamat` FROM `user` WHERE `user`.`role` = 'pembeli' ;

-- --------------------------------------------------------

--
-- Struktur untuk view `jual`
--
DROP TABLE IF EXISTS `jual`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `jual`  AS SELECT `p`.`id_pesanan` AS `id_pesanan`, `p`.`id_user` AS `id_customer`, `dp`.`id_produk` AS `id_produk`, concat('ORD-',`p`.`id_pesanan`) AS `kode_pesanan`, `p`.`total_harga` AS `total_harga`, `p`.`tanggal_pesan` AS `tanggal`, `p`.`status` AS `status`, `dp`.`subtotal`/ `dp`.`jumlah` AS `harga_satuan`, `dp`.`jumlah` AS `jumlah` FROM (`pesanan` `p` join `detail_pesanan` `dp` on(`p`.`id_pesanan` = `dp`.`id_pesanan`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `memberi_nilai`
--
DROP TABLE IF EXISTS `memberi_nilai`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `memberi_nilai`  AS SELECT `r`.`id_rating` AS `id_nilai`, `p`.`id_user` AS `id_customer`, `dp`.`id_produk` AS `id_produk`, `r`.`id_pesanan` AS `id_pesanan`, `r`.`bintang` AS `bintang`, `r`.`ulasan` AS `ulasan`, `p`.`tanggal_pesan` AS `tanggal` FROM ((`rating` `r` join `pesanan` `p` on(`r`.`id_pesanan` = `p`.`id_pesanan`)) join `detail_pesanan` `dp` on(`p`.`id_pesanan` = `dp`.`id_pesanan`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `produk_view`
--
DROP TABLE IF EXISTS `produk_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `produk_view`  AS SELECT `produk`.`id_produk` AS `id_produk`, `produk`.`id_toko` AS `id_user`, `produk`.`nama_produk` AS `nama_produk`, 'Roti' AS `kategori`, `produk`.`harga_asli` AS `harga_asli`, `produk`.`harga_diskon` AS `harga_diskon`, `produk`.`stok` AS `stok`, if(`produk`.`stok` > 0,'Aktif','Habis') AS `status`, `produk`.`expired_at` AS `kadaluarsa`, `produk`.`foto` AS `foto`, `produk`.`deskripsi` AS `deskripsi` FROM `produk` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `staff`
--
DROP TABLE IF EXISTS `staff`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `staff`  AS SELECT `user`.`id_user` AS `id_user`, `user`.`nama` AS `nama`, `user`.`email` AS `email`, `user`.`password` AS `password`, `user`.`no_hp` AS `no_hp`, 'Yogyakarta' AS `alamat` FROM `user` WHERE `user`.`role` in ('admin','toko') ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_pesanans`
--
ALTER TABLE `detail_pesanans`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `detail_pesanans_id_pesanan_foreign` (`id_pesanan`),
  ADD KEY `detail_pesanans_id_produk_foreign` (`id_produk`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `keranjang_items`
--
ALTER TABLE `keranjang_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `pesanans`
--
ALTER TABLE `pesanans`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `pesanans_id_user_foreign` (`id_user`);

--
-- Indeks untuk tabel `produks`
--
ALTER TABLE `produks`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indeks untuk tabel `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id_rating`),
  ADD UNIQUE KEY `ratings_id_pesanan_unique` (`id_pesanan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_pesanans`
--
ALTER TABLE `detail_pesanans`
  MODIFY `id_detail` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `keranjang_items`
--
ALTER TABLE `keranjang_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pesanans`
--
ALTER TABLE `pesanans`
  MODIFY `id_pesanan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `produks`
--
ALTER TABLE `produks`
  MODIFY `id_produk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id_rating` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_pesanans`
--
ALTER TABLE `detail_pesanans`
  ADD CONSTRAINT `detail_pesanans_id_pesanan_foreign` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanans` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanans_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produks` (`id_produk`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesanans`
--
ALTER TABLE `pesanans`
  ADD CONSTRAINT `pesanans_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_id_pesanan_foreign` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanans` (`id_pesanan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
