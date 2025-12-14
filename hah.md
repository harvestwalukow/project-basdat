**2. Data Cleansing:**

- Menghapus duplikat data
- Menangani missing values
- Standardisasi format data (huruf kapital, format tanggal)
- Validasi integritas data (foreign key, range values)
- Handling outliers pada data numerik

**3. Data Aggregation:**

- Menghitung total revenue per transaksi
- Menghitung average rating per layanan
- Mengelompokkan customer berdasarkan segmentasi
- Kategorisasi hewan berdasarkan age dan weight

### 5.2.3 Load (Pemuatan Data)

Data yang sudah ditransformasi dimuat ke Data Warehouse:

```sql
-- Load fact_penitipan
INSERT INTO fact_penitipan 
    (penitipan_id, waktu_key, pelanggan_key, hewan_key, layanan_key,
     duration_days, total_revenue, payment_method, status, rating)
SELECT 
    p.id,
    w.id as waktu_key,
    pel.id as pelanggan_key,
    h.id as hewan_key,
    l.id as layanan_key,
    p.duration,
    p.total_price,
    pb.payment_method,
    p.status,
    r.rating
FROM penitipan p
JOIN dim_waktu w ON DATE(p.checkin_date) = w.date
JOIN dim_pelanggan pel ON p.user_id = pel.user_id
JOIN dim_hewan h ON p.hewan_id = h.hewan_id
JOIN dim_layanan l ON p.paket_id = l.paket_id
LEFT JOIN pembayaran pb ON p.id = pb.penitipan_id
LEFT JOIN review r ON p.id = r.penitipan_id
WHERE p.status = 'completed'
AND p.id NOT IN (SELECT penitipan_id FROM fact_penitipan);
```

**Load Strategy:**

- **Incremental Load**: Hanya data baru yang dimuat (berdasarkan timestamp atau ID)
- **Full Load**: Reload semua data (untuk refresh lengkap)
- **Delta Load**: Hanya perubahan data yang dimuat

### 5.2.4 ETL Scheduling

ETL process dijadwalkan menggunakan Laravel Task Scheduling:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Run ETL every day at 2:00 AM
    $schedule->command('etl:run')->dailyAt('02:00');
    
    // Refresh materialized views every hour
    $schedule->command('etl:refresh-aggregates')->hourly();
    
    // Backup Data Warehouse weekly
    $schedule->command('backup:datawarehouse')->weekly();
}
```

**Gambar 5.2. Proses ETL**

```
[OLTP Database]
    |
    | EXTRACT
    v
[Staging Area]
    |
    | TRANSFORM
    | - Cleansing
    | - Aggregation
    | - Enrichment
    v
[Data Warehouse]
    |
    v
[OLAP Cube] --> [Dashboard Analitik]
```

## 5.3 OLAP (Online Analytical Processing)

OLAP memungkinkan analisis multidimensi pada Data Warehouse untuk menghasilkan insight bisnis.

### 5.3.1 OLAP Operations

**1. Drill-Down (Melihat Detail Lebih Granular)**

```sql
-- Revenue per tahun
SELECT 
    w.year,
    SUM(f.total_revenue) as total_revenue
FROM fact_penitipan f
JOIN dim_waktu w ON f.waktu_key = w.id
GROUP BY w.year;

-- Drill-down: Revenue per bulan dalam tahun tertentu
SELECT 
    w.year,
    w.month_name,
    SUM(f.total_revenue) as total_revenue
FROM fact_penitipan f
JOIN dim_waktu w ON f.waktu_key = w.id
WHERE w.year = 2025
GROUP BY w.year, w.month, w.month_name
ORDER BY w.month;

-- Drill-down lebih detail: Revenue per hari dalam bulan tertentu
SELECT 
    w.date,
    w.day_name,
    SUM(f.total_revenue) as total_revenue
FROM fact_penitipan f
JOIN dim_waktu w ON f.waktu_key = w.id
WHERE w.year = 2025 AND w.month = 1
GROUP BY w.date, w.day_name
ORDER BY w.date;
```

**2. Roll-Up (Agregasi ke Level Lebih Tinggi)**

```sql
-- Detail revenue per hari
SELECT 
    w.date,
    SUM(f.total_revenue) as total_revenue
FROM fact_penitipan f
JOIN dim_waktu w ON f.waktu_key = w.id
GROUP BY w.date;

-- Roll-up: Agregasi ke level bulan
SELECT 
    w.year,
    w.month_name,
    SUM(f.total_revenue) as total_revenue
FROM fact_penitipan f
JOIN dim_waktu w ON f.waktu_key = w.id
GROUP BY w.year, w.month, w.month_name;

-- Roll-up lebih tinggi: Agregasi ke level tahun
SELECT 
    w.year,
    SUM(f.total_revenue) as total_revenue
FROM fact_penitipan f
JOIN dim_waktu w ON f.waktu_key = w.id
GROUP BY w.year;
```

**3. Slice (Memilih Satu Dimensi)**

```sql
-- Slice: Hanya data untuk kucing
SELECT 
    w.month_name,
    l.paket_name,
    SUM(f.total_revenue) as total_revenue,
    COUNT(*) as total_bookings
