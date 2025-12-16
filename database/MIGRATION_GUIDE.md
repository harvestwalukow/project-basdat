# MIGRATION GUIDE: Merge DW into ER_BASDAT

## Execute in This Order

### Step 1: Migrate Tables (MariaDB)
```sql
SOURCE d:/CODE/project-basdat/database/step1_migrate_tables.sql;
```

This will:
- Copy all dim_* tables from dw_basdat to er_basdat
- Copy all fact_* tables from dw_basdat to er_basdat
- Verify row counts match

### Step 2: Update Stored Procedures (MariaDB)
```sql
SOURCE d:/CODE/project-basdat/database/step2_update_procedures.sql;
```

This will:
- Drop old procedures from dw_basdat
- Create new procedures in er_basdat
- Update all references to use er_basdat tables

### Step 3: Update Triggers (MariaDB)
```sql
SOURCE d:/CODE/project-basdat/database/step3_update_triggers.sql;
```

This will:
- Drop all old triggers
- Create new triggers in er_basdat
- All triggers now call er_basdat procedures
- NO TRUNCATE issues (fixed!)

### Step 4: Laravel Models (Already Done! ✅)
All DW models have been updated to use default `mysql` connection.

Updated files:
- FactTransaksi.php
- FactKeuanganPeriodik.php
- FactKapasitasHarian.php
- DimWaktu.php
- DimStatusPenitipan.php
- DimPaket.php
- DimHewan.php
- DimCustomer.php

### Step 5: Test the System

After running all SQL scripts:

1. **Create new penitipan** from admin panel
2. **Verify no errors** 
3. **Check dashboard** displays correctly
4. **Verify fact tables update** automatically

### Verification Queries

```sql
-- Check all tables exist in er_basdat
SHOW TABLES FROM er_basdat LIKE 'dim_%';
SHOW TABLES FROM er_basdat LIKE 'fact_%';

-- Check triggers are in er_basdat
SHOW TRIGGERS FROM er_basdat WHERE `Trigger` LIKE 'sync_%';

-- Check procedures are in er_basdat
SHOW PROCEDURE STATUS WHERE Db = 'er_basdat';

-- Test capacity update
CALL er_basdat.update_fact_kapasitas_for_date(CURDATE());
```

## What Changed

### Before
- 2 databases: `er_basdat` (transactional) + `dw_basdat` (warehouse)
- Cross-database triggers (caused commit errors)
- Laravel models with dual connection

### After
- 1 database: `er_basdat` (everything)
- Same-database triggers (no commit errors!)
- Laravel models use single connection

## Benefits

✅ Simpler architecture
✅ No cross-database trigger restrictions
✅ Easier to backup and manage
✅ Better performance
✅ No more TRUNCATE errors!

## Optional: Cleanup

After verifying everything works, you can optionally drop dw_basdat:

```sql
-- ONLY do this after verifying everything works!
DROP DATABASE dw_basdat;
```
