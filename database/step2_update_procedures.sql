-- =====================================================
-- STEP 2: UPDATE STORED PROCEDURES FOR ER_BASDAT
-- =====================================================
-- This updates all procedures to reference er_basdat
-- instead of dw_basdat
-- =====================================================

USE er_basdat;

DELIMITER $$

-- =====================================================
-- 1. UPDATE update_fact_kapasitas_for_date
-- =====================================================

DROP PROCEDURE IF EXISTS er_basdat.update_fact_kapasitas_for_date$$

CREATE PROCEDURE er_basdat.update_fact_kapasitas_for_date(IN target_date DATE)
BEGIN
    DECLARE v_waktu_key INT DEFAULT NULL;
    DECLARE v_total_penitipan INT DEFAULT 0;
    DECLARE v_penitipan_aktif INT DEFAULT 0;
    DECLARE v_penitipan_pending INT DEFAULT 0;
    DECLARE v_penitipan_selesai INT DEFAULT 0;
    DECLARE v_penitipan_dibatalkan INT DEFAULT 0;
    DECLARE v_total_hewan INT DEFAULT 0;
    
    -- Get waktu_key for the date
    SELECT waktu_key INTO v_waktu_key
    FROM er_basdat.dim_waktu
    WHERE tanggal = target_date;
    
    -- If waktu_key doesn't exist, exit early
    IF v_waktu_key IS NULL THEN
        SET v_waktu_key = v_waktu_key;  -- dummy statement
    ELSE
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
        INSERT INTO er_basdat.fact_kapasitas_harian 
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

-- =====================================================
-- 2. UPDATE update_fact_keuangan_for_month
-- =====================================================

DROP PROCEDURE IF EXISTS er_basdat.update_fact_keuangan_for_month$$

CREATE PROCEDURE er_basdat.update_fact_keuangan_for_month(IN target_year INT, IN target_month INT)
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
    INSERT INTO er_basdat.fact_keuangan_periodik 
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

DELIMITER ;

-- =====================================================
-- VERIFICATION
-- =====================================================

SELECT 'Stored procedures updated successfully!' AS status;

SHOW PROCEDURE STATUS WHERE Db = 'er_basdat' AND Name LIKE '%fact%';