FROM fact_penitipan f
JOIN dim_waktu w ON f.waktu_key = w.id
JOIN dim_layanan l ON f.layanan_key = l.id
WHERE l.animal_type = 'kucing'
GROUP BY w.month, w.month_name, l.paket_name;
```

**4. Dice (Memilih Subset dari Multiple Dimensi)**

```sql
-- Dice: Data kucing, paket premium, tahun 2025, quarter 1
SELECT 
    w.month_name,
    l.paket_name,
    SUM(f.total_revenue) as total_revenue,
    COUNT(*) as total_bookings,
    AVG(f.rating) as avg_rating
FROM fact_penitipan f
JOIN dim_waktu w ON f.waktu_key = w.id
JOIN dim_layanan l ON f.layanan_key = l.id
WHERE l.animal_type = 'kucing'
AND l.category = 'premium'
AND w.year = 2025
AND w.quarter = 1
GROUP BY w.month, w.month_name, l.paket_name;
```

**5. Pivot (Rotasi Perspektif)**

```sql
-- Pivot: Revenue per layanan (rows) x bulan (columns)
SELECT 
    l.paket_name,
    SUM(CASE WHEN w.month = 1 THEN f.total_revenue ELSE 0 END) as Jan,
    SUM(CASE WHEN w.month = 2 THEN f.total_revenue ELSE 0 END) as Feb,
    SUM(CASE WHEN w.month = 3 THEN f.total_revenue ELSE 0 END) as Mar,
    SUM(CASE WHEN w.month = 4 THEN f.total_revenue ELSE 0 END) as Apr,
    SUM(CASE WHEN w.month = 5 THEN f.total_revenue ELSE 0 END) as May,
    SUM(CASE WHEN w.month = 6 THEN f.total_revenue ELSE 0 END) as Jun,
    SUM(f.total_revenue) as Total
FROM fact_penitipan f
JOIN dim_waktu w ON f.waktu_key = w.id
JOIN dim_layanan l ON f.layanan_key = l.id
WHERE w.year = 2025
GROUP BY l.paket_name;
```

### 5.3.2 OLAP Cube

OLAP Cube adalah struktur multidimensi yang memungkinkan analisis cepat dari berbagai perspektif.

**Gambar 5.3. Cube OLAP**

```
Dimensi Cube PawsHotel:
- Dimension 1 (X-axis): Waktu (Hari/Bulan/Tahun)
- Dimension 2 (Y-axis): Layanan (Paket/Kategori)
- Dimension 3 (Z-axis): Pelanggan (Segmen/Lokasi)
- Measures: Revenue, Booking Count, Average Rating
```

### 5.3.3 Analytic Queries untuk Dashboard

**KPI Queries:**

```sql
-- Total Revenue
SELECT SUM(total_revenue) as total_revenue
FROM fact_penitipan
WHERE status = 'completed';

-- Total Bookings
SELECT COUNT(*) as total_bookings
FROM fact_penitipan;

-- Average Rating
SELECT AVG(rating) as avg_rating
FROM fact_penitipan
WHERE rating IS NOT NULL;

-- Occupancy Rate (contoh jika ada data kapasitas)
SELECT 
    (COUNT(*) / (SELECT max_capacity FROM paket_layanan)) * 100 as occupancy_rate
FROM fact_penitipan
WHERE status IN ('confirmed', 'ongoing');
```

**Trend Analysis:**

```sql
-- Revenue Trend (6 bulan terakhir)
SELECT 
    w.year,
    w.month_name,
    SUM(f.total_revenue) as total_revenue,
    COUNT(*) as total_bookings
FROM fact_penitipan f
JOIN dim_waktu w ON f.waktu_key = w.id
WHERE w.date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
GROUP BY w.year, w.month, w.month_name
ORDER BY w.year, w.month;
```

**Top Performers:**

```sql
-- Top 5 Customers by Lifetime Value
SELECT 
    p.name,
    p.email,
    COUNT(*) as total_visits,
    SUM(f.total_revenue) as lifetime_value
FROM fact_penitipan f
JOIN dim_pelanggan p ON f.pelanggan_key = p.id
GROUP BY p.name, p.email
ORDER BY lifetime_value DESC
LIMIT 5;

-- Best Performing Services
SELECT 
    l.paket_name,
    l.category,
    COUNT(*) as total_bookings,
    SUM(f.total_revenue) as total_revenue,
    AVG(f.rating) as avg_rating
FROM fact_penitipan f
JOIN dim_layanan l ON f.layanan_key = l.id
GROUP BY l.paket_name, l.category
ORDER BY total_revenue DESC;
```

**Customer Segmentation Analysis:**

```sql
-- Revenue by Customer Segment
SELECT 
    p.customer_segment,
    COUNT(*) as customer_count,
    SUM(f.total_revenue) as total_revenue,
    AVG(f.total_revenue) as avg_transaction_value
