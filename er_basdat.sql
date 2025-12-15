-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Des 2025 pada 20.19
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `er_basdat`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_penitipan`
--

CREATE TABLE `detail_penitipan` (
  `id_detail` bigint(20) UNSIGNED NOT NULL,
  `id_penitipan` bigint(20) UNSIGNED NOT NULL,
  `id_paket` bigint(20) UNSIGNED NOT NULL,
  `jumlah_hari` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `detail_penitipan`
--

INSERT INTO `detail_penitipan` (`id_detail`, `id_penitipan`, `id_paket`, `jumlah_hari`, `subtotal`, `created_at`) VALUES
(1, 1, 1, 2, 300000.00, '2025-10-06 08:40:38'),
(2, 2, 1, 4, 600000.00, '2025-10-06 10:01:07'),
(3, 3, 2, 1, 250000.00, '2025-10-06 10:01:38'),
(9, 8, 1, 2, 300000.00, '2025-10-07 01:43:12'),
(10, 9, 1, 1, 150000.00, '2025-10-07 01:45:01'),
(11, 10, 1, 2, 300000.00, '2025-10-07 01:46:42'),
(12, 11, 1, 2, 300000.00, '2025-10-07 01:51:30'),
(13, 12, 1, 1, 150000.00, '2025-10-07 01:59:23'),
(14, 13, 1, 3, 450000.00, '2025-10-07 02:00:20'),
(15, 14, 1, 2, 300000.00, '2025-10-07 02:02:41'),
(16, 15, 1, 2, 300000.00, '2025-10-07 02:07:49'),
(17, 16, 1, 2, 300000.00, '2025-10-07 02:12:20'),
(18, 17, 1, 2, 300000.00, '2025-10-07 02:15:32'),
(19, 18, 1, 2, 300000.00, '2025-10-07 02:18:07'),
(20, 19, 1, 1, 150000.00, '2025-10-07 02:21:20'),
(21, 20, 1, 1, 150000.00, '2025-10-07 08:02:55'),
(24, 23, 1, 2, 300000.00, '2025-10-07 08:19:21'),
(25, 23, 4, 1, 100000.00, '2025-10-07 08:19:21'),
(26, 23, 5, 1, 100000.00, '2025-10-07 08:19:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hewan`
--

