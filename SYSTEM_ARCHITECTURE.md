# System Architecture - Pet Hotel Data Warehouse

## 🏗️ Overall System Architecture

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         LARAVEL APPLICATION                              │
│                                                                          │
│  ┌──────────────────────────────────────────────────────────────────┐  │
│  │                    ADMIN DASHBOARD                                │  │
│  │                                                                   │  │
│  │  📊 KPI Revenue (fact_keuangan_periodik)                        │  │
│  │  📈 Monthly Revenue Chart (fact_keuangan_periodik)              │  │
│  │  📋 KPI Penitipan (fact_kapasitas_harian)                       │  │
│  │  📊 Occupancy Chart (fact_kapasitas_harian)                     │  │
│  └──────────────────────────────────────────────────────────────────┘  │
│                                                                          │
│  ┌──────────────────────────────────────────────────────────────────┐  │
│  │               OTHER ADMIN MENUS                                   │  │
│  │                                                                   │  │
│  │  • UPDATE KONDISI (update_kondisi) - Transactional              │  │
│  │  • PAKET LAYANAN (paket_layanan) - Transactional                │  │
│  │  • KARYAWAN (pengguna) - Transactional                          │  │
│  │  • LAPORAN (penitipan, pembayaran) - Transactional              │  │
│  └──────────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────┘
                              ⬇️  ⬆️
                    READ from / WRITE to
                              ⬇️  ⬆️
┌─────────────────────────────────────────────────────────────────────────┐
│                    TRANSACTIONAL DATABASE (er_basdat)                    │
│                                                                          │
│  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌────────────┐       │
│  │  pengguna  │  │   hewan    │  │ penitipan  │  │ pembayaran │       │
│  └────────────┘  └────────────┘  └────────────┘  └────────────┘       │
│                                                                          │
│  ┌────────────┐  ┌──────────────────┐  ┌──────────────────┐           │
│  │paket_layanan│ │detail_penitipan  │  │ update_kondisi   │           │
│  └────────────┘  └──────────────────┘  └──────────────────┘           │
│                                                                          │
│  🔥 TRIGGERS (18 total) - Fires on INSERT/UPDATE/DELETE                │
│  ├─ sync_dim_customer_insert/update                                    │
│  ├─ sync_dim_staff_insert/update                                       │
│  ├─ sync_dim_hewan_insert/update                                       │
│  ├─ sync_dim_paket_insert/update                                       │
│  ├─ sync_facts_penitipan_insert/update/delete                          │
│  ├─ sync_facts_pembayaran_insert/update/delete                         │
│  └─ sync_facts_detail_penitipan_insert/update/delete                   │
└─────────────────────────────────────────────────────────────────────────┘
                              ⬇️
                      TRIGGERS CALL
                              ⬇️
┌─────────────────────────────────────────────────────────────────────────┐
│                  STORED PROCEDURES (4 total)                             │
│                                                                          │
│  1️⃣ update_fact_kapasitas_for_date(date)                               │
│     └─> Updates daily capacity metrics                                  │
│                                                                          │
│  2️⃣ update_fact_keuangan_for_month(year, month)                        │
│     └─> Updates monthly financial metrics                               │
│                                                                          │
│  3️⃣ refresh_fact_transaksi()                                           │
│     └─> Refreshes transaction fact table                                │
│                                                                          │
│  4️⃣ full_etl_refresh()                                                 │
│     └─> Complete ETL refresh (manual)                                   │
└─────────────────────────────────────────────────────────────────────────┘
                              ⬇️
                     UPDATES DATA IN
                              ⬇️
┌─────────────────────────────────────────────────────────────────────────┐
│                  DATA WAREHOUSE (dw_basdat)                              │
│                                                                          │
│  ┌──────────────────────────────────────────────────────────────────┐  │
│  │                    DIMENSION TABLES                               │  │
│  │                                                                   │  │
│  │  📁 dim_customer (pet owners)                                    │  │
│  │  📁 dim_staff (employees)                                        │  │
│  │  📁 dim_hewan (pets)                                             │  │
│  │  📁 dim_paket (service packages)                                 │  │
│  │  📁 dim_waktu (time/date)                                        │  │
│  │  📁 dim_status_penitipan (booking status)                        │  │
│  │  📁 dim_pembayaran (payment methods/status)                      │  │
│  └──────────────────────────────────────────────────────────────────┘  │
│                                                                          │
│  ┌──────────────────────────────────────────────────────────────────┐  │
│  │                      FACT TABLES                                  │  │
│  │                                                                   │  │
│  │  📊 fact_transaksi                                               │  │
│  │     └─> Transaction-level booking data                           │  │
│  │                                                                   │  │
│  │  📊 fact_kapasitas_harian                                        │  │
│  │     └─> Daily capacity metrics                                   │  │
│  │                                                                   │  │
│  │  📊 fact_keuangan_periodik                                       │  │
│  │     └─> Monthly financial metrics                                │  │
│  └──────────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────────────┘
                              ⬆️
                     DASHBOARD READS FROM
                              ⬆️
                    (Back to Laravel Dashboard)