FROM fact_penitipan f
JOIN dim_pelanggan p ON f.pelanggan_key = p.id
GROUP BY p.customer_segment;
```

---

# BAB VI IMPLEMENTASI SISTEM

## 6.1 TEKNOLOGI

Sistem PawsHotel dibangun menggunakan teknologi modern yang reliable dan scalable:

Bagian backend sistem PawsHotel dibangun menggunakan Laravel Framework versi 10.x, yang berjalan pada PHP 8.2 ke atas agar mendapatkan performa optimal. Pada sisi database, Eloquent ORM digunakan untuk memudahkan proses abstraksi database, sedangkan Blade Template Engine diimplementasikan untuk kebutuhan server-side rendering tampilan. Untuk proses autentikasi, sistem memanfaatkan Laravel Sanctum, sementara Laravel Mix digunakan dalam proses kompilasi asset frontend.

Pada lapisan database, sistem menggunakan MySQL 8.0 baik untuk keperluan OLTP (Online Transaction Processing) maupun untuk data warehouse, sehingga konsistensi dan kinerja penyimpanan data dapat terjaga dengan baik. Selain itu, Redis diterapkan sebagai pendukung caching dan manajemen session demi meningkatkan performa sistem secara keseluruhan.

Di sisi frontend, teknologi utama yang diadopsi meliputi HTML5 untuk menyusun struktur semantik halaman, CSS3 dengan dukungan preprocessor SASS untuk styling, serta JavaScript versi ES6+ yang memberikan fitur interaktif pada website. Bootstrap 5 digunakan agar website memiliki grid system yang responsif dan tampilan yang modern di berbagai perangkat.

Beragam library juga digunakan untuk memperkaya fungsi frontend, di antaranya jQuery 3.x sebagai alat bantu manipulasi DOM, Chart.js untuk visualisasi data, DataTables untuk penyajian tabel interaktif, SweetAlert2 dalam menampilkan notifikasi yang menarik, serta Lightbox untuk kebutuhan galeri gambar.

Proses pengembangan sistem didukung dengan sejumlah tools modern. Git digunakan untuk version control guna memastikan pengelolaan kode yang terstruktur dan kolaboratif. Composer menjadi pengelola dependensi PHP dalam proyek ini, sedangkan npm berfungsi untuk manajemen package JavaScript. Selama proses pengembangan dan debugging, programmer memanfaatkan Laravel Artisan sebagai antarmuka command line dan Laravel Tinker untuk eksplorasi langsung serta pengujian kode secara interaktif.

## 6.2 FITUR DAN TAMPILAN

### 6.2.1 User (Pelanggan)

#### 6.2.1.1 Beranda

**Gambar 6.1. Landing Page PawsHotel**

Halaman utama website PawsHotel menampilkan bagian header dengan menu navigasi yang terdiri atas **Beranda**, **Layanan**, **Tentang Kami**, dan **Kontak**. Selain itu, tersedia tombol **Reservasi Sekarang** untuk memudahkan pengguna dalam melakukan pemesanan. 

Pada bagian **hero section**, terdapat judul **"Rumah Hangat untuk Sahabat Berbulu"** yang disertai deskripsi singkat mengenai tujuan layanan, yaitu menyediakan tempat penitipan anjing dan kucing yang nyaman dan aman. Dua tombol aksi, yaitu **Daftar Sekarang** dan **Lihat Fitur**, juga disediakan untuk mengarahkan pengguna lebih lanjut.

Selanjutnya, website menampilkan bagian **Fasilitas Kami**, yang menyajikan enam fitur utama:
- Kamar ber-AC dengan temperature control
- CCTV 24/7 untuk monitoring keamanan
- Area bermain indoor dan outdoor
- Dapur higienis dengan standar kebersihan tinggi
- Sterilisasi rutin untuk kesehatan hewan
- Laporan harian untuk pemilik hewan

Kemudian bagian **"Apa Kata Pelanggan Kami"** memuat testimoni dari pengguna yang telah menggunakan layanan PawsHotel dengan rating bintang 5 dan komentar positif.

Di bagian bawah, terdapat ringkasan pencapaian berupa:
- Jumlah reservasi yang telah dilayani
- Jumlah hewan yang telah dititipkan
- Tingkat kepuasan pelanggan (%)

Penutup halaman menampilkan ajakan untuk melakukan reservasi, diikuti dengan informasi kontak (email, telepon, alamat), serta tautan media sosial (Instagram, Facebook, Twitter).

**Fitur Teknis:**
- Responsive design untuk mobile, tablet, dan desktop
- Smooth scrolling navigation
- Lazy loading untuk optimasi loading image
- SEO-optimized dengan meta tags

#### 6.2.1.2 Form Reservasi

**Gambar 6.2. Form Reservasi**

Halaman form reservasi menampilkan formulir lengkap untuk melakukan pemesanan penitipan. Form dibagi menjadi beberapa section:

**Section 1: Informasi Hewan**
- Pilihan hewan yang sudah terdaftar (dropdown)
- Tombol "Tambah Hewan Baru" jika belum ada data
- Display informasi hewan: nama, jenis, ras, usia, berat

**Section 2: Pilih Layanan**
- Card-based display untuk setiap paket layanan
- Informasi lengkap: nama paket, harga per hari, fasilitas
- Filter berdasarkan jenis hewan (anjing/kucing)
- Highlight paket yang dipilih

**Section 3: Tanggal Penitipan**
- Date picker untuk check-in date
- Date picker untuk check-out date
- Automatic calculation durasi (hari)
- Validasi: check-out harus setelah check-in
- Validasi: minimal 1 hari penitipan

**Section 4: Informasi Tambahan**
- Text area untuk permintaan khusus
- Checkbox untuk layanan tambahan (grooming, veterinary check)
- Contact number untuk emergency

**Section 5: Ringkasan Biaya**
- Detail paket layanan yang dipilih
- Durasi penitipan
- Harga per hari
- Total biaya (auto-calculated)
- Breakdown biaya tambahan jika ada

**Tombol Aksi:**
- "Lanjut ke Pembayaran" (primary button)
- "Batal" (secondary button)

**Fitur Teknis:**
- Real-time form validation
- AJAX submission tanpa reload page
- Auto-save draft (LocalStorage)
- Responsive form layout
- CSRF protection

#### 6.2.1.3 Form Pembayaran

**Gambar 6.3. Detail Transaksi dan Pembayaran**

Halaman pembayaran menampilkan:

**Section 1: Ringkasan Pesanan**
- Kode booking unik
- Tanggal reservasi
- Informasi hewan (nama, jenis)
- Paket layanan yang dipilih
- Durasi penitipan
- Total yang harus dibayar

**Section 2: Metode Pembayaran**
- Pilihan metode pembayaran (radio button):
  - Transfer Bank (BCA, Mandiri, BNI)
  - E-Wallet (GoPay, OVO, Dana)
  - Kartu Kredit/Debit

**Section 3: Detail Pembayaran**
- Untuk Transfer Bank: menampilkan nomor rekening dan nama penerima
- Untuk E-Wallet: QR Code untuk scan
- Untuk Kartu Kredit: form input card details

**Section 4: Upload Bukti Pembayaran**
- Input file untuk upload screenshot/foto bukti transfer
- Preview image setelah upload
- Validasi format file (jpg, png, pdf)
- Validasi ukuran file (max 2MB)

**Section 5: Instruksi Pembayaran**
- Langkah-langkah pembayaran
- Batas waktu pembayaran (24 jam)
- Informasi verifikasi admin

**Tombol Aksi:**
- "Konfirmasi Pembayaran" (primary)
- "Kembali" (secondary)

**Fitur Teknis:**
- Payment gateway integration (future)
- Email notification setelah submit
- Status tracking pembayaran
- Countdown timer untuk batas waktu pembayaran

#### 6.2.1.4 Halaman Layanan

**Gambar 6.4. Halaman Layanan**

Menampilkan semua paket layanan dalam format card grid:

**Untuk Setiap Paket:**
- Gambar representatif paket
- Nama paket (contoh: "Basic Care", "Premium Care", "VIP Suite")
- Badge jenis hewan (Anjing/Kucing/Both)
- Harga per hari
- List fasilitas (dengan icon):
  - Makanan 3x sehari
  - Kamar ber-AC
  - Grooming (untuk premium/vip)
  - Laporan harian
  - CCTV 24/7
  - dll
- Button "Pilih Paket"

**Filter dan Sort:**
- Filter by animal type (All/Anjing/Kucing)
- Sort by price (Low to High, High to Low)
- Sort by popularity

**Comparison Feature:**
- Checkbox untuk select multiple paket
- Button "Bandingkan" untuk side-by-side comparison

#### 6.2.1.5 Halaman Tentang Kami

Menampilkan informasi tentang PawsHotel:
- Visi dan misi perusahaan
- Sejarah pendirian
- Tim yang berpengalaman
- Sertifikasi dan penghargaan
- Galeri foto fasilitas
- Lokasi dengan Google Maps embed

#### 6.2.1.6 Dashboard Pelanggan

Setelah login, pelanggan memiliki dashboard pribadi dengan menu:

**Sidebar Menu:**
- Dashboard (overview)
- Hewan Saya
- Reservasi Aktif
- Riwayat Penitipan
- Profil Saya
- Logout

**Dashboard Overview:**
- Welcome message dengan nama pelanggan
- Quick stats: total hewan, reservasi aktif, riwayat penitipan
- Reservasi yang sedang berlangsung (card view)
- Update kondisi hewan terbaru
- Upcoming check-out dates

**Halaman Hewan Saya:**
- List semua hewan yang terdaftar (card grid)
- Button "Tambah Hewan Baru"
- Untuk setiap hewan: foto, nama, jenis, ras, usia
- Button: Lihat Detail, Edit, Hapus

**Halaman Reservasi Aktif:**
- List reservasi dengan status: pending, confirmed, ongoing
- Status badge dengan warna berbeda
- Informasi: booking ID, hewan, tanggal, status
- Button: Lihat Detail, Batalkan (jika pending)

**Halaman Riwayat Penitipan:**
- DataTable dengan pagination
- Column: Booking ID, Hewan, Tanggal, Durasi, Total, Status, Aksi
- Filter by date range
- Button: Lihat Detail, Download Invoice, Beri Review

**Halaman Profil:**
- Form untuk edit informasi pribadi
- Change password
- Email preferences untuk notifikasi

### 6.2.2 Owner

#### 6.2.2.1 Dashboard Owner

**Gambar 6.5. Dashboard Owner**

Dashboard owner fokus pada business analytics dan KPI:

**Top Section - KPI Cards:**
- Total Revenue (bulan ini dan perbandingan bulan lalu)
- Total Bookings (bulan ini)
- Occupancy Rate (%)
- Average Rating (bintang)

**Row 1: Revenue Analytics**
- Line chart: Revenue Trend (6 bulan terakhir)
- Comparison year-over-year
- Growth percentage

**Row 2: Booking Analytics**
- Bar chart: Bookings per Month
- Pie chart: Bookings by Service Type
- Donut chart: Bookings by Animal Type (Anjing vs Kucing)

**Row 3: Customer Analytics**
- Bar chart: Customer Segmentation (New/Regular/VIP)
- Table: Top 10 Customers by Lifetime Value
- Customer retention rate

**Row 4: Service Performance**
- Table: Best Performing Services (revenue, bookings, rating)
- Comparison chart: Service revenue contribution

**Row 5: Trend Analysis**
- Heatmap: Bookings by Day of Week and Month
- Seasonal trend analysis
- Peak period identification

**Filter Options:**
- Date range picker
- Service type filter
- Animal type filter
- Customer segment filter
- Export to PDF/Excel button

**Fitur Teknis:**
- Real-time data update (auto-refresh setiap 5 menit)
- Interactive charts (hover untuk detail)
- Drill-down capability
- Export functionality
- Responsive dashboard layout

### 6.2.3 Admin

#### 6.2.3.1 Dashboard Admin

**Gambar 6.6. Dashboard Admin**

Dashboard admin fokus pada operational management:

**Top Section - Quick Stats:**
- Pending Reservations (memerlukan konfirmasi)
- Pending Payments (memerlukan verifikasi)
- Active Penitipan (sedang berlangsung)
- Today's Check-ins dan Check-outs

**Recent Activities:**
- Timeline view untuk aktivitas terbaru:
  - New reservations
  - Payments verified
  - Check-ins today
  - Updates posted
  - Reviews received

**Quick Actions:**
- Button "Verifikasi Pembayaran Pending"
- Button "Update Kondisi Hewan"
- Button "Tambah Paket Layanan"

**Calendar View:**
- Calendar showing check-in and check-out dates
- Color coding: check-in (green), check-out (red), ongoing (blue)
- Click date untuk lihat detail bookings

#### 6.2.3.2 Halaman Penitipan

**Gambar 6.7. Manajemen Penitipan**

DataTable dengan columns:
- Booking ID
- Customer Name
- Pet Name
- Service Package
- Check-in Date
- Check-out Date
- Status (dengan badge)
- Room Number
- Aksi (View, Edit, Update Status)

**Filter Options:**
- Filter by status
- Filter by date range
- Search by customer/pet name

**Detail View:**
- Full booking information
- Customer dan pet details
- Payment status
- Room assignment
- Special requests
- History log (status changes, updates)

**Actions:**
- Assign/Change Room
- Update Status (pending → confirmed → ongoing → completed)
- Cancel Booking (dengan reason)
- Print Booking Details

#### 6.2.3.3 Halaman Pengguna

DataTable menampilkan semua users:
- ID
- Name
- Email
- Phone
- Role (User/Admin/Owner)
- Registration Date
- Status (Active/Inactive)
- Aksi (View, Edit, Delete)

**Actions:**
- View user details dan booking history
- Edit user information
- Change role
- Activate/Deactivate account
- Reset password

#### 6.2.3.4 Halaman Hewan

DataTable menampilkan semua hewan:
- ID
- Pet Name
- Owner Name
- Type (Anjing/Kucing)
- Breed
- Age
- Weight
- Health Condition
- Aksi (View, Edit, Delete)

**Filter:**
- Filter by type
- Filter by owner
- Search by name

**Detail View:**
- Complete pet information
- Photo gallery
- Penitipan history
- Health records
- Special notes

#### 6.2.3.5 Halaman Update Kondisi

Form untuk memberikan update kondisi hewan:

**Select Penitipan:**
- Dropdown untuk pilih penitipan yang sedang ongoing
- Display: Pet name, owner, room number

**Form Update:**
- Date dan Time (default: current)
- Activity description (textarea)
- Food consumed (textarea)
- Health status (good/needs attention/sick)
- Photo upload (multiple files)
- Additional notes

**Preview Previous Updates:**
- List semua updates untuk penitipan tersebut
- Chronological order
- Edit atau delete update

**Submit:**
- Save update
- Auto-notification ke owner via email/app

#### 6.2.3.6 Halaman Paket Layanan

DataTable menampilkan semua paket:
- ID
- Package Name
- Animal Type
- Price per Day
- Capacity
- Status (Active/Inactive)
- Aksi (View, Edit, Delete)

**Form Add/Edit Paket:**
- Package Name
- Description (rich text editor)
- Animal Type (Anjing/Kucing/Both)
- Price per Day
- Facilities (checklist atau dynamic input)
- Max Capacity
- Package image upload
- Active status toggle

#### 6.2.3.7 Halaman Pembayaran

DataTable menampilkan semua pembayaran:
- Payment ID
- Booking ID
- Customer Name
- Amount
- Payment Method
- Status (Pending/Verified/Failed)
- Payment Date
- Aksi (View, Verify, Reject)

**Filter:**
- Filter by status
- Filter by payment method
- Date range

**Verify Payment Modal:**
- Display bukti pembayaran (image preview)
- Booking details
- Amount verification
- Notes (optional)
- Button: Confirm Verification / Reject Payment

**Actions:**
- Verify payment (status → verified, penitipan status → confirmed)
- Reject payment (dengan reason, user dapat re-upload)
- Download invoice
- Send receipt via email

## 6.3 INTEGRASI DENGAN DATA WAREHOUSE

### 6.3.1 Dashboard Analitik Terintegrasi

**Gambar 6.8. Dashboard Analitik dengan Data Warehouse**

Dashboard analitik mengambil data dari Data Warehouse dan menampilkan visualisasi interaktif:

**Implementation:**

```php
// AnalyticsController.php
public function dashboard()
{
    // Get data from Data Warehouse
    $monthlyRevenue = DB::connection('datawarehouse')
        ->table('fact_penitipan as f')
        ->join('dim_waktu as w', 'f.waktu_key', '=', 'w.id')
        ->select('w.month_name', DB::raw('SUM(f.total_revenue) as revenue'))
        ->where('w.year', 2025)
        ->groupBy('w.month', 'w.month_name')
        ->get();
    
    $servicePerformance = DB::connection('datawarehouse')
        ->table('fact_penitipan as f')
        ->join('dim_layanan as l', 'f.layanan_key', '=', 'l.id')
        ->select('l.paket_name', 
                 DB::raw('COUNT(*) as bookings'),
                 DB::raw('SUM(f.total_revenue) as revenue'),
                 DB::raw('AVG(f.rating) as avg_rating'))
        ->groupBy('l.id', 'l.paket_name')
        ->get();
    
    $customerSegmentation = DB::connection('datawarehouse')
        ->table('fact_penitipan as f')
        ->join('dim_pelanggan as p', 'f.pelanggan_key', '=', 'p.id')
        ->select('p.customer_segment',
                 DB::raw('COUNT(DISTINCT p.id) as customer_count'),
                 DB::raw('SUM(f.total_revenue) as revenue'))
        ->groupBy('p.customer_segment')
        ->get();
    
    return view('analytics.dashboard', compact(
        'monthlyRevenue',
        'servicePerformance',
        'customerSegmentation'
    ));
}
```

**Frontend Visualization (Chart.js):**

```javascript
// Revenue Trend Line Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyRevenue->pluck('month_name')) !!},
        datasets: [{
            label: 'Revenue',
            data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Revenue Trend 2025'
            }
        }
    }
});

