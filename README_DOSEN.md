# Sistem Manajemen Penitipan Hewan (Pet Boarding Management System)

## 📋 Informasi Proyek

**Nama Proyek:** Harvest Walu Kow - Pet Boarding Management System  
**Framework:** Laravel 12  
**PHP Version:** ^8.2  
**Database:** MySQL dengan implementasi Data Warehouse  
**Tipe Aplikasi:** Web-based Enterprise Resource Planning (ERP) dan Business Intelligence System

---

## 🎯 Deskripsi Proyek

Sistem ini merupakan aplikasi manajemen penitipan hewan berbasis web yang mengintegrasikan **sistem transaksional (OLTP)** dengan **sistem analitik (OLAP)** menggunakan arsitektur **Data Warehouse**. Aplikasi ini dirancang untuk mengelola operasional bisnis penitipan hewan sekaligus menyediakan laporan bisnis intelligence untuk pengambilan keputusan strategis.

### Fitur Utama

#### 1. **Modul Operasional (OLTP)**
- **Manajemen Pengguna**: Autentikasi dan autorisasi berbasis role (Admin, Staff, Pet Owner)
- **Manajemen Hewan Peliharaan**: CRUD data hewan peliharaan dengan informasi detail (jenis, usia, kondisi kesehatan, dll)
- **Manajemen Booking**: Pemesanan penitipan dengan validasi kapasitas dan status
- **Manajemen Pembayaran**: Transaksi pembayaran dengan berbagai metode pembayaran
- **Dashboard Operasional**: Monitoring KPI real-time untuk revenue dan occupancy

#### 2. **Modul Business Intelligence (OLAP)**
- **Data Warehouse**: Implementasi star schema dengan fact tables dan dimension tables
- **ETL (Extract, Transform, Load)**: Sinkronisasi otomatis data transaksional ke data warehouse menggunakan stored procedures dan triggers
- **Dashboard Analytics**: Visualisasi data dengan charts (pie chart, line chart, bar chart)
- **Laporan Multidimensional**:
  - Revenue trends (harian, bulanan, kuartalan, tahunan)
  - Top 5 Products/Services
  - Top 5 Customers
  - Occupancy Rate Analysis
  - Histogram distribusi data
- **Interactive Filters**: Filter berdasarkan tahun, kuartal, dan bulan

#### 3. **Reporting & Export**
- Export laporan transaksi ke format CSV
- Laporan dengan multiple data sources (Fact Tables dan Dimension Tables)

---

## 🏗️ Arsitektur Sistem

### Database Architecture

Sistem menggunakan **single database** (`er_basdat`) yang berisi:

1. **Transactional Tables (OLTP)**:
   - `users` - Data pengguna sistem
   - `hewans` - Data hewan peliharaan
   - `bookings` - Data pemesanan
   - `payments` - Data pembayaran

2. **Data Warehouse Tables (OLAP)**:
   - **Fact Tables**:
     - `fact_transaksi` - Fakta transaksi pembayaran
     - `fact_booking` - Fakta booking/penitipan
   - **Dimension Tables**:
     - `dim_customer` - Dimensi pelanggan
     - `dim_date` - Dimensi waktu
     - `dim_pet` - Dimensi hewan peliharaan
     - `dim_service` - Dimensi layanan

3. **ETL Mechanisms**:
   - Stored Procedures untuk incremental updates
   - Triggers untuk sinkronisasi real-time
   - Batch processing untuk initial load

### Technology Stack

- **Backend**: Laravel 12 (PHP 8.2)
- **Frontend**: Blade Templates, JavaScript, Chart.js
- **Database**: MySQL 8.0+
- **CSS Framework**: Custom CSS dengan responsive design
- **Development Tools**: Composer, NPM, Artisan CLI

---

## 📦 Instalasi & Setup

### Prerequisites

Pastikan sudah terinstall:
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (untuk asset compilation)
- Git

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd project-basdat
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=er_basdat
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Database Setup**
   
   **Opsi A: Menggunakan SQL Files (Recommended)**
   ```bash
   # Import schema dan data
   mysql -u root -p < er_basdat.sql
   
   # Import stored procedures untuk ETL
   mysql -u root -p er_basdat < database/install_procedure_clean.sql
   mysql -u root -p er_basdat < database/install_sync_system_fixed.sql
   ```
   
   **Opsi B: Menggunakan Laravel Migrations & Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Run Initial ETL (Load Data Warehouse)**
   ```sql
   -- Jalankan di MySQL client
   CALL LoadDimDate();
   CALL LoadDimCustomer();
   CALL LoadDimPet();
   CALL LoadDimService();
   CALL LoadFactTransaksi();
   CALL LoadFactBooking();
   ```

7. **Run Development Server**
   ```bash
   php artisan serve
   ```
   
   Aplikasi akan berjalan di: `http://127.0.0.1:8000`

---

## 👤 Default Users