```

## 🔄 Data Synchronization Flow

### Example: User Updates Payment Status

```
┌────────────────────────────────────────────────────────────────────┐
│ STEP 1: User Action                                                │
│                                                                    │
│ Admin clicks "Mark as Paid" in Laravel UI                         │
│      ⬇️                                                            │
│ Laravel Controller executes:                                       │
│ Pembayaran::update(['status_pembayaran' => 'lunas'])              │
└────────────────────────────────────────────────────────────────────┘
                              ⬇️
┌────────────────────────────────────────────────────────────────────┐
│ STEP 2: Database Write                                             │
│                                                                    │
│ UPDATE er_basdat.pembayaran                                        │
│ SET status_pembayaran = 'lunas', tanggal_bayar = NOW()            │
│ WHERE id_pembayaran = 1;                                           │
└────────────────────────────────────────────────────────────────────┘
                              ⬇️
┌────────────────────────────────────────────────────────────────────┐
│ STEP 3: Trigger Fires (Automatic)                                 │
│                                                                    │
│ 🔥 sync_facts_pembayaran_update                                   │
│    Detects: Status changed from 'pending' to 'lunas'              │
└────────────────────────────────────────────────────────────────────┘
                              ⬇️
┌────────────────────────────────────────────────────────────────────┐
│ STEP 4: Stored Procedures Execute                                 │
│                                                                    │
│ 1️⃣ CALL update_fact_keuangan_for_month(2025, 12)                 │
│    └─> Recalculates December 2025 revenue                         │
│    └─> Updates fact_keuangan_periodik                             │
│                                                                    │
│ 2️⃣ CALL refresh_fact_transaksi()                                 │
│    └─> Updates transaction record                                 │
│    └─> Reflects new payment status                                │
└────────────────────────────────────────────────────────────────────┘
                              ⬇️
┌────────────────────────────────────────────────────────────────────┐
│ STEP 5: Data Warehouse Updated                                    │
│                                                                    │
│ ✅ fact_keuangan_periodik: Revenue +300,000                       │
│ ✅ fact_transaksi: status_pembayaran = 'lunas'                    │
└────────────────────────────────────────────────────────────────────┘
                              ⬇️
┌────────────────────────────────────────────────────────────────────┐
│ STEP 6: Dashboard Reflects Changes                                │
│                                                                    │
│ Next time admin visits dashboard:                                  │
│ 📊 Total Revenue: Rp 2,850,000 → Rp 3,150,000                    │
│ 📈 Monthly chart shows updated bar                                │
│                                                                    │
│ ⏱️ Time elapsed: < 1 second                                       │
└────────────────────────────────────────────────────────────────────┘
```

## 🎯 Trigger → Procedure Mapping

| Trigger Event | Trigger Name | Calls Procedure | Updates |
|--------------|-------------|-----------------|---------|
| **INSERT penitipan** | sync_facts_penitipan_insert | update_fact_kapasitas_for_date() + refresh_fact_transaksi() | fact_kapasitas_harian, fact_transaksi |
| **UPDATE penitipan** | sync_facts_penitipan_update | update_fact_kapasitas_for_date() + refresh_fact_transaksi() | fact_kapasitas_harian, fact_transaksi |
| **DELETE penitipan** | sync_facts_penitipan_delete | update_fact_kapasitas_for_date() + refresh_fact_transaksi() | fact_kapasitas_harian, fact_transaksi |
| **INSERT pembayaran** | sync_facts_pembayaran_insert | update_fact_keuangan_for_month() + refresh_fact_transaksi() | fact_keuangan_periodik, fact_transaksi |
| **UPDATE pembayaran** | sync_facts_pembayaran_update | update_fact_keuangan_for_month() + refresh_fact_transaksi() | fact_keuangan_periodik, fact_transaksi |
| **DELETE pembayaran** | sync_facts_pembayaran_delete | update_fact_keuangan_for_month() + refresh_fact_transaksi() | fact_keuangan_periodik, fact_transaksi |
| **INSERT/UPDATE/DELETE detail_penitipan** | sync_facts_detail_penitipan_* | refresh_fact_transaksi() | fact_transaksi |
| **INSERT/UPDATE pengguna** (pet_owner) | sync_dim_customer_* | Direct INSERT/UPDATE | dim_customer |
| **INSERT/UPDATE pengguna** (staff/admin) | sync_dim_staff_* | Direct INSERT/UPDATE | dim_staff |
| **INSERT/UPDATE hewan** | sync_dim_hewan_* | Direct INSERT/UPDATE | dim_hewan |
| **INSERT/UPDATE paket_layanan** | sync_dim_paket_* | Direct INSERT/UPDATE | dim_paket |

## 📊 Data Flow Diagram

```
┌──────────────┐
│ Laravel App  │
└──────┬───────┘
       │
       ▼ WRITE
