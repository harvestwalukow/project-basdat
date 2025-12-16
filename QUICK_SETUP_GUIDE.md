# Quick Setup Guide - Data Warehouse Sync System

## 🚀 Installation (5 Minutes)

### Step 1: Open MySQL Client

```bash
mysql -u root -p
```

### Step 2: Run Installation Script

```sql
SOURCE d:/CODE/project-basdat/database/install_sync_system.sql;
```

This will automatically:
- ✅ Set up stored procedures
- ✅ Create triggers
- ✅ Populate all dimension and fact tables
- ✅ Verify installation

### Step 3: Verify Installation

You should see output like:

```
=== Installation completed successfully ===
```

And tables counts showing:
- `dim_customer`: X rows
- `dim_staff`: X rows  
- `dim_hewan`: X rows
- `fact_transaksi`: X rows
- `fact_kapasitas_harian`: X rows
- `fact_keuangan_periodik`: X rows

## ✅ Testing

### Test 1: Update a Booking

```sql
-- Update booking status
UPDATE er_basdat.penitipan 
SET status = 'aktif' 
WHERE id_penitipan = 1;

-- Verify sync (should see updated status)
SELECT * FROM dw_basdat.fact_transaksi WHERE id_penitipan = 1;
```

### Test 2: Update Payment

```sql
-- Mark payment as paid
UPDATE er_basdat.pembayaran 
SET status_pembayaran = 'lunas', tanggal_bayar = NOW()
WHERE id_pembayaran = 1;

-- Verify monthly revenue updated
SELECT * FROM dw_basdat.fact_keuangan_periodik 
WHERE tahun = YEAR(NOW()) AND bulan = MONTH(NOW());
```

### Test 3: Check Dashboard

1. Open your Laravel application
2. Login as admin
3. Navigate to Dashboard
4. You should see:
   - ✅ KPI Revenue (from fact_keuangan_periodik)
   - ✅ Monthly Revenue Chart (from fact_keuangan_periodik)
   - ✅ KPI Penitipan Hari Ini (from fact_kapasitas_harian)
   - ✅ Daily Occupancy Chart (from fact_kapasitas_harian)

## 📊 What's Different Now?

### Dashboard (USES DATA WAREHOUSE)
- ✅ Revenue metrics from `fact_keuangan_periodik`
- ✅ Capacity metrics from `fact_kapasitas_harian`
- ✅ Transaction data from `fact_transaksi`

### Other Menus (STILL USE TRANSACTIONAL DATA)
- ✅ UPDATE KONDISI - uses `update_kondisi` table
- ✅ PAKET LAYANAN - uses `paket_layanan` table
- ✅ KARYAWAN - uses `pengguna` table
- ✅ TRANSAKSI (booking list) - shows fact data but uses transactional for pet details

## 🔄 How It Works

```
When you create/update transactional data:
1. Normal Laravel operation (Penitipan created)
2. MySQL trigger fires automatically
3. Stored procedure updates fact tables
4. Dashboard shows updated data immediately
```

**Example:**
```php
// In your Laravel controller
Pembayaran::where('id_pembayaran', $id)->update([
    'status_pembayaran' => 'lunas',
    'tanggal_bayar' => now()
]);

// Automatically triggers:
// → sync_facts_pembayaran_update
// → update_fact_keuangan_for_month()
// → refresh_fact_transaksi()
// → Dashboard shows new revenue instantly! ✨
```

## 🛠️ Maintenance

### Daily
✅ Automatic - triggers handle everything

### Weekly  
Check data consistency:
```sql
CALL dw_basdat.full_etl_refresh();
```

### When Needed
Manual refresh:
```sql
-- Refresh everything
CALL dw_basdat.full_etl_refresh();

-- Or refresh specific tables
CALL dw_basdat.update_fact_kapasitas_for_date(CURDATE());
CALL dw_basdat.update_fact_keuangan_for_month(2025, 12);
```

## ❓ Troubleshooting

### Dashboard not showing data?
```sql
-- Check if fact tables have data
SELECT COUNT(*) FROM dw_basdat.fact_transaksi;
SELECT COUNT(*) FROM dw_basdat.fact_kapasitas_harian;
SELECT COUNT(*) FROM dw_basdat.fact_keuangan_periodik;

-- If empty, run full refresh
CALL dw_basdat.full_etl_refresh();
```

### Triggers not working?
```sql
-- Check triggers exist (should show 18)
SELECT COUNT(*) FROM information_schema.triggers 
WHERE TRIGGER_SCHEMA = 'er_basdat' AND TRIGGER_NAME LIKE 'sync_%';

-- Re-install if needed
SOURCE d:/CODE/project-basdat/database/sync_triggers.sql;
```

### Data out of sync?
```sql
-- Full refresh to fix
CALL dw_basdat.full_etl_refresh();
```

## 📚 More Information

See `DATABASE_SYNC_DOCUMENTATION.md` for complete details.

## 🎉 That's It!

Your system is now automatically syncing transactional data to the data warehouse!

All dashboard analytics use the warehouse, while other admin functions still use transactional data for real-time operations.