Setelah seeding, gunakan kredensial berikut untuk login:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Staff | staff@example.com | password |
| Pet Owner | owner@example.com | password |

---

## 📊 Dokumentasi Database

### Star Schema Design

```
┌─────────────────┐
│  dim_customer   │
│  - customer_key │
│  - user_id      │
│  - name         │
│  - email        │
└─────────────────┘
         │
         │ customer_key
         ▼
┌──────────────────────┐      ┌─────────────────┐
│  fact_transaksi      │◄─────│   dim_date      │
│  - transaksi_key     │      │   - date_key    │
│  - customer_key      │      │   - date        │
│  - pet_key           │      │   - year        │
│  - service_key       │      │   - quarter     │
│  - date_key          │      │   - month       │
│  - amount            │      │   - day         │
│  - payment_method    │      └─────────────────┘
└──────────────────────┘
         ▲
         │ pet_key
         │
┌─────────────────┐
│    dim_pet      │
│  - pet_key      │
│  - pet_id       │
│  - name         │
│  - species      │
└─────────────────┘
```

### ETL Process Flow

1. **Initial Load**: Menggunakan stored procedures `Load*` untuk memuat data historis
2. **Incremental Updates**: Menggunakan stored procedures `Update*` dipanggil oleh triggers
3. **Synchronization**: Triggers pada tabel transaksional (`AFTER INSERT`, `AFTER UPDATE`, `AFTER DELETE`)

---

## 🔧 Struktur Kode

```
project-basdat/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AdminController.php       # Controller admin & dashboard
│   │       ├── OwnerReservationController.php  # Controller pet owner
│   │       └── PenitipanController.php   # Controller operasional
│   └── Models/
│       ├── User.php
│       ├── Hewan.php
│       ├── Booking.php
│       ├── Payment.php
│       ├── FactTransaksi.php
│       └── FactBooking.php
├── database/
│   ├── migrations/                       # Database migrations
│   ├── seeders/                          # Database seeders
│   ├── install_procedure_clean.sql      # Stored procedures untuk ETL
│   └── install_sync_system_fixed.sql    # Triggers untuk sync
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── dashboard.blade.php      # Dashboard operasional
│       │   ├── reports.blade.php        # OLAP reports
│       │   ├── payments.blade.php       # Transaksi
│       │   └── booking.blade.php        # Operasional penitipan
│       └── layouts/
│           └── app.blade.php            # Main layout
└── routes/
    └── web.php                          # Route definitions
```

---

## 🧪 Testing & Verifikasi

### 1. Test Operasional Features
```bash
# Jalankan development server
php artisan serve

# Akses di browser:
# - Login: http://127.0.0.1:8000/login
# - Dashboard: http://127.0.0.1:8000/admin/dashboard
# - Operasional: http://127.0.0.1:8000/admin/penitipan
# - Transaksi: http://127.0.0.1:8000/admin/payments
```

### 2. Test OLAP Reports
- Akses: `http://127.0.0.1:8000/admin/reports`
- Verifikasi semua visualisasi chart muncul
- Test filter tahun, kuartal, dan bulan
- Verifikasi data source dari Fact Tables

### 3. Test ETL Synchronization
```sql
-- Insert data baru di tabel transaksional
INSERT INTO bookings (...) VALUES (...);

-- Cek apakah fact_booking ter-update otomatis
SELECT * FROM fact_booking ORDER BY booking_key DESC LIMIT 1;
```

---

## 📚 Fitur-Fitur Khusus Basis Data

### 1. Stored Procedures

| Procedure Name | Fungsi |
|----------------|--------|
| `LoadDimDate()` | Initial load dimensi waktu |
| `LoadDimCustomer()` | Initial load dimensi customer |
| `LoadDimPet()` | Initial load dimensi hewan |
| `LoadDimService()` | Initial load dimensi layanan |
| `LoadFactTransaksi()` | Initial load fact transaksi |
| `LoadFactBooking()` | Initial load fact booking |
| `UpdateFactTransaksiIncremental()` | Update incremental fact transaksi |
| `UpdateFactBookingIncremental()` | Update incremental fact booking |

### 2. Triggers

| Trigger Name | Event | Fungsi |
|--------------|-------|--------|
| `after_payment_insert` | AFTER INSERT payments | Sync ke fact_transaksi |
| `after_payment_update` | AFTER UPDATE payments | Update fact_transaksi |
| `after_booking_insert` | AFTER INSERT bookings | Sync ke fact_booking |
| `after_booking_update` | AFTER UPDATE bookings | Update fact_booking |

### 3. Views (Optional)
Sistem menggunakan Eloquent Models untuk mengakses fact tables dengan relationship ke dimension tables.

---

## 🎓 Konsep Basis Data yang Diimplementasikan

### 1. **OLTP (Online Transaction Processing)**
- Normalized database design (3NF)
- ACID compliance untuk transaksional
- Primary Keys dan Foreign Keys
- Indexing untuk performance

