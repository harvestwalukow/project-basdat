# Staff & Reports Features Documentation

## Overview

Halaman Staff dan Laporan telah dibuat dan diintegrasikan dengan database dengan fitur lengkap CRUD dan analytics.

## 1. HALAMAN STAFF (Manajemen Karyawan)

### URL Akses

-   **Route**: `/admin/staff`
-   **Route Name**: `admin.staff`

### Fitur Utama

#### A. Tampilan Data Karyawan

-   Menampilkan semua staff dan admin dari tabel `pengguna`
-   Informasi ditampilkan dalam bentuk card dengan detail:
    -   Nama lengkap
    -   Posisi (Administrator/Staff Operasional)
    -   Status (Active)
    -   Departemen
    -   Email & No. Telepon
    -   Shift kerja
    -   Spesialisasi
    -   Pengalaman kerja
    -   Tanggal bergabung
    -   Rating

#### B. Statistik Departemen

-   Tampilan jumlah karyawan per departemen:
    -   Operasional
    -   Administrasi
    -   Grooming
    -   Veteriner
    -   Customer Service

#### C. Tab Payroll

-   Total Payroll bulan ini
-   Total karyawan aktif
-   Rata-rata gaji
-   Detail gaji + bonus per karyawan
-   Bonus dihitung berdasarkan:
    -   Penitipan yang ditangani: Rp 100.000 per penitipan
    -   Update kondisi yang dibuat: Rp 50.000 per update

### CRUD Operations

#### 1. Tambah Karyawan

-   **Button**: "Tambah Karyawan" (kanan atas)
-   **Form Fields**:
    -   Nama Lengkap (required)
    -   Email (required, unique)
    -   No. Telepon (required)
    -   Password (required)
    -   Role (Staff/Admin)
    -   Alamat (required)
-   **Route**: `POST /admin/staff`
-   **Controller**: `AdminController@storeStaff`

#### 2. Edit Karyawan

-   **Button**: "Edit" (pada setiap card karyawan)
-   **Form Fields**: Sama seperti tambah, password optional
-   **Route**: `PUT /admin/staff/{id}`
-   **Controller**: `AdminController@updateStaff`

#### 3. Hapus Karyawan

-   **Button**: "Hapus" (pada setiap card karyawan)
-   **Validasi**: Tidak bisa hapus jika karyawan memiliki data penitipan atau update kondisi
-   **Route**: `DELETE /admin/staff/{id}`
-   **Controller**: `AdminController@deleteStaff`

#### 4. Lihat Detail

-   **Route**: `GET /admin/staff/{id}`
-   **Controller**: `AdminController@showStaff`
-   **Return**: JSON data karyawan

---

## 2. HALAMAN LAPORAN (Reports & Analytics)

### URL Akses

-   **Route**: `/admin/laporan`
-   **Route Name**: `admin.reports`

### Fitur Utama

#### A. Report Controls

-   Dropdown filter jenis laporan:
    -   Executive Summary
    -   Financial Report
    -   Operational Report
    -   Customer Analytics
-   Dropdown filter periode waktu:
    -   Hari Ini
    -   Minggu Ini
    -   Bulan Ini (default)
    -   3 Bulan Terakhir
    -   6 Bulan Terakhir
    -   Tahun Ini
-   Button Export PDF

#### B. 4 Tab Analytics

##### Tab 1: Executive Summary

**Key Metrics:**

-   Total Revenue (dengan growth %)
-   Total Bookings (dengan growth %)
-   Active Customers (dengan growth %)
-   Average Rating (dengan perubahan)

**Charts:**

1. Revenue Trend (Line Chart) - 6 bulan terakhir
2. Booking vs Customer (Bar Chart) - 6 bulan terakhir

**Table:**

-   Service Performance (Revenue, Bookings, Rating, Growth per layanan)

##### Tab 2: Revenue Analysis

**Fitur:**

-   Chart analisis pendapatan bulanan (Line Chart)
-   Top Revenue Sources (list layanan teratas)
-   Revenue Growth Trend
-   Total Revenue Year-to-Date

##### Tab 3: Customer Insights

**Metrics:**

-   Total Customers
-   New Customers bulan ini
-   Customer Satisfaction Rating

**Chart:**

-   Customer Booking Patterns (Line Chart)

##### Tab 4: Operational KPIs

**Metrics:**

-   Total Bookings
-   Active Penitipan
-   Total Pets
-   Staff Active

**Additional:**

-   Occupancy Rate dengan progress bar
-   Kapasitas ruangan (Current/Max)

### Data Sources

Semua data diambil langsung dari database:

-   Tabel: `pembayaran` (revenue data)
-   Tabel: `penitipan` (booking data)
-   Tabel: `pengguna` (customer & staff data)
-   Tabel: `hewan` (pet data)
-   Tabel: `paket_layanan` & `detail_penitipan` (service performance)

### Chart.js Integration

-   Semua chart menggunakan Chart.js (sudah included di layout)
-   Chart types:
    -   Line Chart: Revenue trends, Customer patterns
    -   Bar Chart: Bookings vs Customers
-   Interactive tooltips dengan format Rupiah
-   Responsive design

---

