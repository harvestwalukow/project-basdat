# ETL Installation Guide

## Run These Commands in MariaDB

```sql
-- Connect to your database
mysql -u your_username -p

-- Step 1: Install stored procedures
SOURCE d:/CODE/project-basdat/database/sync_procedures.sql;

-- Step 2: Install triggers
SOURCE d:/CODE/project-basdat/database/sync_triggers.sql;

-- Step 3: Run initial ETL refresh
CALL dw_basdat.full_etl_refresh();

-- Step 4: Verify installation
SHOW TRIGGERS FROM er_basdat WHERE `Trigger` LIKE 'sync_%';
SHOW PROCEDURE STATUS WHERE Db = 'dw_basdat';
```

## Expected Results

**Triggers (should show 12):**
- sync_dim_customer_insert
- sync_dim_customer_update
- sync_dim_staff_insert
- sync_dim_staff_update
- sync_dim_hewan_insert
- sync_dim_hewan_update
- sync_dim_paket_insert
- sync_dim_paket_update
- sync_facts_penitipan_insert
- sync_facts_penitipan_update
- sync_facts_penitipan_delete
- sync_facts_pembayaran_insert
- sync_facts_pembayaran_update
- sync_facts_pembayaran_delete
- sync_facts_detail_penitipan_insert
- sync_facts_detail_penitipan_update
- sync_facts_detail_penitipan_delete

**Procedures (should show 4):**
- update_fact_kapasitas_for_date
- update_fact_keuangan_for_month
- refresh_fact_transaksi
- full_etl_refresh

## Test the System

After installation, test by creating a new booking:
1. Go to admin panel and create a new penitipan
2. Check if it appears in fact_transaksi immediately
3. Update payment status to 'lunas'
4. Verify fact_keuangan_periodik updates