### 2. **OLAP (Online Analytical Processing)**
- Star Schema untuk data warehouse
- Denormalization untuk query performance
- Aggregate functions untuk reporting
- Multidimensional analysis

### 3. **ETL (Extract, Transform, Load)**
- Data extraction dari OLTP
- Data transformation (cleaning, formatting)
- Data loading ke data warehouse
- Incremental updates vs Full load

### 4. **Database Programming**
- Stored Procedures untuk business logic
- Triggers untuk automation
- Functions untuk reusable logic
- Error handling dalam procedures

### 5. **Query Optimization**
- Indexing strategy
- Query execution plans
- Composite keys untuk fact tables
- Partitioning considerations (untuk scalability)

---

## 📈 Business Intelligence Features

### KPI Metrics
1. **Revenue KPI**: Total pendapatan periode tertentu
2. **Occupancy Rate**: Tingkat penggunaan kapasitas
3. **Average Transaction Value**: Rata-rata nilai transaksi
4. **Top Performing Services**: Layanan terlaris
5. **Customer Retention**: Pelanggan dengan repeat booking

### Analytical Queries
Sistem dapat menjawab pertanyaan bisnis seperti:
- Berapa total revenue per bulan/kuartal/tahun?
- Siapa 5 pelanggan dengan spending tertinggi?
- Layanan apa yang paling populer?
- Bagaimana trend occupancy rate?
- Kapan peak season untuk booking?

---

## 🔐 Security Features

- **Authentication**: Laravel Breeze/built-in auth
- **Authorization**: Role-based access control (RBAC)
- **SQL Injection Prevention**: Eloquent ORM & prepared statements
- **CSRF Protection**: Laravel CSRF tokens
- **Password Hashing**: bcrypt algorithm
- **Environment Variables**: Sensitive data di .env

---

## 🐛 Troubleshooting

### Database Connection Error
```bash
# Pastikan MySQL service running
# Windows:
net start MySQL80

# Cek kredensial di .env sesuai dengan MySQL setup
```

### Stored Procedure Not Found
```bash
# Re-run installation SQL files
mysql -u root -p er_basdat < database/install_procedure_clean.sql
mysql -u root -p er_basdat < database/install_sync_system_fixed.sql
```

### Chart Not Displaying
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### ETL Not Syncing
```sql
-- Cek apakah triggers aktif
SHOW TRIGGERS FROM er_basdat;

-- Manual trigger ETL
CALL UpdateFactTransaksiIncremental();
CALL UpdateFactBookingIncremental();
```

---

## 📞 Dokumentasi Tambahan

File-file dokumentasi pelengkap tersedia di root directory:

- `SYSTEM_ARCHITECTURE.md` - Arsitektur sistem lengkap
- `DATABASE_SYNC_DOCUMENTATION.md` - Detail ETL dan sinkronisasi
- `IMPLEMENTATION_SUMMARY.md` - Summary implementasi fitur
- `QUICK_SETUP_GUIDE.md` - Panduan setup cepat
- `CHANGELOG_FACT_TABLES.md` - Perubahan pada fact tables

---

## 👨‍💻 Development Team

**Harvest Walu Kow Team**  
Proyek Basis Data - [Institusi/Universitas]

---

## 📄 License

This project is developed for educational purposes as part of Database course project.

---

## 📝 Catatan untuk Dosen

### Poin-Poin Evaluasi yang Dapat Didemonstrasikan:

✅ **Database Design**
- ER Diagram dengan relationship yang jelas
- Normalisasi (3NF) pada OLTP tables
- Denormalisasi (Star Schema) pada OLAP tables

✅ **SQL Programming**
- Complex queries dengan JOIN, GROUP BY, aggregation
- Stored Procedures dengan parameter dan logic control
- Triggers untuk automasi
- Views (via Eloquent) untuk abstraksi

✅ **Transaction Management**
- ACID properties implementation
- Commit/Rollback handling
- Isolation levels

✅ **Performance Optimization**
- Indexing strategy
- Query optimization
- Efficient ETL processes

✅ **Business Intelligence**
- Data Warehouse implementation
- ETL pipeline
- OLAP queries dan multidimensional analysis
- Interactive reporting dan visualization

✅ **Integration**
- Database integration dengan Laravel framework
- ORM (Eloquent) usage
- Database migrations dan seeding

### Demo Workflow Suggestion

1. **Login ke sistem** dengan role Admin
2. **Lihat Dashboard Operasional** - KPI real-time
3. **Buat Booking baru** - Demonstrasi CRUD
4. **Proses Pembayaran** - Demonstrasi trigger ETL
5. **Akses OLAP Reports** - Demonstrasi BI features
6. **Filter data** berdasarkan periode - Interactive analysis
7. **Export Laporan** - CSV export functionality
8. **Check Database** - Verifikasi fact tables ter-update

---

**Terima kasih atas perhatian Bapak/Ibu Dosen! 🙏**
