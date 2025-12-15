-- =====================================================
-- TRIGGERS FOR SYNCING TRANSACTIONAL DATA TO DW
-- =====================================================
-- This file contains triggers to automatically sync data
-- from er_basdat (transactional) to dw_basdat (warehouse)
-- =====================================================

DELIMITER $$

-- =====================================================
-- 1. DIMENSION TABLES SYNC
-- =====================================================

-- Trigger: Sync dim_customer when pengguna (pet_owner) is inserted/updated
DROP TRIGGER IF EXISTS er_basdat.sync_dim_customer_insert$$
CREATE TRIGGER er_basdat.sync_dim_customer_insert
AFTER INSERT ON er_basdat.pengguna
FOR EACH ROW
BEGIN
    IF NEW.role = 'pet_owner' THEN
        INSERT INTO dw_basdat.dim_customer (id_pengguna, nama_lengkap, email, alamat, no_telepon)
        VALUES (NEW.id_pengguna, NEW.nama_lengkap, NEW.email, NEW.alamat, NEW.no_telepon)
        ON DUPLICATE KEY UPDATE
            nama_lengkap = NEW.nama_lengkap,
            email = NEW.email,
            alamat = NEW.alamat,
            no_telepon = NEW.no_telepon;
    END IF;
END$$

DROP TRIGGER IF EXISTS er_basdat.sync_dim_customer_update$$
CREATE TRIGGER er_basdat.sync_dim_customer_update
AFTER UPDATE ON er_basdat.pengguna
FOR EACH ROW
BEGIN
    IF NEW.role = 'pet_owner' THEN
        UPDATE dw_basdat.dim_customer
        SET nama_lengkap = NEW.nama_lengkap,
            email = NEW.email,
            alamat = NEW.alamat,
            no_telepon = NEW.no_telepon
        WHERE id_pengguna = NEW.id_pengguna;
    END IF;
END$$

-- Trigger: Sync dim_staff when pengguna (staff/admin) is inserted/updated
DROP TRIGGER IF EXISTS er_basdat.sync_dim_staff_insert$$
CREATE TRIGGER er_basdat.sync_dim_staff_insert
AFTER INSERT ON er_basdat.pengguna
FOR EACH ROW
BEGIN
    IF NEW.role IN ('staff', 'admin') THEN
        INSERT INTO dw_basdat.dim_staff (id_pengguna, nama_lengkap, email, role, specialization)
        VALUES (NEW.id_pengguna, NEW.nama_lengkap, NEW.email, NEW.role, NEW.specialization)
        ON DUPLICATE KEY UPDATE
            nama_lengkap = NEW.nama_lengkap,
            email = NEW.email,
            role = NEW.role,
            specialization = NEW.specialization;
    END IF;
END$$

DROP TRIGGER IF EXISTS er_basdat.sync_dim_staff_update$$
CREATE TRIGGER er_basdat.sync_dim_staff_update
AFTER UPDATE ON er_basdat.pengguna
FOR EACH ROW
BEGIN
    IF NEW.role IN ('staff', 'admin') THEN
        UPDATE dw_basdat.dim_staff
        SET nama_lengkap = NEW.nama_lengkap,
            email = NEW.email,
            role = NEW.role,
            specialization = NEW.specialization
        WHERE id_pengguna = NEW.id_pengguna;
    END IF;
END$$

-- Trigger: Sync dim_hewan when hewan is inserted/updated
DROP TRIGGER IF EXISTS er_basdat.sync_dim_hewan_insert$$
CREATE TRIGGER er_basdat.sync_dim_hewan_insert
AFTER INSERT ON er_basdat.hewan
FOR EACH ROW
BEGIN
    INSERT INTO dw_basdat.dim_hewan (id_hewan, nama_hewan, jenis_hewan, ras, umur, jenis_kelamin, berat)
    VALUES (NEW.id_hewan, NEW.nama_hewan, NEW.jenis_hewan, NEW.ras, NEW.umur, NEW.jenis_kelamin, NEW.berat)
    ON DUPLICATE KEY UPDATE
        nama_hewan = NEW.nama_hewan,
        jenis_hewan = NEW.jenis_hewan,
        ras = NEW.ras,
        umur = NEW.umur,
        jenis_kelamin = NEW.jenis_kelamin,
        berat = NEW.berat;
END$$

DROP TRIGGER IF EXISTS er_basdat.sync_dim_hewan_update$$
CREATE TRIGGER er_basdat.sync_dim_hewan_update
AFTER UPDATE ON er_basdat.hewan
FOR EACH ROW
BEGIN
    UPDATE dw_basdat.dim_hewan
    SET nama_hewan = NEW.nama_hewan,
        jenis_hewan = NEW.jenis_hewan,
        ras = NEW.ras,
        umur = NEW.umur,
        jenis_kelamin = NEW.jenis_kelamin,
        berat = NEW.berat
    WHERE id_hewan = NEW.id_hewan;
END$$

-- Trigger: Sync dim_paket when paket_layanan is inserted/updated
DROP TRIGGER IF EXISTS er_basdat.sync_dim_paket_insert$$
CREATE TRIGGER er_basdat.sync_dim_paket_insert
AFTER INSERT ON er_basdat.paket_layanan
FOR EACH ROW
BEGIN
    INSERT INTO dw_basdat.dim_paket (id_paket, nama_paket, harga_per_hari, is_active)
    VALUES (NEW.id_paket, NEW.nama_paket, NEW.harga_per_hari, NEW.is_active)
    ON DUPLICATE KEY UPDATE
        nama_paket = NEW.nama_paket,
        harga_per_hari = NEW.harga_per_hari,
        is_active = NEW.is_active;
