-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Des 2025 pada 15.33
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

DELIMITER $$
--
-- Prosedur
--
CREATE PROCEDURE `update_fact_kapasitas_for_date` (IN `target_date` DATE)
BEGIN
                DECLARE v_waktu_key INT DEFAULT NULL;
                DECLARE v_total_penitipan INT DEFAULT 0;
                DECLARE v_penitipan_aktif INT DEFAULT 0;
                DECLARE v_penitipan_pending INT DEFAULT 0;
                DECLARE v_penitipan_selesai INT DEFAULT 0;
                DECLARE v_penitipan_dibatalkan INT DEFAULT 0;
                DECLARE v_total_hewan INT DEFAULT 0;
                
                SELECT waktu_key INTO v_waktu_key
                FROM dim_waktu
                WHERE tanggal = target_date;
                
                IF v_waktu_key IS NULL THEN
                    SET v_waktu_key = v_waktu_key;
                ELSE
                    SELECT 
                        COUNT(*),
                        SUM(CASE WHEN status = 'aktif' THEN 1 ELSE 0 END),
                        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END),
                        SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END),
                        SUM(CASE WHEN status = 'dibatalkan' THEN 1 ELSE 0 END),
                        COUNT(DISTINCT id_hewan)
                    INTO v_total_penitipan, v_penitipan_aktif, v_penitipan_pending, 
                         v_penitipan_selesai, v_penitipan_dibatalkan, v_total_hewan
                    FROM penitipan
                    WHERE target_date BETWEEN DATE(tanggal_masuk) AND DATE(tanggal_keluar);
                    
                    INSERT INTO fact_kapasitas_harian 
                        (waktu_key, total_penitipan, penitipan_aktif, penitipan_pending, 
                         penitipan_selesai, penitipan_dibatalkan, total_hewan)
                    VALUES 
                        (v_waktu_key, 
                         COALESCE(v_total_penitipan, 0), 
                         COALESCE(v_penitipan_aktif, 0), 
                         COALESCE(v_penitipan_pending, 0),
                         COALESCE(v_penitipan_selesai, 0), 
                         COALESCE(v_penitipan_dibatalkan, 0), 
                         COALESCE(v_total_hewan, 0))
                    ON DUPLICATE KEY UPDATE
                        total_penitipan = COALESCE(v_total_penitipan, 0),
                        penitipan_aktif = COALESCE(v_penitipan_aktif, 0),
                        penitipan_pending = COALESCE(v_penitipan_pending, 0),
                        penitipan_selesai = COALESCE(v_penitipan_selesai, 0),
                        penitipan_dibatalkan = COALESCE(v_penitipan_dibatalkan, 0),
                        total_hewan = COALESCE(v_total_hewan, 0);
                END IF;
            END$$

CREATE PROCEDURE `update_fact_keuangan_for_month` (IN `target_year` INT, IN `target_month` INT)
BEGIN
                DECLARE v_total_revenue DECIMAL(12,2);
                DECLARE v_jumlah_transaksi INT;
                DECLARE v_avg_transaksi DECIMAL(12,2);
                DECLARE v_periode_yyyymm INT;
                
                SET v_periode_yyyymm = target_year * 100 + target_month;
                
                SELECT 
                    SUM(jumlah_bayar),
                    COUNT(*),
                    AVG(jumlah_bayar)
                INTO v_total_revenue, v_jumlah_transaksi, v_avg_transaksi
                FROM pembayaran
                WHERE status_pembayaran = 'lunas'
                  AND YEAR(tanggal_bayar) = target_year
                  AND MONTH(tanggal_bayar) = target_month;
                
                INSERT INTO fact_keuangan_periodik 
                    (periode_yyyymm, tahun, bulan, total_revenue, jumlah_transaksi, avg_transaksi)
                VALUES 
                    (v_periode_yyyymm, target_year, target_month,
                     COALESCE(v_total_revenue, 0), 
                     COALESCE(v_jumlah_transaksi, 0), 
                     COALESCE(v_avg_transaksi, 0))
                ON DUPLICATE KEY UPDATE
                    total_revenue = COALESCE(v_total_revenue, 0),
                    jumlah_transaksi = COALESCE(v_jumlah_transaksi, 0),
                    avg_transaksi = COALESCE(v_avg_transaksi, 0);
            END$$

CREATE PROCEDURE `upsert_fact_transaksi_for_penitipan` (IN `p_id_penitipan` INT)
BEGIN
                DECLARE v_waktu_key INT;
                DECLARE v_customer_key INT;
                DECLARE v_hewan_key INT;
                DECLARE v_paket_key INT;
                DECLARE v_staff_key INT;
                DECLARE v_status_key INT;
                DECLARE v_pembayaran_key INT;
                DECLARE v_jumlah_hari INT;
                DECLARE v_total_biaya DECIMAL(12,2);
                DECLARE v_tanggal_masuk DATETIME;
                DECLARE v_id_pemilik INT;
                DECLARE v_id_hewan INT;
                DECLARE v_id_paket INT;
                DECLARE v_id_staff INT;
                DECLARE v_status VARCHAR(50);
                DECLARE v_metode_pembayaran VARCHAR(50);
                DECLARE v_status_pembayaran VARCHAR(50);
                
                SELECT 
                    p.tanggal_masuk,
                    p.tanggal_keluar,
                    p.id_pemilik,
                    p.id_hewan,
                    p.id_staff,
                    p.status,
                    p.total_biaya,
                    DATEDIFF(p.tanggal_keluar, p.tanggal_masuk),
                    dp.id_paket,
                    pay.metode_pembayaran,
                    pay.status_pembayaran
                INTO
                    v_tanggal_masuk,
                    @tanggal_keluar,
                    v_id_pemilik,
                    v_id_hewan,
                    v_id_staff,
                    v_status,
                    v_total_biaya,
                    v_jumlah_hari,
                    v_id_paket,
                    v_metode_pembayaran,
                    v_status_pembayaran
                FROM penitipan p
                LEFT JOIN detail_penitipan dp ON p.id_penitipan = dp.id_penitipan
                LEFT JOIN pembayaran pay ON p.id_penitipan = pay.id_penitipan
                WHERE p.id_penitipan = p_id_penitipan
                LIMIT 1;
                
                SELECT waktu_key INTO v_waktu_key FROM dim_waktu WHERE tanggal = DATE(v_tanggal_masuk) LIMIT 1;
                SELECT customer_key INTO v_customer_key FROM dim_customer WHERE id_pengguna = v_id_pemilik LIMIT 1;
                SELECT hewan_key INTO v_hewan_key FROM dim_hewan WHERE id_hewan = v_id_hewan LIMIT 1;
                SELECT paket_key INTO v_paket_key FROM dim_paket WHERE id_paket = v_id_paket LIMIT 1;
                SELECT staff_key INTO v_staff_key FROM dim_staff WHERE id_pengguna = v_id_staff LIMIT 1;
                SELECT status_key INTO v_status_key FROM dim_status_penitipan WHERE status = v_status LIMIT 1;
                SELECT pembayaran_key INTO v_pembayaran_key FROM dim_pembayaran 
                    WHERE metode_pembayaran = v_metode_pembayaran AND status_pembayaran = v_status_pembayaran LIMIT 1;
                
                INSERT INTO fact_transaksi
                    (waktu_key, customer_key, hewan_key, paket_key, staff_key, status_key, pembayaran_key,
                     jumlah_hari, total_biaya, jumlah_transaksi,
                     id_penitipan, tanggal_masuk, id_pemilik, id_hewan, id_paket, id_staff,
                     status, metode_pembayaran, status_pembayaran)
                VALUES
                    (v_waktu_key, v_customer_key, v_hewan_key, v_paket_key, v_staff_key, v_status_key, v_pembayaran_key,
                     COALESCE(v_jumlah_hari, 0), COALESCE(v_total_biaya, 0), 1,
                     p_id_penitipan, v_tanggal_masuk, v_id_pemilik, v_id_hewan, v_id_paket, v_id_staff,
                     v_status, v_metode_pembayaran, v_status_pembayaran)
                ON DUPLICATE KEY UPDATE
                    waktu_key = v_waktu_key,
                    customer_key = v_customer_key,
                    hewan_key = v_hewan_key,
                    paket_key = v_paket_key,
                    staff_key = v_staff_key,
                    status_key = v_status_key,
                    pembayaran_key = v_pembayaran_key,
                    jumlah_hari = COALESCE(v_jumlah_hari, 0),
                    total_biaya = COALESCE(v_total_biaya, 0),
                    tanggal_masuk = v_tanggal_masuk,
                    id_pemilik = v_id_pemilik,
                    id_hewan = v_id_hewan,
                    id_paket = v_id_paket,
                    id_staff = v_id_staff,
                    status = v_status,
                    metode_pembayaran = v_metode_pembayaran,
                    status_pembayaran = v_status_pembayaran;
            END$$

DELIMITER ;

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
-- Struktur dari tabel `data_warehouse_tables`
--

CREATE TABLE `data_warehouse_tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, 1, 1, 7, 1050000.00, '2025-12-16 12:14:00'),
(2, 2, 3, 5, 1000000.00, '2025-12-16 12:14:01'),
(3, 3, 2, 7, 1750000.00, '2025-12-16 12:14:01'),
(4, 4, 4, 7, 2450000.00, '2025-12-16 12:14:02'),
(5, 5, 1, 2, 300000.00, '2025-12-16 05:46:13'),
(6, 5, 6, 1, 45000.00, '2025-12-16 05:46:13'),
(7, 5, 4, 1, 100000.00, '2025-12-16 05:46:13');

--
-- Trigger `detail_penitipan`
--
DELIMITER $$
CREATE TRIGGER `sync_facts_detail_penitipan_delete` AFTER DELETE ON `detail_penitipan` FOR EACH ROW BEGIN
                CALL upsert_fact_transaksi_for_penitipan(OLD.id_penitipan);
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_facts_detail_penitipan_insert` AFTER INSERT ON `detail_penitipan` FOR EACH ROW BEGIN
                CALL upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_facts_detail_penitipan_update` AFTER UPDATE ON `detail_penitipan` FOR EACH ROW BEGIN
                CALL upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
            END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dim_customer`
--

