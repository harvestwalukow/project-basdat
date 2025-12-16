-- =====================================================
-- STORED PROCEDURES FOR ETL OPERATIONS
-- =====================================================
-- This file contains stored procedures for syncing data
-- from er_basdat (transactional) to dw_basdat (warehouse)
-- =====================================================

USE dw_basdat;

DELIMITER $$

-- =====================================================
-- 1. FACT_KAPASITAS_HARIAN UPDATE
-- =====================================================

DROP PROCEDURE IF EXISTS update_fact_kapasitas_for_date$$
CREATE PROCEDURE update_fact_kapasitas_for_date(IN target_date DATE)
BEGIN
    DECLARE v_waktu_key INT;
    DECLARE v_total_penitipan INT;
    DECLARE v_penitipan_aktif INT;
    DECLARE v_penitipan_pending INT;
    DECLARE v_penitipan_selesai INT;
    DECLARE v_penitipan_dibatalkan INT;
    DECLARE v_total_hewan INT;
    
    -- Get waktu_key for the date
    SELECT waktu_key INTO v_waktu_key
    FROM dw_basdat.dim_waktu
    WHERE tanggal = target_date;
    
    -- If waktu_key doesn't exist, exit
    IF v_waktu_key IS NULL THEN
        RETURN;
    END IF;
    
    -- Count penitipan statistics for the date
    SELECT 
        COUNT(*),
        SUM(CASE WHEN status = 'aktif' THEN 1 ELSE 0 END),
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END),
        SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END),
        SUM(CASE WHEN status = 'dibatalkan' THEN 1 ELSE 0 END),
        COUNT(DISTINCT id_hewan)
    INTO v_total_penitipan, v_penitipan_aktif, v_penitipan_pending, 
         v_penitipan_selesai, v_penitipan_dibatalkan, v_total_hewan
    FROM er_basdat.penitipan
    WHERE target_date BETWEEN DATE(tanggal_masuk) AND DATE(tanggal_keluar);
    
    -- Upsert into fact_kapasitas_harian
    INSERT INTO dw_basdat.fact_kapasitas_harian 
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
END$$

-- =====================================================
-- 2. FACT_KEUANGAN_PERIODIK UPDATE
-- =====================================================

DROP PROCEDURE IF EXISTS update_fact_keuangan_for_month$$
CREATE PROCEDURE update_fact_keuangan_for_month(IN target_year INT, IN target_month INT)
BEGIN
    DECLARE v_total_revenue DECIMAL(12,2);
    DECLARE v_jumlah_transaksi INT;
    DECLARE v_avg_transaksi DECIMAL(12,2);
    DECLARE v_periode_yyyymm INT;
    
    SET v_periode_yyyymm = target_year * 100 + target_month;
    
    -- Calculate monthly financial statistics
    SELECT 
        SUM(jumlah_bayar),
        COUNT(*),
        AVG(jumlah_bayar)
    INTO v_total_revenue, v_jumlah_transaksi, v_avg_transaksi
    FROM er_basdat.pembayaran
    WHERE status_pembayaran = 'lunas'
      AND YEAR(tanggal_bayar) = target_year
      AND MONTH(tanggal_bayar) = target_month;
    
    -- Upsert into fact_keuangan_periodik
    INSERT INTO dw_basdat.fact_keuangan_periodik 
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

-- =====================================================
-- 3. FACT_TRANSAKSI REFRESH
-- =====================================================

