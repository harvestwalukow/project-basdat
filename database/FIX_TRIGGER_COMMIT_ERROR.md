# Fix: Commit Not Allowed in Trigger

## The Problem

Error: `Explicit or implicit commit is not allowed in stored function or trigger`

This happens because:
1. Triggers call `refresh_fact_transaksi()` procedure
2. That procedure uses `TRUNCATE TABLE` (line 118 in sync_procedures.sql)
3. TRUNCATE causes an implicit COMMIT
4. COMMITs are NOT allowed inside triggers in MySQL/MariaDB

## The Solution

**Remove calls to `refresh_fact_transaksi()` from triggers.**

Instead of refreshing the entire fact_transaksi table on every insert, we:
- Keep `update_fact_kapasitas_for_date()` in penitipan triggers (this works fine)
- Keep `update_fact_keuangan_for_month()` in pembayaran triggers (this works fine)
- Remove `refresh_fact_transaksi()` calls (this was causing the error)

## Install Fixed Triggers

Run this in MariaDB:

```sql
SOURCE d:/CODE/project-basdat/database/fix_triggers_no_truncate.sql;
```

Or copy-paste the entire content of `fix_triggers_no_truncate.sql` into MariaDB console.

## What Changes

**Before:** 17 triggers (including detail_penitipan triggers)
**After:** 6 triggers (only essential ones)

The 6 triggers are:
1. `sync_facts_penitipan_insert`
2. `sync_facts_penitipan_update`  
3. `sync_facts_penitipan_delete`
4. `sync_facts_pembayaran_insert`
5. `sync_facts_pembayaran_update`
6. `sync_facts_pembayaran_delete`

Dimension sync triggers (customer, staff, hewan, paket) remain unchanged.

## About fact_transaksi

`fact_transaksi` won't auto-update anymore, but that's OK because:
- It's primarily used for reporting/analytics
- You can manually refresh it when needed: `CALL dw_basdat.refresh_fact_transaksi();`
- The important real-time metrics (capacity and finance) still auto-update

## Test After Installation

Try creating a new penitipan from admin panel. Should work without errors!

## Manual Refresh (Optional)

If you need to update fact_transaksi:

```sql
-- Run this manually when needed (NOT from trigger)
CALL dw_basdat.refresh_fact_transaksi();
```
