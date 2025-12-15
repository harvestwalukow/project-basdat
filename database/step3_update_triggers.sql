-- =====================================================
-- STEP 3: UPDATE TRIGGERS FOR ER_BASDAT
-- =====================================================
-- This updates all triggers to call procedures in er_basdat
-- and reference er_basdat tables
-- =====================================================

USE er_basdat;

DELIMITER $$

-- Drop ALL existing sync triggers first
DROP TRIGGER IF EXISTS er_basdat.sync_dim_customer_insert$$
DROP TRIGGER IF EXISTS er_basdat.sync_dim_customer_update$$
DROP TRIGGER IF EXISTS er_basdat.sync_dim_staff_insert$$
DROP TRIGGER IF EXISTS er_basdat.sync_dim_staff_update$$
DROP TRIGGER IF EXISTS er_basdat.sync_dim_hewan_insert$$
DROP TRIGGER IF EXISTS er_basdat.sync_dim_hewan_update$$
DROP TRIGGER IF EXISTS er_basdat.sync_dim_paket_insert$$
DROP TRIGGER IF EXISTS er_basdat.sync_dim_paket_update$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_insert$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_update$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_delete$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_pembayaran_insert$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_pembayaran_update$$
DROP TRIGGER IF EXISTS er_basdat.sync_facts_pembayaran_delete$$

-- =====================================================
-- DIMENSION SYNC TRIGGERS
-- =====================================================

-- sync_dim_customer_insert
CREATE TRIGGER er_basdat.sync_dim_customer_insert
AFTER INSERT ON er_basdat.pengguna
FOR EACH ROW
BEGIN
    IF NEW.role = 'pet_owner' THEN
        INSERT INTO er_basdat.dim_customer (id_pengguna, nama_lengkap, email, alamat, no_telepon)
        VALUES (NEW.id_pengguna, NEW.nama_lengkap, NEW.email, NEW.alamat, NEW.no_telepon)
        ON DUPLICATE KEY UPDATE
            nama_lengkap = NEW.nama_lengkap,
            email = NEW.email,
            alamat = NEW.alamat,
            no_telepon = NEW.no_telepon;
    END IF;
END$$

-- sync_dim_customer_update
CREATE TRIGGER er_basdat.sync_dim_customer_update
AFTER UPDATE ON er_basdat.pengguna
FOR EACH ROW
BEGIN
    IF NEW.role = 'pet_owner' THEN
        UPDATE er_basdat.dim_customer
        SET nama_lengkap = NEW.nama_lengkap,
            email = NEW.email,
            alamat = NEW.alamat,
            no_telepon = NEW.no_telepon
        WHERE id_pengguna = NEW.id_pengguna;
    END IF;
END$$

-- sync_dim_staff_insert
CREATE TRIGGER er_basdat.sync_dim_staff_insert
AFTER INSERT ON er_basdat.pengguna
FOR EACH ROW
BEGIN
    IF NEW.role IN ('staff', 'admin') THEN
        INSERT INTO er_basdat.dim_staff (id_pengguna, nama_lengkap, email, role, specialization)
        VALUES (NEW.id_pengguna, NEW.nama_lengkap, NEW.email, NEW.role, NEW.specialization)
        ON DUPLICATE KEY UPDATE
            nama_lengkap = NEW.nama_lengkap,
            email = NEW.email,
            role = NEW.role,
            specialization = NEW.specialization;
    END IF;
END$$

-- sync_dim_staff_update
CREATE TRIGGER er_basdat.sync_dim_staff_update
AFTER UPDATE ON er_basdat.pengguna
FOR EACH ROW
BEGIN
    IF NEW.role IN ('staff', 'admin') THEN
        UPDATE er_basdat.dim_staff
        SET nama_lengkap = NEW.nama_lengkap,
            email = NEW.email,
            role = NEW.role,
            specialization = NEW.specialization
        WHERE id_pengguna = NEW.id_pengguna;
    END IF;
END$$

-- sync_dim_hewan_insert
CREATE TRIGGER er_basdat.sync_dim_hewan_insert
AFTER INSERT ON er_basdat.hewan
FOR EACH ROW
BEGIN
    INSERT INTO er_basdat.dim_hewan (id_hewan, nama_hewan, jenis_hewan, ras, umur, jenis_kelamin, berat)
    VALUES (NEW.id_hewan, NEW.nama_hewan, NEW.jenis_hewan, NEW.ras, NEW.umur, NEW.jenis_kelamin, NEW.berat)
    ON DUPLICATE KEY UPDATE
        nama_hewan = NEW.nama_hewan,
        jenis_hewan = NEW.jenis_hewan,
        ras = NEW.ras,
        umur = NEW.umur,
        jenis_kelamin = NEW.jenis_kelamin,
        berat = NEW.berat;
