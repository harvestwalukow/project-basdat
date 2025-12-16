# FINAL SOLUTION - Install Missing Procedure

## Problem
`update_fact_kapasitas_for_date` procedure tidak terinstall karena syntax error dengan delimiter.

## Solution

**METHOD 1: Copy-Paste (RECOMMENDED)**

1. Open file: `d:\CODE\project-basdat\database\install_procedure_clean.sql`
2. **Copy ENTIRE content** dari file tersebut
3. **Paste ke MariaDB console**
4. Press Enter
5. Should see procedure created!

**METHOD 2: SOURCE command**

```sql
SOURCE d:/CODE/project-basdat/database/install_procedure_clean.sql;
```

## Verification

After running, you should see:
```
1 row in set
```

Then verify:
```sql
SHOW PROCEDURE STATUS WHERE Db = 'dw_basdat';
```

Should now show **4 procedures** including `update_fact_kapasitas_for_date`.

## Test

After procedure is installed, try creating penitipan from admin panel again. Should work without errors!

## Why Previous Attempts Failed

MariaDB SOURCE command has issues with DELIMITER when there are syntax errors. The RETURN statement was causing problems. The new version uses IF-ELSE instead of early RETURN.
