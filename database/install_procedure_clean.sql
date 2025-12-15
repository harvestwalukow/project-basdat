-- =====================================================
-- CLEAN INSTALL: update_fact_kapasitas_for_date
-- =====================================================
-- INSTRUCTIONS:
-- 1. Copy EVERYTHING below this line
-- 2. Paste into MariaDB console
-- 3. Press Enter
-- =====================================================

USE dw_basdat;

DROP PROCEDURE IF EXISTS update_fact_kapasitas_for_date;

DELIMITER $$

CREATE PROCEDURE update_fact_kapasitas_for_date(IN target_date DATE)
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
    FROM dw_basdat.dim_waktu
    WHERE tanggal = target_date;
    
    -- If waktu_key doesn't exist, exit early
    IF v_waktu_key IS NULL THEN
        -- Just return, don't do anything
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
    END IF;
END$$

DELIMITER ;

-- Verify it was created
SELECT 'Checking if procedure exists...' AS status;
SHOW PROCEDURE STATUS WHERE Db = 'dw_basdat' AND Name = 'update_fact_kapasitas_for_date';