END$$

-- sync_dim_hewan_update
CREATE TRIGGER er_basdat.sync_dim_hewan_update
AFTER UPDATE ON er_basdat.hewan
FOR EACH ROW
BEGIN
    UPDATE er_basdat.dim_hewan
    SET nama_hewan = NEW.nama_hewan,
        jenis_hewan = NEW.jenis_hewan,
        ras = NEW.ras,
        umur = NEW.umur,
        jenis_kelamin = NEW.jenis_kelamin,
        berat = NEW.berat
    WHERE id_hewan = NEW.id_hewan;
END$$

-- sync_dim_paket_insert
CREATE TRIGGER er_basdat.sync_dim_paket_insert
AFTER INSERT ON er_basdat.paket_layanan
FOR EACH ROW
BEGIN
    INSERT INTO er_basdat.dim_paket (id_paket, nama_paket, harga_per_hari, is_active)
    VALUES (NEW.id_paket, NEW.nama_paket, NEW.harga_per_hari, NEW.is_active)
    ON DUPLICATE KEY UPDATE
        nama_paket = NEW.nama_paket,
        harga_per_hari = NEW.harga_per_hari,
        is_active = NEW.is_active;
END$$

-- sync_dim_paket_update
CREATE TRIGGER er_basdat.sync_dim_paket_update
AFTER UPDATE ON er_basdat.paket_layanan
FOR EACH ROW
BEGIN
    UPDATE er_basdat.dim_paket
    SET nama_paket = NEW.nama_paket,
        harga_per_hari = NEW.harga_per_hari,
        is_active = NEW.is_active
    WHERE id_paket = NEW.id_paket;
END$$

-- =====================================================
-- FACT SYNC TRIGGERS (NO TRUNCATE)
-- =====================================================

-- sync_facts_penitipan_insert
CREATE TRIGGER er_basdat.sync_facts_penitipan_insert
AFTER INSERT ON er_basdat.penitipan
FOR EACH ROW
BEGIN
    CALL er_basdat.update_fact_kapasitas_for_date(DATE(NEW.tanggal_masuk));
END$$

-- sync_facts_penitipan_update
CREATE TRIGGER er_basdat.sync_facts_penitipan_update
AFTER UPDATE ON er_basdat.penitipan
FOR EACH ROW
BEGIN
    CALL er_basdat.update_fact_kapasitas_for_date(DATE(OLD.tanggal_masuk));
    CALL er_basdat.update_fact_kapasitas_for_date(DATE(NEW.tanggal_masuk));
END$$

-- sync_facts_penitipan_delete
CREATE TRIGGER er_basdat.sync_facts_penitipan_delete
AFTER DELETE ON er_basdat.penitipan
FOR EACH ROW
BEGIN
    CALL er_basdat.update_fact_kapasitas_for_date(DATE(OLD.tanggal_masuk));
END$$

-- sync_facts_pembayaran_insert
CREATE TRIGGER er_basdat.sync_facts_pembayaran_insert
AFTER INSERT ON er_basdat.pembayaran
FOR EACH ROW
BEGIN
    IF NEW.status_pembayaran = 'lunas' AND NEW.tanggal_bayar IS NOT NULL THEN
        CALL er_basdat.update_fact_keuangan_for_month(YEAR(NEW.tanggal_bayar), MONTH(NEW.tanggal_bayar));
    END IF;
END$$

-- sync_facts_pembayaran_update
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
END$$

-- sync_facts_pembayaran_delete
CREATE TRIGGER er_basdat.sync_facts_pembayaran_delete
AFTER DELETE ON er_basdat.pembayaran
FOR EACH ROW
BEGIN
    IF OLD.status_pembayaran = 'lunas' AND OLD.tanggal_bayar IS NOT NULL THEN
        CALL er_basdat.update_fact_keuangan_for_month(YEAR(OLD.tanggal_bayar), MONTH(OLD.tanggal_bayar));
    END IF;
END$$

DELIMITER ;

-- =====================================================
-- VERIFICATION
-- =====================================================

SELECT 'Triggers updated successfully!' AS status;

SHOW TRIGGERS FROM er_basdat WHERE `Trigger` LIKE 'sync_%';