END$$

DROP TRIGGER IF EXISTS er_basdat.sync_dim_paket_update$$
CREATE TRIGGER er_basdat.sync_dim_paket_update
AFTER UPDATE ON er_basdat.paket_layanan
FOR EACH ROW
BEGIN
    UPDATE dw_basdat.dim_paket
    SET nama_paket = NEW.nama_paket,
        harga_per_hari = NEW.harga_per_hari,
        is_active = NEW.is_active
    WHERE id_paket = NEW.id_paket;
END$$

-- =====================================================
-- 2. FACT TABLES SYNC - PENITIPAN TRIGGERS
-- =====================================================

-- Trigger: Update fact tables when penitipan is inserted
DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_insert$$
CREATE TRIGGER er_basdat.sync_facts_penitipan_insert
AFTER INSERT ON er_basdat.penitipan
FOR EACH ROW
BEGIN
    CALL dw_basdat.update_fact_kapasitas_for_date(DATE(NEW.tanggal_masuk));
    CALL dw_basdat.refresh_fact_transaksi();
END$$

-- Trigger: Update fact tables when penitipan is updated
DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_update$$
CREATE TRIGGER er_basdat.sync_facts_penitipan_update
AFTER UPDATE ON er_basdat.penitipan
FOR EACH ROW
BEGIN
    CALL dw_basdat.update_fact_kapasitas_for_date(DATE(OLD.tanggal_masuk));
    CALL dw_basdat.update_fact_kapasitas_for_date(DATE(NEW.tanggal_masuk));
    CALL dw_basdat.refresh_fact_transaksi();
END$$

-- Trigger: Update fact tables when penitipan is deleted
DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_delete$$
CREATE TRIGGER er_basdat.sync_facts_penitipan_delete
AFTER DELETE ON er_basdat.penitipan
FOR EACH ROW
BEGIN
    CALL dw_basdat.update_fact_kapasitas_for_date(DATE(OLD.tanggal_masuk));
    CALL dw_basdat.refresh_fact_transaksi();
END$$

-- =====================================================
-- 3. FACT TABLES SYNC - PEMBAYARAN TRIGGERS
-- =====================================================

-- Trigger: Update fact tables when pembayaran is inserted
DROP TRIGGER IF EXISTS er_basdat.sync_facts_pembayaran_insert$$
CREATE TRIGGER er_basdat.sync_facts_pembayaran_insert
AFTER INSERT ON er_basdat.pembayaran
FOR EACH ROW
BEGIN
    IF NEW.status_pembayaran = 'lunas' AND NEW.tanggal_bayar IS NOT NULL THEN
        CALL dw_basdat.update_fact_keuangan_for_month(YEAR(NEW.tanggal_bayar), MONTH(NEW.tanggal_bayar));
    END IF;
    CALL dw_basdat.refresh_fact_transaksi();
END$$

-- Trigger: Update fact tables when pembayaran is updated
DROP TRIGGER IF EXISTS er_basdat.sync_facts_pembayaran_update$$
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
    
    CALL dw_basdat.refresh_fact_transaksi();
END$$

-- Trigger: Update fact tables when pembayaran is deleted
DROP TRIGGER IF EXISTS er_basdat.sync_facts_pembayaran_delete$$
CREATE TRIGGER er_basdat.sync_facts_pembayaran_delete
AFTER DELETE ON er_basdat.pembayaran
FOR EACH ROW
BEGIN
    IF OLD.status_pembayaran = 'lunas' AND OLD.tanggal_bayar IS NOT NULL THEN
        CALL dw_basdat.update_fact_keuangan_for_month(YEAR(OLD.tanggal_bayar), MONTH(OLD.tanggal_bayar));
    END IF;
    CALL dw_basdat.refresh_fact_transaksi();
END$$

-- =====================================================
-- 4. FACT TABLES SYNC - DETAIL_PENITIPAN TRIGGERS
-- =====================================================

-- Trigger: Update fact_transaksi when detail_penitipan changes
DROP TRIGGER IF EXISTS er_basdat.sync_facts_detail_penitipan_insert$$
CREATE TRIGGER er_basdat.sync_facts_detail_penitipan_insert
AFTER INSERT ON er_basdat.detail_penitipan
FOR EACH ROW
BEGIN
    CALL dw_basdat.refresh_fact_transaksi();
END$$

DROP TRIGGER IF EXISTS er_basdat.sync_facts_detail_penitipan_update$$
CREATE TRIGGER er_basdat.sync_facts_detail_penitipan_update
AFTER UPDATE ON er_basdat.detail_penitipan
FOR EACH ROW
BEGIN
    CALL dw_basdat.refresh_fact_transaksi();
END$$

DROP TRIGGER IF EXISTS er_basdat.sync_facts_detail_penitipan_delete$$
CREATE TRIGGER er_basdat.sync_facts_detail_penitipan_delete
AFTER DELETE ON er_basdat.detail_penitipan
FOR EACH ROW
BEGIN
    CALL dw_basdat.refresh_fact_transaksi();
END$$

DELIMITER ;

-- =====================================================
-- END OF TRIGGERS
-- =====================================================


