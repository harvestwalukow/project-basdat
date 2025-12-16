# Quick Installation Commands

## ⚠️ UPDATED - Errors Fixed!

Saya sudah fix syntax errors di `sync_procedures.sql`. Silakan run commands ini **dalam urutan**:

```sql
-- Step 1: Install stored procedures (FIXED VERSION)
SOURCE d:/CODE/project-basdat/database/sync_procedures.sql;

-- Step 2: Install triggers
SOURCE d:/CODE/project-basdat/database/sync_triggers.sql;

-- Step 3: Run initial ETL refresh
CALL dw_basdat.full_etl_refresh();

-- Step 4: Verify triggers installed
SHOW TRIGGERS FROM er_basdat WHERE `Trigger` LIKE 'sync_%';
```

## Expected Output

**Step 4 should show 17 triggers:**
```
sync_dim_customer_insert
sync_dim_customer_update
sync_dim_staff_insert
sync_dim_staff_update
sync_dim_hewan_insert
sync_dim_hewan_update
sync_dim_paket_insert
sync_dim_paket_update
sync_facts_penitipan_insert
sync_facts_penitipan_update
sync_facts_penitipan_delete
sync_facts_pembayaran_insert
sync_facts_pembayaran_update
sync_facts_pembayaran_delete
sync_facts_detail_penitipan_insert
sync_facts_detail_penitipan_update
sync_facts_detail_penitipan_delete
```

## What Was Fixed

1. **Line 34**: Changed `LEAVE;` to `RETURN;` (correct SQL syntax)
2. **Line 169**: Renamed `current_date` to `v_current_date` (avoided MariaDB reserved word)

## After Installation

Once completed, your data warehouse will auto-sync:
- ✅ New bookings → fact_transaksi updates
- ✅ Payment updates → fact_keuangan_periodik updates
- ✅ Any changes to customers/pets/packages → dimension tables sync
