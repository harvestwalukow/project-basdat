# Data Warehouse Synchronization System

## Overview

This system automatically synchronizes data from the transactional database (`er_basdat`) to the data warehouse (`dw_basdat`) using MySQL triggers and stored procedures.

## Architecture

```
┌─────────────────────────────────────┐
│   Transactional Database (ER)      │
│        (er_basdat)                  │
├─────────────────────────────────────┤
│  - pengguna                         │
│  - hewan                            │
│  - penitipan                        │
│  - pembayaran                       │
│  - paket_layanan                    │
│  - detail_penitipan                 │
│  - update_kondisi                   │
└──────────┬──────────────────────────┘
           │
           │ TRIGGERS (Real-time sync)
           │
           ▼
┌─────────────────────────────────────┐
│    Data Warehouse (DW)              │
│        (dw_basdat)                  │
├─────────────────────────────────────┤
│  Dimension Tables:                  │
│  - dim_customer                     │
│  - dim_staff                        │
│  - dim_hewan                        │
│  - dim_paket                        │
│  - dim_waktu                        │
│  - dim_status_penitipan             │
│  - dim_pembayaran                   │
│                                     │
│  Fact Tables:                       │
│  - fact_transaksi                   │
│  - fact_kapasitas_harian            │
│  - fact_keuangan_periodik           │
└─────────────────────────────────────┘
```

## Installation

### Step 1: Prerequisites

Ensure you have:
- MySQL/MariaDB server running
- Both `er_basdat` and `dw_basdat` databases created
- Proper user permissions (CREATE TRIGGER, CREATE PROCEDURE)

### Step 2: Run Installation Script

```bash
# Navigate to the database folder
cd d:\CODE\project-basdat\database

# Run the installation script
mysql -u root -p < install_sync_system.sql
```

Or from MySQL client:

```sql
SOURCE d:/CODE/project-basdat/database/install_sync_system.sql;
```

This will:
1. Set up dimension table reference data
2. Create all stored procedures
3. Create all triggers
4. Perform initial ETL to populate fact tables

## Components

### 1. Triggers (sync_triggers.sql)

Automatically sync data when changes occur in the transactional database:

#### Dimension Sync Triggers:
- `sync_dim_customer_insert/update` - Syncs pet owners
- `sync_dim_staff_insert/update` - Syncs staff/admin users
- `sync_dim_hewan_insert/update` - Syncs pet data
- `sync_dim_paket_insert/update` - Syncs service packages

#### Fact Table Sync Triggers:
- `sync_facts_penitipan_*` - Updates fact_transaksi and fact_kapasitas_harian
- `sync_facts_pembayaran_*` - Updates fact_transaksi and fact_keuangan_periodik
- `sync_facts_detail_penitipan_*` - Updates fact_transaksi

### 2. Stored Procedures (sync_procedures.sql)

ETL procedures for batch processing:

#### Main Procedures:

**`update_fact_kapasitas_for_date(target_date)`**
- Updates daily capacity metrics for a specific date
- Calculates: total, active, pending, completed, cancelled bookings
- Used by: penitipan triggers

**`update_fact_keuangan_for_month(target_year, target_month)`**
- Updates monthly financial metrics
- Calculates: total revenue, transaction count, average transaction
- Used by: pembayaran triggers

**`refresh_fact_transaksi()`**
- Complete refresh of transaction fact table
- Joins all dimension and transactional tables
- Used by: all penitipan, pembayaran, detail_penitipan triggers

**`full_etl_refresh()`**
- Complete ETL refresh of all dimension and fact tables
- Use for: initial setup, data recovery, periodic full refresh
- Processes last 2 years of data

## Usage

### Automatic Synchronization

Once installed, the system automatically syncs data when you:

1. **Insert/Update/Delete a Booking (penitipan)**
   ```sql
   -- This will automatically update fact_transaksi and fact_kapasitas_harian
   INSERT INTO er_basdat.penitipan (id_hewan, id_pemilik, tanggal_masuk, ...)
   VALUES (...);
   ```

