# Quick Verification - Run This First

Check which procedures actually exist:

```sql
SHOW PROCEDURE STATUS WHERE Db = 'dw_basdat';
```

Expected output should show **4 procedures**:
- update_fact_kapasitas_for_date
- update_fact_keuangan_for_month
- refresh_fact_transaksi
- full_etl_refresh

If `update_fact_kapasitas_for_date` is missing from the list, it means the installation failed despite the success message.
