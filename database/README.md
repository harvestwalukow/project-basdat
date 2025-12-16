# Database Synchronization System

This folder contains SQL scripts for automatic synchronization between the transactional database (`er_basdat`) and the data warehouse (`dw_basdat`).

## Files

### 1. `install_sync_system.sql`
**Purpose:** Main installation script that sets up the complete synchronization system.

**Usage:**
```bash
mysql -u root -p < install_sync_system.sql
```

**What it does:**
- Sets up dimension table reference data
- Installs all stored procedures
- Installs all triggers
- Runs initial ETL to populate fact tables
- Verifies installation

### 2. `sync_procedures.sql`
**Purpose:** Contains stored procedures for ETL operations.

**Procedures:**
- `update_fact_kapasitas_for_date(target_date)` - Updates daily capacity metrics
- `update_fact_keuangan_for_month(year, month)` - Updates monthly financial metrics
- `refresh_fact_transaksi()` - Refreshes transaction fact table
- `full_etl_refresh()` - Complete ETL refresh of all tables

**Usage:**
```sql
-- Refresh all data
CALL dw_basdat.full_etl_refresh();

-- Refresh specific date
CALL dw_basdat.update_fact_kapasitas_for_date('2025-12-15');

-- Refresh specific month
CALL dw_basdat.update_fact_keuangan_for_month(2025, 12);

-- Refresh transactions
CALL dw_basdat.refresh_fact_transaksi();
```

### 3. `sync_triggers.sql`
**Purpose:** Contains triggers that automatically sync data when transactional data changes.

**Triggers:**

**Dimension Sync:**
- `sync_dim_customer_insert/update` - Syncs pet owners to dim_customer
- `sync_dim_staff_insert/update` - Syncs staff/admin to dim_staff
- `sync_dim_hewan_insert/update` - Syncs pets to dim_hewan
- `sync_dim_paket_insert/update` - Syncs packages to dim_paket

**Fact Sync:**
- `sync_facts_penitipan_*` - Updates fact_transaksi and fact_kapasitas_harian
- `sync_facts_pembayaran_*` - Updates fact_transaksi and fact_keuangan_periodik
- `sync_facts_detail_penitipan_*` - Updates fact_transaksi

## Quick Start

1. **Install the system:**
   ```bash
   cd database
   mysql -u root -p < install_sync_system.sql
   ```

2. **Verify installation:**
   ```sql
   -- Check triggers (should return 18)
   SELECT COUNT(*) FROM information_schema.triggers 
   WHERE TRIGGER_SCHEMA = 'er_basdat' AND TRIGGER_NAME LIKE 'sync_%';
   
   -- Check procedures (should return 4)
   SELECT COUNT(*) FROM information_schema.routines
   WHERE ROUTINE_SCHEMA = 'dw_basdat' AND ROUTINE_TYPE = 'PROCEDURE';
   ```

3. **Test the system:**
   ```sql
   -- Update a booking
   UPDATE er_basdat.penitipan SET status = 'aktif' WHERE id_penitipan = 1;
   
   -- Check if fact table updated
   SELECT * FROM dw_basdat.fact_transaksi WHERE id_penitipan = 1;
   ```

## Architecture

```
Transactional DB (er_basdat)    →    TRIGGERS    →    Data Warehouse (dw_basdat)
┌─────────────────────────┐                          ┌─────────────────────────┐
│ - pengguna              │                          │ Dimensions:             │
│ - hewan                 │     ─────────→           │ - dim_customer          │
│ - penitipan             │                          │ - dim_staff             │
│ - pembayaran            │                          │ - dim_hewan             │
│ - paket_layanan         │                          │ - dim_paket             │
│ - detail_penitipan      │                          │ - dim_waktu             │
│ - update_kondisi        │                          │                         │
└─────────────────────────┘                          │ Facts:                  │
                                                     │ - fact_transaksi        │
                                                     │ - fact_kapasitas_harian │
                                                     │ - fact_keuangan_periodik│
                                                     └─────────────────────────┘
```

## Maintenance

### Daily
✅ Automatic - triggers handle synchronization

### Weekly
```sql
-- Verify data consistency
CALL dw_basdat.full_etl_refresh();
```

### When Problems Occur
```sql
-- Re-install triggers
SOURCE sync_triggers.sql;

-- Re-install procedures
SOURCE sync_procedures.sql;

-- Full refresh
CALL dw_basdat.full_etl_refresh();
```

## Support

- See `../DATABASE_SYNC_DOCUMENTATION.md` for detailed documentation
- See `../QUICK_SETUP_GUIDE.md` for quick start guide

## Version History

- **v1.0** (2025-12-15) - Initial release
  - 18 triggers for automatic synchronization
  - 4 stored procedures for ETL operations
  - Complete installation and verification system



