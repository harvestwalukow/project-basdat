# Fix: Install Stored Procedures

## Problem
The `SOURCE` command isn't properly handling the `DELIMITER $$` in the SQL file.

## Solution: Run These Commands in MariaDB

```sql
-- Step 1: Ensure you're in the right database
USE dw_basdat;

-- Step 2: Manually check current procedures
SHOW PROCEDURE STATUS WHERE Db = 'dw_basdat';

-- Step 3: If procedures exist partially, drop them first
DROP PROCEDURE IF EXISTS update_fact_kapasitas_for_date;
DROP PROCEDURE IF EXISTS update_fact_keuangan_for_month;
DROP PROCEDURE IF EXISTS refresh_fact_transaksi;
DROP PROCEDURE IF EXISTS full_etl_refresh;

-- Step 4: Now source the file
SOURCE d:/CODE/project-basdat/database/sync_procedures.sql;

-- Step 5: Verify all 4 procedures are created
SHOW PROCEDURE STATUS WHERE Db = 'dw_basdat';
```

## Alternative: Run Procedures One by One

If SOURCE still fails, copy-paste each procedure individually from the file into MariaDB console.

## After Procedures Install Successfully

Run the full ETL refresh:
```sql
CALL dw_basdat.full_etl_refresh();
```

## Test the System

After everything is installed, try creating a new penitipan from the admin panel. It should work without errors!
