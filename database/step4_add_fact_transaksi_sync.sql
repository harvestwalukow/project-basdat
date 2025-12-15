-- =====================================================
-- STEP 4: ADD FACT_TRANSAKSI INCREMENTAL UPDATE
-- =====================================================
-- This adds incremental update capability for fact_transaksi
-- instead of full TRUNCATE+reload (which doesn't work in triggers)
-- =====================================================

USE er_basdat;

DELIMITER $$

-- =====================================================
-- PROCEDURE: Upsert single transaction to fact_transaksi
-- =====================================================

DROP PROCEDURE IF EXISTS er_basdat.upsert_fact_transaksi_for_penitipan$$

CREATE PROCEDURE er_basdat.upsert_fact_transaksi_for_penitipan(IN p_id_penitipan INT)
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
    
    -- Get penitipan data
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
    FROM er_basdat.penitipan p
    LEFT JOIN er_basdat.detail_penitipan dp ON p.id_penitipan = dp.id_penitipan
    LEFT JOIN er_basdat.pembayaran pay ON p.id_penitipan = pay.id_penitipan
    WHERE p.id_penitipan = p_id_penitipan
    LIMIT 1;
    
    -- Get dimension keys
    SELECT waktu_key INTO v_waktu_key 
    FROM er_basdat.dim_waktu 
    WHERE tanggal = DATE(v_tanggal_masuk) 
    LIMIT 1;
    
    SELECT customer_key INTO v_customer_key 
    FROM er_basdat.dim_customer 
    WHERE id_pengguna = v_id_pemilik 
    LIMIT 1;
    
    SELECT hewan_key INTO v_hewan_key 
    FROM er_basdat.dim_hewan 
    WHERE id_hewan = v_id_hewan 
    LIMIT 1;
    
    SELECT paket_key INTO v_paket_key 
    FROM er_basdat.dim_paket 
    WHERE id_paket = v_id_paket 
    LIMIT 1;
    
    SELECT staff_key INTO v_staff_key 
    FROM er_basdat.dim_staff 
    WHERE id_pengguna = v_id_staff 
    LIMIT 1;
    
    SELECT status_key INTO v_status_key 
    FROM er_basdat.dim_status_penitipan 
    WHERE status = v_status 
    LIMIT 1;
    
    SELECT pembayaran_key INTO v_pembayaran_key 
    FROM er_basdat.dim_pembayaran 
    WHERE metode_pembayaran = v_metode_pembayaran 
      AND status_pembayaran = v_status_pembayaran 
    LIMIT 1;
    
    -- Upsert into fact_transaksi
    INSERT INTO er_basdat.fact_transaksi
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

-- =====================================================
-- UPDATE TRIGGERS to include fact_transaksi sync
-- =====================================================

-- Drop existing penitipan triggers
DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_insert$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_update$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_delete$$

-- Recreate with fact_transaksi update
CREATE TRIGGER er_basdat.sync_facts_penitipan_insert
AFTER INSERT ON er_basdat.penitipan
FOR EACH ROW
BEGIN
    CALL er_basdat.update_fact_kapasitas_for_date(DATE(NEW.tanggal_masuk));
    CALL er_basdat.upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
END$$

CREATE TRIGGER er_basdat.sync_facts_penitipan_update
AFTER UPDATE ON er_basdat.penitipan
FOR EACH ROW
BEGIN
    CALL er_basdat.update_fact_kapasitas_for_date(DATE(OLD.tanggal_masuk));
    CALL er_basdat.update_fact_kapasitas_for_date(DATE(NEW.tanggal_masuk));
    CALL er_basdat.upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
END$$

CREATE TRIGGER er_basdat.sync_facts_penitipan_delete
AFTER DELETE ON er_basdat.penitipan
FOR EACH ROW
BEGIN
    CALL er_basdat.update_fact_kapasitas_for_date(DATE(OLD.tanggal_masuk));
    DELETE FROM er_basdat.fact_transaksi WHERE id_penitipan = OLD.id_penitipan;
END$$

-- Also update detail_penitipan triggers (needed for paket updates)
DROP TRIGGER IF EXISTS er_basdat.sync_facts_detail_penitipan_insert$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_detail_penitipan_update$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_detail_penitipan_delete$$

CREATE TRIGGER er_basdat.sync_facts_detail_penitipan_insert
AFTER INSERT ON er_basdat.detail_penitipan
FOR EACH ROW
BEGIN
    CALL er_basdat.upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
END$$

CREATE TRIGGER er_basdat.sync_facts_detail_penitipan_update
AFTER UPDATE ON er_basdat.detail_penitipan
FOR EACH ROW
BEGIN
    CALL er_basdat.upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
END$$

CREATE TRIGGER er_basdat.sync_facts_detail_penitipan_delete
AFTER DELETE ON er_basdat.detail_penitipan
FOR EACH ROW
BEGIN
    CALL er_basdat.upsert_fact_transaksi_for_penitipan(OLD.id_penitipan);
END$$

-- Also update pembayaran triggers (needed for payment status updates)
DROP TRIGGER IF EXISTS er_basdat.sync_facts_pembayaran_insert$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_pembayaran_update$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_pembayaran_delete$$

CREATE TRIGGER er_basdat.sync_facts_pembayaran_insert
AFTER INSERT ON er_basdat.pembayaran
FOR EACH ROW
BEGIN
    IF NEW.status_pembayaran = 'lunas' AND NEW.tanggal_bayar IS NOT NULL THEN
        CALL er_basdat.update_fact_keuangan_for_month(YEAR(NEW.tanggal_bayar), MONTH(NEW.tanggal_bayar));
    END IF;
    CALL er_basdat.upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
END$$

CREATE TRIGGER er_basdat.sync_facts_pembayaran_update
AFTER UPDATE ON er_basdat.pembayaran
FOR EACH ROW
BEGIN
    IF OLD.status_pembayaran = 'lunas' AND OLD.tanggal_bayar IS NOT NULL THEN
        CALL er_basdat.update_fact_keuangan_for_month(YEAR(OLD.tanggal_bayar), MONTH(OLD.tanggal_bayar));
    END IF;
    
    IF NEW.status_pembayaran = 'lunas' AND NEW.tanggal_bayar IS NOT NULL THEN
        CALL er_basdat.update_fact_keuangan_for_month(YEAR(NEW.tanggal_bayar), MONTH(NEW.tanggal_bayar));
    END IF;
    
    CALL er_basdat.upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
END$$

CREATE TRIGGER er_basdat.sync_facts_pembayaran_delete
AFTER DELETE ON er_basdat.pembayaran
FOR EACH ROW
BEGIN
    IF OLD.status_pembayaran = 'lunas' AND OLD.tanggal_bayar IS NOT NULL THEN
        CALL er_basdat.update_fact_keuangan_for_month(YEAR(OLD.tanggal_bayar), MONTH(OLD.tanggal_bayar));
    END IF;
    CALL er_basdat.upsert_fact_transaksi_for_penitipan(OLD.id_penitipan);
END$$

DELIMITER ;

-- =====================================================
-- VERIFICATION
-- =====================================================

SELECT 'fact_transaksi incremental update added successfully!' AS status;

SHOW PROCEDURE STATUS WHERE Db = 'er_basdat' AND Name LIKE '%fact%';

SHOW TRIGGERS FROM er_basdat WHERE `Trigger` LIKE 'sync_%';
