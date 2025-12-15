# Migrasi ke Fact-Only Architecture

## Perubahan yang Dilakukan

### 1. AdminController.php
**Prinsip**: Hanya menggunakan **Fact Tables** untuk data, dan mengambil detail dari **tabel transaksional/operasional** (bukan Dim tables).

#### Removed Dependencies:
- ❌ `DimHewan`
- ❌ `DimStaff`
- ❌ `DimPaket`
- ❌ `DimCustomer`

#### Kept Dependencies:
- ✅ `FactTransaksi` (untuk data transaksi)
- ✅ `FactKeuangan` (untuk data keuangan)
- ✅ `FactLayananPeriodik` (untuk statistik layanan)
- ✅ `Pengguna` (tabel operasional untuk detail customer & staff)
- ✅ `Hewan` (tabel operasional untuk detail hewan)
- ✅ `PaketLayanan` (tabel operasional untuk detail paket)
- ✅ `Pembayaran` (tabel operasional untuk id_pembayaran)

### 2. Perubahan per Method:

#### `dashboard()`
- **Before**: Menggunakan `FactTransaksi` dengan join ke `DimHewan`, `DimCustomer`
- **After**: Menggunakan `FactTransaksi` dengan `id_hewan` dan `id_pemilik` untuk join ke tabel operasional
- **Data Source**: 
  - Statistik → `FactTransaksi` (count distinct `id_hewan`, `id_pemilik`)
  - Revenue → `FactKeuangan`
  - Detail → `Hewan`, `Pengguna`

#### `booking()`
- **Before**: `FactTransaksi::with(['dimHewan', 'dimCustomer', 'dimStaff', 'dimPaket'])`
- **After**: `FactTransaksi` + manual mapping ke `Hewan::find()`, `Pengguna::find()`
- **Data Source**: 
  - List → `FactTransaksi`
  - Detail → `Hewan`, `Pengguna`, `PaketLayanan`

#### `pets()`
- **Before**: `FactTransaksi::with(['dimHewan', 'dimCustomer'])`
- **After**: `FactTransaksi` groupBy `id_hewan` + join ke `Hewan`, `Pengguna`
- **Data Source**: 
  - List unik hewan → `FactTransaksi` (select distinct id_hewan)
  - Detail hewan → `Hewan`
  - Owner → `Pengguna`

#### `service()`
- **Before**: `DimPaket::all()` + stats dari `FactLayananPeriodik`
- **After**: `PaketLayanan::all()` + stats dari `FactLayananPeriodik` menggunakan `id_paket`
- **Data Source**: 
  - List paket → `PaketLayanan`
  - Usage stats → `FactLayananPeriodik` (groupBy id_paket)

#### `payments()`
- **Before**: `FactTransaksi::with(['dimCustomer', 'dimPembayaran'])`
- **After**: `FactTransaksi` + `Pengguna::find()` + `Pembayaran` untuk id
- **Data Source**: 
  - List → `FactTransaksi`
  - Customer detail → `Pengguna`
  - Payment ID → `Pembayaran` (untuk update functionality)
  - Statistics → `FactKeuangan`

#### `staff()`
- **Before**: `DimStaff::all()` + task count dari `FactTransaksi`
- **After**: `Pengguna::whereIn('role', ['staff', 'admin'])` + task count menggunakan `id_staff`
- **Data Source**: 
  - List staff → `Pengguna`
  - Task count → `FactTransaksi` (where id_staff)

#### `reports()`
- **Before**: Join `FactTransaksi` dengan `dim_paket`
- **After**: Aggregate `FactTransaksi` groupBy `id_paket`, lalu join dengan `PaketLayanan`
- **Data Source**: 
  - Revenue → `FactKeuangan`
  - Bookings → `FactTransaksi`
  - Customers → `FactTransaksi` (distinct id_pemilik)
  - Service performance → `FactTransaksi` groupBy id_paket + `PaketLayanan`

### 3. FactTransaksi.php Model
**Perubahan Relationships**: Mengganti Dim relationships dengan Operational table relationships

#### Removed:
```php
dimCustomer(), dimHewan(), dimPaket(), dimStaff(), 
dimStatus(), dimPembayaran(), dimWaktu()
```

#### Added:
```php
pemilik() -> Pengguna (id_pemilik)
hewan() -> Hewan (id_hewan)
paket() -> PaketLayanan (id_paket)
staff() -> Pengguna (id_staff)
penitipan() -> Penitipan (id_penitipan)
```

## Keuntungan Arsitektur Ini:

1. **Fact Tables untuk Agregasi**: Semua counting, summing, dan analytics menggunakan Fact Tables (cepat, teroptimasi)
2. **Operational Tables untuk Detail**: Data detail seperti nama, email, deskripsi diambil dari tabel operasional (data terkini)
3. **Konsistensi Data**: Detail selalu up-to-date karena langsung dari operational tables
4. **Simplicity**: Tidak perlu sinkronisasi Dim tables, lebih mudah maintenance
5. **Best of Both Worlds**: Kecepatan query analytics dari Fact, akurasi data dari Operational

## Kolom Kunci di FactTransaksi:

```sql
- id_penitipan (link ke penitipan)
- id_pemilik (link ke pengguna - customer)
- id_hewan (link ke hewan)
- id_paket (link ke paket_layanan)
- id_staff (link ke pengguna - staff)
- total_biaya (untuk revenue calculation)
- status (untuk filtering aktif/selesai)
- status_pembayaran (untuk payment filtering)
- tanggal_masuk (untuk date range queries)
- jumlah_hari (untuk calculating tanggal_keluar)
```

## Testing Checklist:

- [ ] Dashboard menampilkan statistik dengan benar
- [ ] Booking list menampilkan data penitipan dengan nama customer dan hewan
- [ ] Pets list menampilkan daftar hewan dengan owner
- [ ] Service menampilkan paket dengan usage count
- [ ] Payments menampilkan daftar pembayaran dengan customer detail
- [ ] Staff menampilkan list karyawan dengan task count
- [ ] Reports menampilkan analytics dengan benar
- [ ] Chart revenue berfungsi dengan data dari FactKeuangan
- [ ] Filter dan search pada setiap halaman berfungsi

## Notes:

- UpdateKondisi masih menggunakan tabel operasional (by design, karena real-time operational data)
- Payment update masih memerlukan `id_pembayaran` dari tabel operasional untuk functionality
- Views tidak perlu diubah karena menggunakan naming yang sama (pemilik, hewan, etc.)
