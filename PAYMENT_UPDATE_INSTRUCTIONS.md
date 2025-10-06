# Instruksi Update Fitur Pembayaran Cash

## Perubahan yang Dilakukan

### 1. **Migration Database**

Ditambahkan metode pembayaran `cash` ke dalam enum `metode_pembayaran`:

-   `cash` (Cash/Tunai) - **BARU**
-   `transfer` (Transfer Bank)
-   `e_wallet` (E-Wallet)
-   `qris` (QRIS)
-   `kartu_kredit` (Kartu Kredit)

### 2. **Fitur Update Status Pembayaran Manual oleh Admin**

Admin sekarang dapat:

-   ✅ Melihat tombol "Konfirmasi Bayar" untuk pembayaran dengan status `pending`
-   ✅ Mengubah metode pembayaran (cash, transfer, e-wallet, dll)
-   ✅ Mengubah status pembayaran (pending, lunas, gagal)
-   ✅ Mengisi tanggal pembayaran secara manual atau otomatis
-   ✅ Menambahkan catatan pembayaran (opsional)
-   ✅ Status penitipan otomatis berubah menjadi `aktif` ketika pembayaran di-set `lunas`

### 3. **Perubahan Default**

-   Pembayaran baru sekarang default menggunakan metode `cash` (sebelumnya `transfer`)
-   Admin dapat mengubah metode pembayaran sesuai kebutuhan di halaman Pembayaran

## Cara Menjalankan Update Database

### Opsi 1: Fresh Migration (Recommended untuk Development)

**⚠️ PERHATIAN: Ini akan menghapus semua data!**

```bash
php artisan migrate:fresh --seed
```

### Opsi 2: Rollback dan Migrate Ulang

**⚠️ PERHATIAN: Ini akan menghapus data pembayaran!**

```bash
php artisan migrate:rollback --step=1
php artisan migrate
```

### Opsi 3: Manual Alter Table (Untuk Production)

Jika Anda ingin mempertahankan data yang ada:

**Untuk SQLite:**

```sql
-- Sayangnya SQLite tidak mendukung ALTER COLUMN untuk ENUM
-- Anda perlu melakukan:
-- 1. Backup data pembayaran
-- 2. Drop table pembayaran
-- 3. Recreate dengan migration baru
-- 4. Restore data
```

**Untuk MySQL/PostgreSQL:**

```sql
ALTER TABLE pembayaran
MODIFY metode_pembayaran ENUM('cash', 'transfer', 'e_wallet', 'qris', 'kartu_kredit');
```

## Cara Menggunakan Fitur Baru

### Sebagai Admin:

1. **Login sebagai Admin**

    - Email: admin@pethotel.com (atau sesuai data Anda)

2. **Buka Halaman Pembayaran**

    - Navigasi ke menu "Pembayaran" di sidebar admin

3. **Update Status Pembayaran:**

    - Cari pembayaran dengan status "Pending"
    - Klik tombol "Konfirmasi Bayar"
    - Modal akan muncul dengan form:
        - **Metode Pembayaran**: Pilih Cash/Transfer/E-Wallet/QRIS/Kartu Kredit
        - **Status Pembayaran**: Pilih Pending/Lunas/Gagal
        - **Tanggal Pembayaran**: Opsional (otomatis terisi waktu sekarang jika status lunas)
        - **Catatan**: Opsional
    - Klik "Update Status"

4. **Hasil:**
    - Status pembayaran berhasil diupdate
    - Jika status di-set `lunas`, status penitipan otomatis menjadi `aktif`
    - Tanggal pembayaran tercatat
    - Grafik pendapatan mingguan otomatis terupdate

## File yang Diubah

1. `database/migrations/2024_01_01_000006_create_pembayaran_table.php` - Tambah enum 'cash'
2. `resources/views/admin/payments.blade.php` - Tambah kolom aksi & modal
3. `routes/web.php` - Tambah route update status
4. `app/Http/Controllers/AdminController.php` - Tambah method updatePaymentStatus
5. `app/Http/Controllers/PenitipanController.php` - Ubah default metode ke 'cash'

## Testing

### Test Case 1: Konfirmasi Pembayaran Cash

1. Buat reservasi baru dari user biasa
2. Login sebagai admin
3. Buka halaman Pembayaran
4. Klik "Konfirmasi Bayar" pada pembayaran pending
5. Pilih metode "Cash" dan status "Lunas"
6. Submit form
7. ✅ Verifikasi status berubah menjadi lunas dan penitipan menjadi aktif

### Test Case 2: Ubah Metode Pembayaran

1. Buka pembayaran dengan metode cash
2. Ubah metode menjadi "Transfer Bank"
3. Submit
4. ✅ Verifikasi metode berhasil diubah

### Test Case 3: Filter Pembayaran

1. Gunakan filter metode pembayaran
2. Pilih "Cash"
3. ✅ Verifikasi hanya pembayaran cash yang ditampilkan

## Troubleshooting

### Error: Unknown column 'cash' in 'field list'

**Solusi:** Jalankan migration ulang (lihat Opsi 1 atau 2 di atas)

### Modal tidak muncul

**Solusi:** Clear browser cache atau hard refresh (Ctrl+Shift+R)

### Grafik tidak update setelah konfirmasi pembayaran

**Solusi:** Refresh halaman pembayaran/dashboard

## Support

Untuk pertanyaan atau bantuan, hubungi developer team.
