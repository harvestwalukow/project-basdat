# Fix Update Kondisi Error

## Masalah

Error terjadi saat menambah update kondisi:

```
SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row:
a foreign key constraint fails (`er_basdat`.`update_kondisi`, CONSTRAINT
`update_kondisi_id_staff_foreign` FOREIGN KEY (`id_staff`) REFERENCES `pengguna` (`id_pengguna`))
```

## Penyebab

Admin manual dengan ID 9999 tidak ada di tabel `pengguna`, sehingga foreign key constraint gagal.

## Solusi

### Opsi 1: Jalankan Seeder (Recommended)

Jalankan perintah berikut untuk menambahkan admin ke database:

```bash
php artisan db:seed --class=AdminSeeder
```

### Opsi 2: Insert Manual via SQL

Jika tidak bisa menjalankan seeder, insert manual via phpMyAdmin atau MySQL client:

```sql
INSERT INTO pengguna (id_pengguna, nama_lengkap, email, password, no_telepon, alamat, role, specialization, created_at, updated_at)
VALUES (9999, 'Admin', 'admin@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', 'Alamat Admin', 'admin', NULL, NOW(), NOW());
```

Password: `123456`

## Perubahan yang Dilakukan

### 1. AdminController.php

-   Menambahkan validasi untuk memastikan staff ID valid sebelum insert
-   Menambahkan statistik kapasitas kamar (Premium & Basic)

### 2. booking.blade.php

-   Menambahkan card statistik untuk Kamar Premium (50 total)
-   Menambahkan card statistik untuk Kamar Basic (50 total)
-   Menampilkan jumlah terpakai dan tersedia

### 3. AdminSeeder.php (NEW)

-   Seeder baru untuk insert admin dengan ID 9999
-   Sesuai dengan admin manual di routes/web.php

## Testing

Setelah menjalankan seeder:

1. Login sebagai admin (admin@gmail.com / 123456)
2. Coba tambah update kondisi
3. Error seharusnya sudah tidak muncul

## Catatan

Jika sudah ada data di database dengan ID yang berbeda, pastikan session user_id sesuai dengan ID di tabel pengguna.