┌──────────────────────┐         ┌────────────────────┐
│  Transactional DB    │────────>│  Triggers (18)     │
│  (er_basdat)         │         └────────┬───────────┘
│                      │                  │
│ • pengguna           │                  ▼ CALL
│ • hewan              │         ┌────────────────────┐
│ • penitipan          │         │ Procedures (4)     │
│ • pembayaran         │         └────────┬───────────┘
│ • detail_penitipan   │                  │
│ • paket_layanan      │                  ▼ UPDATE
└──────┬───────────────┘         ┌────────────────────┐
       │                         │  Data Warehouse    │
       │                         │  (dw_basdat)       │
       │ READ                    │                    │
       │ (Reports, etc)          │ • dim_* (7 tables) │
       │                         │ • fact_* (3 tables)│
       └────────────────────────>└────────┬───────────┘
                                          │
                                          ▼ READ
                                 ┌────────────────────┐
                                 │ Dashboard Analytics│
                                 └────────────────────┘
```

## 🔐 Security & Permissions

```
┌────────────────────────────────────────────────────────┐
│ MySQL User Permissions Required                        │
├────────────────────────────────────────────────────────┤
│ Database: er_basdat                                    │
│ ├─ CREATE TRIGGER                                      │
│ ├─ SELECT, INSERT, UPDATE, DELETE (on all tables)     │
│ └─ EXECUTE (for calling procedures)                    │
│                                                        │
│ Database: dw_basdat                                    │
│ ├─ CREATE PROCEDURE                                    │
│ ├─ SELECT, INSERT, UPDATE, DELETE, TRUNCATE           │
│ └─ EXECUTE (for procedures)                            │
└────────────────────────────────────────────────────────┘
```

## ⚡ Performance Characteristics

| Operation | Timing | Impact |
|-----------|--------|--------|
| Trigger execution | < 10ms | Negligible |
| update_fact_kapasitas_for_date() | 50-100ms | Low |
| update_fact_keuangan_for_month() | 50-100ms | Low |
| refresh_fact_transaksi() | 200-500ms | Medium (full refresh) |
| full_etl_refresh() | 5-10 seconds | High (use off-hours) |

## 🔍 Monitoring Points

```
1️⃣ Trigger Status
   └─> SHOW TRIGGERS FROM er_basdat WHERE Trigger LIKE 'sync_%';

2️⃣ Procedure Status
   └─> SHOW PROCEDURE STATUS WHERE Db = 'dw_basdat';

3️⃣ Data Consistency
   └─> Compare counts: er_basdat.penitipan vs dw_basdat.fact_transaksi

4️⃣ Last Sync Time
   └─> Check max(tanggal) in fact_kapasitas_harian with data

5️⃣ Error Logs
   └─> Check MySQL error log for trigger/procedure failures
```

## 📝 Summary

**What happens when you:**

1. **Create a booking** → fact_transaksi + fact_kapasitas_harian updated
2. **Update payment** → fact_keuangan_periodik + fact_transaksi updated
3. **Add pet** → dim_hewan updated
4. **Modify package** → dim_paket updated
5. **View dashboard** → Reads from fact tables (always current)

**Result:** Real-time analytics without manual intervention! 🎉

---

**Architecture Version:** 1.0  
**Last Updated:** December 15, 2025