2. **Update Payment Status (pembayaran)**
   ```sql
   -- This will automatically update fact_transaksi and fact_keuangan_periodik
   UPDATE er_basdat.pembayaran 
   SET status_pembayaran = 'lunas', tanggal_bayar = NOW()
   WHERE id_pembayaran = 1;
   ```

3. **Add/Update Pet Data (hewan)**
   ```sql
   -- This will automatically update dim_hewan
   UPDATE er_basdat.hewan 
   SET nama_hewan = 'Buddy', berat = 25.5
   WHERE id_hewan = 1;
   ```

4. **Modify Service Package (paket_layanan)**
   ```sql
   -- This will automatically update dim_paket
   UPDATE er_basdat.paket_layanan 
   SET harga_per_hari = 200000
   WHERE id_paket = 1;
   ```

### Manual Refresh

If you need to manually refresh the data warehouse:

```sql
-- Refresh all fact tables (full ETL)
CALL dw_basdat.full_etl_refresh();

-- Refresh specific date capacity
CALL dw_basdat.update_fact_kapasitas_for_date('2025-12-15');

-- Refresh specific month financial data
CALL dw_basdat.update_fact_keuangan_for_month(2025, 12);

-- Refresh transaction fact table
CALL dw_basdat.refresh_fact_transaksi();
```

## Dashboard Integration

### Current Implementation

The admin dashboard (`AdminController::dashboard()`) already uses fact tables:

```php
// KPI Revenue - from fact_keuangan_periodik
$currentRevenue = FactKeuanganPeriodik::where('tahun', $year)
    ->where('bulan', $month)
    ->first();

// KPI Penitipan - from fact_kapasitas_harian
$todayCapacity = FactKapasitasHarian::where('waktu_key', $todayWaktuKey)
    ->first();
```

### Other Admin Menus

These menus continue to use transactional data (as required):

- **UPDATE KONDISI** (`admin.rooms`) - uses `update_kondisi` table
- **PAKET LAYANAN** (`admin.service`) - uses `paket_layanan` table
- **KARYAWAN** (`admin.staff`) - uses `pengguna` table
- **LAPORAN** (`admin.reports`) - uses transactional tables for detailed reports

## Data Flow Examples

### Example 1: New Booking Created

```
1. User creates new booking (Penitipan)
   ↓
2. Trigger: sync_facts_penitipan_insert fires
   ↓
3. Calls: update_fact_kapasitas_for_date()
   → Updates fact_kapasitas_harian for booking date
   ↓
4. Calls: refresh_fact_transaksi()
   → Refreshes all transaction records
   ↓
5. Dashboard automatically shows updated data
```

### Example 2: Payment Status Updated

```
1. Admin updates payment status to 'lunas'
   ↓
2. Trigger: sync_facts_pembayaran_update fires
   ↓
3. Calls: update_fact_keuangan_for_month()
   → Updates monthly revenue for payment month
   ↓
4. Calls: refresh_fact_transaksi()
   → Updates transaction records with new payment status
   ↓
5. Dashboard shows updated revenue and payment stats
```

## Monitoring and Maintenance

### Check Sync Status

```sql
-- Verify trigger count
SELECT COUNT(*) as trigger_count 
FROM information_schema.triggers 
WHERE TRIGGER_SCHEMA = 'er_basdat' 
  AND TRIGGER_NAME LIKE 'sync_%';
-- Should return 18 triggers

-- Verify procedure count
SELECT COUNT(*) as procedure_count
FROM information_schema.routines
WHERE ROUTINE_SCHEMA = 'dw_basdat'
  AND ROUTINE_TYPE = 'PROCEDURE';
-- Should return 4 procedures
```

### Check Data Consistency

