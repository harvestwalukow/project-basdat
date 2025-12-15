-- =====================================================
-- STEP 1: MIGRATE DW TABLES TO ER_BASDAT
-- =====================================================
-- This script copies all dimension and fact tables
-- from dw_basdat to er_basdat with their data
-- =====================================================

USE er_basdat;

-- =====================================================
-- COPY DIMENSION TABLES
-- =====================================================

-- 1. dim_customer
CREATE TABLE IF NOT EXISTS er_basdat.dim_customer LIKE dw_basdat.dim_customer;
INSERT IGNORE INTO er_basdat.dim_customer SELECT * FROM dw_basdat.dim_customer;

-- 2. dim_staff
CREATE TABLE IF NOT EXISTS er_basdat.dim_staff LIKE dw_basdat.dim_staff;
INSERT IGNORE INTO er_basdat.dim_staff SELECT * FROM dw_basdat.dim_staff;

-- 3. dim_hewan
CREATE TABLE IF NOT EXISTS er_basdat.dim_hewan LIKE dw_basdat.dim_hewan;
INSERT IGNORE INTO er_basdat.dim_hewan SELECT * FROM dw_basdat.dim_hewan;

-- 4. dim_paket
CREATE TABLE IF NOT EXISTS er_basdat.dim_paket LIKE dw_basdat.dim_paket;
INSERT IGNORE INTO er_basdat.dim_paket SELECT * FROM dw_basdat.dim_paket;

-- 5. dim_waktu
CREATE TABLE IF NOT EXISTS er_basdat.dim_waktu LIKE dw_basdat.dim_waktu;
INSERT IGNORE INTO er_basdat.dim_waktu SELECT * FROM dw_basdat.dim_waktu;

-- 6. dim_status_penitipan
CREATE TABLE IF NOT EXISTS er_basdat.dim_status_penitipan LIKE dw_basdat.dim_status_penitipan;
INSERT IGNORE INTO er_basdat.dim_status_penitipan SELECT * FROM dw_basdat.dim_status_penitipan;

-- 7. dim_pembayaran
CREATE TABLE IF NOT EXISTS er_basdat.dim_pembayaran LIKE dw_basdat.dim_pembayaran;
INSERT IGNORE INTO er_basdat.dim_pembayaran SELECT * FROM dw_basdat.dim_pembayaran;

-- =====================================================
-- COPY FACT TABLES
-- =====================================================

-- 1. fact_transaksi
CREATE TABLE IF NOT EXISTS er_basdat.fact_transaksi LIKE dw_basdat.fact_transaksi;
INSERT IGNORE INTO er_basdat.fact_transaksi SELECT * FROM dw_basdat.fact_transaksi;

-- 2. fact_kapasitas_harian
CREATE TABLE IF NOT EXISTS er_basdat.fact_kapasitas_harian LIKE dw_basdat.fact_kapasitas_harian;
INSERT IGNORE INTO er_basdat.fact_kapasitas_harian SELECT * FROM dw_basdat.fact_kapasitas_harian;

-- 3. fact_keuangan_periodik
CREATE TABLE IF NOT EXISTS er_basdat.fact_keuangan_periodik LIKE dw_basdat.fact_keuangan_periodik;
INSERT IGNORE INTO er_basdat.fact_keuangan_periodik SELECT * FROM dw_basdat.fact_keuangan_periodik;

-- =====================================================
-- VERIFICATION
-- =====================================================

SELECT 'Migration completed! Verifying table counts...' AS status;

SELECT 'dim_customer' AS table_name, 
       (SELECT COUNT(*) FROM dw_basdat.dim_customer) AS source_count,
       (SELECT COUNT(*) FROM er_basdat.dim_customer) AS target_count;

SELECT 'dim_staff' AS table_name,
       (SELECT COUNT(*) FROM dw_basdat.dim_staff) AS source_count,
       (SELECT COUNT(*) FROM er_basdat.dim_staff) AS target_count;

SELECT 'dim_hewan' AS table_name,
       (SELECT COUNT(*) FROM dw_basdat.dim_hewan) AS source_count,
       (SELECT COUNT(*) FROM er_basdat.dim_hewan) AS target_count;

SELECT 'dim_paket' AS table_name,
       (SELECT COUNT(*) FROM dw_basdat.dim_paket) AS source_count,
       (SELECT COUNT(*) FROM er_basdat.dim_paket) AS target_count;

SELECT 'dim_waktu' AS table_name,
       (SELECT COUNT(*) FROM dw_basdat.dim_waktu) AS source_count,
       (SELECT COUNT(*) FROM er_basdat.dim_waktu) AS target_count;

SELECT 'fact_transaksi' AS table_name,
       (SELECT COUNT(*) FROM dw_basdat.fact_transaksi) AS source_count,
       (SELECT COUNT(*) FROM er_basdat.fact_transaksi) AS target_count;

SELECT 'fact_kapasitas_harian' AS table_name,
       (SELECT COUNT(*) FROM dw_basdat.fact_kapasitas_harian) AS source_count,
       (SELECT COUNT(*) FROM er_basdat.fact_kapasitas_harian) AS target_count;

SELECT 'fact_keuangan_periodik' AS table_name,
       (SELECT COUNT(*) FROM dw_basdat.fact_keuangan_periodik) AS source_count,
       (SELECT COUNT(*) FROM er_basdat.fact_keuangan_periodik) AS target_count;

SELECT '=== Tables migrated successfully ===' AS status;