CREATE TABLE `dim_customer` (
  `customer_key` int(10) UNSIGNED NOT NULL,
  `id_pengguna` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dim_customer`
--

INSERT INTO `dim_customer` (`customer_key`, `id_pengguna`, `nama_lengkap`, `email`, `alamat`, `no_telepon`, `created_at`, `updated_at`) VALUES
(1, 3, 'Baim Wong', 'baim@gmail.com', 'Jl. Baim No. 10, Jakarta Selatan', '081234567801', '2025-12-16 12:14:00', '2025-12-16 12:14:00'),
(2, 4, 'Hanny Puspita', 'hanny@gmail.com', 'Jl. Hanny No. 20, Jakarta Barat', '081234567802', '2025-12-16 12:14:01', '2025-12-16 12:14:01'),
(3, 5, 'Salwa Azzahra', 'salwa@gmail.com', 'Jl. Salwa No. 30, Jakarta Utara', '081234567803', '2025-12-16 12:14:01', '2025-12-16 12:14:01'),
(4, 6, 'Mayla Cantika', 'mayla@gmail.com', 'Jl. Mayla No. 40, Jakarta Timur', '081234567804', '2025-12-16 12:14:02', '2025-12-16 12:14:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dim_hewan`
--

CREATE TABLE `dim_hewan` (
  `hewan_key` int(10) UNSIGNED NOT NULL,
  `id_hewan` bigint(20) UNSIGNED NOT NULL,
  `nama_hewan` varchar(100) NOT NULL,
  `jenis_hewan` varchar(50) NOT NULL,
  `ras` varchar(100) DEFAULT NULL,
  `umur` int(11) DEFAULT NULL,
  `jenis_kelamin` varchar(50) DEFAULT NULL,
  `berat` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dim_hewan`
--

INSERT INTO `dim_hewan` (`hewan_key`, `id_hewan`, `nama_hewan`, `jenis_hewan`, `ras`, `umur`, `jenis_kelamin`, `berat`, `created_at`, `updated_at`) VALUES
(1, 1, 'Luna', 'kucing', 'Persian', 2, 'betina', 4.50, '2025-12-16 12:14:00', '2025-12-16 12:14:00'),
(2, 2, 'Max', 'anjing', 'Golden Retriever', 3, 'jantan', 28.00, '2025-12-16 12:14:01', '2025-12-16 12:14:01'),
(3, 3, 'Mochi', 'kucing', 'British Shorthair', 1, 'jantan', 3.80, '2025-12-16 12:14:01', '2025-12-16 12:14:01'),
(4, 4, 'Rocky', 'anjing', 'German Shepherd', 4, 'jantan', 32.50, '2025-12-16 12:14:02', '2025-12-16 12:14:02'),
(5, 6, 'Luna', 'kucing', 'Persian', 20, 'tidak diketahui', 20.00, '2025-12-16 12:46:13', '2025-12-16 12:46:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dim_paket`
--

CREATE TABLE `dim_paket` (
  `paket_key` int(10) UNSIGNED NOT NULL,
  `id_paket` bigint(20) UNSIGNED NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `harga_per_hari` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dim_paket`
--

INSERT INTO `dim_paket` (`paket_key`, `id_paket`, `nama_paket`, `harga_per_hari`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Paket Basic', 150000.00, 1, '2025-12-16 12:13:58', '2025-12-16 12:41:03'),
(2, 2, 'Paket Premium', 250000.00, 1, '2025-12-16 12:13:58', '2025-12-16 12:41:03'),
(3, 3, 'Grooming Premium', 150000.00, 1, '2025-12-16 12:13:58', '2025-12-16 12:41:03'),
(4, 4, 'Kolam Renang', 100000.00, 1, '2025-12-16 12:13:58', '2025-12-16 12:41:03'),
(5, 5, 'Pick-up & Delivery', 100000.00, 1, '2025-12-16 12:41:03', '2025-12-16 12:41:03'),
(6, 6, 'Enrichment Extra', 45000.00, 1, '2025-12-16 12:41:03', '2025-12-16 12:41:03'),
(7, 7, 'OKE BOS', 12222000.00, 1, '2025-12-16 14:27:32', '2025-12-16 14:27:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dim_pembayaran`
--

CREATE TABLE `dim_pembayaran` (
  `pembayaran_key` int(10) UNSIGNED NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `status_pembayaran` varchar(50) NOT NULL,
  `deskripsi` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dim_pembayaran`
--

INSERT INTO `dim_pembayaran` (`pembayaran_key`, `metode_pembayaran`, `status_pembayaran`, `deskripsi`) VALUES
(1, 'cash', 'pending', 'Cash - Pending'),
(2, 'cash', 'lunas', 'Cash - Lunas'),
(3, 'cash', 'gagal', 'Cash - Gagal'),
(4, 'transfer', 'pending', 'Transfer - Pending'),
(5, 'transfer', 'lunas', 'Transfer - Lunas'),
(6, 'transfer', 'gagal', 'Transfer - Gagal'),
(7, 'e_wallet', 'pending', 'E_wallet - Pending'),
(8, 'e_wallet', 'lunas', 'E_wallet - Lunas'),
(9, 'e_wallet', 'gagal', 'E_wallet - Gagal'),
(10, 'qris', 'pending', 'Qris - Pending'),
(11, 'qris', 'lunas', 'Qris - Lunas'),
(12, 'qris', 'gagal', 'Qris - Gagal'),
(13, 'kartu_kredit', 'pending', 'Kartu_kredit - Pending'),
(14, 'kartu_kredit', 'lunas', 'Kartu_kredit - Lunas'),
(15, 'kartu_kredit', 'gagal', 'Kartu_kredit - Gagal');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dim_staff`
--

CREATE TABLE `dim_staff` (
  `staff_key` int(10) UNSIGNED NOT NULL,
  `id_pengguna` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dim_staff`
--

INSERT INTO `dim_staff` (`staff_key`, `id_pengguna`, `nama_lengkap`, `email`, `role`, `specialization`, `created_at`, `updated_at`) VALUES
(1, 1, 'Harvest Walukow', 'harvest@gmail.com', 'staff', 'handler', '2025-12-16 12:13:59', '2025-12-16 13:05:21'),
(2, 2, 'Fatma Staffina', 'fatma@gmail.com', 'staff', 'groomer', '2025-12-16 12:14:00', '2025-12-16 14:32:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dim_status_penitipan`
--

CREATE TABLE `dim_status_penitipan` (
  `status_key` int(10) UNSIGNED NOT NULL,
  `status` varchar(50) NOT NULL,
  `deskripsi` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dim_status_penitipan`
--

INSERT INTO `dim_status_penitipan` (`status_key`, `status`, `deskripsi`) VALUES
(1, 'pending', 'Penitipan menunggu konfirmasi'),
(2, 'aktif', 'Penitipan sedang berlangsung'),
(3, 'selesai', 'Penitipan telah selesai'),
(4, 'dibatalkan', 'Penitipan dibatalkan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dim_waktu`
--

CREATE TABLE `dim_waktu` (
  `waktu_key` int(10) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `nama_bulan` varchar(20) NOT NULL,
  `kuartal` int(11) NOT NULL,
  `hari` int(11) NOT NULL,
  `nama_hari` varchar(20) NOT NULL,
  `minggu_ke` int(11) NOT NULL,
  `is_weekend` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dim_waktu`
--

INSERT INTO `dim_waktu` (`waktu_key`, `tanggal`, `tahun`, `bulan`, `nama_bulan`, `kuartal`, `hari`, `nama_hari`, `minggu_ke`, `is_weekend`) VALUES
(1, '2024-12-16', 2024, 12, 'Desember', 4, 16, 'Senin', 51, 0),
(2, '2024-12-17', 2024, 12, 'Desember', 4, 17, 'Selasa', 51, 0),
(3, '2024-12-18', 2024, 12, 'Desember', 4, 18, 'Rabu', 51, 0),
(4, '2024-12-19', 2024, 12, 'Desember', 4, 19, 'Kamis', 51, 0),
(5, '2024-12-20', 2024, 12, 'Desember', 4, 20, 'Jumat', 51, 0),
(6, '2024-12-21', 2024, 12, 'Desember', 4, 21, 'Sabtu', 51, 1),
(7, '2024-12-22', 2024, 12, 'Desember', 4, 22, 'Minggu', 51, 1),
(8, '2024-12-23', 2024, 12, 'Desember', 4, 23, 'Senin', 52, 0),
(9, '2024-12-24', 2024, 12, 'Desember', 4, 24, 'Selasa', 52, 0),
(10, '2024-12-25', 2024, 12, 'Desember', 4, 25, 'Rabu', 52, 0),
(11, '2024-12-26', 2024, 12, 'Desember', 4, 26, 'Kamis', 52, 0),
(12, '2024-12-27', 2024, 12, 'Desember', 4, 27, 'Jumat', 52, 0),
(13, '2024-12-28', 2024, 12, 'Desember', 4, 28, 'Sabtu', 52, 1),
(14, '2024-12-29', 2024, 12, 'Desember', 4, 29, 'Minggu', 52, 1),
(15, '2024-12-30', 2024, 12, 'Desember', 4, 30, 'Senin', 1, 0),
(16, '2024-12-31', 2024, 12, 'Desember', 4, 31, 'Selasa', 1, 0),
(17, '2025-01-01', 2025, 1, 'Januari', 1, 1, 'Rabu', 1, 0),
(18, '2025-01-02', 2025, 1, 'Januari', 1, 2, 'Kamis', 1, 0),
(19, '2025-01-03', 2025, 1, 'Januari', 1, 3, 'Jumat', 1, 0),
(20, '2025-01-04', 2025, 1, 'Januari', 1, 4, 'Sabtu', 1, 1),
(21, '2025-01-05', 2025, 1, 'Januari', 1, 5, 'Minggu', 1, 1),
(22, '2025-01-06', 2025, 1, 'Januari', 1, 6, 'Senin', 2, 0),
(23, '2025-01-07', 2025, 1, 'Januari', 1, 7, 'Selasa', 2, 0),
(24, '2025-01-08', 2025, 1, 'Januari', 1, 8, 'Rabu', 2, 0),
(25, '2025-01-09', 2025, 1, 'Januari', 1, 9, 'Kamis', 2, 0),
(26, '2025-01-10', 2025, 1, 'Januari', 1, 10, 'Jumat', 2, 0),
(27, '2025-01-11', 2025, 1, 'Januari', 1, 11, 'Sabtu', 2, 1),
(28, '2025-01-12', 2025, 1, 'Januari', 1, 12, 'Minggu', 2, 1),
(29, '2025-01-13', 2025, 1, 'Januari', 1, 13, 'Senin', 3, 0),
(30, '2025-01-14', 2025, 1, 'Januari', 1, 14, 'Selasa', 3, 0),
(31, '2025-01-15', 2025, 1, 'Januari', 1, 15, 'Rabu', 3, 0),
(32, '2025-01-16', 2025, 1, 'Januari', 1, 16, 'Kamis', 3, 0),
(33, '2025-01-17', 2025, 1, 'Januari', 1, 17, 'Jumat', 3, 0),
(34, '2025-01-18', 2025, 1, 'Januari', 1, 18, 'Sabtu', 3, 1),
(35, '2025-01-19', 2025, 1, 'Januari', 1, 19, 'Minggu', 3, 1),
(36, '2025-01-20', 2025, 1, 'Januari', 1, 20, 'Senin', 4, 0),
(37, '2025-01-21', 2025, 1, 'Januari', 1, 21, 'Selasa', 4, 0),
(38, '2025-01-22', 2025, 1, 'Januari', 1, 22, 'Rabu', 4, 0),
(39, '2025-01-23', 2025, 1, 'Januari', 1, 23, 'Kamis', 4, 0),
(40, '2025-01-24', 2025, 1, 'Januari', 1, 24, 'Jumat', 4, 0),
(41, '2025-01-25', 2025, 1, 'Januari', 1, 25, 'Sabtu', 4, 1),
(42, '2025-01-26', 2025, 1, 'Januari', 1, 26, 'Minggu', 4, 1),
(43, '2025-01-27', 2025, 1, 'Januari', 1, 27, 'Senin', 5, 0),
(44, '2025-01-28', 2025, 1, 'Januari', 1, 28, 'Selasa', 5, 0),
(45, '2025-01-29', 2025, 1, 'Januari', 1, 29, 'Rabu', 5, 0),
(46, '2025-01-30', 2025, 1, 'Januari', 1, 30, 'Kamis', 5, 0),
(47, '2025-01-31', 2025, 1, 'Januari', 1, 31, 'Jumat', 5, 0),
(48, '2025-02-01', 2025, 2, 'Februari', 1, 1, 'Sabtu', 5, 1),
(49, '2025-02-02', 2025, 2, 'Februari', 1, 2, 'Minggu', 5, 1),
(50, '2025-02-03', 2025, 2, 'Februari', 1, 3, 'Senin', 6, 0),
(51, '2025-02-04', 2025, 2, 'Februari', 1, 4, 'Selasa', 6, 0),
(52, '2025-02-05', 2025, 2, 'Februari', 1, 5, 'Rabu', 6, 0),
(53, '2025-02-06', 2025, 2, 'Februari', 1, 6, 'Kamis', 6, 0),
(54, '2025-02-07', 2025, 2, 'Februari', 1, 7, 'Jumat', 6, 0),
(55, '2025-02-08', 2025, 2, 'Februari', 1, 8, 'Sabtu', 6, 1),
(56, '2025-02-09', 2025, 2, 'Februari', 1, 9, 'Minggu', 6, 1),
(57, '2025-02-10', 2025, 2, 'Februari', 1, 10, 'Senin', 7, 0),
(58, '2025-02-11', 2025, 2, 'Februari', 1, 11, 'Selasa', 7, 0),
(59, '2025-02-12', 2025, 2, 'Februari', 1, 12, 'Rabu', 7, 0),
(60, '2025-02-13', 2025, 2, 'Februari', 1, 13, 'Kamis', 7, 0),
(61, '2025-02-14', 2025, 2, 'Februari', 1, 14, 'Jumat', 7, 0),
(62, '2025-02-15', 2025, 2, 'Februari', 1, 15, 'Sabtu', 7, 1),
(63, '2025-02-16', 2025, 2, 'Februari', 1, 16, 'Minggu', 7, 1),
(64, '2025-02-17', 2025, 2, 'Februari', 1, 17, 'Senin', 8, 0),
(65, '2025-02-18', 2025, 2, 'Februari', 1, 18, 'Selasa', 8, 0),
(66, '2025-02-19', 2025, 2, 'Februari', 1, 19, 'Rabu', 8, 0),
(67, '2025-02-20', 2025, 2, 'Februari', 1, 20, 'Kamis', 8, 0),
(68, '2025-02-21', 2025, 2, 'Februari', 1, 21, 'Jumat', 8, 0),
(69, '2025-02-22', 2025, 2, 'Februari', 1, 22, 'Sabtu', 8, 1),
(70, '2025-02-23', 2025, 2, 'Februari', 1, 23, 'Minggu', 8, 1),
(71, '2025-02-24', 2025, 2, 'Februari', 1, 24, 'Senin', 9, 0),
(72, '2025-02-25', 2025, 2, 'Februari', 1, 25, 'Selasa', 9, 0),
(73, '2025-02-26', 2025, 2, 'Februari', 1, 26, 'Rabu', 9, 0),
(74, '2025-02-27', 2025, 2, 'Februari', 1, 27, 'Kamis', 9, 0),
(75, '2025-02-28', 2025, 2, 'Februari', 1, 28, 'Jumat', 9, 0),
(76, '2025-03-01', 2025, 3, 'Maret', 1, 1, 'Sabtu', 9, 1),
(77, '2025-03-02', 2025, 3, 'Maret', 1, 2, 'Minggu', 9, 1),
(78, '2025-03-03', 2025, 3, 'Maret', 1, 3, 'Senin', 10, 0),
(79, '2025-03-04', 2025, 3, 'Maret', 1, 4, 'Selasa', 10, 0),
(80, '2025-03-05', 2025, 3, 'Maret', 1, 5, 'Rabu', 10, 0),
(81, '2025-03-06', 2025, 3, 'Maret', 1, 6, 'Kamis', 10, 0),
(82, '2025-03-07', 2025, 3, 'Maret', 1, 7, 'Jumat', 10, 0),
(83, '2025-03-08', 2025, 3, 'Maret', 1, 8, 'Sabtu', 10, 1),
(84, '2025-03-09', 2025, 3, 'Maret', 1, 9, 'Minggu', 10, 1),
(85, '2025-03-10', 2025, 3, 'Maret', 1, 10, 'Senin', 11, 0),
(86, '2025-03-11', 2025, 3, 'Maret', 1, 11, 'Selasa', 11, 0),
(87, '2025-03-12', 2025, 3, 'Maret', 1, 12, 'Rabu', 11, 0),
(88, '2025-03-13', 2025, 3, 'Maret', 1, 13, 'Kamis', 11, 0),
(89, '2025-03-14', 2025, 3, 'Maret', 1, 14, 'Jumat', 11, 0),
(90, '2025-03-15', 2025, 3, 'Maret', 1, 15, 'Sabtu', 11, 1),
(91, '2025-03-16', 2025, 3, 'Maret', 1, 16, 'Minggu', 11, 1),
(92, '2025-03-17', 2025, 3, 'Maret', 1, 17, 'Senin', 12, 0),
(93, '2025-03-18', 2025, 3, 'Maret', 1, 18, 'Selasa', 12, 0),
(94, '2025-03-19', 2025, 3, 'Maret', 1, 19, 'Rabu', 12, 0),
(95, '2025-03-20', 2025, 3, 'Maret', 1, 20, 'Kamis', 12, 0),
(96, '2025-03-21', 2025, 3, 'Maret', 1, 21, 'Jumat', 12, 0),
(97, '2025-03-22', 2025, 3, 'Maret', 1, 22, 'Sabtu', 12, 1),
(98, '2025-03-23', 2025, 3, 'Maret', 1, 23, 'Minggu', 12, 1),
(99, '2025-03-24', 2025, 3, 'Maret', 1, 24, 'Senin', 13, 0),
(100, '2025-03-25', 2025, 3, 'Maret', 1, 25, 'Selasa', 13, 0),
(101, '2025-03-26', 2025, 3, 'Maret', 1, 26, 'Rabu', 13, 0),
(102, '2025-03-27', 2025, 3, 'Maret', 1, 27, 'Kamis', 13, 0),
(103, '2025-03-28', 2025, 3, 'Maret', 1, 28, 'Jumat', 13, 0),
(104, '2025-03-29', 2025, 3, 'Maret', 1, 29, 'Sabtu', 13, 1),
(105, '2025-03-30', 2025, 3, 'Maret', 1, 30, 'Minggu', 13, 1),
(106, '2025-03-31', 2025, 3, 'Maret', 1, 31, 'Senin', 14, 0),
(107, '2025-04-01', 2025, 4, 'April', 2, 1, 'Selasa', 14, 0),
(108, '2025-04-02', 2025, 4, 'April', 2, 2, 'Rabu', 14, 0),
(109, '2025-04-03', 2025, 4, 'April', 2, 3, 'Kamis', 14, 0),
(110, '2025-04-04', 2025, 4, 'April', 2, 4, 'Jumat', 14, 0),
(111, '2025-04-05', 2025, 4, 'April', 2, 5, 'Sabtu', 14, 1),
(112, '2025-04-06', 2025, 4, 'April', 2, 6, 'Minggu', 14, 1),
(113, '2025-04-07', 2025, 4, 'April', 2, 7, 'Senin', 15, 0),
(114, '2025-04-08', 2025, 4, 'April', 2, 8, 'Selasa', 15, 0),
(115, '2025-04-09', 2025, 4, 'April', 2, 9, 'Rabu', 15, 0),
(116, '2025-04-10', 2025, 4, 'April', 2, 10, 'Kamis', 15, 0),
(117, '2025-04-11', 2025, 4, 'April', 2, 11, 'Jumat', 15, 0),
(118, '2025-04-12', 2025, 4, 'April', 2, 12, 'Sabtu', 15, 1),
(119, '2025-04-13', 2025, 4, 'April', 2, 13, 'Minggu', 15, 1),
(120, '2025-04-14', 2025, 4, 'April', 2, 14, 'Senin', 16, 0),
(121, '2025-04-15', 2025, 4, 'April', 2, 15, 'Selasa', 16, 0),
(122, '2025-04-16', 2025, 4, 'April', 2, 16, 'Rabu', 16, 0),
(123, '2025-04-17', 2025, 4, 'April', 2, 17, 'Kamis', 16, 0),
(124, '2025-04-18', 2025, 4, 'April', 2, 18, 'Jumat', 16, 0),
(125, '2025-04-19', 2025, 4, 'April', 2, 19, 'Sabtu', 16, 1),
(126, '2025-04-20', 2025, 4, 'April', 2, 20, 'Minggu', 16, 1),
(127, '2025-04-21', 2025, 4, 'April', 2, 21, 'Senin', 17, 0),
(128, '2025-04-22', 2025, 4, 'April', 2, 22, 'Selasa', 17, 0),
(129, '2025-04-23', 2025, 4, 'April', 2, 23, 'Rabu', 17, 0),
(130, '2025-04-24', 2025, 4, 'April', 2, 24, 'Kamis', 17, 0),
(131, '2025-04-25', 2025, 4, 'April', 2, 25, 'Jumat', 17, 0),
(132, '2025-04-26', 2025, 4, 'April', 2, 26, 'Sabtu', 17, 1),
(133, '2025-04-27', 2025, 4, 'April', 2, 27, 'Minggu', 17, 1),
(134, '2025-04-28', 2025, 4, 'April', 2, 28, 'Senin', 18, 0),
(135, '2025-04-29', 2025, 4, 'April', 2, 29, 'Selasa', 18, 0),
(136, '2025-04-30', 2025, 4, 'April', 2, 30, 'Rabu', 18, 0),
(137, '2025-05-01', 2025, 5, 'Mei', 2, 1, 'Kamis', 18, 0),
(138, '2025-05-02', 2025, 5, 'Mei', 2, 2, 'Jumat', 18, 0),
(139, '2025-05-03', 2025, 5, 'Mei', 2, 3, 'Sabtu', 18, 1),
(140, '2025-05-04', 2025, 5, 'Mei', 2, 4, 'Minggu', 18, 1),
(141, '2025-05-05', 2025, 5, 'Mei', 2, 5, 'Senin', 19, 0),
(142, '2025-05-06', 2025, 5, 'Mei', 2, 6, 'Selasa', 19, 0),
(143, '2025-05-07', 2025, 5, 'Mei', 2, 7, 'Rabu', 19, 0),
(144, '2025-05-08', 2025, 5, 'Mei', 2, 8, 'Kamis', 19, 0),
(145, '2025-05-09', 2025, 5, 'Mei', 2, 9, 'Jumat', 19, 0),
(146, '2025-05-10', 2025, 5, 'Mei', 2, 10, 'Sabtu', 19, 1),
(147, '2025-05-11', 2025, 5, 'Mei', 2, 11, 'Minggu', 19, 1),
(148, '2025-05-12', 2025, 5, 'Mei', 2, 12, 'Senin', 20, 0),
(149, '2025-05-13', 2025, 5, 'Mei', 2, 13, 'Selasa', 20, 0),
(150, '2025-05-14', 2025, 5, 'Mei', 2, 14, 'Rabu', 20, 0),
(151, '2025-05-15', 2025, 5, 'Mei', 2, 15, 'Kamis', 20, 0),
(152, '2025-05-16', 2025, 5, 'Mei', 2, 16, 'Jumat', 20, 0),
(153, '2025-05-17', 2025, 5, 'Mei', 2, 17, 'Sabtu', 20, 1),
(154, '2025-05-18', 2025, 5, 'Mei', 2, 18, 'Minggu', 20, 1),
(155, '2025-05-19', 2025, 5, 'Mei', 2, 19, 'Senin', 21, 0),
(156, '2025-05-20', 2025, 5, 'Mei', 2, 20, 'Selasa', 21, 0),
(157, '2025-05-21', 2025, 5, 'Mei', 2, 21, 'Rabu', 21, 0),
(158, '2025-05-22', 2025, 5, 'Mei', 2, 22, 'Kamis', 21, 0),
(159, '2025-05-23', 2025, 5, 'Mei', 2, 23, 'Jumat', 21, 0),
(160, '2025-05-24', 2025, 5, 'Mei', 2, 24, 'Sabtu', 21, 1),
(161, '2025-05-25', 2025, 5, 'Mei', 2, 25, 'Minggu', 21, 1),
(162, '2025-05-26', 2025, 5, 'Mei', 2, 26, 'Senin', 22, 0),
(163, '2025-05-27', 2025, 5, 'Mei', 2, 27, 'Selasa', 22, 0),
(164, '2025-05-28', 2025, 5, 'Mei', 2, 28, 'Rabu', 22, 0),
(165, '2025-05-29', 2025, 5, 'Mei', 2, 29, 'Kamis', 22, 0),
(166, '2025-05-30', 2025, 5, 'Mei', 2, 30, 'Jumat', 22, 0),
(167, '2025-05-31', 2025, 5, 'Mei', 2, 31, 'Sabtu', 22, 1),
(168, '2025-06-01', 2025, 6, 'Juni', 2, 1, 'Minggu', 22, 1),
(169, '2025-06-02', 2025, 6, 'Juni', 2, 2, 'Senin', 23, 0),
(170, '2025-06-03', 2025, 6, 'Juni', 2, 3, 'Selasa', 23, 0),
(171, '2025-06-04', 2025, 6, 'Juni', 2, 4, 'Rabu', 23, 0),
(172, '2025-06-05', 2025, 6, 'Juni', 2, 5, 'Kamis', 23, 0),
(173, '2025-06-06', 2025, 6, 'Juni', 2, 6, 'Jumat', 23, 0),
(174, '2025-06-07', 2025, 6, 'Juni', 2, 7, 'Sabtu', 23, 1),
(175, '2025-06-08', 2025, 6, 'Juni', 2, 8, 'Minggu', 23, 1),
(176, '2025-06-09', 2025, 6, 'Juni', 2, 9, 'Senin', 24, 0),
(177, '2025-06-10', 2025, 6, 'Juni', 2, 10, 'Selasa', 24, 0),
(178, '2025-06-11', 2025, 6, 'Juni', 2, 11, 'Rabu', 24, 0),
(179, '2025-06-12', 2025, 6, 'Juni', 2, 12, 'Kamis', 24, 0),
(180, '2025-06-13', 2025, 6, 'Juni', 2, 13, 'Jumat', 24, 0),
(181, '2025-06-14', 2025, 6, 'Juni', 2, 14, 'Sabtu', 24, 1),
(182, '2025-06-15', 2025, 6, 'Juni', 2, 15, 'Minggu', 24, 1),
(183, '2025-06-16', 2025, 6, 'Juni', 2, 16, 'Senin', 25, 0),
(184, '2025-06-17', 2025, 6, 'Juni', 2, 17, 'Selasa', 25, 0),
(185, '2025-06-18', 2025, 6, 'Juni', 2, 18, 'Rabu', 25, 0),
(186, '2025-06-19', 2025, 6, 'Juni', 2, 19, 'Kamis', 25, 0),
(187, '2025-06-20', 2025, 6, 'Juni', 2, 20, 'Jumat', 25, 0),
(188, '2025-06-21', 2025, 6, 'Juni', 2, 21, 'Sabtu', 25, 1),
(189, '2025-06-22', 2025, 6, 'Juni', 2, 22, 'Minggu', 25, 1),
(190, '2025-06-23', 2025, 6, 'Juni', 2, 23, 'Senin', 26, 0),
(191, '2025-06-24', 2025, 6, 'Juni', 2, 24, 'Selasa', 26, 0),
(192, '2025-06-25', 2025, 6, 'Juni', 2, 25, 'Rabu', 26, 0),
(193, '2025-06-26', 2025, 6, 'Juni', 2, 26, 'Kamis', 26, 0),
(194, '2025-06-27', 2025, 6, 'Juni', 2, 27, 'Jumat', 26, 0),
(195, '2025-06-28', 2025, 6, 'Juni', 2, 28, 'Sabtu', 26, 1),
(196, '2025-06-29', 2025, 6, 'Juni', 2, 29, 'Minggu', 26, 1),
(197, '2025-06-30', 2025, 6, 'Juni', 2, 30, 'Senin', 27, 0),
(198, '2025-07-01', 2025, 7, 'Juli', 3, 1, 'Selasa', 27, 0),
(199, '2025-07-02', 2025, 7, 'Juli', 3, 2, 'Rabu', 27, 0),
(200, '2025-07-03', 2025, 7, 'Juli', 3, 3, 'Kamis', 27, 0),
(201, '2025-07-04', 2025, 7, 'Juli', 3, 4, 'Jumat', 27, 0),
(202, '2025-07-05', 2025, 7, 'Juli', 3, 5, 'Sabtu', 27, 1),
(203, '2025-07-06', 2025, 7, 'Juli', 3, 6, 'Minggu', 27, 1),
(204, '2025-07-07', 2025, 7, 'Juli', 3, 7, 'Senin', 28, 0),
(205, '2025-07-08', 2025, 7, 'Juli', 3, 8, 'Selasa', 28, 0),
(206, '2025-07-09', 2025, 7, 'Juli', 3, 9, 'Rabu', 28, 0),
(207, '2025-07-10', 2025, 7, 'Juli', 3, 10, 'Kamis', 28, 0),
(208, '2025-07-11', 2025, 7, 'Juli', 3, 11, 'Jumat', 28, 0),
(209, '2025-07-12', 2025, 7, 'Juli', 3, 12, 'Sabtu', 28, 1),
(210, '2025-07-13', 2025, 7, 'Juli', 3, 13, 'Minggu', 28, 1),
(211, '2025-07-14', 2025, 7, 'Juli', 3, 14, 'Senin', 29, 0),
(212, '2025-07-15', 2025, 7, 'Juli', 3, 15, 'Selasa', 29, 0),
(213, '2025-07-16', 2025, 7, 'Juli', 3, 16, 'Rabu', 29, 0),
(214, '2025-07-17', 2025, 7, 'Juli', 3, 17, 'Kamis', 29, 0),
(215, '2025-07-18', 2025, 7, 'Juli', 3, 18, 'Jumat', 29, 0),
(216, '2025-07-19', 2025, 7, 'Juli', 3, 19, 'Sabtu', 29, 1),
(217, '2025-07-20', 2025, 7, 'Juli', 3, 20, 'Minggu', 29, 1),
(218, '2025-07-21', 2025, 7, 'Juli', 3, 21, 'Senin', 30, 0),
(219, '2025-07-22', 2025, 7, 'Juli', 3, 22, 'Selasa', 30, 0),
(220, '2025-07-23', 2025, 7, 'Juli', 3, 23, 'Rabu', 30, 0),
(221, '2025-07-24', 2025, 7, 'Juli', 3, 24, 'Kamis', 30, 0),
(222, '2025-07-25', 2025, 7, 'Juli', 3, 25, 'Jumat', 30, 0),
(223, '2025-07-26', 2025, 7, 'Juli', 3, 26, 'Sabtu', 30, 1),
(224, '2025-07-27', 2025, 7, 'Juli', 3, 27, 'Minggu', 30, 1),
(225, '2025-07-28', 2025, 7, 'Juli', 3, 28, 'Senin', 31, 0),
(226, '2025-07-29', 2025, 7, 'Juli', 3, 29, 'Selasa', 31, 0),
(227, '2025-07-30', 2025, 7, 'Juli', 3, 30, 'Rabu', 31, 0),
(228, '2025-07-31', 2025, 7, 'Juli', 3, 31, 'Kamis', 31, 0),
(229, '2025-08-01', 2025, 8, 'Agustus', 3, 1, 'Jumat', 31, 0),
(230, '2025-08-02', 2025, 8, 'Agustus', 3, 2, 'Sabtu', 31, 1),
(231, '2025-08-03', 2025, 8, 'Agustus', 3, 3, 'Minggu', 31, 1),
(232, '2025-08-04', 2025, 8, 'Agustus', 3, 4, 'Senin', 32, 0),
(233, '2025-08-05', 2025, 8, 'Agustus', 3, 5, 'Selasa', 32, 0),
(234, '2025-08-06', 2025, 8, 'Agustus', 3, 6, 'Rabu', 32, 0),
(235, '2025-08-07', 2025, 8, 'Agustus', 3, 7, 'Kamis', 32, 0),
(236, '2025-08-08', 2025, 8, 'Agustus', 3, 8, 'Jumat', 32, 0),
(237, '2025-08-09', 2025, 8, 'Agustus', 3, 9, 'Sabtu', 32, 1),
(238, '2025-08-10', 2025, 8, 'Agustus', 3, 10, 'Minggu', 32, 1),
(239, '2025-08-11', 2025, 8, 'Agustus', 3, 11, 'Senin', 33, 0),
(240, '2025-08-12', 2025, 8, 'Agustus', 3, 12, 'Selasa', 33, 0),
(241, '2025-08-13', 2025, 8, 'Agustus', 3, 13, 'Rabu', 33, 0),
(242, '2025-08-14', 2025, 8, 'Agustus', 3, 14, 'Kamis', 33, 0),
(243, '2025-08-15', 2025, 8, 'Agustus', 3, 15, 'Jumat', 33, 0),
(244, '2025-08-16', 2025, 8, 'Agustus', 3, 16, 'Sabtu', 33, 1),
(245, '2025-08-17', 2025, 8, 'Agustus', 3, 17, 'Minggu', 33, 1),
(246, '2025-08-18', 2025, 8, 'Agustus', 3, 18, 'Senin', 34, 0),
(247, '2025-08-19', 2025, 8, 'Agustus', 3, 19, 'Selasa', 34, 0),
(248, '2025-08-20', 2025, 8, 'Agustus', 3, 20, 'Rabu', 34, 0),
(249, '2025-08-21', 2025, 8, 'Agustus', 3, 21, 'Kamis', 34, 0),
(250, '2025-08-22', 2025, 8, 'Agustus', 3, 22, 'Jumat', 34, 0),
(251, '2025-08-23', 2025, 8, 'Agustus', 3, 23, 'Sabtu', 34, 1),
(252, '2025-08-24', 2025, 8, 'Agustus', 3, 24, 'Minggu', 34, 1),
(253, '2025-08-25', 2025, 8, 'Agustus', 3, 25, 'Senin', 35, 0),
(254, '2025-08-26', 2025, 8, 'Agustus', 3, 26, 'Selasa', 35, 0),
(255, '2025-08-27', 2025, 8, 'Agustus', 3, 27, 'Rabu', 35, 0),
(256, '2025-08-28', 2025, 8, 'Agustus', 3, 28, 'Kamis', 35, 0),
(257, '2025-08-29', 2025, 8, 'Agustus', 3, 29, 'Jumat', 35, 0),
(258, '2025-08-30', 2025, 8, 'Agustus', 3, 30, 'Sabtu', 35, 1),
(259, '2025-08-31', 2025, 8, 'Agustus', 3, 31, 'Minggu', 35, 1),
(260, '2025-09-01', 2025, 9, 'September', 3, 1, 'Senin', 36, 0),
(261, '2025-09-02', 2025, 9, 'September', 3, 2, 'Selasa', 36, 0),
(262, '2025-09-03', 2025, 9, 'September', 3, 3, 'Rabu', 36, 0),
(263, '2025-09-04', 2025, 9, 'September', 3, 4, 'Kamis', 36, 0),
(264, '2025-09-05', 2025, 9, 'September', 3, 5, 'Jumat', 36, 0),
(265, '2025-09-06', 2025, 9, 'September', 3, 6, 'Sabtu', 36, 1),
(266, '2025-09-07', 2025, 9, 'September', 3, 7, 'Minggu', 36, 1),
(267, '2025-09-08', 2025, 9, 'September', 3, 8, 'Senin', 37, 0),
(268, '2025-09-09', 2025, 9, 'September', 3, 9, 'Selasa', 37, 0),
(269, '2025-09-10', 2025, 9, 'September', 3, 10, 'Rabu', 37, 0),
(270, '2025-09-11', 2025, 9, 'September', 3, 11, 'Kamis', 37, 0),
(271, '2025-09-12', 2025, 9, 'September', 3, 12, 'Jumat', 37, 0),
(272, '2025-09-13', 2025, 9, 'September', 3, 13, 'Sabtu', 37, 1),
(273, '2025-09-14', 2025, 9, 'September', 3, 14, 'Minggu', 37, 1),
(274, '2025-09-15', 2025, 9, 'September', 3, 15, 'Senin', 38, 0),
(275, '2025-09-16', 2025, 9, 'September', 3, 16, 'Selasa', 38, 0),
(276, '2025-09-17', 2025, 9, 'September', 3, 17, 'Rabu', 38, 0),
(277, '2025-09-18', 2025, 9, 'September', 3, 18, 'Kamis', 38, 0),
(278, '2025-09-19', 2025, 9, 'September', 3, 19, 'Jumat', 38, 0),
(279, '2025-09-20', 2025, 9, 'September', 3, 20, 'Sabtu', 38, 1),
(280, '2025-09-21', 2025, 9, 'September', 3, 21, 'Minggu', 38, 1),
(281, '2025-09-22', 2025, 9, 'September', 3, 22, 'Senin', 39, 0),
(282, '2025-09-23', 2025, 9, 'September', 3, 23, 'Selasa', 39, 0),
(283, '2025-09-24', 2025, 9, 'September', 3, 24, 'Rabu', 39, 0),
(284, '2025-09-25', 2025, 9, 'September', 3, 25, 'Kamis', 39, 0),
(285, '2025-09-26', 2025, 9, 'September', 3, 26, 'Jumat', 39, 0),
(286, '2025-09-27', 2025, 9, 'September', 3, 27, 'Sabtu', 39, 1),
(287, '2025-09-28', 2025, 9, 'September', 3, 28, 'Minggu', 39, 1),
(288, '2025-09-29', 2025, 9, 'September', 3, 29, 'Senin', 40, 0),
(289, '2025-09-30', 2025, 9, 'September', 3, 30, 'Selasa', 40, 0),
(290, '2025-10-01', 2025, 10, 'Oktober', 4, 1, 'Rabu', 40, 0),
(291, '2025-10-02', 2025, 10, 'Oktober', 4, 2, 'Kamis', 40, 0),
(292, '2025-10-03', 2025, 10, 'Oktober', 4, 3, 'Jumat', 40, 0),
(293, '2025-10-04', 2025, 10, 'Oktober', 4, 4, 'Sabtu', 40, 1),
(294, '2025-10-05', 2025, 10, 'Oktober', 4, 5, 'Minggu', 40, 1),
(295, '2025-10-06', 2025, 10, 'Oktober', 4, 6, 'Senin', 41, 0),
(296, '2025-10-07', 2025, 10, 'Oktober', 4, 7, 'Selasa', 41, 0),
(297, '2025-10-08', 2025, 10, 'Oktober', 4, 8, 'Rabu', 41, 0),
(298, '2025-10-09', 2025, 10, 'Oktober', 4, 9, 'Kamis', 41, 0),
(299, '2025-10-10', 2025, 10, 'Oktober', 4, 10, 'Jumat', 41, 0),
(300, '2025-10-11', 2025, 10, 'Oktober', 4, 11, 'Sabtu', 41, 1),
(301, '2025-10-12', 2025, 10, 'Oktober', 4, 12, 'Minggu', 41, 1),
(302, '2025-10-13', 2025, 10, 'Oktober', 4, 13, 'Senin', 42, 0),
(303, '2025-10-14', 2025, 10, 'Oktober', 4, 14, 'Selasa', 42, 0),
(304, '2025-10-15', 2025, 10, 'Oktober', 4, 15, 'Rabu', 42, 0),
(305, '2025-10-16', 2025, 10, 'Oktober', 4, 16, 'Kamis', 42, 0),
(306, '2025-10-17', 2025, 10, 'Oktober', 4, 17, 'Jumat', 42, 0),
(307, '2025-10-18', 2025, 10, 'Oktober', 4, 18, 'Sabtu', 42, 1),
(308, '2025-10-19', 2025, 10, 'Oktober', 4, 19, 'Minggu', 42, 1),
(309, '2025-10-20', 2025, 10, 'Oktober', 4, 20, 'Senin', 43, 0),
(310, '2025-10-21', 2025, 10, 'Oktober', 4, 21, 'Selasa', 43, 0),
(311, '2025-10-22', 2025, 10, 'Oktober', 4, 22, 'Rabu', 43, 0),
(312, '2025-10-23', 2025, 10, 'Oktober', 4, 23, 'Kamis', 43, 0),
(313, '2025-10-24', 2025, 10, 'Oktober', 4, 24, 'Jumat', 43, 0),
(314, '2025-10-25', 2025, 10, 'Oktober', 4, 25, 'Sabtu', 43, 1),
(315, '2025-10-26', 2025, 10, 'Oktober', 4, 26, 'Minggu', 43, 1),
(316, '2025-10-27', 2025, 10, 'Oktober', 4, 27, 'Senin', 44, 0),
(317, '2025-10-28', 2025, 10, 'Oktober', 4, 28, 'Selasa', 44, 0),
(318, '2025-10-29', 2025, 10, 'Oktober', 4, 29, 'Rabu', 44, 0),
(319, '2025-10-30', 2025, 10, 'Oktober', 4, 30, 'Kamis', 44, 0),
(320, '2025-10-31', 2025, 10, 'Oktober', 4, 31, 'Jumat', 44, 0),
(321, '2025-11-01', 2025, 11, 'November', 4, 1, 'Sabtu', 44, 1),
(322, '2025-11-02', 2025, 11, 'November', 4, 2, 'Minggu', 44, 1),
(323, '2025-11-03', 2025, 11, 'November', 4, 3, 'Senin', 45, 0),
(324, '2025-11-04', 2025, 11, 'November', 4, 4, 'Selasa', 45, 0),
(325, '2025-11-05', 2025, 11, 'November', 4, 5, 'Rabu', 45, 0),
(326, '2025-11-06', 2025, 11, 'November', 4, 6, 'Kamis', 45, 0),
(327, '2025-11-07', 2025, 11, 'November', 4, 7, 'Jumat', 45, 0),
(328, '2025-11-08', 2025, 11, 'November', 4, 8, 'Sabtu', 45, 1),
(329, '2025-11-09', 2025, 11, 'November', 4, 9, 'Minggu', 45, 1),
(330, '2025-11-10', 2025, 11, 'November', 4, 10, 'Senin', 46, 0),
(331, '2025-11-11', 2025, 11, 'November', 4, 11, 'Selasa', 46, 0),
(332, '2025-11-12', 2025, 11, 'November', 4, 12, 'Rabu', 46, 0),
(333, '2025-11-13', 2025, 11, 'November', 4, 13, 'Kamis', 46, 0),
(334, '2025-11-14', 2025, 11, 'November', 4, 14, 'Jumat', 46, 0),
(335, '2025-11-15', 2025, 11, 'November', 4, 15, 'Sabtu', 46, 1),
(336, '2025-11-16', 2025, 11, 'November', 4, 16, 'Minggu', 46, 1),
(337, '2025-11-17', 2025, 11, 'November', 4, 17, 'Senin', 47, 0),
(338, '2025-11-18', 2025, 11, 'November', 4, 18, 'Selasa', 47, 0),
(339, '2025-11-19', 2025, 11, 'November', 4, 19, 'Rabu', 47, 0),
(340, '2025-11-20', 2025, 11, 'November', 4, 20, 'Kamis', 47, 0),
(341, '2025-11-21', 2025, 11, 'November', 4, 21, 'Jumat', 47, 0),
(342, '2025-11-22', 2025, 11, 'November', 4, 22, 'Sabtu', 47, 1),
(343, '2025-11-23', 2025, 11, 'November', 4, 23, 'Minggu', 47, 1),
(344, '2025-11-24', 2025, 11, 'November', 4, 24, 'Senin', 48, 0),
(345, '2025-11-25', 2025, 11, 'November', 4, 25, 'Selasa', 48, 0),
(346, '2025-11-26', 2025, 11, 'November', 4, 26, 'Rabu', 48, 0),
(347, '2025-11-27', 2025, 11, 'November', 4, 27, 'Kamis', 48, 0),
(348, '2025-11-28', 2025, 11, 'November', 4, 28, 'Jumat', 48, 0),
(349, '2025-11-29', 2025, 11, 'November', 4, 29, 'Sabtu', 48, 1),
(350, '2025-11-30', 2025, 11, 'November', 4, 30, 'Minggu', 48, 1),
(351, '2025-12-01', 2025, 12, 'Desember', 4, 1, 'Senin', 49, 0),
(352, '2025-12-02', 2025, 12, 'Desember', 4, 2, 'Selasa', 49, 0),
(353, '2025-12-03', 2025, 12, 'Desember', 4, 3, 'Rabu', 49, 0),
(354, '2025-12-04', 2025, 12, 'Desember', 4, 4, 'Kamis', 49, 0),
(355, '2025-12-05', 2025, 12, 'Desember', 4, 5, 'Jumat', 49, 0),
(356, '2025-12-06', 2025, 12, 'Desember', 4, 6, 'Sabtu', 49, 1),
(357, '2025-12-07', 2025, 12, 'Desember', 4, 7, 'Minggu', 49, 1),
(358, '2025-12-08', 2025, 12, 'Desember', 4, 8, 'Senin', 50, 0),
(359, '2025-12-09', 2025, 12, 'Desember', 4, 9, 'Selasa', 50, 0),
(360, '2025-12-10', 2025, 12, 'Desember', 4, 10, 'Rabu', 50, 0),
(361, '2025-12-11', 2025, 12, 'Desember', 4, 11, 'Kamis', 50, 0),
(362, '2025-12-12', 2025, 12, 'Desember', 4, 12, 'Jumat', 50, 0),
(363, '2025-12-13', 2025, 12, 'Desember', 4, 13, 'Sabtu', 50, 1),
(364, '2025-12-14', 2025, 12, 'Desember', 4, 14, 'Minggu', 50, 1),
(365, '2025-12-15', 2025, 12, 'Desember', 4, 15, 'Senin', 51, 0),
(366, '2025-12-16', 2025, 12, 'Desember', 4, 16, 'Selasa', 51, 0),
(367, '2025-12-17', 2025, 12, 'Desember', 4, 17, 'Rabu', 51, 0),
(368, '2025-12-18', 2025, 12, 'Desember', 4, 18, 'Kamis', 51, 0),
(369, '2025-12-19', 2025, 12, 'Desember', 4, 19, 'Jumat', 51, 0),
(370, '2025-12-20', 2025, 12, 'Desember', 4, 20, 'Sabtu', 51, 1),
(371, '2025-12-21', 2025, 12, 'Desember', 4, 21, 'Minggu', 51, 1),
(372, '2025-12-22', 2025, 12, 'Desember', 4, 22, 'Senin', 52, 0),
(373, '2025-12-23', 2025, 12, 'Desember', 4, 23, 'Selasa', 52, 0),
(374, '2025-12-24', 2025, 12, 'Desember', 4, 24, 'Rabu', 52, 0),
(375, '2025-12-25', 2025, 12, 'Desember', 4, 25, 'Kamis', 52, 0),
(376, '2025-12-26', 2025, 12, 'Desember', 4, 26, 'Jumat', 52, 0),
(377, '2025-12-27', 2025, 12, 'Desember', 4, 27, 'Sabtu', 52, 1),
(378, '2025-12-28', 2025, 12, 'Desember', 4, 28, 'Minggu', 52, 1),
(379, '2025-12-29', 2025, 12, 'Desember', 4, 29, 'Senin', 1, 0),
(380, '2025-12-30', 2025, 12, 'Desember', 4, 30, 'Selasa', 1, 0),
(381, '2025-12-31', 2025, 12, 'Desember', 4, 31, 'Rabu', 1, 0),
(382, '2026-01-01', 2026, 1, 'Januari', 1, 1, 'Kamis', 1, 0),
(383, '2026-01-02', 2026, 1, 'Januari', 1, 2, 'Jumat', 1, 0),
(384, '2026-01-03', 2026, 1, 'Januari', 1, 3, 'Sabtu', 1, 1),
(385, '2026-01-04', 2026, 1, 'Januari', 1, 4, 'Minggu', 1, 1),
(386, '2026-01-05', 2026, 1, 'Januari', 1, 5, 'Senin', 2, 0),
(387, '2026-01-06', 2026, 1, 'Januari', 1, 6, 'Selasa', 2, 0),
(388, '2026-01-07', 2026, 1, 'Januari', 1, 7, 'Rabu', 2, 0),
(389, '2026-01-08', 2026, 1, 'Januari', 1, 8, 'Kamis', 2, 0),
(390, '2026-01-09', 2026, 1, 'Januari', 1, 9, 'Jumat', 2, 0),
(391, '2026-01-10', 2026, 1, 'Januari', 1, 10, 'Sabtu', 2, 1),
(392, '2026-01-11', 2026, 1, 'Januari', 1, 11, 'Minggu', 2, 1),
(393, '2026-01-12', 2026, 1, 'Januari', 1, 12, 'Senin', 3, 0),
(394, '2026-01-13', 2026, 1, 'Januari', 1, 13, 'Selasa', 3, 0),
(395, '2026-01-14', 2026, 1, 'Januari', 1, 14, 'Rabu', 3, 0),
(396, '2026-01-15', 2026, 1, 'Januari', 1, 15, 'Kamis', 3, 0),
(397, '2026-01-16', 2026, 1, 'Januari', 1, 16, 'Jumat', 3, 0),
(398, '2026-01-17', 2026, 1, 'Januari', 1, 17, 'Sabtu', 3, 1),
(399, '2026-01-18', 2026, 1, 'Januari', 1, 18, 'Minggu', 3, 1),
(400, '2026-01-19', 2026, 1, 'Januari', 1, 19, 'Senin', 4, 0),
(401, '2026-01-20', 2026, 1, 'Januari', 1, 20, 'Selasa', 4, 0),
(402, '2026-01-21', 2026, 1, 'Januari', 1, 21, 'Rabu', 4, 0),
(403, '2026-01-22', 2026, 1, 'Januari', 1, 22, 'Kamis', 4, 0),
(404, '2026-01-23', 2026, 1, 'Januari', 1, 23, 'Jumat', 4, 0),
(405, '2026-01-24', 2026, 1, 'Januari', 1, 24, 'Sabtu', 4, 1),
(406, '2026-01-25', 2026, 1, 'Januari', 1, 25, 'Minggu', 4, 1),
(407, '2026-01-26', 2026, 1, 'Januari', 1, 26, 'Senin', 5, 0),
(408, '2026-01-27', 2026, 1, 'Januari', 1, 27, 'Selasa', 5, 0),
(409, '2026-01-28', 2026, 1, 'Januari', 1, 28, 'Rabu', 5, 0),
(410, '2026-01-29', 2026, 1, 'Januari', 1, 29, 'Kamis', 5, 0),
(411, '2026-01-30', 2026, 1, 'Januari', 1, 30, 'Jumat', 5, 0),
(412, '2026-01-31', 2026, 1, 'Januari', 1, 31, 'Sabtu', 5, 1),
(413, '2026-02-01', 2026, 2, 'Februari', 1, 1, 'Minggu', 5, 1),
(414, '2026-02-02', 2026, 2, 'Februari', 1, 2, 'Senin', 6, 0),
(415, '2026-02-03', 2026, 2, 'Februari', 1, 3, 'Selasa', 6, 0),
(416, '2026-02-04', 2026, 2, 'Februari', 1, 4, 'Rabu', 6, 0),
(417, '2026-02-05', 2026, 2, 'Februari', 1, 5, 'Kamis', 6, 0),
(418, '2026-02-06', 2026, 2, 'Februari', 1, 6, 'Jumat', 6, 0),
(419, '2026-02-07', 2026, 2, 'Februari', 1, 7, 'Sabtu', 6, 1),
(420, '2026-02-08', 2026, 2, 'Februari', 1, 8, 'Minggu', 6, 1),
(421, '2026-02-09', 2026, 2, 'Februari', 1, 9, 'Senin', 7, 0),
(422, '2026-02-10', 2026, 2, 'Februari', 1, 10, 'Selasa', 7, 0),
(423, '2026-02-11', 2026, 2, 'Februari', 1, 11, 'Rabu', 7, 0),
(424, '2026-02-12', 2026, 2, 'Februari', 1, 12, 'Kamis', 7, 0),
(425, '2026-02-13', 2026, 2, 'Februari', 1, 13, 'Jumat', 7, 0),
(426, '2026-02-14', 2026, 2, 'Februari', 1, 14, 'Sabtu', 7, 1),
(427, '2026-02-15', 2026, 2, 'Februari', 1, 15, 'Minggu', 7, 1),
(428, '2026-02-16', 2026, 2, 'Februari', 1, 16, 'Senin', 8, 0),
(429, '2026-02-17', 2026, 2, 'Februari', 1, 17, 'Selasa', 8, 0),
(430, '2026-02-18', 2026, 2, 'Februari', 1, 18, 'Rabu', 8, 0),
(431, '2026-02-19', 2026, 2, 'Februari', 1, 19, 'Kamis', 8, 0),
(432, '2026-02-20', 2026, 2, 'Februari', 1, 20, 'Jumat', 8, 0),
(433, '2026-02-21', 2026, 2, 'Februari', 1, 21, 'Sabtu', 8, 1),
(434, '2026-02-22', 2026, 2, 'Februari', 1, 22, 'Minggu', 8, 1),
(435, '2026-02-23', 2026, 2, 'Februari', 1, 23, 'Senin', 9, 0),
(436, '2026-02-24', 2026, 2, 'Februari', 1, 24, 'Selasa', 9, 0),
(437, '2026-02-25', 2026, 2, 'Februari', 1, 25, 'Rabu', 9, 0),
(438, '2026-02-26', 2026, 2, 'Februari', 1, 26, 'Kamis', 9, 0),
(439, '2026-02-27', 2026, 2, 'Februari', 1, 27, 'Jumat', 9, 0),
(440, '2026-02-28', 2026, 2, 'Februari', 1, 28, 'Sabtu', 9, 1),
(441, '2026-03-01', 2026, 3, 'Maret', 1, 1, 'Minggu', 9, 1),
(442, '2026-03-02', 2026, 3, 'Maret', 1, 2, 'Senin', 10, 0),
(443, '2026-03-03', 2026, 3, 'Maret', 1, 3, 'Selasa', 10, 0),
(444, '2026-03-04', 2026, 3, 'Maret', 1, 4, 'Rabu', 10, 0),
(445, '2026-03-05', 2026, 3, 'Maret', 1, 5, 'Kamis', 10, 0),
(446, '2026-03-06', 2026, 3, 'Maret', 1, 6, 'Jumat', 10, 0),
(447, '2026-03-07', 2026, 3, 'Maret', 1, 7, 'Sabtu', 10, 1),
(448, '2026-03-08', 2026, 3, 'Maret', 1, 8, 'Minggu', 10, 1),
(449, '2026-03-09', 2026, 3, 'Maret', 1, 9, 'Senin', 11, 0),
(450, '2026-03-10', 2026, 3, 'Maret', 1, 10, 'Selasa', 11, 0),
(451, '2026-03-11', 2026, 3, 'Maret', 1, 11, 'Rabu', 11, 0),
(452, '2026-03-12', 2026, 3, 'Maret', 1, 12, 'Kamis', 11, 0),
(453, '2026-03-13', 2026, 3, 'Maret', 1, 13, 'Jumat', 11, 0),
(454, '2026-03-14', 2026, 3, 'Maret', 1, 14, 'Sabtu', 11, 1),
(455, '2026-03-15', 2026, 3, 'Maret', 1, 15, 'Minggu', 11, 1),
(456, '2026-03-16', 2026, 3, 'Maret', 1, 16, 'Senin', 12, 0),
(457, '2026-03-17', 2026, 3, 'Maret', 1, 17, 'Selasa', 12, 0),
(458, '2026-03-18', 2026, 3, 'Maret', 1, 18, 'Rabu', 12, 0),
(459, '2026-03-19', 2026, 3, 'Maret', 1, 19, 'Kamis', 12, 0),
(460, '2026-03-20', 2026, 3, 'Maret', 1, 20, 'Jumat', 12, 0),
(461, '2026-03-21', 2026, 3, 'Maret', 1, 21, 'Sabtu', 12, 1),
(462, '2026-03-22', 2026, 3, 'Maret', 1, 22, 'Minggu', 12, 1),
(463, '2026-03-23', 2026, 3, 'Maret', 1, 23, 'Senin', 13, 0),
(464, '2026-03-24', 2026, 3, 'Maret', 1, 24, 'Selasa', 13, 0),
(465, '2026-03-25', 2026, 3, 'Maret', 1, 25, 'Rabu', 13, 0),
(466, '2026-03-26', 2026, 3, 'Maret', 1, 26, 'Kamis', 13, 0),
(467, '2026-03-27', 2026, 3, 'Maret', 1, 27, 'Jumat', 13, 0),
(468, '2026-03-28', 2026, 3, 'Maret', 1, 28, 'Sabtu', 13, 1),
(469, '2026-03-29', 2026, 3, 'Maret', 1, 29, 'Minggu', 13, 1),
(470, '2026-03-30', 2026, 3, 'Maret', 1, 30, 'Senin', 14, 0),
(471, '2026-03-31', 2026, 3, 'Maret', 1, 31, 'Selasa', 14, 0),
(472, '2026-04-01', 2026, 4, 'April', 2, 1, 'Rabu', 14, 0),
(473, '2026-04-02', 2026, 4, 'April', 2, 2, 'Kamis', 14, 0),
(474, '2026-04-03', 2026, 4, 'April', 2, 3, 'Jumat', 14, 0),
(475, '2026-04-04', 2026, 4, 'April', 2, 4, 'Sabtu', 14, 1),
(476, '2026-04-05', 2026, 4, 'April', 2, 5, 'Minggu', 14, 1),
(477, '2026-04-06', 2026, 4, 'April', 2, 6, 'Senin', 15, 0),
(478, '2026-04-07', 2026, 4, 'April', 2, 7, 'Selasa', 15, 0),
(479, '2026-04-08', 2026, 4, 'April', 2, 8, 'Rabu', 15, 0),
(480, '2026-04-09', 2026, 4, 'April', 2, 9, 'Kamis', 15, 0),
(481, '2026-04-10', 2026, 4, 'April', 2, 10, 'Jumat', 15, 0),
(482, '2026-04-11', 2026, 4, 'April', 2, 11, 'Sabtu', 15, 1),
(483, '2026-04-12', 2026, 4, 'April', 2, 12, 'Minggu', 15, 1),
(484, '2026-04-13', 2026, 4, 'April', 2, 13, 'Senin', 16, 0),
(485, '2026-04-14', 2026, 4, 'April', 2, 14, 'Selasa', 16, 0),
(486, '2026-04-15', 2026, 4, 'April', 2, 15, 'Rabu', 16, 0),
(487, '2026-04-16', 2026, 4, 'April', 2, 16, 'Kamis', 16, 0),
(488, '2026-04-17', 2026, 4, 'April', 2, 17, 'Jumat', 16, 0),
(489, '2026-04-18', 2026, 4, 'April', 2, 18, 'Sabtu', 16, 1),
(490, '2026-04-19', 2026, 4, 'April', 2, 19, 'Minggu', 16, 1),
(491, '2026-04-20', 2026, 4, 'April', 2, 20, 'Senin', 17, 0),
(492, '2026-04-21', 2026, 4, 'April', 2, 21, 'Selasa', 17, 0),
(493, '2026-04-22', 2026, 4, 'April', 2, 22, 'Rabu', 17, 0),
(494, '2026-04-23', 2026, 4, 'April', 2, 23, 'Kamis', 17, 0),
(495, '2026-04-24', 2026, 4, 'April', 2, 24, 'Jumat', 17, 0),
(496, '2026-04-25', 2026, 4, 'April', 2, 25, 'Sabtu', 17, 1),
(497, '2026-04-26', 2026, 4, 'April', 2, 26, 'Minggu', 17, 1),
(498, '2026-04-27', 2026, 4, 'April', 2, 27, 'Senin', 18, 0),
(499, '2026-04-28', 2026, 4, 'April', 2, 28, 'Selasa', 18, 0),
(500, '2026-04-29', 2026, 4, 'April', 2, 29, 'Rabu', 18, 0),
(501, '2026-04-30', 2026, 4, 'April', 2, 30, 'Kamis', 18, 0),
(502, '2026-05-01', 2026, 5, 'Mei', 2, 1, 'Jumat', 18, 0),
(503, '2026-05-02', 2026, 5, 'Mei', 2, 2, 'Sabtu', 18, 1),
(504, '2026-05-03', 2026, 5, 'Mei', 2, 3, 'Minggu', 18, 1),
(505, '2026-05-04', 2026, 5, 'Mei', 2, 4, 'Senin', 19, 0),
(506, '2026-05-05', 2026, 5, 'Mei', 2, 5, 'Selasa', 19, 0),
(507, '2026-05-06', 2026, 5, 'Mei', 2, 6, 'Rabu', 19, 0),
(508, '2026-05-07', 2026, 5, 'Mei', 2, 7, 'Kamis', 19, 0),
(509, '2026-05-08', 2026, 5, 'Mei', 2, 8, 'Jumat', 19, 0),
(510, '2026-05-09', 2026, 5, 'Mei', 2, 9, 'Sabtu', 19, 1),
(511, '2026-05-10', 2026, 5, 'Mei', 2, 10, 'Minggu', 19, 1),
(512, '2026-05-11', 2026, 5, 'Mei', 2, 11, 'Senin', 20, 0),
(513, '2026-05-12', 2026, 5, 'Mei', 2, 12, 'Selasa', 20, 0),
(514, '2026-05-13', 2026, 5, 'Mei', 2, 13, 'Rabu', 20, 0),
(515, '2026-05-14', 2026, 5, 'Mei', 2, 14, 'Kamis', 20, 0),
(516, '2026-05-15', 2026, 5, 'Mei', 2, 15, 'Jumat', 20, 0),
(517, '2026-05-16', 2026, 5, 'Mei', 2, 16, 'Sabtu', 20, 1),
(518, '2026-05-17', 2026, 5, 'Mei', 2, 17, 'Minggu', 20, 1),
(519, '2026-05-18', 2026, 5, 'Mei', 2, 18, 'Senin', 21, 0),
(520, '2026-05-19', 2026, 5, 'Mei', 2, 19, 'Selasa', 21, 0),
(521, '2026-05-20', 2026, 5, 'Mei', 2, 20, 'Rabu', 21, 0),
(522, '2026-05-21', 2026, 5, 'Mei', 2, 21, 'Kamis', 21, 0),
(523, '2026-05-22', 2026, 5, 'Mei', 2, 22, 'Jumat', 21, 0),
(524, '2026-05-23', 2026, 5, 'Mei', 2, 23, 'Sabtu', 21, 1),
(525, '2026-05-24', 2026, 5, 'Mei', 2, 24, 'Minggu', 21, 1),
(526, '2026-05-25', 2026, 5, 'Mei', 2, 25, 'Senin', 22, 0),
(527, '2026-05-26', 2026, 5, 'Mei', 2, 26, 'Selasa', 22, 0),
(528, '2026-05-27', 2026, 5, 'Mei', 2, 27, 'Rabu', 22, 0),
(529, '2026-05-28', 2026, 5, 'Mei', 2, 28, 'Kamis', 22, 0),
(530, '2026-05-29', 2026, 5, 'Mei', 2, 29, 'Jumat', 22, 0),
(531, '2026-05-30', 2026, 5, 'Mei', 2, 30, 'Sabtu', 22, 1),
(532, '2026-05-31', 2026, 5, 'Mei', 2, 31, 'Minggu', 22, 1),
(533, '2026-06-01', 2026, 6, 'Juni', 2, 1, 'Senin', 23, 0),
(534, '2026-06-02', 2026, 6, 'Juni', 2, 2, 'Selasa', 23, 0),
(535, '2026-06-03', 2026, 6, 'Juni', 2, 3, 'Rabu', 23, 0),
(536, '2026-06-04', 2026, 6, 'Juni', 2, 4, 'Kamis', 23, 0),
(537, '2026-06-05', 2026, 6, 'Juni', 2, 5, 'Jumat', 23, 0),
(538, '2026-06-06', 2026, 6, 'Juni', 2, 6, 'Sabtu', 23, 1),
(539, '2026-06-07', 2026, 6, 'Juni', 2, 7, 'Minggu', 23, 1),
(540, '2026-06-08', 2026, 6, 'Juni', 2, 8, 'Senin', 24, 0),
(541, '2026-06-09', 2026, 6, 'Juni', 2, 9, 'Selasa', 24, 0),
(542, '2026-06-10', 2026, 6, 'Juni', 2, 10, 'Rabu', 24, 0),
(543, '2026-06-11', 2026, 6, 'Juni', 2, 11, 'Kamis', 24, 0),
(544, '2026-06-12', 2026, 6, 'Juni', 2, 12, 'Jumat', 24, 0),
(545, '2026-06-13', 2026, 6, 'Juni', 2, 13, 'Sabtu', 24, 1),
(546, '2026-06-14', 2026, 6, 'Juni', 2, 14, 'Minggu', 24, 1),
(547, '2026-06-15', 2026, 6, 'Juni', 2, 15, 'Senin', 25, 0),
(548, '2026-06-16', 2026, 6, 'Juni', 2, 16, 'Selasa', 25, 0),
(549, '2026-06-17', 2026, 6, 'Juni', 2, 17, 'Rabu', 25, 0),
(550, '2026-06-18', 2026, 6, 'Juni', 2, 18, 'Kamis', 25, 0),
(551, '2026-06-19', 2026, 6, 'Juni', 2, 19, 'Jumat', 25, 0),
(552, '2026-06-20', 2026, 6, 'Juni', 2, 20, 'Sabtu', 25, 1),
(553, '2026-06-21', 2026, 6, 'Juni', 2, 21, 'Minggu', 25, 1),
(554, '2026-06-22', 2026, 6, 'Juni', 2, 22, 'Senin', 26, 0),
(555, '2026-06-23', 2026, 6, 'Juni', 2, 23, 'Selasa', 26, 0),
(556, '2026-06-24', 2026, 6, 'Juni', 2, 24, 'Rabu', 26, 0),
(557, '2026-06-25', 2026, 6, 'Juni', 2, 25, 'Kamis', 26, 0),
(558, '2026-06-26', 2026, 6, 'Juni', 2, 26, 'Jumat', 26, 0),
(559, '2026-06-27', 2026, 6, 'Juni', 2, 27, 'Sabtu', 26, 1),
(560, '2026-06-28', 2026, 6, 'Juni', 2, 28, 'Minggu', 26, 1),
(561, '2026-06-29', 2026, 6, 'Juni', 2, 29, 'Senin', 27, 0),
(562, '2026-06-30', 2026, 6, 'Juni', 2, 30, 'Selasa', 27, 0),
(563, '2026-07-01', 2026, 7, 'Juli', 3, 1, 'Rabu', 27, 0),
(564, '2026-07-02', 2026, 7, 'Juli', 3, 2, 'Kamis', 27, 0),
(565, '2026-07-03', 2026, 7, 'Juli', 3, 3, 'Jumat', 27, 0),
(566, '2026-07-04', 2026, 7, 'Juli', 3, 4, 'Sabtu', 27, 1),
(567, '2026-07-05', 2026, 7, 'Juli', 3, 5, 'Minggu', 27, 1),
(568, '2026-07-06', 2026, 7, 'Juli', 3, 6, 'Senin', 28, 0),
(569, '2026-07-07', 2026, 7, 'Juli', 3, 7, 'Selasa', 28, 0),
(570, '2026-07-08', 2026, 7, 'Juli', 3, 8, 'Rabu', 28, 0),
(571, '2026-07-09', 2026, 7, 'Juli', 3, 9, 'Kamis', 28, 0),
(572, '2026-07-10', 2026, 7, 'Juli', 3, 10, 'Jumat', 28, 0),
(573, '2026-07-11', 2026, 7, 'Juli', 3, 11, 'Sabtu', 28, 1),
(574, '2026-07-12', 2026, 7, 'Juli', 3, 12, 'Minggu', 28, 1),
(575, '2026-07-13', 2026, 7, 'Juli', 3, 13, 'Senin', 29, 0),
(576, '2026-07-14', 2026, 7, 'Juli', 3, 14, 'Selasa', 29, 0),
(577, '2026-07-15', 2026, 7, 'Juli', 3, 15, 'Rabu', 29, 0),
(578, '2026-07-16', 2026, 7, 'Juli', 3, 16, 'Kamis', 29, 0),
(579, '2026-07-17', 2026, 7, 'Juli', 3, 17, 'Jumat', 29, 0),
(580, '2026-07-18', 2026, 7, 'Juli', 3, 18, 'Sabtu', 29, 1),
(581, '2026-07-19', 2026, 7, 'Juli', 3, 19, 'Minggu', 29, 1),
(582, '2026-07-20', 2026, 7, 'Juli', 3, 20, 'Senin', 30, 0),
(583, '2026-07-21', 2026, 7, 'Juli', 3, 21, 'Selasa', 30, 0),
(584, '2026-07-22', 2026, 7, 'Juli', 3, 22, 'Rabu', 30, 0),
(585, '2026-07-23', 2026, 7, 'Juli', 3, 23, 'Kamis', 30, 0),
(586, '2026-07-24', 2026, 7, 'Juli', 3, 24, 'Jumat', 30, 0),
(587, '2026-07-25', 2026, 7, 'Juli', 3, 25, 'Sabtu', 30, 1),
(588, '2026-07-26', 2026, 7, 'Juli', 3, 26, 'Minggu', 30, 1),
(589, '2026-07-27', 2026, 7, 'Juli', 3, 27, 'Senin', 31, 0),
(590, '2026-07-28', 2026, 7, 'Juli', 3, 28, 'Selasa', 31, 0),
(591, '2026-07-29', 2026, 7, 'Juli', 3, 29, 'Rabu', 31, 0),
(592, '2026-07-30', 2026, 7, 'Juli', 3, 30, 'Kamis', 31, 0),
(593, '2026-07-31', 2026, 7, 'Juli', 3, 31, 'Jumat', 31, 0),
(594, '2026-08-01', 2026, 8, 'Agustus', 3, 1, 'Sabtu', 31, 1),
(595, '2026-08-02', 2026, 8, 'Agustus', 3, 2, 'Minggu', 31, 1),
(596, '2026-08-03', 2026, 8, 'Agustus', 3, 3, 'Senin', 32, 0),
(597, '2026-08-04', 2026, 8, 'Agustus', 3, 4, 'Selasa', 32, 0),
(598, '2026-08-05', 2026, 8, 'Agustus', 3, 5, 'Rabu', 32, 0),
(599, '2026-08-06', 2026, 8, 'Agustus', 3, 6, 'Kamis', 32, 0),
(600, '2026-08-07', 2026, 8, 'Agustus', 3, 7, 'Jumat', 32, 0),
(601, '2026-08-08', 2026, 8, 'Agustus', 3, 8, 'Sabtu', 32, 1),
(602, '2026-08-09', 2026, 8, 'Agustus', 3, 9, 'Minggu', 32, 1),
(603, '2026-08-10', 2026, 8, 'Agustus', 3, 10, 'Senin', 33, 0),
(604, '2026-08-11', 2026, 8, 'Agustus', 3, 11, 'Selasa', 33, 0),
(605, '2026-08-12', 2026, 8, 'Agustus', 3, 12, 'Rabu', 33, 0),
(606, '2026-08-13', 2026, 8, 'Agustus', 3, 13, 'Kamis', 33, 0),
(607, '2026-08-14', 2026, 8, 'Agustus', 3, 14, 'Jumat', 33, 0),
(608, '2026-08-15', 2026, 8, 'Agustus', 3, 15, 'Sabtu', 33, 1),
(609, '2026-08-16', 2026, 8, 'Agustus', 3, 16, 'Minggu', 33, 1),
(610, '2026-08-17', 2026, 8, 'Agustus', 3, 17, 'Senin', 34, 0),
(611, '2026-08-18', 2026, 8, 'Agustus', 3, 18, 'Selasa', 34, 0),
(612, '2026-08-19', 2026, 8, 'Agustus', 3, 19, 'Rabu', 34, 0),
(613, '2026-08-20', 2026, 8, 'Agustus', 3, 20, 'Kamis', 34, 0),
(614, '2026-08-21', 2026, 8, 'Agustus', 3, 21, 'Jumat', 34, 0),
(615, '2026-08-22', 2026, 8, 'Agustus', 3, 22, 'Sabtu', 34, 1),
(616, '2026-08-23', 2026, 8, 'Agustus', 3, 23, 'Minggu', 34, 1),
(617, '2026-08-24', 2026, 8, 'Agustus', 3, 24, 'Senin', 35, 0),
(618, '2026-08-25', 2026, 8, 'Agustus', 3, 25, 'Selasa', 35, 0),
(619, '2026-08-26', 2026, 8, 'Agustus', 3, 26, 'Rabu', 35, 0),
(620, '2026-08-27', 2026, 8, 'Agustus', 3, 27, 'Kamis', 35, 0),
(621, '2026-08-28', 2026, 8, 'Agustus', 3, 28, 'Jumat', 35, 0),
(622, '2026-08-29', 2026, 8, 'Agustus', 3, 29, 'Sabtu', 35, 1),
(623, '2026-08-30', 2026, 8, 'Agustus', 3, 30, 'Minggu', 35, 1),
(624, '2026-08-31', 2026, 8, 'Agustus', 3, 31, 'Senin', 36, 0),
(625, '2026-09-01', 2026, 9, 'September', 3, 1, 'Selasa', 36, 0),
(626, '2026-09-02', 2026, 9, 'September', 3, 2, 'Rabu', 36, 0),
(627, '2026-09-03', 2026, 9, 'September', 3, 3, 'Kamis', 36, 0),
(628, '2026-09-04', 2026, 9, 'September', 3, 4, 'Jumat', 36, 0),
(629, '2026-09-05', 2026, 9, 'September', 3, 5, 'Sabtu', 36, 1),
(630, '2026-09-06', 2026, 9, 'September', 3, 6, 'Minggu', 36, 1),
(631, '2026-09-07', 2026, 9, 'September', 3, 7, 'Senin', 37, 0),
(632, '2026-09-08', 2026, 9, 'September', 3, 8, 'Selasa', 37, 0),
(633, '2026-09-09', 2026, 9, 'September', 3, 9, 'Rabu', 37, 0),
(634, '2026-09-10', 2026, 9, 'September', 3, 10, 'Kamis', 37, 0),
(635, '2026-09-11', 2026, 9, 'September', 3, 11, 'Jumat', 37, 0),
(636, '2026-09-12', 2026, 9, 'September', 3, 12, 'Sabtu', 37, 1),
(637, '2026-09-13', 2026, 9, 'September', 3, 13, 'Minggu', 37, 1),
(638, '2026-09-14', 2026, 9, 'September', 3, 14, 'Senin', 38, 0),
(639, '2026-09-15', 2026, 9, 'September', 3, 15, 'Selasa', 38, 0),
(640, '2026-09-16', 2026, 9, 'September', 3, 16, 'Rabu', 38, 0),
(641, '2026-09-17', 2026, 9, 'September', 3, 17, 'Kamis', 38, 0),
(642, '2026-09-18', 2026, 9, 'September', 3, 18, 'Jumat', 38, 0),
(643, '2026-09-19', 2026, 9, 'September', 3, 19, 'Sabtu', 38, 1),
(644, '2026-09-20', 2026, 9, 'September', 3, 20, 'Minggu', 38, 1),
(645, '2026-09-21', 2026, 9, 'September', 3, 21, 'Senin', 39, 0),
(646, '2026-09-22', 2026, 9, 'September', 3, 22, 'Selasa', 39, 0),
(647, '2026-09-23', 2026, 9, 'September', 3, 23, 'Rabu', 39, 0),
(648, '2026-09-24', 2026, 9, 'September', 3, 24, 'Kamis', 39, 0),
(649, '2026-09-25', 2026, 9, 'September', 3, 25, 'Jumat', 39, 0),
(650, '2026-09-26', 2026, 9, 'September', 3, 26, 'Sabtu', 39, 1),
(651, '2026-09-27', 2026, 9, 'September', 3, 27, 'Minggu', 39, 1),
(652, '2026-09-28', 2026, 9, 'September', 3, 28, 'Senin', 40, 0),
(653, '2026-09-29', 2026, 9, 'September', 3, 29, 'Selasa', 40, 0),
(654, '2026-09-30', 2026, 9, 'September', 3, 30, 'Rabu', 40, 0),
(655, '2026-10-01', 2026, 10, 'Oktober', 4, 1, 'Kamis', 40, 0),
(656, '2026-10-02', 2026, 10, 'Oktober', 4, 2, 'Jumat', 40, 0),
(657, '2026-10-03', 2026, 10, 'Oktober', 4, 3, 'Sabtu', 40, 1),
(658, '2026-10-04', 2026, 10, 'Oktober', 4, 4, 'Minggu', 40, 1),
(659, '2026-10-05', 2026, 10, 'Oktober', 4, 5, 'Senin', 41, 0),
(660, '2026-10-06', 2026, 10, 'Oktober', 4, 6, 'Selasa', 41, 0),
(661, '2026-10-07', 2026, 10, 'Oktober', 4, 7, 'Rabu', 41, 0),
(662, '2026-10-08', 2026, 10, 'Oktober', 4, 8, 'Kamis', 41, 0),
(663, '2026-10-09', 2026, 10, 'Oktober', 4, 9, 'Jumat', 41, 0),
(664, '2026-10-10', 2026, 10, 'Oktober', 4, 10, 'Sabtu', 41, 1),
(665, '2026-10-11', 2026, 10, 'Oktober', 4, 11, 'Minggu', 41, 1),
(666, '2026-10-12', 2026, 10, 'Oktober', 4, 12, 'Senin', 42, 0),
(667, '2026-10-13', 2026, 10, 'Oktober', 4, 13, 'Selasa', 42, 0),
(668, '2026-10-14', 2026, 10, 'Oktober', 4, 14, 'Rabu', 42, 0),
(669, '2026-10-15', 2026, 10, 'Oktober', 4, 15, 'Kamis', 42, 0),
(670, '2026-10-16', 2026, 10, 'Oktober', 4, 16, 'Jumat', 42, 0),
(671, '2026-10-17', 2026, 10, 'Oktober', 4, 17, 'Sabtu', 42, 1),
(672, '2026-10-18', 2026, 10, 'Oktober', 4, 18, 'Minggu', 42, 1),
(673, '2026-10-19', 2026, 10, 'Oktober', 4, 19, 'Senin', 43, 0),
(674, '2026-10-20', 2026, 10, 'Oktober', 4, 20, 'Selasa', 43, 0),
(675, '2026-10-21', 2026, 10, 'Oktober', 4, 21, 'Rabu', 43, 0),
(676, '2026-10-22', 2026, 10, 'Oktober', 4, 22, 'Kamis', 43, 0),
(677, '2026-10-23', 2026, 10, 'Oktober', 4, 23, 'Jumat', 43, 0),
(678, '2026-10-24', 2026, 10, 'Oktober', 4, 24, 'Sabtu', 43, 1),
(679, '2026-10-25', 2026, 10, 'Oktober', 4, 25, 'Minggu', 43, 1),
(680, '2026-10-26', 2026, 10, 'Oktober', 4, 26, 'Senin', 44, 0),
(681, '2026-10-27', 2026, 10, 'Oktober', 4, 27, 'Selasa', 44, 0),
(682, '2026-10-28', 2026, 10, 'Oktober', 4, 28, 'Rabu', 44, 0),
(683, '2026-10-29', 2026, 10, 'Oktober', 4, 29, 'Kamis', 44, 0),
(684, '2026-10-30', 2026, 10, 'Oktober', 4, 30, 'Jumat', 44, 0),
(685, '2026-10-31', 2026, 10, 'Oktober', 4, 31, 'Sabtu', 44, 1),
(686, '2026-11-01', 2026, 11, 'November', 4, 1, 'Minggu', 44, 1),
(687, '2026-11-02', 2026, 11, 'November', 4, 2, 'Senin', 45, 0),
(688, '2026-11-03', 2026, 11, 'November', 4, 3, 'Selasa', 45, 0),
(689, '2026-11-04', 2026, 11, 'November', 4, 4, 'Rabu', 45, 0),
(690, '2026-11-05', 2026, 11, 'November', 4, 5, 'Kamis', 45, 0),
(691, '2026-11-06', 2026, 11, 'November', 4, 6, 'Jumat', 45, 0),
(692, '2026-11-07', 2026, 11, 'November', 4, 7, 'Sabtu', 45, 1),
(693, '2026-11-08', 2026, 11, 'November', 4, 8, 'Minggu', 45, 1),
(694, '2026-11-09', 2026, 11, 'November', 4, 9, 'Senin', 46, 0),
(695, '2026-11-10', 2026, 11, 'November', 4, 10, 'Selasa', 46, 0),
(696, '2026-11-11', 2026, 11, 'November', 4, 11, 'Rabu', 46, 0),
(697, '2026-11-12', 2026, 11, 'November', 4, 12, 'Kamis', 46, 0),
(698, '2026-11-13', 2026, 11, 'November', 4, 13, 'Jumat', 46, 0),
(699, '2026-11-14', 2026, 11, 'November', 4, 14, 'Sabtu', 46, 1),
(700, '2026-11-15', 2026, 11, 'November', 4, 15, 'Minggu', 46, 1),
(701, '2026-11-16', 2026, 11, 'November', 4, 16, 'Senin', 47, 0),
(702, '2026-11-17', 2026, 11, 'November', 4, 17, 'Selasa', 47, 0),
(703, '2026-11-18', 2026, 11, 'November', 4, 18, 'Rabu', 47, 0),
(704, '2026-11-19', 2026, 11, 'November', 4, 19, 'Kamis', 47, 0),
(705, '2026-11-20', 2026, 11, 'November', 4, 20, 'Jumat', 47, 0),
(706, '2026-11-21', 2026, 11, 'November', 4, 21, 'Sabtu', 47, 1),
(707, '2026-11-22', 2026, 11, 'November', 4, 22, 'Minggu', 47, 1),
(708, '2026-11-23', 2026, 11, 'November', 4, 23, 'Senin', 48, 0),
(709, '2026-11-24', 2026, 11, 'November', 4, 24, 'Selasa', 48, 0),
(710, '2026-11-25', 2026, 11, 'November', 4, 25, 'Rabu', 48, 0),
(711, '2026-11-26', 2026, 11, 'November', 4, 26, 'Kamis', 48, 0),
(712, '2026-11-27', 2026, 11, 'November', 4, 27, 'Jumat', 48, 0),
(713, '2026-11-28', 2026, 11, 'November', 4, 28, 'Sabtu', 48, 1),
(714, '2026-11-29', 2026, 11, 'November', 4, 29, 'Minggu', 48, 1),
(715, '2026-11-30', 2026, 11, 'November', 4, 30, 'Senin', 49, 0),
(716, '2026-12-01', 2026, 12, 'Desember', 4, 1, 'Selasa', 49, 0),
(717, '2026-12-02', 2026, 12, 'Desember', 4, 2, 'Rabu', 49, 0),
(718, '2026-12-03', 2026, 12, 'Desember', 4, 3, 'Kamis', 49, 0),
(719, '2026-12-04', 2026, 12, 'Desember', 4, 4, 'Jumat', 49, 0),
(720, '2026-12-05', 2026, 12, 'Desember', 4, 5, 'Sabtu', 49, 1),
(721, '2026-12-06', 2026, 12, 'Desember', 4, 6, 'Minggu', 49, 1),
(722, '2026-12-07', 2026, 12, 'Desember', 4, 7, 'Senin', 50, 0),
(723, '2026-12-08', 2026, 12, 'Desember', 4, 8, 'Selasa', 50, 0),
(724, '2026-12-09', 2026, 12, 'Desember', 4, 9, 'Rabu', 50, 0),
(725, '2026-12-10', 2026, 12, 'Desember', 4, 10, 'Kamis', 50, 0),
(726, '2026-12-11', 2026, 12, 'Desember', 4, 11, 'Jumat', 50, 0),
(727, '2026-12-12', 2026, 12, 'Desember', 4, 12, 'Sabtu', 50, 1),
(728, '2026-12-13', 2026, 12, 'Desember', 4, 13, 'Minggu', 50, 1),
(729, '2026-12-14', 2026, 12, 'Desember', 4, 14, 'Senin', 51, 0),
(730, '2026-12-15', 2026, 12, 'Desember', 4, 15, 'Selasa', 51, 0),
(731, '2026-12-16', 2026, 12, 'Desember', 4, 16, 'Rabu', 51, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `fact_kapasitas_harian`
--

CREATE TABLE `fact_kapasitas_harian` (
  `kapasitas_key` bigint(20) UNSIGNED NOT NULL,
  `waktu_key` int(10) UNSIGNED NOT NULL,
  `total_penitipan` int(11) NOT NULL DEFAULT 0,
  `penitipan_aktif` int(11) NOT NULL DEFAULT 0,
  `penitipan_pending` int(11) NOT NULL DEFAULT 0,
  `penitipan_selesai` int(11) NOT NULL DEFAULT 0,
  `penitipan_dibatalkan` int(11) NOT NULL DEFAULT 0,
  `total_hewan` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `fact_kapasitas_harian`
--

INSERT INTO `fact_kapasitas_harian` (`kapasitas_key`, `waktu_key`, `total_penitipan`, `penitipan_aktif`, `penitipan_pending`, `penitipan_selesai`, `penitipan_dibatalkan`, `total_hewan`) VALUES
(1, 361, 1, 1, 0, 0, 0, 1),
(2, 364, 2, 1, 1, 0, 0, 2),
(3, 365, 3, 2, 1, 0, 0, 3),
(4, 363, 2, 1, 0, 1, 0, 2),
(5, 366, 5, 3, 2, 0, 0, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `fact_keuangan_periodik`
--

CREATE TABLE `fact_keuangan_periodik` (
  `keuangan_key` bigint(20) UNSIGNED NOT NULL,
  `periode_yyyymm` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `total_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
  `jumlah_transaksi` int(11) NOT NULL DEFAULT 0,
  `avg_transaksi` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `fact_keuangan_periodik`
--

INSERT INTO `fact_keuangan_periodik` (`keuangan_key`, `periode_yyyymm`, `tahun`, `bulan`, `total_revenue`, `jumlah_transaksi`, `avg_transaksi`) VALUES
(1, 202512, 2025, 12, 5695000.00, 4, 1423750.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `fact_transaksi`
--

CREATE TABLE `fact_transaksi` (
  `transaksi_key` bigint(20) UNSIGNED NOT NULL,
  `waktu_key` int(10) UNSIGNED DEFAULT NULL,
  `customer_key` int(10) UNSIGNED DEFAULT NULL,
  `hewan_key` int(10) UNSIGNED DEFAULT NULL,
  `paket_key` int(10) UNSIGNED DEFAULT NULL,
  `staff_key` int(10) UNSIGNED DEFAULT NULL,
  `status_key` int(10) UNSIGNED DEFAULT NULL,
  `pembayaran_key` int(10) UNSIGNED DEFAULT NULL,
  `jumlah_hari` int(11) NOT NULL DEFAULT 0,
  `total_biaya` decimal(12,2) NOT NULL DEFAULT 0.00,
  `jumlah_transaksi` int(11) NOT NULL DEFAULT 1,
  `id_penitipan` bigint(20) UNSIGNED NOT NULL,
  `tanggal_masuk` datetime NOT NULL,
  `id_pemilik` bigint(20) UNSIGNED DEFAULT NULL,
  `id_hewan` bigint(20) UNSIGNED DEFAULT NULL,
  `id_paket` bigint(20) UNSIGNED DEFAULT NULL,
  `id_staff` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `status_pembayaran` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `fact_transaksi`
--

INSERT INTO `fact_transaksi` (`transaksi_key`, `waktu_key`, `customer_key`, `hewan_key`, `paket_key`, `staff_key`, `status_key`, `pembayaran_key`, `jumlah_hari`, `total_biaya`, `jumlah_transaksi`, `id_penitipan`, `tanggal_masuk`, `id_pemilik`, `id_hewan`, `id_paket`, `id_staff`, `status`, `metode_pembayaran`, `status_pembayaran`) VALUES
(1, 361, 1, 1, 1, 1, 2, 5, 7, 1050000.00, 1, 1, '2025-12-11 12:14:00', 3, 1, 1, 1, 'aktif', 'transfer', 'lunas'),
(4, 364, 2, 2, 3, 2, 1, 1, 5, 1000000.00, 1, 2, '2025-12-14 12:14:01', 4, 2, 3, 2, 'pending', 'cash', 'pending'),
(7, 365, 3, 3, 2, 1, 2, 8, 7, 1750000.00, 1, 3, '2025-12-15 12:14:01', 5, 3, 2, 1, 'aktif', 'e_wallet', 'lunas'),
(10, 363, 4, 4, 4, 2, 3, 14, 7, 2450000.00, 1, 4, '2025-12-13 12:14:02', 6, 4, 4, 2, 'selesai', 'kartu_kredit', 'lunas'),
(13, 366, 1, 5, 1, NULL, 2, 11, 2, 445000.00, 1, 5, '2025-12-16 00:00:00', 3, 6, 1, NULL, 'aktif', 'qris', 'lunas');

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
(1, 3, 'Luna', 'kucing', 'Persian', 2, 'betina', 4.50, 'Alergi makanan tertentu', 'Sudah vaksin lengkap', '2025-12-16 05:14:00', '2025-12-16 05:14:00'),
(2, 4, 'Max', 'anjing', 'Golden Retriever', 3, 'jantan', 28.00, NULL, 'Sehat, vaksin lengkap', '2025-12-16 05:14:01', '2025-12-16 05:14:01'),
(3, 5, 'Mochi', 'kucing', 'British Shorthair', 1, 'jantan', 3.80, 'Pemalu', 'Vaksin up to date', '2025-12-16 05:14:01', '2025-12-16 05:14:01'),
(4, 6, 'Rocky', 'anjing', 'German Shepherd', 4, 'jantan', 32.50, 'Energik', 'Sehat, sudah dikebiri', '2025-12-16 05:14:02', '2025-12-16 05:14:02'),
(6, 3, 'Luna', 'kucing', 'Persian', 20, 'tidak diketahui', 20.00, NULL, NULL, '2025-12-16 05:46:13', '2025-12-16 05:46:13');

--
-- Trigger `hewan`
--
DELIMITER $$
CREATE TRIGGER `sync_dim_hewan_insert` AFTER INSERT ON `hewan` FOR EACH ROW BEGIN
                INSERT INTO dim_hewan (id_hewan, nama_hewan, jenis_hewan, ras, umur, jenis_kelamin, berat, created_at, updated_at)
                VALUES (NEW.id_hewan, NEW.nama_hewan, NEW.jenis_hewan, NEW.ras, NEW.umur, NEW.jenis_kelamin, NEW.berat, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    nama_hewan = NEW.nama_hewan,
                    jenis_hewan = NEW.jenis_hewan,
                    ras = NEW.ras,
                    umur = NEW.umur,
                    jenis_kelamin = NEW.jenis_kelamin,
                    berat = NEW.berat,
                    updated_at = NOW();
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_dim_hewan_update` AFTER UPDATE ON `hewan` FOR EACH ROW BEGIN
                UPDATE dim_hewan
                SET nama_hewan = NEW.nama_hewan,
                    jenis_hewan = NEW.jenis_hewan,
                    ras = NEW.ras,
                    umur = NEW.umur,
                    jenis_kelamin = NEW.jenis_kelamin,
                    berat = NEW.berat,
                    updated_at = NOW()
                WHERE id_hewan = NEW.id_hewan;
            END
$$
DELIMITER ;

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
(10, '2024_12_01_000001_add_specialization_to_pengguna', 1),
(11, '2025_12_15_071502_create_data_warehouse_tables', 1),
(12, '2025_12_15_071542_create_data_warehouse_tables', 1),
(13, '2025_12_15_071618_create_stored_procedures_and_triggers', 1),
(14, '2025_12_16_124325_alter_dim_hewan_jenis_kelamin_length', 2);

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
(1, 'Paket Basic', 'Paket dasar dengan fasilitas standar untuk penitipan hewan', 150000.00, 'Kamar Ber-AC\nMakan 3x sehari\nArea bermain indoor/outdoor\nLaporan harian via WA (foto)', 1, '2025-10-06 01:38:35', '2025-10-06 01:38:35'),
(2, 'Paket Premium', 'Paket premium dengan fasilitas lengkap dan layanan ekstra', 250000.00, 'Kamar Ber-AC\nMakan 3x sehari\nArea bermain indoor/outdoor\nLaporan harian via WA + VC\nSnack & Treats', 1, '2025-10-06 01:38:35', '2025-10-06 01:38:35'),
(3, 'Grooming Premium', 'Layanan spa lengkap untuk hewan kesayangan Anda', 150000.00, 'Spa lengkap\nPotong kuku\nBersih telinga\nAromaterapi', 1, '2025-10-06 01:38:35', '2025-10-06 01:38:35'),
(4, 'Kolam Renang', 'Layanan berenang untuk kesehatan dan kesenangan hewan', 100000.00, 'Sesi berenang dengan pengawasan\nPeralatan keamanan standar\nHanduk dan perawatan setelah berenang', 1, '2025-10-06 01:38:35', '2025-10-06 01:38:35'),
(5, 'Pick-up & Delivery', 'Layanan antar jemput hewan peliharaan Anda', 100000.00, 'Layanan antar jemput dalam radius 10km\nKendaraan ber-AC\nPenanganan hewan yang aman', 1, '2025-10-06 01:38:35', '2025-10-06 01:38:35'),
(6, 'Enrichment Extra', 'Sesi stimulasi mental dan fisik untuk hewan', 45000.00, 'Sesi stimulasi 15–20 menit\nPuzzle feeder\nLick mat\nSniffing activities', 1, '2025-10-06 01:38:35', '2025-10-06 01:38:35');

--
-- Trigger `paket_layanan`
--
DELIMITER $$
CREATE TRIGGER `sync_dim_paket_insert` AFTER INSERT ON `paket_layanan` FOR EACH ROW BEGIN
                INSERT INTO dim_paket (id_paket, nama_paket, harga_per_hari, is_active, created_at, updated_at)
                VALUES (NEW.id_paket, NEW.nama_paket, NEW.harga_per_hari, NEW.is_active, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    nama_paket = NEW.nama_paket,
                    harga_per_hari = NEW.harga_per_hari,
                    is_active = NEW.is_active,
                    updated_at = NOW();
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_dim_paket_update` AFTER UPDATE ON `paket_layanan` FOR EACH ROW BEGIN
                UPDATE dim_paket
                SET nama_paket = NEW.nama_paket,
                    harga_per_hari = NEW.harga_per_hari,
                    is_active = NEW.is_active,
                    updated_at = NOW()
                WHERE id_paket = NEW.id_paket;
            END
$$
DELIMITER ;

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
(1, 1, 'TRX-20251216-000001', 1050000.00, 'transfer', 'lunas', '2025-12-11 12:14:00', NULL, '2025-12-16 05:14:00', '2025-12-16 05:14:00'),
(2, 2, 'TRX-20251216-000002', 1000000.00, 'cash', 'pending', NULL, NULL, '2025-12-16 05:14:01', '2025-12-16 05:14:01'),
(3, 3, 'TRX-20251216-000003', 1750000.00, 'e_wallet', 'lunas', '2025-12-15 12:14:01', NULL, '2025-12-16 05:14:01', '2025-12-16 05:14:01'),
(4, 4, 'TRX-20251216-000004', 2450000.00, 'kartu_kredit', 'lunas', '2025-12-16 13:55:16', NULL, '2025-12-16 05:14:02', '2025-12-16 06:55:16'),
(5, 5, 'TRX-20251216-000005', 445000.00, 'qris', 'lunas', '2025-12-16 20:05:00', NULL, '2025-12-16 05:46:13', '2025-12-16 06:06:05');

--
-- Trigger `pembayaran`
--
DELIMITER $$
CREATE TRIGGER `sync_facts_pembayaran_delete` AFTER DELETE ON `pembayaran` FOR EACH ROW BEGIN
                IF OLD.status_pembayaran = 'lunas' AND OLD.tanggal_bayar IS NOT NULL THEN
                    CALL update_fact_keuangan_for_month(YEAR(OLD.tanggal_bayar), MONTH(OLD.tanggal_bayar));
                END IF;
                CALL upsert_fact_transaksi_for_penitipan(OLD.id_penitipan);
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_facts_pembayaran_insert` AFTER INSERT ON `pembayaran` FOR EACH ROW BEGIN
                IF NEW.status_pembayaran = 'lunas' AND NEW.tanggal_bayar IS NOT NULL THEN
                    CALL update_fact_keuangan_for_month(YEAR(NEW.tanggal_bayar), MONTH(NEW.tanggal_bayar));
                END IF;
                CALL upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_facts_pembayaran_update` AFTER UPDATE ON `pembayaran` FOR EACH ROW BEGIN
                IF OLD.status_pembayaran = 'lunas' AND OLD.tanggal_bayar IS NOT NULL THEN
                    CALL update_fact_keuangan_for_month(YEAR(OLD.tanggal_bayar), MONTH(OLD.tanggal_bayar));
                END IF;
                
                IF NEW.status_pembayaran = 'lunas' AND NEW.tanggal_bayar IS NOT NULL THEN
                    CALL update_fact_keuangan_for_month(YEAR(NEW.tanggal_bayar), MONTH(NEW.tanggal_bayar));
                END IF;
                
                CALL upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
            END
$$
DELIMITER ;

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
(1, 'Harvest Walukow', 'harvest@gmail.com', '$2y$12$WVI0xzJ1fXwYpWKoT2PSzuhd8MCzflHah4A8A/x4AL9.9wIOmpECu', '081234567890', 'Jl. Admin No. 1, Jakarta', 'staff', 'handler', '2025-12-16 05:13:59', '2025-12-16 05:13:59'),
(2, 'Fatma Staffina', 'fatma@gmail.com', '$2y$12$dYUKQrOuXREdEEq2YwHL8uvW1.PrH5VHXdNER7xw7msAcHBUzBBBC', '081234567891', 'Jl. Staff No. 2, Jakarta', 'staff', 'groomer', '2025-12-16 05:14:00', '2025-12-16 05:14:00'),
(3, 'Baim Wong', 'baim@gmail.com', '$2y$12$HBLArhJol4SWbXk.NImxdufIEZOgj47RW8F6AEs.1C1emvScVGQHq', '081234567801', 'Jl. Baim No. 10, Jakarta Selatan', 'pet_owner', NULL, '2025-12-16 05:14:00', '2025-12-16 05:14:00'),
(4, 'Hanny Puspita', 'hanny@gmail.com', '$2y$12$bhbBiU/SG5FHx16zthKTfOTYE98z1xN21J7qT4dIRLFJ7oP0VzKWm', '081234567802', 'Jl. Hanny No. 20, Jakarta Barat', 'pet_owner', NULL, '2025-12-16 05:14:01', '2025-12-16 05:14:01'),
(5, 'Salwa Azzahra', 'salwa@gmail.com', '$2y$12$1FI3PBdXSkd0ZNhEJ67lcuhWO24R5lZIfL3byMMrPPXmH605sTH9m', '081234567803', 'Jl. Salwa No. 30, Jakarta Utara', 'pet_owner', NULL, '2025-12-16 05:14:01', '2025-12-16 05:14:01'),
(6, 'Mayla Cantika', 'mayla@gmail.com', '$2y$12$hjppG.ZOTIbzcQt8x25T.u3ash7U7g15f9xOk3Wp.VDUwk3mWVvqO', '081234567804', 'Jl. Mayla No. 40, Jakarta Timur', 'pet_owner', NULL, '2025-12-16 05:14:02', '2025-12-16 05:14:02');

--
-- Trigger `pengguna`
--
DELIMITER $$
CREATE TRIGGER `sync_dim_customer_insert` AFTER INSERT ON `pengguna` FOR EACH ROW BEGIN
                IF NEW.role = 'pet_owner' THEN
                    INSERT INTO dim_customer (id_pengguna, nama_lengkap, email, alamat, no_telepon, created_at, updated_at)
                    VALUES (NEW.id_pengguna, NEW.nama_lengkap, NEW.email, NEW.alamat, NEW.no_telepon, NOW(), NOW())
                    ON DUPLICATE KEY UPDATE
                        nama_lengkap = NEW.nama_lengkap,
                        email = NEW.email,
                        alamat = NEW.alamat,
                        no_telepon = NEW.no_telepon,
                        updated_at = NOW();
                END IF;
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_dim_customer_update` AFTER UPDATE ON `pengguna` FOR EACH ROW BEGIN
                IF NEW.role = 'pet_owner' THEN
                    UPDATE dim_customer
                    SET nama_lengkap = NEW.nama_lengkap,
                        email = NEW.email,
                        alamat = NEW.alamat,
                        no_telepon = NEW.no_telepon,
                        updated_at = NOW()
                    WHERE id_pengguna = NEW.id_pengguna;
                END IF;
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_dim_staff_insert` AFTER INSERT ON `pengguna` FOR EACH ROW BEGIN
                IF NEW.role IN ('staff', 'admin') THEN
                    INSERT INTO dim_staff (id_pengguna, nama_lengkap, email, role, specialization, created_at, updated_at)
                    VALUES (NEW.id_pengguna, NEW.nama_lengkap, NEW.email, NEW.role, NEW.specialization, NOW(), NOW())
                    ON DUPLICATE KEY UPDATE
                        nama_lengkap = NEW.nama_lengkap,
                        email = NEW.email,
                        role = NEW.role,
                        specialization = NEW.specialization,
                        updated_at = NOW();
                END IF;
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_dim_staff_update` AFTER UPDATE ON `pengguna` FOR EACH ROW BEGIN
                IF NEW.role IN ('staff', 'admin') THEN
                    UPDATE dim_staff
                    SET nama_lengkap = NEW.nama_lengkap,
                        email = NEW.email,
                        role = NEW.role,
                        specialization = NEW.specialization,
                        updated_at = NOW()
                    WHERE id_pengguna = NEW.id_pengguna;
                END IF;
            END
$$
DELIMITER ;

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
(1, 1, 3, 1, '2025-12-11 12:14:00', '2025-12-18 12:14:00', 'aktif', 'Mohon berikan makanan khusus yang sudah dibawa', 1050000.00, '2025-12-16 05:14:00', '2025-12-16 05:14:00'),
(2, 2, 4, 2, '2025-12-14 12:14:01', '2025-12-19 12:14:01', 'pending', 'Suka bermain di taman', 1000000.00, '2025-12-16 05:14:01', '2025-12-16 05:14:01'),
(3, 3, 5, 1, '2025-12-15 12:14:01', '2025-12-22 12:14:01', 'aktif', 'Butuh perhatian ekstra karena pemalu', 1750000.00, '2025-12-16 05:14:01', '2025-12-16 05:14:01'),
(4, 4, 6, 2, '2025-12-13 12:14:02', '2025-12-20 12:14:02', 'selesai', 'Perlu banyak aktivitas fisik', 2450000.00, '2025-12-16 05:14:02', '2025-12-16 07:04:04'),
(5, 6, 3, NULL, '2025-12-16 00:00:00', '2025-12-18 00:00:00', 'aktif', NULL, 445000.00, '2025-12-16 05:46:13', '2025-12-16 06:06:05');

--
-- Trigger `penitipan`
--
DELIMITER $$
CREATE TRIGGER `sync_facts_penitipan_delete` AFTER DELETE ON `penitipan` FOR EACH ROW BEGIN
                CALL update_fact_kapasitas_for_date(DATE(OLD.tanggal_masuk));
                DELETE FROM fact_transaksi WHERE id_penitipan = OLD.id_penitipan;
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_facts_penitipan_insert` AFTER INSERT ON `penitipan` FOR EACH ROW BEGIN
                CALL update_fact_kapasitas_for_date(DATE(NEW.tanggal_masuk));
                CALL upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
            END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `sync_facts_penitipan_update` AFTER UPDATE ON `penitipan` FOR EACH ROW BEGIN
                CALL update_fact_kapasitas_for_date(DATE(OLD.tanggal_masuk));
                CALL update_fact_kapasitas_for_date(DATE(NEW.tanggal_masuk));
                CALL upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
            END
$$
DELIMITER ;

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
('7fIJ4Xa6VbY48xdBDwyMLHgpOHhsGuwWUU187WoF', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiRU5Gck1ZQkJ6anh6ZU95Y3B6OEVPbVk4OXppaWozU080WkVvWXRTVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wZW5pdGlwYW4iO31zOjc6InVzZXJfaWQiO2k6OTk5OTtzOjEwOiJ1c2VyX2VtYWlsIjtzOjE1OiJhZG1pbkBnbWFpbC5jb20iO3M6OToidXNlcl9uYW1lIjtzOjU6IkFkbWluIjtzOjk6InVzZXJfcm9sZSI7czo1OiJhZG1pbiI7fQ==', 1765889116),
('fVof7zlMiwdhR1ZpKK2VuyvKuzPk6KJGG65Er9ot', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3N0YWZmIjt9czo2OiJfdG9rZW4iO3M6NDA6InJLaExZZGZuZ1doNVBQOHIycmtJYmRpT3FjWWR1VVRNVGFkUm9IRmUiO3M6NzoidXNlcl9pZCI7aToyO3M6MTA6InVzZXJfZW1haWwiO3M6MTU6ImZhdG1hQGdtYWlsLmNvbSI7czo5OiJ1c2VyX25hbWUiO3M6MTQ6IkZhdG1hIFN0YWZmaW5hIjtzOjk6InVzZXJfcm9sZSI7czo1OiJhZG1pbiI7fQ==', 1765895482);

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
(1, 5, 1, 'sehat', 'Main FF', 'GGWP', 'uploads/update_kondisi/1765895028_images.jpg', '2025-12-16 14:23:48', '2025-12-16 14:23:48');

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
-- Indeks untuk tabel `data_warehouse_tables`
--
ALTER TABLE `data_warehouse_tables`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `detail_penitipan`
--
ALTER TABLE `detail_penitipan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `detail_penitipan_id_penitipan_foreign` (`id_penitipan`),
  ADD KEY `detail_penitipan_id_paket_foreign` (`id_paket`);

--
-- Indeks untuk tabel `dim_customer`
--
ALTER TABLE `dim_customer`
  ADD PRIMARY KEY (`customer_key`),
  ADD UNIQUE KEY `dim_customer_id_pengguna_unique` (`id_pengguna`),
  ADD KEY `dim_customer_id_pengguna_index` (`id_pengguna`);

--
-- Indeks untuk tabel `dim_hewan`
--
ALTER TABLE `dim_hewan`
  ADD PRIMARY KEY (`hewan_key`),
  ADD UNIQUE KEY `dim_hewan_id_hewan_unique` (`id_hewan`),
  ADD KEY `dim_hewan_id_hewan_index` (`id_hewan`);

--
-- Indeks untuk tabel `dim_paket`
--
ALTER TABLE `dim_paket`
  ADD PRIMARY KEY (`paket_key`),
  ADD UNIQUE KEY `dim_paket_id_paket_unique` (`id_paket`),
  ADD KEY `dim_paket_id_paket_index` (`id_paket`);

--
-- Indeks untuk tabel `dim_pembayaran`
--
ALTER TABLE `dim_pembayaran`
  ADD PRIMARY KEY (`pembayaran_key`),
  ADD UNIQUE KEY `dim_pembayaran_metode_pembayaran_status_pembayaran_unique` (`metode_pembayaran`,`status_pembayaran`);

--
-- Indeks untuk tabel `dim_staff`
--
ALTER TABLE `dim_staff`
  ADD PRIMARY KEY (`staff_key`),
  ADD UNIQUE KEY `dim_staff_id_pengguna_unique` (`id_pengguna`),
  ADD KEY `dim_staff_id_pengguna_index` (`id_pengguna`);

--
-- Indeks untuk tabel `dim_status_penitipan`
--
ALTER TABLE `dim_status_penitipan`
  ADD PRIMARY KEY (`status_key`),
  ADD UNIQUE KEY `dim_status_penitipan_status_unique` (`status`);

--
-- Indeks untuk tabel `dim_waktu`
--
ALTER TABLE `dim_waktu`
  ADD PRIMARY KEY (`waktu_key`),
  ADD UNIQUE KEY `dim_waktu_tanggal_unique` (`tanggal`),
  ADD KEY `dim_waktu_tanggal_index` (`tanggal`),
  ADD KEY `dim_waktu_tahun_bulan_index` (`tahun`,`bulan`);

--
-- Indeks untuk tabel `fact_kapasitas_harian`
--
ALTER TABLE `fact_kapasitas_harian`
  ADD PRIMARY KEY (`kapasitas_key`),
  ADD UNIQUE KEY `fact_kapasitas_harian_waktu_key_unique` (`waktu_key`);

--
-- Indeks untuk tabel `fact_keuangan_periodik`
--
ALTER TABLE `fact_keuangan_periodik`
  ADD PRIMARY KEY (`keuangan_key`),
  ADD UNIQUE KEY `fact_keuangan_periodik_periode_yyyymm_unique` (`periode_yyyymm`),
  ADD KEY `fact_keuangan_periodik_tahun_bulan_index` (`tahun`,`bulan`);

--
-- Indeks untuk tabel `fact_transaksi`
--
ALTER TABLE `fact_transaksi`
  ADD PRIMARY KEY (`transaksi_key`),
  ADD UNIQUE KEY `fact_transaksi_id_penitipan_unique` (`id_penitipan`),
  ADD KEY `fact_transaksi_waktu_key_foreign` (`waktu_key`),
  ADD KEY `fact_transaksi_customer_key_foreign` (`customer_key`),
  ADD KEY `fact_transaksi_hewan_key_foreign` (`hewan_key`),
  ADD KEY `fact_transaksi_paket_key_foreign` (`paket_key`),
  ADD KEY `fact_transaksi_staff_key_foreign` (`staff_key`),
  ADD KEY `fact_transaksi_status_key_foreign` (`status_key`),
  ADD KEY `fact_transaksi_pembayaran_key_foreign` (`pembayaran_key`),
  ADD KEY `fact_transaksi_id_penitipan_index` (`id_penitipan`),
  ADD KEY `fact_transaksi_tanggal_masuk_index` (`tanggal_masuk`);

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
-- AUTO_INCREMENT untuk tabel `data_warehouse_tables`
--
ALTER TABLE `data_warehouse_tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `detail_penitipan`
--
ALTER TABLE `detail_penitipan`
  MODIFY `id_detail` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `dim_customer`
--
ALTER TABLE `dim_customer`
  MODIFY `customer_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `dim_hewan`
--
ALTER TABLE `dim_hewan`
  MODIFY `hewan_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `dim_paket`
--
ALTER TABLE `dim_paket`
  MODIFY `paket_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `dim_pembayaran`
--
ALTER TABLE `dim_pembayaran`
  MODIFY `pembayaran_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `dim_staff`
--
ALTER TABLE `dim_staff`
  MODIFY `staff_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `dim_status_penitipan`
--
ALTER TABLE `dim_status_penitipan`
  MODIFY `status_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `dim_waktu`
--
ALTER TABLE `dim_waktu`
  MODIFY `waktu_key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=732;

--
-- AUTO_INCREMENT untuk tabel `fact_kapasitas_harian`
--
ALTER TABLE `fact_kapasitas_harian`
  MODIFY `kapasitas_key` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `fact_keuangan_periodik`
--
ALTER TABLE `fact_keuangan_periodik`
  MODIFY `keuangan_key` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `fact_transaksi`
--
ALTER TABLE `fact_transaksi`
  MODIFY `transaksi_key` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `hewan`
--
ALTER TABLE `hewan`
  MODIFY `id_hewan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `paket_layanan`
--
ALTER TABLE `paket_layanan`
  MODIFY `id_paket` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `penitipan`
--
ALTER TABLE `penitipan`
  MODIFY `id_penitipan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `update_kondisi`
--
ALTER TABLE `update_kondisi`
  MODIFY `id_update` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Ketidakleluasaan untuk tabel `fact_kapasitas_harian`
--
ALTER TABLE `fact_kapasitas_harian`
  ADD CONSTRAINT `fact_kapasitas_harian_waktu_key_foreign` FOREIGN KEY (`waktu_key`) REFERENCES `dim_waktu` (`waktu_key`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `fact_transaksi`
--
ALTER TABLE `fact_transaksi`
  ADD CONSTRAINT `fact_transaksi_customer_key_foreign` FOREIGN KEY (`customer_key`) REFERENCES `dim_customer` (`customer_key`) ON DELETE SET NULL,
  ADD CONSTRAINT `fact_transaksi_hewan_key_foreign` FOREIGN KEY (`hewan_key`) REFERENCES `dim_hewan` (`hewan_key`) ON DELETE SET NULL,
  ADD CONSTRAINT `fact_transaksi_paket_key_foreign` FOREIGN KEY (`paket_key`) REFERENCES `dim_paket` (`paket_key`) ON DELETE SET NULL,
  ADD CONSTRAINT `fact_transaksi_pembayaran_key_foreign` FOREIGN KEY (`pembayaran_key`) REFERENCES `dim_pembayaran` (`pembayaran_key`) ON DELETE SET NULL,
  ADD CONSTRAINT `fact_transaksi_staff_key_foreign` FOREIGN KEY (`staff_key`) REFERENCES `dim_staff` (`staff_key`) ON DELETE SET NULL,
  ADD CONSTRAINT `fact_transaksi_status_key_foreign` FOREIGN KEY (`status_key`) REFERENCES `dim_status_penitipan` (`status_key`) ON DELETE SET NULL,
  ADD CONSTRAINT `fact_transaksi_waktu_key_foreign` FOREIGN KEY (`waktu_key`) REFERENCES `dim_waktu` (`waktu_key`) ON DELETE SET NULL;

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