DROP PROCEDURE IF EXISTS refresh_fact_transaksi$$
CREATE PROCEDURE refresh_fact_transaksi()
BEGIN
    -- Clear existing fact_transaksi data
    TRUNCATE TABLE dw_basdat.fact_transaksi;
    
    -- Repopulate fact_transaksi from transactional database
    INSERT INTO dw_basdat.fact_transaksi
    (waktu_key, customer_key, hewan_key, paket_key, staff_key, status_key, pembayaran_key,
     jumlah_hari, total_biaya, jumlah_transaksi,
     id_penitipan, tanggal_masuk, id_pemilik, id_hewan, id_paket, id_staff, 
     status, metode_pembayaran, status_pembayaran)
    SELECT 
        dw.waktu_key,
        dc.customer_key,
        dh.hewan_key,
        dp.paket_key,
        ds.staff_key,
        dst.status_key,
        dpb.pembayaran_key,
        DATEDIFF(p.tanggal_keluar, p.tanggal_masuk) AS jumlah_hari,
        p.total_biaya,
        1 AS jumlah_transaksi,
        p.id_penitipan,
        p.tanggal_masuk,
        p.id_pemilik,
        p.id_hewan,
        detail.id_paket,
        p.id_staff,
        p.status,
        pay.metode_pembayaran,
        pay.status_pembayaran
    FROM er_basdat.penitipan p
    INNER JOIN er_basdat.hewan h ON p.id_hewan = h.id_hewan
    INNER JOIN er_basdat.detail_penitipan detail ON p.id_penitipan = detail.id_penitipan
    LEFT JOIN er_basdat.pembayaran pay ON p.id_penitipan = pay.id_penitipan
    -- Join with dimension tables
    LEFT JOIN dw_basdat.dim_waktu dw ON dw.tanggal = DATE(p.tanggal_masuk)
    LEFT JOIN dw_basdat.dim_customer dc ON dc.id_pengguna = p.id_pemilik
    LEFT JOIN dw_basdat.dim_hewan dh ON dh.id_hewan = p.id_hewan
    LEFT JOIN dw_basdat.dim_paket dp ON dp.id_paket = detail.id_paket
    LEFT JOIN dw_basdat.dim_staff ds ON ds.id_pengguna = p.id_staff
    LEFT JOIN dw_basdat.dim_status_penitipan dst ON dst.status = p.status
    LEFT JOIN dw_basdat.dim_pembayaran dpb ON dpb.metode_pembayaran = pay.metode_pembayaran 
                                            AND dpb.status_pembayaran = pay.status_pembayaran;
END$$

-- =====================================================
-- 4. FULL ETL REFRESH
-- =====================================================

DROP PROCEDURE IF EXISTS full_etl_refresh$$
CREATE PROCEDURE full_etl_refresh()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_current_date DATE;
    DECLARE v_start_date DATE;
    DECLARE v_end_date DATE;
    DECLARE v_current_year INT;
    DECLARE v_current_month INT;
    
    -- Set date range (last 2 years)
    SET v_end_date = CURDATE();
    SET v_start_date = DATE_SUB(v_end_date, INTERVAL 2 YEAR);
    SET v_current_date = v_start_date;
    
    -- 1. Refresh dimension tables
    TRUNCATE TABLE dw_basdat.dim_customer;
    INSERT INTO dw_basdat.dim_customer (id_pengguna, nama_lengkap, email, alamat, no_telepon)
    SELECT id_pengguna, nama_lengkap, email, alamat, no_telepon
    FROM er_basdat.pengguna
    WHERE role = 'pet_owner';
    
    TRUNCATE TABLE dw_basdat.dim_staff;
    INSERT INTO dw_basdat.dim_staff (id_pengguna, nama_lengkap, email, role, specialization)
    SELECT id_pengguna, nama_lengkap, email, role, specialization
    FROM er_basdat.pengguna
    WHERE role IN ('staff', 'admin');
    
    TRUNCATE TABLE dw_basdat.dim_hewan;
    INSERT INTO dw_basdat.dim_hewan (id_hewan, nama_hewan, jenis_hewan, ras, umur, jenis_kelamin, berat)
    SELECT id_hewan, nama_hewan, jenis_hewan, ras, umur, jenis_kelamin, berat
    FROM er_basdat.hewan;
    
    TRUNCATE TABLE dw_basdat.dim_paket;
    INSERT INTO dw_basdat.dim_paket (id_paket, nama_paket, harga_per_hari, is_active)
    SELECT id_paket, nama_paket, harga_per_hari, is_active
    FROM er_basdat.paket_layanan;
    
    -- 2. Refresh fact_kapasitas_harian for all dates in range
    WHILE v_current_date <= v_end_date DO
        CALL update_fact_kapasitas_for_date(v_current_date);
        SET v_current_date = DATE_ADD(v_current_date, INTERVAL 1 DAY);
    END WHILE;
    
    -- 3. Refresh fact_keuangan_periodik for all months in range
    SET v_current_date = v_start_date;
    WHILE v_current_date <= v_end_date DO
        SET v_current_year = YEAR(v_current_date);
        SET v_current_month = MONTH(v_current_date);
        CALL update_fact_keuangan_for_month(v_current_year, v_current_month);
        SET v_current_date = DATE_ADD(v_current_date, INTERVAL 1 MONTH);
    END WHILE;
    
    -- 4. Refresh fact_transaksi
    CALL refresh_fact_transaksi();
    
    SELECT 'ETL refresh completed successfully' AS status;
END$$

DELIMITER ;

-- =====================================================
-- END OF STORED PROCEDURES
-- =====================================================