## 3. ROUTES SUMMARY

```php
// Staff Management Routes
GET    /admin/staff           -> admin.staff          (List all staff)
POST   /admin/staff           -> admin.staff.store    (Create new staff)
GET    /admin/staff/{id}      -> admin.staff.show     (Get staff details JSON)
PUT    /admin/staff/{id}      -> admin.staff.update   (Update staff)
DELETE /admin/staff/{id}      -> admin.staff.delete   (Delete staff)

// Reports Routes
GET    /admin/laporan         -> admin.reports        (View reports)
```

---

## 4. CONTROLLER METHODS

### AdminController

#### Staff Methods:

1. `staff()` - Display staff list dengan statistics
2. `storeStaff(Request $request)` - Create new staff
3. `updateStaff(Request $request, $id)` - Update staff data
4. `deleteStaff($id)` - Delete staff (dengan validasi)
5. `showStaff($id)` - Get staff details (JSON response)

#### Reports Methods:

1. `reports()` - Display reports dengan:
    - Revenue metrics & growth
    - Booking statistics
    - Customer analytics
    - Service performance data
    - Chart data (6 months)

---

## 5. SECURITY & VALIDATION

### Middleware

-   Semua routes dilindungi dengan `admin` middleware
-   Hanya user dengan role `admin` atau `owner` yang bisa akses

### Validation Rules

**Staff Creation/Update:**

-   nama_lengkap: required, string, max 255
-   email: required, email, unique (kecuali update)
-   password: required (create), nullable (update), min 6
-   no_telepon: required, string, max 20
-   alamat: required, string
-   role: required, in:admin,staff

**Staff Deletion:**

-   Check relasi dengan penitipan dan update_kondisi
-   Prevent deletion jika ada data terkait

---

## 6. UI/UX FEATURES

### Modals

-   Add Staff Modal (overlay dengan form)
-   Edit Staff Modal (overlay dengan form pre-filled)
-   Confirmation dialog untuk delete

### Notifications

-   Success message (green alert)
-   Error message (red alert)
-   Auto-dismiss atau manual close

### Responsive Design

-   Tailwind CSS untuk styling
-   Grid layout responsive
-   Mobile-friendly tabs dan charts

### Interactive Elements

-   Tab switching tanpa reload
-   Modal open/close dengan animasi
-   Chart tooltips
-   Hover effects pada buttons

---

## 7. DATABASE INTEGRATION

### Tables Used:

-   `pengguna` - Staff & customer data
-   `penitipan` - Booking data
-   `pembayaran` - Payment & revenue data
-   `hewan` - Pet data
-   `paket_layanan` - Service packages
-   `detail_penitipan` - Booking details
-   `update_kondisi` - Pet condition updates

### Relationships:

-   Staff hasMany Penitipan (via staffPenitipans)
-   Staff hasMany UpdateKondisi (via updateKondisis)
-   Calculations menggunakan withCount untuk performance

---

## 8. FUTURE ENHANCEMENTS (Optional)

### Staff Page:

-   [ ] Upload foto karyawan
-   [ ] Advanced scheduling system
-   [ ] Performance review module
-   [ ] Leave management
-   [ ] Attendance tracking

### Reports Page:

-   [ ] Real PDF export functionality
-   [ ] Email report scheduling
-   [ ] Custom date range picker
-   [ ] Export to Excel
-   [ ] More detailed analytics
-   [ ] Comparison reports (YoY, MoM)
-   [ ] Predictive analytics
-   [ ] Custom dashboard builder

---

## 9. TESTING CHECKLIST

### Staff Page:

-   [x] View staff list
-   [x] Add new staff
-   [x] Edit existing staff
-   [x] Delete staff (with validation)
-   [x] View department statistics
-   [x] View payroll tab
-   [x] Tab switching (Employees, Schedule, Payroll)
-   [x] Form validation
-   [x] Modal open/close
-   [x] Success/error messages

### Reports Page:

-   [x] View executive summary
-   [x] View revenue analysis
-   [x] View customer insights
-   [x] View operational KPIs
-   [x] Tab switching
-   [x] Chart rendering (4 charts)
-   [x] Real-time data from database
-   [x] Responsive layout
-   [x] Filter dropdowns (UI ready)

---

## 10. QUICK START GUIDE

### Untuk Admin/Owner:

1. **Login** dengan credentials:

    - Admin: admin@gmail.com / 123456
    - Owner: owner@gmail.com / 123456

2. **Akses Staff Page**:

    - Klik menu "KARYAWAN" di sidebar
    - Lihat daftar karyawan dan statistik
    - Klik "Tambah Karyawan" untuk menambah staff baru

3. **Akses Reports Page**:
    - Klik menu "LAPORAN" di sidebar
    - Pilih tab analytics yang diinginkan
    - Gunakan filter untuk menyesuaikan periode

### Catatan Penting:

-   Menu Staff & Laporan hanya muncul untuk user dengan role `owner`
-   Untuk testing, gunakan account owner@gmail.com
-   Semua data realtime dari database
-   Charts akan update otomatis sesuai data yang ada

---

**Last Updated**: 2024
**Version**: 1.0
**Status**: Production Ready ✅

