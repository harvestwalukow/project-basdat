<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set delimiter temporarily for procedures
        DB::unprepared("DROP PROCEDURE IF EXISTS update_fact_kapasitas_for_date");
        
        DB::unprepared("
            CREATE PROCEDURE update_fact_kapasitas_for_date(IN target_date DATE)
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
            END
        ");
        
        DB::unprepared("DROP PROCEDURE IF EXISTS update_fact_keuangan_for_month");
        
        DB::unprepared("
            CREATE PROCEDURE update_fact_keuangan_for_month(IN target_year INT, IN target_month INT)
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
            END
        ");
        
        DB::unprepared("DROP PROCEDURE IF EXISTS upsert_fact_transaksi_for_penitipan");
        
        DB::unprepared("
            CREATE PROCEDURE upsert_fact_transaksi_for_penitipan(IN p_id_penitipan INT)
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
            END
        ");
        
        // Create triggers
        $this->createTriggers();
    }
    
    private function createTriggers()
    {
        // Dimension sync triggers
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_customer_insert");
        DB::unprepared("
            CREATE TRIGGER sync_dim_customer_insert
            AFTER INSERT ON pengguna
            FOR EACH ROW
            BEGIN
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
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_customer_update");
        DB::unprepared("
            CREATE TRIGGER sync_dim_customer_update
            AFTER UPDATE ON pengguna
            FOR EACH ROW
            BEGIN
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
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_staff_insert");
        DB::unprepared("
            CREATE TRIGGER sync_dim_staff_insert
            AFTER INSERT ON pengguna
            FOR EACH ROW
            BEGIN
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
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_staff_update");
        DB::unprepared("
            CREATE TRIGGER sync_dim_staff_update
            AFTER UPDATE ON pengguna
            FOR EACH ROW
            BEGIN
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
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_hewan_insert");
        DB::unprepared("
            CREATE TRIGGER sync_dim_hewan_insert
            AFTER INSERT ON hewan
            FOR EACH ROW
            BEGIN
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
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_hewan_update");
        DB::unprepared("
            CREATE TRIGGER sync_dim_hewan_update
            AFTER UPDATE ON hewan
            FOR EACH ROW
            BEGIN
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
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_paket_insert");
        DB::unprepared("
            CREATE TRIGGER sync_dim_paket_insert
            AFTER INSERT ON paket_layanan
            FOR EACH ROW
            BEGIN
                INSERT INTO dim_paket (id_paket, nama_paket, harga_per_hari, is_active, created_at, updated_at)
                VALUES (NEW.id_paket, NEW.nama_paket, NEW.harga_per_hari, NEW.is_active, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    nama_paket = NEW.nama_paket,
                    harga_per_hari = NEW.harga_per_hari,
                    is_active = NEW.is_active,
                    updated_at = NOW();
            END
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_paket_update");
        DB::unprepared("
            CREATE TRIGGER sync_dim_paket_update
            AFTER UPDATE ON paket_layanan
            FOR EACH ROW
            BEGIN
                UPDATE dim_paket
                SET nama_paket = NEW.nama_paket,
                    harga_per_hari = NEW.harga_per_hari,
                    is_active = NEW.is_active,
                    updated_at = NOW()
                WHERE id_paket = NEW.id_paket;
            END
        ");
        
        // Fact sync triggers
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_penitipan_insert");
        DB::unprepared("
            CREATE TRIGGER sync_facts_penitipan_insert
            AFTER INSERT ON penitipan
            FOR EACH ROW
            BEGIN
                CALL update_fact_kapasitas_for_date(DATE(NEW.tanggal_masuk));
                CALL upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
            END
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_penitipan_update");
        DB::unprepared("
            CREATE TRIGGER sync_facts_penitipan_update
            AFTER UPDATE ON penitipan
            FOR EACH ROW
            BEGIN
                CALL update_fact_kapasitas_for_date(DATE(OLD.tanggal_masuk));
                CALL update_fact_kapasitas_for_date(DATE(NEW.tanggal_masuk));
                CALL upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
            END
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_penitipan_delete");
        DB::unprepared("
            CREATE TRIGGER sync_facts_penitipan_delete
            AFTER DELETE ON penitipan
            FOR EACH ROW
            BEGIN
                CALL update_fact_kapasitas_for_date(DATE(OLD.tanggal_masuk));
                DELETE FROM fact_transaksi WHERE id_penitipan = OLD.id_penitipan;
            END
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_detail_penitipan_insert");
        DB::unprepared("
            CREATE TRIGGER sync_facts_detail_penitipan_insert
            AFTER INSERT ON detail_penitipan
            FOR EACH ROW
            BEGIN
                CALL upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
            END
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_detail_penitipan_update");
        DB::unprepared("
            CREATE TRIGGER sync_facts_detail_penitipan_update
            AFTER UPDATE ON detail_penitipan
            FOR EACH ROW
            BEGIN
                CALL upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
            END
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_detail_penitipan_delete");
        DB::unprepared("
            CREATE TRIGGER sync_facts_detail_penitipan_delete
            AFTER DELETE ON detail_penitipan
            FOR EACH ROW
            BEGIN
                CALL upsert_fact_transaksi_for_penitipan(OLD.id_penitipan);
            END
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_pembayaran_insert");
        DB::unprepared("
            CREATE TRIGGER sync_facts_pembayaran_insert
            AFTER INSERT ON pembayaran
            FOR EACH ROW
            BEGIN
                IF NEW.status_pembayaran = 'lunas' AND NEW.tanggal_bayar IS NOT NULL THEN
                    CALL update_fact_keuangan_for_month(YEAR(NEW.tanggal_bayar), MONTH(NEW.tanggal_bayar));
                END IF;
                CALL upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
            END
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_pembayaran_update");
        DB::unprepared("
            CREATE TRIGGER sync_facts_pembayaran_update
            AFTER UPDATE ON pembayaran
            FOR EACH ROW
            BEGIN
                IF OLD.status_pembayaran = 'lunas' AND OLD.tanggal_bayar IS NOT NULL THEN
                    CALL update_fact_keuangan_for_month(YEAR(OLD.tanggal_bayar), MONTH(OLD.tanggal_bayar));
                END IF;
                
                IF NEW.status_pembayaran = 'lunas' AND NEW.tanggal_bayar IS NOT NULL THEN
                    CALL update_fact_keuangan_for_month(YEAR(NEW.tanggal_bayar), MONTH(NEW.tanggal_bayar));
                END IF;
                
                CALL upsert_fact_transaksi_for_penitipan(NEW.id_penitipan);
            END
        ");
        
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_pembayaran_delete");
        DB::unprepared("
            CREATE TRIGGER sync_facts_pembayaran_delete
            AFTER DELETE ON pembayaran
            FOR EACH ROW
            BEGIN
                IF OLD.status_pembayaran = 'lunas' AND OLD.tanggal_bayar IS NOT NULL THEN
                    CALL update_fact_keuangan_for_month(YEAR(OLD.tanggal_bayar), MONTH(OLD.tanggal_bayar));
                END IF;
                CALL upsert_fact_transaksi_for_penitipan(OLD.id_penitipan);
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_pembayaran_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_pembayaran_update");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_pembayaran_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_detail_penitipan_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_detail_penitipan_update");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_detail_penitipan_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_penitipan_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_penitipan_update");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_facts_penitipan_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_paket_update");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_paket_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_hewan_update");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_hewan_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_staff_update");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_staff_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_customer_update");
        DB::unprepared("DROP TRIGGER IF EXISTS sync_dim_customer_insert");
        
        // Drop procedures
        DB::unprepared("DROP PROCEDURE IF EXISTS upsert_fact_transaksi_for_penitipan");
        DB::unprepared("DROP PROCEDURE IF EXISTS update_fact_keuangan_for_month");
        DB::unprepared("DROP PROCEDURE IF EXISTS update_fact_kapasitas_for_date");
    }
};