// Service Performance Bar Chart
const serviceCtx = document.getElementById('serviceChart').getContext('2d');
new Chart(serviceCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($servicePerformance->pluck('paket_name')) !!},
        datasets: [{
            label: 'Revenue',
            data: {!! json_encode($servicePerformance->pluck('revenue')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.5)'
        }]
    }
});

// Customer Segmentation Pie Chart
const customerCtx = document.getElementById('customerChart').getContext('2d');
new Chart(customerCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode($customerSegmentation->pluck('customer_segment')) !!},
        datasets: [{
            data: {!! json_encode($customerSegmentation->pluck('revenue')) !!},
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)'
            ]
        }]
    }
});
```

### 6.3.2 Real-Time Data Synchronization

ETL process berjalan otomatis untuk menjaga data warehouse up-to-date:

```php
// app/Console/Commands/RunETL.php
class RunETL extends Command
{
    protected $signature = 'etl:run';
    protected $description = 'Run ETL process to update Data Warehouse';
    
    public function handle()
    {
        $this->info('Starting ETL process...');
        
        // Extract
        $this->info('Extracting data from OLTP...');
        $newPenitipan = $this->extractData();
        
        // Transform
        $this->info('Transforming data...');
        $transformedData = $this->transformData($newPenitipan);
        
        // Load
        $this->info('Loading data to Data Warehouse...');
        $this->loadData($transformedData);
        
        // Refresh aggregates
        $this->info('Refreshing materialized views...');
        $this->refreshAggregates();
        
        $this->info('ETL process completed successfully!');
    }
}
```

---

# BAB VII PENGUJIAN SISTEM

## 7.1 PENGUJIAN BLACKBOX

Pengujian blackbox dilakukan untuk memverifikasi bahwa setiap fitur sistem berfungsi sesuai dengan spesifikasi kebutuhan fungsional.

**Tabel 7.1: Hasil Pengujian Blackbox**

| No | Fitur | Skenario Pengujian | Input | Output yang Diharapkan | Hasil | Status |
|----|-------|-------------------|-------|----------------------|-------|--------|
| 1 | Registrasi User | User mengisi form registrasi dengan data valid | Nama, email, password, phone | Akun berhasil dibuat, redirect ke halaman login | Sesuai harapan | ✓ Pass |
| 2 | Registrasi User | User mengisi form dengan email yang sudah terdaftar | Email existing | Error message "Email sudah terdaftar" | Sesuai harapan | ✓ Pass |
| 3 | Login | User login dengan kredensial valid | Email dan password benar | Redirect ke dashboard sesuai role | Sesuai harapan | ✓ Pass |
| 4 | Login | User login dengan password salah | Email benar, password salah | Error message "Password salah" | Sesuai harapan | ✓ Pass |
| 5 | Tambah Hewan | User menambah data hewan dengan informasi lengkap | Nama, jenis, ras, usia, berat, foto | Data hewan tersimpan, muncul di list | Sesuai harapan | ✓ Pass |
| 6 | Tambah Hewan | User upload foto dengan format tidak valid | File .exe | Error "Format file tidak valid" | Sesuai harapan | ✓ Pass |
| 7 | Browse Paket | User melihat daftar paket layanan | - | Tampil semua paket aktif dengan detail | Sesuai harapan | ✓ Pass |
| 8 | Filter Paket | User filter paket untuk kucing saja | Filter: kucing | Tampil hanya paket untuk kucing/both | Sesuai harapan | ✓ Pass |
| 9 | Buat Reservasi | User membuat reservasi dengan data lengkap | Hewan, paket, tanggal check-in/out | Reservasi tersimpan, status pending | Sesuai harapan | ✓ Pass |
| 10 | Buat Reservasi | User pilih check-out sebelum check-in | Check-out < check-in | Error "Check-out harus setelah check-in" | Sesuai harapan | ✓ Pass |
| 11 | Hitung Biaya | Sistem menghitung total biaya otomatis | Paket Rp50.000/hari, durasi 5 hari | Total: Rp250.000 | Sesuai harapan | ✓ Pass |
| 12 | Upload Bukti Bayar | User upload bukti pembayaran valid | File .jpg, size 1MB | File tersimpan, status pending verification | Sesuai harapan | ✓ Pass |
| 13 | Upload Bukti Bayar | User upload file terlalu besar | File 5MB | Error "File maksimal 2MB" | Sesuai harapan | ✓ Pass |
| 14 | Verifikasi Pembayaran | Admin verifikasi pembayaran | Klik verify | Status payment: verified, penitipan: confirmed | Sesuai harapan | ✓ Pass |
| 15 | Reject Pembayaran | Admin reject pembayaran dengan reason | Klik reject, input reason | Status: failed, notif ke user | Sesuai harapan | ✓ Pass |
| 16 | Update Kondisi | Admin submit update kondisi hewan | Aktivitas, foto, catatan | Update tersimpan, notif ke owner | Sesuai harapan | ✓ Pass |
| 17 | Lihat Update | User melihat update kondisi hewan real-time | - | Tampil semua update chronological | Sesuai harapan | ✓ Pass |
| 18 | Assign Room | Admin assign kamar untuk penitipan | Room number: A-101 | Room tersimpan di data penitipan | Sesuai harapan | ✓ Pass |
| 19 | Update Status | Admin update status ke ongoing saat check-in | Status: ongoing | Status berubah, notif ke user | Sesuai harapan | ✓ Pass |
| 20 | Beri Review | User beri review setelah penitipan completed | Rating 5, comment | Review tersimpan, tampil di testimonial | Sesuai harapan | ✓ Pass |
| 21 | Dashboard Analitik | Owner akses dashboard | - | Tampil chart revenue, KPI, trends | Sesuai harapan | ✓ Pass |
| 22 | Filter Dashboard | Owner filter data by date range | Jan 2025 - Mar 2025 | Data filtered sesuai range | Sesuai harapan | ✓ Pass |
| 23 | Export Laporan | Owner export laporan ke Excel | Klik export Excel | File .xlsx terdownload | Sesuai harapan | ✓ Pass |
| 24 | Notifikasi Email | System kirim email saat pembayaran verified | - | Email diterima user | Sesuai harapan | ✓ Pass |
| 25 | Cancel Booking | User cancel booking dengan status pending | Klik cancel | Status: cancelled, refund info | Sesuai harapan | ✓ Pass |
| 26 | Edit Profil | User edit informasi profil | Update phone, address | Data tersimpan, success message | Sesuai harapan | ✓ Pass |
| 27 | Change Password | User ganti password | Old password, new password | Password updated, re-login required | Sesuai harapan | ✓ Pass |
| 28 | Manage Paket | Admin tambah paket layanan baru | Nama, harga, fasilitas | Paket tersimpan, tampil di list | Sesuai harapan | ✓ Pass |
| 29 | Deactivate Paket | Admin nonaktifkan paket | Toggle status | Status: inactive, tidak tampil di user | Sesuai harapan | ✓ Pass |
| 30 | Search Functionality | Admin search penitipan by customer name | Input: "John" | Tampil penitipan milik John | Sesuai harapan | ✓ Pass |

**Ringkasan Hasil Pengujian:**
- Total Test Cases: 30
- Passed: 30 (100%)
- Failed: 0 (0%)
- **Kesimpulan**: Semua fitur fungsional berjalan sesuai ekspektasi

## 7.2 PENGUJIAN QUERY DATA WAREHOUSE

Pengujian query OLAP untuk memverifikasi bahwa Data Warehouse dan operasi analitik berfungsi dengan baik.

**Tabel 7.2: Hasil Pengujian Query OLAP**

| No | Operasi OLAP | Query | Expected Result | Actual Result | Status |
|----|--------------|-------|-----------------|---------------|--------|
| 1 | Roll-Up | Agregasi revenue dari hari ke bulan | Total revenue per bulan | Data agregat benar | ✓ Pass |
| 2 | Drill-Down | Detail revenue dari tahun ke bulan ke hari | Revenue breakdown per level | Data detail benar | ✓ Pass |
| 3 | Slice | Filter data hanya untuk kucing | Data hanya animal_type='kucing' | Filter berhasil | ✓ Pass |
| 4 | Dice | Multi-filter: kucing, premium, Q1 2025 | Data subset sesuai kriteria | Subset benar | ✓ Pass |
| 5 | Pivot | Rotasi: layanan (row) x bulan (column) | Matrix revenue | Pivot table benar | ✓ Pass |
| 6 | Total Revenue | SUM(total_revenue) | Total revenue keseluruhan | Sesuai data OLTP | ✓ Pass |
| 7 | Average Rating | AVG(rating) WHERE rating NOT NULL | Average rating | Perhitungan benar | ✓ Pass |
| 8 | Top Customers | ORDER BY lifetime_value DESC LIMIT 5 | 5 customer tertinggi | Ranking benar | ✓ Pass |
| 9 | Revenue Trend | Revenue 6 bulan terakhir | Line chart data | Trend data benar | ✓ Pass |
| 10 | Service Performance | Revenue per paket layanan | Bar chart data | Data per service benar | ✓ Pass |
| 11 | Customer Segmentation | Count dan revenue by segment | Segment breakdown | Segmentasi benar | ✓ Pass |
| 12 | Monthly Comparison | Revenue bulan ini vs bulan lalu | Perbandingan dan % growth | Comparison benar | ✓ Pass |
| 13 | Occupancy Rate | (Bookings/Capacity) * 100 | Percentage occupancy | Kalkulasi benar | ✓ Pass |
| 14 | Seasonal Trend | Bookings by month across years | Pattern musiman | Trend teridentifikasi | ✓ Pass |
| 15 | ETL Process | Run ETL, verify data sync | Data OLTP → DW tersinkron | Sinkronisasi berhasil | ✓ Pass |

**Performance Testing:**

| Query Type | Dataset Size | Execution Time | Status |
|------------|--------------|----------------|--------|
| Simple Aggregation | 10,000 records | 0.15s | ✓ Excellent |
| Multi-Dimension Join | 10,000 records | 0.35s | ✓ Good |
| Complex OLAP | 10,000 records | 1.2s | ✓ Acceptable |
| Dashboard Load | All data | 2.5s | ✓ Good |
| ETL Process | 1,000 new records | 15s | ✓ Good |

**Kesimpulan Pengujian Data Warehouse:**
- Semua query OLAP berfungsi dengan benar
- Performance query memenuhi target (< 5 detik)
- ETL process berhasil mensinkronkan data
- Integritas data terjaga antara OLTP dan DW

---

# BAB VIII HASIL DAN PEMBAHASAN

## 8.1 HASIL

Implementasi Sistem Informasi Penitipan Anjing dan Kucing berbasis Web (PawsHotel) dengan Data Warehouse telah berhasil diselesaikan dengan hasil sebagai berikut:

### 8.1.1 Hasil Pengembangan Sistem Transaksional (OLTP)

1. **Website Fungsional**
   - Sistem website telah berhasil dibangun dengan arsitektur MVC menggunakan Laravel Framework
   - Interface responsive yang dapat diakses dari desktop, tablet, dan mobile devices
   - Total 22 fitur utama telah diimplementasikan dan berfungsi dengan baik

2. **Database Relasional**
   - Database MySQL dengan 8 tabel utama (users, hewan, penitipan, pembayaran, paket_layanan, update_kondisi, review, notifications)
   - Relasi antar tabel dengan foreign key constraints untuk menjaga integritas referensial
   - Normalisasi hingga 3NF untuk menghindari redundansi data
   - Index optimization untuk meningkatkan performa query

3. **Fitur Utama**
   - **Untuk User**: Registrasi/login, manajemen hewan, browse layanan, reservasi online, pembayaran, tracking kondisi hewan real-time, riwayat penitipan, review
   - **Untuk Admin**: Dashboard operasional, manajemen penitipan, verifikasi pembayaran, update kondisi hewan, assign kamar, manajemen paket layanan
   - **Untuk Owner**: Dashboard analitik dengan visualisasi data, KPI monitoring, export laporan

4. **Security Implementation**
   - Authentication berbasis session dengan Laravel Sanctum
   - Password hashing menggunakan bcrypt
   - Role-Based Access Control (RBAC)
   - CSRF protection untuk semua form
   - Input validation dan sanitization

### 8.1.2 Hasil Implementasi Data Warehouse

1. **Dimensional Model**
   - Star Schema dengan 1 fact table (fact_penitipan) dan 4 dimension tables (dim_waktu, dim_pelanggan, dim_hewan, dim_layanan)
   - Fact table menyimpan measures: duration_days, total_revenue, rating
   - Dimension tables menyimpan context descriptive untuk analisis multidimensi

2. **ETL Process**
   - Automated ETL menggunakan Laravel Task Scheduling (daily at 2:00 AM)
   - Extract data dari database OLTP (penitipan, pembayaran, user, hewan, paket)
   - Transform dengan data cleansing, aggregation, dan enrichment (customer segmentation, categorization)
   - Load ke Data Warehouse dengan incremental load strategy
   - Waktu eksekusi: ~15 detik untuk 1,000 records baru

3. **OLAP Operations**
   - Implementasi semua operasi OLAP: drill-down, roll-up, slice, dice, pivot
   - Query performa baik (< 5 detik untuk dataset normal)
   - Materialized views untuk agregat yang sering digunakan

4. **Dashboard Analitik**
   - Visualisasi interaktif menggunakan Chart.js
   - KPI cards: Total Revenue, Total Bookings, Average Rating, Occupancy Rate
   - Charts: Revenue trend line chart, service performance bar chart, customer segmentation pie chart
   - Filter dan drill-down capability
   - Export to PDF dan Excel

### 8.1.3 Hasil Pengujian

1. **Blackbox Testing**
   - 30 test cases untuk fitur fungsional
   - Success rate: 100% (semua test passed)
   - Validasi input berfungsi dengan baik
   - Error handling informatif

2. **Data Warehouse Testing**
   - 15 test cases untuk query OLAP
   - Semua operasi OLAP berfungsi correctly
   - Data integrity terjaga antara OLTP dan DW
   - Performance testing menunjukkan hasil yang acceptable

3. **Performance Testing**
   - Page load time: 1-3 detik (acceptable)
   - Query database OLTP: < 1 detik
   - Query OLAP: < 5 detik
   - System dapat