CREATE TABLE `hewan` (
  `id_hewan` bigint(20) UNSIGNED NOT NULL,
  `id_pemilik` bigint(20) UNSIGNED NOT NULL,
  `nama_hewan` varchar(255) NOT NULL,
  `jenis_hewan` varchar(255) NOT NULL,
  `ras` varchar(255) NOT NULL,
  `umur` int(11) NOT NULL,
  `jenis_kelamin` varchar(255) NOT NULL,
  `berat` decimal(8,2) NOT NULL,
  `kondisi_khusus` text DEFAULT NULL,
  `catatan_medis` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `hewan`
--

INSERT INTO `hewan` (`id_hewan`, `id_pemilik`, `nama_hewan`, `jenis_hewan`, `ras`, `umur`, `jenis_kelamin`, `berat`, `kondisi_khusus`, `catatan_medis`, `created_at`, `updated_at`) VALUES
(1, 1, 'Roscoe', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, 'Mantap', NULL, '2025-10-06 08:40:38', '2025-10-06 08:40:38'),
(2, 2, 'Coco', 'dog', 'Bulldog', 20, 'tidak diketahui', 10.00, NULL, NULL, '2025-10-06 10:01:07', '2025-10-06 10:01:07'),
(3, 2, 'Roscoe', 'anjing', 'Bulldog', 1, 'tidak diketahui', 2.00, NULL, NULL, '2025-10-06 10:01:38', '2025-10-06 23:43:11'),
(8, 1, 'Roscoe', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-10-07 01:43:12', '2025-10-07 01:43:12'),
(9, 1, 'Roscoe', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-10-07 01:45:01', '2025-10-07 01:45:01'),
(10, 1, 'Roscoe', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-10-07 01:46:42', '2025-10-07 01:46:42'),
(11, 1, 'Roscoe', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-10-07 01:51:30', '2025-10-07 01:51:30'),
(12, 1, 'Roscoe', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-10-07 01:59:23', '2025-10-07 01:59:23'),
(13, 1, 'Black', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-10-07 02:00:20', '2025-10-07 02:00:20'),
(14, 1, 'Roscoe', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-10-07 02:02:41', '2025-10-07 02:02:41'),
(15, 1, 'Roscoe', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-10-07 02:07:49', '2025-10-07 02:07:49'),
(16, 1, 'Roscoe', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-10-07 02:12:20', '2025-10-07 02:12:20'),
(17, 1, 'Roscoe', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-10-07 02:15:32', '2025-10-07 02:15:32'),
(18, 1, 'Coco', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-10-07 02:18:07', '2025-10-07 02:18:07'),
(19, 1, 'Roscoe', 'dog', 'Bulldog', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-10-07 02:21:19', '2025-10-07 02:21:19'),
(20, 2, 'Black', 'cat', 'Bulldog', 20, 'tidak diketahui', 50.00, NULL, NULL, '2025-10-07 08:02:55', '2025-10-07 08:02:55'),
(24, 1, 'Helo', 'kucing', 'Bulldog', 2, 'tidak diketahui', 2.00, NULL, NULL, '2025-10-07 08:19:21', '2025-10-07 08:19:21');

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
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_sessions_table', 1),
(3, '2024_01_01_000001_create_pengguna_table', 1),
(4, '2024_01_01_000002_create_paket_layanan_table', 1),
(5, '2024_01_01_000003_create_hewan_table', 1),
(6, '2024_01_01_000004_create_penitipan_table', 1),
(7, '2024_01_01_000005_create_detail_penitipan_table', 1),
(8, '2024_01_01_000006_create_pembayaran_table', 1),
(9, '2024_01_01_000007_create_update_kondisi_table', 1),
(11, '2024_12_01_000001_add_specialization_to_pengguna', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `paket_layanan`
--

CREATE TABLE `paket_layanan` (
  `id_paket` bigint(20) UNSIGNED NOT NULL,
  `nama_paket` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga_per_hari` decimal(10,2) NOT NULL,
  `fasilitas` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `paket_layanan`
--

INSERT INTO `paket_layanan` (`id_paket`, `nama_paket`, `deskripsi`, `harga_per_hari`, `fasilitas`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Paket Basic', 'Paket dasar dengan fasilitas standar untuk penitipan hewan', 150000.00, 'Kamar Ber-AC\nMakan 3x sehari\nArea bermain indoor/outdoor\nLaporan harian via WA (foto)', 1, '2025-10-06 08:38:35', '2025-10-06 08:38:35'),
(2, 'Paket Premium', 'Paket premium dengan fasilitas lengkap dan layanan ekstra', 250000.00, 'Kamar Ber-AC\nMakan 3x sehari\nArea bermain indoor/outdoor\nLaporan harian via WA + VC\nSnack & Treats', 1, '2025-10-06 08:38:35', '2025-10-06 08:38:35'),
(3, 'Grooming Premium', 'Layanan spa lengkap untuk hewan kesayangan Anda', 150000.00, 'Spa lengkap\nPotong kuku\nBersih telinga\nAromaterapi', 1, '2025-10-06 08:38:35', '2025-10-06 08:38:35'),
(4, 'Kolam Renang', 'Layanan berenang untuk kesehatan dan kesenangan hewan', 100000.00, 'Sesi berenang dengan pengawasan\nPeralatan keamanan standar\nHanduk dan perawatan setelah berenang', 1, '2025-10-06 08:38:35', '2025-10-06 08:38:35'),
(5, 'Pick-up & Delivery', 'Layanan antar jemput hewan peliharaan Anda', 100000.00, 'Layanan antar jemput dalam radius 10km\nKendaraan ber-AC\nPenanganan hewan yang aman', 1, '2025-10-06 08:38:35', '2025-10-06 08:38:35'),
(6, 'Enrichment Extra', 'Sesi stimulasi mental dan fisik untuk hewan', 45000.00, 'Sesi stimulasi 15–20 menit\nPuzzle feeder\nLick mat\nSniffing activities', 1, '2025-10-06 08:38:35', '2025-10-06 08:38:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` bigint(20) UNSIGNED NOT NULL,
  `id_penitipan` bigint(20) UNSIGNED NOT NULL,
  `nomor_transaksi` varchar(255) NOT NULL,
  `jumlah_bayar` decimal(10,2) NOT NULL,
  `metode_pembayaran` enum('cash','transfer','e_wallet','qris','kartu_kredit') NOT NULL,
  `status_pembayaran` enum('pending','lunas','gagal') NOT NULL DEFAULT 'pending',
  `tanggal_bayar` datetime DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_penitipan`, `nomor_transaksi`, `jumlah_bayar`, `metode_pembayaran`, `status_pembayaran`, `tanggal_bayar`, `bukti_pembayaran`, `created_at`, `updated_at`) VALUES
(1, 1, 'TRX-20251006-000001', 300000.00, 'cash', 'lunas', '2025-10-06 17:03:11', NULL, '2025-10-06 08:40:38', '2025-10-06 10:03:11'),
(2, 2, 'TRX-20251006-000002', 600000.00, 'qris', 'lunas', '2025-10-06 00:02:00', NULL, '2025-10-06 10:01:07', '2025-10-06 10:02:58'),
(3, 3, 'TRX-20251006-000003', 250000.00, 'cash', 'lunas', '2025-10-06 17:02:43', NULL, '2025-10-06 10:01:38', '2025-10-06 10:02:43'),
(4, 8, 'TRX-20251007-000008', 300000.00, 'cash', 'pending', NULL, NULL, '2025-10-07 01:43:12', '2025-10-07 01:43:12'),
(5, 9, 'TRX-20251007-000009', 150000.00, 'cash', 'pending', NULL, NULL, '2025-10-07 01:45:01', '2025-10-07 01:45:01'),
(6, 10, 'TRX-20251007-000010', 300000.00, 'cash', 'pending', NULL, NULL, '2025-10-07 01:46:42', '2025-10-07 01:46:42'),
(7, 11, 'TRX-20251007-000011', 300000.00, 'cash', 'pending', NULL, NULL, '2025-10-07 01:51:30', '2025-10-07 01:51:30'),
(8, 12, 'TRX-20251007-000012', 150000.00, 'cash', 'pending', NULL, NULL, '2025-10-07 01:59:23', '2025-10-07 01:59:23'),
(9, 13, 'TRX-20251007-000013', 450000.00, 'cash', 'pending', NULL, NULL, '2025-10-07 02:00:20', '2025-10-07 02:00:20'),
(10, 14, 'TRX-20251007-000014', 300000.00, 'cash', 'pending', NULL, NULL, '2025-10-07 02:02:41', '2025-10-07 02:02:41'),
(11, 15, 'TRX-20251007-000015', 300000.00, 'cash', 'pending', NULL, NULL, '2025-10-07 02:07:49', '2025-10-07 02:07:49'),
(12, 16, 'TRX-20251007-000016', 300000.00, 'cash', 'lunas', '2025-10-07 09:12:56', NULL, '2025-10-07 02:12:20', '2025-10-07 02:12:56'),
(13, 17, 'TRX-20251007-000017', 300000.00, 'qris', 'lunas', '2025-10-07 09:16:05', NULL, '2025-10-07 02:15:32', '2025-10-07 02:16:05'),
(14, 18, 'TRX-20251007-000018', 300000.00, 'qris', 'lunas', '2025-10-07 09:19:58', NULL, '2025-10-07 02:18:07', '2025-10-07 02:19:58'),
(15, 19, 'TRX-20251007-000019', 150000.00, 'kartu_kredit', 'lunas', '2025-10-07 09:23:24', NULL, '2025-10-07 02:21:20', '2025-10-07 02:23:24'),
(16, 20, 'TRX-20251007-000020', 150000.00, 'cash', 'lunas', '2025-10-07 15:03:41', NULL, '2025-10-07 08:02:55', '2025-10-07 08:03:41'),
(17, 23, 'TRX-20251007-000023', 500000.00, 'cash', 'lunas', '2025-10-07 15:23:43', NULL, '2025-10-07 08:19:21', '2025-10-07 08:23:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telepon` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `role` enum('admin','pet_owner','staff') NOT NULL,
  `specialization` enum('groomer','handler','trainer') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_lengkap`, `email`, `password`, `no_telepon`, `alamat`, `role`, `specialization`, `created_at`, `updated_at`) VALUES
(1, 'Harvest Walukow', 'harvest@gmail.com', '$2y$12$U/haEZ4Lq.Ud9A6WRJS5R.yLfPfERhmHmpZ4hM8rfqJrmxR9y0qvi', '082396333595', 'Surabaya', 'pet_owner', NULL, '2025-10-06 08:39:16', '2025-10-06 08:39:16'),
(2, 'Coba Aja', 'tes1@gmail.com', '$2y$12$0R4LQt3Ihqwll5pVDIQQG.rwJmhlmDPaQeSCWxiUTjEQkiBmcxrEC', '0812345678', 'Surabaya', 'pet_owner', NULL, '2025-10-06 10:00:16', '2025-10-06 10:00:16'),
(5, 'baim', 'baim@gmail.com', '$2y$12$FqIlccPmBGxwZQoVzuk4d..QceB1rlZcWT1ObHebOUHOy6rxXvFmC', '0812345678', 'mantap', 'staff', 'groomer', '2025-10-06 22:04:21', '2025-10-06 22:04:21'),
(6, 'Harvest', 'apes@gmail.com', '$2y$12$9NJCGg8OQ7xBGEgvJ7jsKeE9PdN7Fq8cQJ2P5hiF7G.gqwg7hHQvK', '082396333595', 'ok', 'admin', 'handler', '2025-10-06 22:06:42', '2025-10-06 22:06:42'),
(8, 'Tes', 'testes@gmail.com', '$2y$12$eNDdgfLWqvzd2YJBbgcXZO8/rGvJUErxk0Da1XAgWRe.Ecmsw633i', '08123123123', 'tes', 'staff', 'groomer', '2025-10-07 18:49:12', '2025-10-07 18:49:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penitipan`
--

CREATE TABLE `penitipan` (
  `id_penitipan` bigint(20) UNSIGNED NOT NULL,
  `id_hewan` bigint(20) UNSIGNED NOT NULL,
  `id_pemilik` bigint(20) UNSIGNED NOT NULL,
  `id_staff` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_masuk` datetime NOT NULL,
  `tanggal_keluar` datetime NOT NULL,
  `status` enum('pending','aktif','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `catatan_khusus` text DEFAULT NULL,
  `total_biaya` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penitipan`
--

INSERT INTO `penitipan` (`id_penitipan`, `id_hewan`, `id_pemilik`, `id_staff`, `tanggal_masuk`, `tanggal_keluar`, `status`, `catatan_khusus`, `total_biaya`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '2025-10-08 00:00:00', '2025-10-10 00:00:00', 'aktif', 'Mantap', 300000.00, '2025-10-06 08:40:38', '2025-10-06 10:03:11'),
(2, 2, 2, NULL, '2025-10-07 00:00:00', '2025-10-11 00:00:00', 'aktif', NULL, 600000.00, '2025-10-06 10:01:07', '2025-10-06 10:02:58'),
(3, 3, 2, NULL, '2025-10-10 00:00:00', '2025-10-11 00:00:00', 'aktif', NULL, 250000.00, '2025-10-06 10:01:38', '2025-10-06 10:02:43'),
(8, 8, 1, NULL, '2025-10-07 00:00:00', '2025-10-09 00:00:00', 'pending', NULL, 300000.00, '2025-10-07 01:43:12', '2025-10-07 01:43:12'),
(9, 9, 1, NULL, '2025-10-07 00:00:00', '2025-10-08 00:00:00', 'pending', NULL, 150000.00, '2025-10-07 01:45:01', '2025-10-07 01:45:01'),
(10, 10, 1, NULL, '2025-10-07 00:00:00', '2025-10-09 00:00:00', 'pending', NULL, 300000.00, '2025-10-07 01:46:42', '2025-10-07 01:46:42'),
(11, 11, 1, NULL, '2025-10-07 00:00:00', '2025-10-09 00:00:00', 'pending', NULL, 300000.00, '2025-10-07 01:51:30', '2025-10-07 01:51:30'),
(12, 12, 1, NULL, '2025-10-07 00:00:00', '2025-10-08 00:00:00', 'pending', NULL, 150000.00, '2025-10-07 01:59:23', '2025-10-07 01:59:23'),
(13, 13, 1, NULL, '2025-10-07 00:00:00', '2025-10-10 00:00:00', 'pending', NULL, 450000.00, '2025-10-07 02:00:20', '2025-10-07 02:00:20'),
(14, 14, 1, NULL, '2025-10-07 00:00:00', '2025-10-09 00:00:00', 'pending', NULL, 300000.00, '2025-10-07 02:02:41', '2025-10-07 02:02:41'),
(15, 15, 1, NULL, '2025-10-07 00:00:00', '2025-10-09 00:00:00', 'pending', NULL, 300000.00, '2025-10-07 02:07:49', '2025-10-07 02:07:49'),
(16, 16, 1, NULL, '2025-10-07 00:00:00', '2025-10-09 00:00:00', 'aktif', NULL, 300000.00, '2025-10-07 02:12:20', '2025-10-07 02:12:56'),
(17, 17, 1, NULL, '2025-10-07 00:00:00', '2025-10-09 00:00:00', 'aktif', NULL, 300000.00, '2025-10-07 02:15:32', '2025-10-07 02:16:05'),
(18, 18, 1, NULL, '2025-10-07 00:00:00', '2025-10-09 00:00:00', 'aktif', NULL, 300000.00, '2025-10-07 02:18:07', '2025-10-07 02:19:58'),
(19, 19, 1, NULL, '2025-10-09 00:00:00', '2025-10-10 00:00:00', 'aktif', NULL, 150000.00, '2025-10-07 02:21:20', '2025-10-07 02:23:24'),
(20, 20, 2, NULL, '2025-10-07 00:00:00', '2025-10-08 00:00:00', 'aktif', NULL, 150000.00, '2025-10-07 08:02:55', '2025-10-07 08:03:41'),
(23, 24, 1, NULL, '2025-10-07 00:00:00', '2025-10-09 00:00:00', 'aktif', NULL, 500000.00, '2025-10-07 08:19:21', '2025-10-07 08:23:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('g6FtC1ccDC2W4QqarQT2z1L8TRjHpl2bypigVk5t', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiY1IyS0s4ZWFtNk84SlUzYXFERWFUT2tXWHEyaXRldjI2Wmp2V1B4SCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NzoidXNlcl9pZCI7aTo5OTk5O3M6MTA6InVzZXJfZW1haWwiO3M6MTU6ImFkbWluQGdtYWlsLmNvbSI7czo5OiJ1c2VyX25hbWUiO3M6NToiQWRtaW4iO3M6OToidXNlcl9yb2xlIjtzOjU6ImFkbWluIjt9', 1765739953);

-- --------------------------------------------------------

--
-- Struktur dari tabel `update_kondisi`
--

CREATE TABLE `update_kondisi` (
  `id_update` bigint(20) UNSIGNED NOT NULL,
  `id_penitipan` bigint(20) UNSIGNED NOT NULL,
  `id_staff` bigint(20) UNSIGNED NOT NULL,
  `kondisi_hewan` text NOT NULL,
  `aktivitas_hari_ini` text NOT NULL,
  `catatan_staff` text DEFAULT NULL,
  `foto_hewan` varchar(255) DEFAULT NULL,
  `waktu_update` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `update_kondisi`
--

INSERT INTO `update_kondisi` (`id_update`, `id_penitipan`, `id_staff`, `kondisi_hewan`, `aktivitas_hari_ini`, `catatan_staff`, `foto_hewan`, `waktu_update`, `created_at`) VALUES
(3, 1, 6, 'sehat', 'Mantap', NULL, 'uploads/update_kondisi/1759819321_pngimg.com - elon_musk_PNG16.png', '2025-10-07 06:42:01', '2025-10-07 06:42:01');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `detail_penitipan`
--
ALTER TABLE `detail_penitipan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `detail_penitipan_id_penitipan_foreign` (`id_penitipan`),
  ADD KEY `detail_penitipan_id_paket_foreign` (`id_paket`);

--
-- Indeks untuk tabel `hewan`
--
ALTER TABLE `hewan`
  ADD PRIMARY KEY (`id_hewan`),
  ADD KEY `hewan_id_pemilik_foreign` (`id_pemilik`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `paket_layanan`
--
ALTER TABLE `paket_layanan`
  ADD PRIMARY KEY (`id_paket`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD UNIQUE KEY `pembayaran_nomor_transaksi_unique` (`nomor_transaksi`),
  ADD KEY `pembayaran_id_penitipan_foreign` (`id_penitipan`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `pengguna_email_unique` (`email`);

--
-- Indeks untuk tabel `penitipan`
--
ALTER TABLE `penitipan`
  ADD PRIMARY KEY (`id_penitipan`),
  ADD KEY `penitipan_id_hewan_foreign` (`id_hewan`),
  ADD KEY `penitipan_id_pemilik_foreign` (`id_pemilik`),
  ADD KEY `penitipan_id_staff_foreign` (`id_staff`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `update_kondisi`
--
ALTER TABLE `update_kondisi`
  ADD PRIMARY KEY (`id_update`),
  ADD KEY `update_kondisi_id_penitipan_foreign` (`id_penitipan`),
  ADD KEY `update_kondisi_id_staff_foreign` (`id_staff`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_penitipan`
--
ALTER TABLE `detail_penitipan`
  MODIFY `id_detail` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `hewan`
--
ALTER TABLE `hewan`
  MODIFY `id_hewan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `paket_layanan`
--
ALTER TABLE `paket_layanan`
  MODIFY `id_paket` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `penitipan`
--
ALTER TABLE `penitipan`
  MODIFY `id_penitipan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `update_kondisi`
--
ALTER TABLE `update_kondisi`
  MODIFY `id_update` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_penitipan`
--
ALTER TABLE `detail_penitipan`
  ADD CONSTRAINT `detail_penitipan_id_paket_foreign` FOREIGN KEY (`id_paket`) REFERENCES `paket_layanan` (`id_paket`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_penitipan_id_penitipan_foreign` FOREIGN KEY (`id_penitipan`) REFERENCES `penitipan` (`id_penitipan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `hewan`
--
ALTER TABLE `hewan`
  ADD CONSTRAINT `hewan_id_pemilik_foreign` FOREIGN KEY (`id_pemilik`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_id_penitipan_foreign` FOREIGN KEY (`id_penitipan`) REFERENCES `penitipan` (`id_penitipan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penitipan`
--
ALTER TABLE `penitipan`
  ADD CONSTRAINT `penitipan_id_hewan_foreign` FOREIGN KEY (`id_hewan`) REFERENCES `hewan` (`id_hewan`) ON DELETE CASCADE,
  ADD CONSTRAINT `penitipan_id_pemilik_foreign` FOREIGN KEY (`id_pemilik`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE,
  ADD CONSTRAINT `penitipan_id_staff_foreign` FOREIGN KEY (`id_staff`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `update_kondisi`
--
ALTER TABLE `update_kondisi`
  ADD CONSTRAINT `update_kondisi_id_penitipan_foreign` FOREIGN KEY (`id_penitipan`) REFERENCES `penitipan` (`id_penitipan`) ON DELETE CASCADE,
  ADD CONSTRAINT `update_kondisi_id_staff_foreign` FOREIGN KEY (`id_staff`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