```sql
-- Compare counts between transactional and warehouse
SELECT 
    'Transactional' as source,
    COUNT(*) as penitipan_count
FROM er_basdat.penitipan

UNION ALL

SELECT 
    'Warehouse' as source,
    COUNT(DISTINCT id_penitipan) as penitipan_count
FROM dw_basdat.fact_transaksi;

-- Check latest sync date
SELECT 
    MAX(dw.tanggal) as latest_sync_date
FROM dw_basdat.fact_kapasitas_harian fk
JOIN dw_basdat.dim_waktu dw ON fk.waktu_key = dw.waktu_key
WHERE fk.total_penitipan > 0;
```

### Periodic Maintenance

Recommended maintenance schedule:

1. **Daily** - Automatic via triggers (no action needed)
2. **Weekly** - Verify data consistency (run check queries above)
3. **Monthly** - Full ETL refresh (optional, for data quality assurance)
   ```sql
   CALL dw_basdat.full_etl_refresh();
   ```

## Troubleshooting

### Triggers Not Firing

```sql
-- Check if triggers exist
SHOW TRIGGERS FROM er_basdat WHERE `Trigger` LIKE 'sync_%';

-- Re-install triggers
SOURCE d:/CODE/project-basdat/database/sync_triggers.sql;
```

### Fact Tables Not Updating

```sql
-- Check procedures exist
SHOW PROCEDURE STATUS WHERE Db = 'dw_basdat';

-- Re-install procedures
SOURCE d:/CODE/project-basdat/database/sync_procedures.sql;

-- Manual refresh
CALL dw_basdat.full_etl_refresh();
```

### Data Inconsistency

```sql
-- Full refresh to resync all data
CALL dw_basdat.full_etl_refresh();
```

### Performance Issues

If triggers cause performance issues:

1. **Disable automatic sync temporarily:**
   ```sql
   -- Drop all sync triggers
   DROP TRIGGER IF EXISTS er_basdat.sync_facts_penitipan_insert;
   -- (repeat for all triggers)
   ```

2. **Use scheduled batch processing:**
   ```sql
   -- Create a scheduled event for nightly refresh
   CREATE EVENT nightly_etl_refresh
   ON SCHEDULE EVERY 1 DAY
   STARTS '2025-01-01 02:00:00'
   DO CALL dw_basdat.full_etl_refresh();
   ```

3. **Re-enable triggers when ready:**
   ```sql
   SOURCE d:/CODE/project-basdat/database/sync_triggers.sql;
   ```

## Testing

### Test Trigger Functionality

```sql
-- Test dimension sync
INSERT INTO er_basdat.pengguna 
(nama_lengkap, email, password, no_telepon, alamat, role)
VALUES 
('Test User', 'test@test.com', 'password', '08123456789', 'Test Address', 'pet_owner');

-- Verify sync
SELECT * FROM dw_basdat.dim_customer WHERE email = 'test@test.com';

-- Clean up
DELETE FROM er_basdat.pengguna WHERE email = 'test@test.com';
```

```sql
-- Test fact table sync
UPDATE er_basdat.penitipan 
SET status = 'aktif' 
WHERE id_penitipan = 1;

-- Verify sync (should see updated status)
SELECT * FROM dw_basdat.fact_transaksi WHERE id_penitipan = 1;
```

## Performance Considerations

- **Triggers are lightweight** - They call stored procedures which do bulk operations
- **fact_transaksi refresh** - Uses TRUNCATE and INSERT for speed
- **Indexes** - All dimension and fact tables have proper indexes
- **Transaction safety** - All operations are wrapped in implicit transactions

## Backup and Recovery

Before making changes:

```sql
-- Backup data warehouse
mysqldump -u root -p dw_basdat > dw_basdat_backup_$(date +%Y%m%d).sql

-- Restore if needed
mysql -u root -p dw_basdat < dw_basdat_backup_20251215.sql
```

## Support

For issues or questions:
1. Check this documentation
2. Review trigger and procedure code in `database/` folder
3. Check MySQL error logs
4. Run verification queries to identify inconsistencies

---

**Last Updated:** December 15, 2025
**Version:** 1.0



