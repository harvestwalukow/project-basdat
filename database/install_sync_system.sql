-- =====================================================
-- INSTALLATION SCRIPT FOR DATA WAREHOUSE SYNC SYSTEM
-- =====================================================
-- Run this script to set up the complete synchronization
-- system between er_basdat and dw_basdat
-- =====================================================

-- Step 1: Ensure dim_status_penitipan has all statuses
USE dw_basdat;

INSERT INTO dim_status_penitipan (status) VALUES ('pending')
ON DUPLICATE KEY UPDATE status = status;

INSERT INTO dim_status_penitipan (status) VALUES ('aktif')
ON DUPLICATE KEY UPDATE status = status;

INSERT INTO dim_status_penitipan (status) VALUES ('selesai')
ON DUPLICATE KEY UPDATE status = status;

INSERT INTO dim_status_penitipan (status) VALUES ('dibatalkan')
ON DUPLICATE KEY UPDATE status = status;

-- Step 2: Ensure dim_pembayaran has all payment combinations
INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('cash', 'pending')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('cash', 'lunas')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('cash', 'gagal')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('transfer', 'pending')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('transfer', 'lunas')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('transfer', 'gagal')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('e_wallet', 'pending')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('e_wallet', 'lunas')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('e_wallet', 'gagal')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('qris', 'pending')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('qris', 'lunas')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('qris', 'gagal')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('kartu_kredit', 'pending')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('kartu_kredit', 'lunas')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

INSERT INTO dim_pembayaran (metode_pembayaran, status_pembayaran) VALUES ('kartu_kredit', 'gagal')
ON DUPLICATE KEY UPDATE metode_pembayaran = metode_pembayaran;

-- Step 3: Create stored procedures (must run first before triggers)
SOURCE sync_procedures.sql;

-- Step 4: Create triggers (depends on stored procedures)
SOURCE sync_triggers.sql;

-- Step 5: Perform initial ETL to populate all fact tables
CALL dw_basdat.full_etl_refresh();

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Check dimension table counts
SELECT 'dim_customer' AS table_name, COUNT(*) AS row_count FROM dw_basdat.dim_customer
UNION ALL
SELECT 'dim_staff', COUNT(*) FROM dw_basdat.dim_staff
UNION ALL
SELECT 'dim_hewan', COUNT(*) FROM dw_basdat.dim_hewan
UNION ALL
SELECT 'dim_paket', COUNT(*) FROM dw_basdat.dim_paket
UNION ALL
SELECT 'dim_waktu', COUNT(*) FROM dw_basdat.dim_waktu
UNION ALL
SELECT 'dim_status_penitipan', COUNT(*) FROM dw_basdat.dim_status_penitipan
UNION ALL
SELECT 'dim_pembayaran', COUNT(*) FROM dw_basdat.dim_pembayaran;

-- Check fact table counts
SELECT 'fact_transaksi' AS table_name, COUNT(*) AS row_count FROM dw_basdat.fact_transaksi
UNION ALL
SELECT 'fact_kapasitas_harian', COUNT(*) FROM dw_basdat.fact_kapasitas_harian
UNION ALL
SELECT 'fact_keuangan_periodik', COUNT(*) FROM dw_basdat.fact_keuangan_periodik;

-- Check triggers are installed
SHOW TRIGGERS FROM er_basdat WHERE `Trigger` LIKE 'sync_%';

-- Check procedures are installed
SHOW PROCEDURE STATUS WHERE Db = 'dw_basdat' AND Name LIKE '%fact%';

SELECT '=== Installation completed successfully ===' AS status;


