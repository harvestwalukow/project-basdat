-- =====================================================
-- FIX TRIGGERS - Remove refresh_fact_transaksi calls
-- =====================================================
-- This fixes the "commit not allowed in trigger" error
-- by removing TRUNCATE-based procedure calls from triggers
-- =====================================================

USE er_basdat;

DELIMITER $$

-- Drop existing triggers
DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_insert$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_update$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_delete$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_pembayaran_insert$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_pembayaran_update$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_pembayaran_delete$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_detail_penitipan_insert$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_detail_penitipan_update$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_detail_penitipan_delete$$

-- =====================================================
-- PENITIPAN TRIGGERS (WITHOUT refresh_fact_transaksi)
-- =====================================================

CREATE TRIGGER er_basdat.sync_facts_penitipan_insert
AFTER INSERT ON er_basdat.penitipan
FOR EACH ROW
BEGIN
    -- Only update capacity facts, not transaction facts
    CALL dw_basdat.update_fact_kapasitas_for_date(DATE(NEW.tanggal_masuk));
END$$

CREATE TRIGGER er_basdat.sync_facts_penitipan_update
AFTER UPDATE ON er_basdat.penitipan
FOR EACH ROW
BEGIN
    -- Update capacity for both old and new dates
    CALL dw_basdat.update_fact_kapasitas_for_date(DATE(OLD.tanggal_masuk));
    CALL dw_basdat.update_fact_kapasitas_for_date(DATE(NEW.tanggal_masuk));
END$$

CREATE TRIGGER er_basdat.sync_facts_penitipan_delete
AFTER DELETE ON er_basdat.penitipan
FOR EACH ROW
BEGIN
    -- Update capacity for deleted date
    CALL dw_basdat.update_fact_kapasitas_for_date(DATE(OLD.tanggal_masuk));
END$$

-- =====================================================
-- PEMBAYARAN TRIGGERS (WITHOUT refresh_fact_transaksi)
-- =====================================================

CREATE TRIGGER er_basdat.sync_facts_pembayaran_insert
AFTER INSERT ON er_basdat.pembayaran
FOR EACH ROW
BEGIN
    -- Only update financial facts if payment is complete
    IF NEW.status_pembayaran = 'lunas' AND NEW.tanggal_bayar IS NOT NULL THEN
        CALL dw_basdat.update_fact_keuangan_for_month(YEAR(NEW.tanggal_bayar), MONTH(NEW.tanggal_bayar));
    END IF;
END$$

CREATE TRIGGER er_basdat.sync_facts_pembayaran_update
AFTER UPDATE ON er_basdat.pembayaran
FOR EACH ROW
BEGIN
    -- Update old month if status changed from lunas
    IF OLD.status_pembayaran = 'lunas' AND OLD.tanggal_bayar IS NOT NULL THEN
        CALL dw_basdat.update_fact_keuangan_for_month(YEAR(OLD.tanggal_bayar), MONTH(OLD.tanggal_bayar));
    END IF;
    
    -- Update new month if status is now lunas
    IF NEW.status_pembayaran = 'lunas' AND NEW.tanggal_bayar IS NOT NULL THEN
        CALL dw_basdat.update_fact_keuangan_for_month(YEAR(NEW.tanggal_bayar), MONTH(NEW.tanggal_bayar));
    END IF;
END$$

CREATE TRIGGER er_basdat.sync_facts_pembayaran_delete
AFTER DELETE ON er_basdat.pembayaran
FOR EACH ROW
BEGIN
    -- Update month if deleted payment was lunas
    IF OLD.status_pembayaran = 'lunas' AND OLD.tanggal_bayar IS NOT NULL THEN
        CALL dw_basdat.update_fact_keuangan_for_month(YEAR(OLD.tanggal_bayar), MONTH(OLD.tanggal_bayar));
    END IF;
END$$

-- =====================================================
-- DETAIL_PENITIPAN TRIGGERS (REMOVED - not critical)
-- =====================================================
-- We're removing these triggers as they tried to call refresh_fact_transaksi
-- which uses TRUNCATE. For now, fact_transaksi can be refreshed manually if needed.

DELIMITER ;

-- Verify triggers are installed
SELECT 'Triggers updated successfully!' AS status;
SHOW TRIGGERS FROM er_basdat WHERE `Trigger` LIKE 'sync_%';
