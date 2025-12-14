# LAPORAN PROJECT BASIS DATA

# SISTEM INFORMASI PENITIPAN ANJING DAN KUCING BERBASIS WEB (PAWSHOTEL) DENGAN IMPLEMENTASI DATA WAREHOUSE

---

**KELOMPOK C:**

| Nama | NIM |
|------|-----|
| FATMA HIDAYATUL KHUSNA | 164231002 |
| MAYLA FAIZA RAHMA | 164231090 |
| IBRAHIM IHRAM HAKIM | 164231094 |
| SALWA DEWI AQIILAH | 164231101 |
| HARVEST ECCLESIANO C. W. | 164231104 |
| HANNY MARCELLY | 164231111 |

---

**PROGRAM SARJANA**  
**TEKNOLOGI SAINS DATA**  
**DEPARTEMEN TEKNIK**  
**FAKULTAS TEKNOLOGI MAJU DAN MULTIDISIPLIN**  
**UNIVERSITAS AIRLANGGA**  
**2025**

---

# DAFTAR ISI

- [LAPORAN PROJECT BASIS DATA](#laporan-project-basis-data)
- [DAFTAR ISI](#daftar-isi)
- [DAFTAR TABEL](#daftar-tabel)
- [DAFTAR GAMBAR](#daftar-gambar)
- [BAB I PENDAHULUAN](#bab-i-pendahuluan)
  - [1.1 LATAR BELAKANG](#11-latar-belakang)
  - [1.2 RUMUSAN MASALAH](#12-rumusan-masalah)
  - [1.3 TUJUAN](#13-tujuan)
  - [1.4 MANFAAT](#14-manfaat)
- [BAB II TINJAUAN PUSTAKA](#bab-ii-tinjauan-pustaka)
  - [2.1 Database Management System (DBMS)](#21-database-management-system-dbms)
  - [2.2 Data Warehouse](#22-data-warehouse)
  - [2.3 Extract, Transform, Load (ETL)](#23-extract-transform-load-etl)
  - [2.4 Online Analytical Processing (OLAP)](#24-online-analytical-processing-olap)
  - [2.5 Framework Pengembangan Website](#25-framework-pengembangan-website)
  - [2.6 Arsitektur Model-View-Controller (MVC)](#26-arsitektur-model-view-controller-mvc)
- [BAB III ANALISIS KEBUTUHAN SISTEM](#bab-iii-analisis-kebutuhan-sistem)
  - [3.1 ANALISIS KEBUTUHAN FUNGSIONAL](#31-analisis-kebutuhan-fungsional)
  - [3.2 ANALISIS KEBUTUHAN NON-FUNGSIONAL](#32-analisis-kebutuhan-non-fungsional)
  - [3.3 USE CASE DIAGRAM](#33-use-case-diagram)
- [BAB IV PERANCANGAN SISTEM](#bab-iv-perancangan-sistem)
  - [4.1 PERANCANGAN BASIS DATA](#41-perancangan-basis-data)
  - [4.2 PERANCANGAN SISTEM](#42-perancangan-sistem)
- [BAB V IMPLEMENTASI DATA WAREHOUSE](#bab-v-implementasi-data-warehouse)
  - [5.1 PERANCANGAN DATA WAREHOUSE](#51-perancangan-data-warehouse)
  - [5.2 ETL](#52-etl)
  - [5.3 OLAP](#53-olap)
- [BAB VI IMPLEMENTASI SISTEM](#bab-vi-implementasi-sistem)
  - [6.1 TEKNOLOGI](#61-teknologi)
  - [6.2 FITUR DAN TAMPILAN](#62-fitur-dan-tampilan)
  - [6.3 INTEGRASI DENGAN DATA WAREHOUSE](#63-integrasi-dengan-data-warehouse)
- [BAB VII PENGUJIAN SISTEM](#bab-vii-pengujian-sistem)
  - [7.1 PENGUJIAN BLACKBOX](#71-pengujian-blackbox)
  - [7.2 PENGUJIAN QUERY DATA WAREHOUSE](#72-pengujian-query-data-warehouse)
- [BAB VIII HASIL DAN PEMBAHASAN](#bab-viii-hasil-dan-pembahasan)
  - [8.1 HASIL](#81-hasil)
  - [8.2 PEMBAHASAN](#82-pembahasan)
- [BAB IX KESIMPULAN DAN SARAN](#bab-ix-kesimpulan-dan-saran)
  - [9.1 KESIMPULAN](#91-kesimpulan)
  - [9.2 SARAN](#92-saran)
- [DAFTAR PUSTAKA](#daftar-pustaka)
- [LAMPIRAN](#lampiran)

---

# DAFTAR TABEL

| Tabel | Judul | Halaman |
|-------|-------|---------|
| Tabel 3.1 | Analisis Kebutuhan Fungsional | 15 |
| Tabel 4.1 | Struktur Tabel Users | 22 |
| Tabel 4.2 | Struktur Tabel Hewan | 23 |
| Tabel 4.3 | Struktur Tabel Penitipan | 24 |
| Tabel 4.4 | Struktur Tabel Pembayaran | 25 |
| Tabel 4.5 | Struktur Tabel Paket Layanan | 26 |
| Tabel 5.1 | Fact Table Penitipan | 30 |
| Tabel 5.2 | Dimension Table Waktu | 31 |
| Tabel 5.3 | Dimension Table Layanan | 32 |
| Tabel 5.4 | Dimension Table Pelanggan | 33 |
| Tabel 7.1 | Hasil Pengujian Blackbox | 45 |
| Tabel 7.2 | Hasil Pengujian Query OLAP | 48 |

---

# DAFTAR GAMBAR

| Gambar | Judul | Halaman |
|--------|-------|---------|
| Gambar 3.1 | Use Case Diagram Sistem PawsHotel | 18 |
| Gambar 4.1 | Entity Relationship Diagram (ERD) | 21 |
| Gambar 4.2 | Diagram Relasi Antar Tabel | 27 |
| Gambar 4.3 | Arsitektur Sistem PawsHotel | 28 |
| Gambar 5.1 | Skema Star Schema Data Warehouse | 29 |
| Gambar 5.2 | Proses ETL | 34 |
| Gambar 5.3 | Cube OLAP | 36 |
| Gambar 6.1 | Landing Page PawsHotel | 38 |
| Gambar 6.2 | Form Reservasi | 39 |
| Gambar 6.3 | Detail Transaksi | 40 |
| Gambar 6.4 | Halaman Layanan | 41 |
| Gambar 6.5 | Dashboard Owner | 42 |
| Gambar 6.6 | Dashboard Admin | 43 |
| Gambar 6.7 | Dashboard Analitik | 44 |

---

# BAB I PENDAHULUAN

## 1.1 LATAR BELAKANG

Di era digital saat ini, kebutuhan masyarakat terhadap layanan penitipan hewan semakin meningkat seiring bertambahnya jumlah pemilik hewan peliharaan. Hewan seperti kucing, anjing, maupun jenis peliharaan lain seringkali memerlukan perhatian khusus, terutama ketika pemiliknya harus bepergian, bekerja, atau memiliki kesibukan yang menyita waktu. Hal ini memunculkan kebutuhan akan jasa penitipan hewan yang aman, terpercaya, dan mudah diakses. Meskipun jasa penitipan hewan sudah banyak tersedia, sebagian besar layanan masih berfokus pada penyediaan tempat tanpa adanya sistem pencatatan yang komprehensif. Padahal, kebutuhan pemilik hewan tidak hanya sebatas menitipkan, tetapi juga memperoleh jaminan keamanan, kesehatan, dan informasi kondisi hewan selama masa penitipan.

Namun, permasalahan yang sering muncul dalam jasa penitipan hewan adalah kurangnya sistem informasi yang terintegrasi. Banyak layanan masih mengandalkan pencatatan manual menggunakan buku atau kertas, sehingga menyulitkan pegawai dalam mengelola data pelanggan maupun riwayat hewan. Kondisi ini dapat menimbulkan berbagai kendala, misalnya data yang terselip, informasi yang tidak akurat, hingga kesalahan dalam proses antar jemput hewan. Santoso dan Munawaroh (2023) menegaskan bahwa pencatatan manual membuat pegawai harus mengeluarkan waktu dan tenaga ekstra serta berisiko tinggi terhadap kesalahan data, sehingga dibutuhkan solusi berbasis teknologi informasi untuk meningkatkan efektivitas dan efisiensi pengelolaan layanan penitipan.

Selain itu, Rian dan Nugraha (2021) menyatakan bahwa tidak digunakannya teknologi informasi yang baik dalam jasa penitipan hewan menyebabkan kualitas layanan menurun serta menimbulkan ketidakpuasan pelanggan. Mereka menekankan pentingnya sistem informasi berbasis web yang mampu memberikan akses cepat, pencatatan terstruktur, serta laporan berkala mengenai kondisi hewan. Dengan adanya media berbasis website, pemilik hewan dapat memperoleh informasi yang lebih lengkap mengenai aktivitas dan kondisi hewan mereka selama penitipan, sekaligus memudahkan pihak penyedia jasa dalam mengelola data administrasi.

Sejalan dengan itu, penelitian lain juga menunjukkan bahwa penerapan metode Model-View-Controller (MVC) dalam pengembangan sistem penitipan anjing dan kucing berbasis web memberikan manfaat signifikan. Metode ini memungkinkan pemisahan antara logika bisnis, tampilan, dan pengolahan data sehingga sistem lebih terstruktur, fleksibel, dan mudah dikembangkan di masa depan. Dengan pendekatan MVC, sistem dapat mengoptimalkan fungsi pendaftaran, pencatatan rekam medis, serta pemantauan kondisi hewan secara real-time.

Selain pengembangan sistem transaksional (OLTP), aspek analitik juga menjadi bagian penting dalam meningkatkan kualitas layanan. Implementasi Data Warehouse memungkinkan pihak manajemen untuk melakukan analisis mendalam terhadap pola penitipan, preferensi pelanggan, performa layanan, dan tren bisnis. Dengan mengintegrasikan proses ETL (Extract, Transform, Load) dan operasi OLAP (Online Analytical Processing), data transaksional dapat ditransformasikan menjadi informasi strategis yang mendukung pengambilan keputusan berbasis data (Kimball & Ross, 2020).

Oleh karena itu, pengembangan website penitipan anjing dan kucing yang terintegrasi dengan metode pengelolaan data modern dan Data Warehouse menjadi solusi penting dalam menjawab kebutuhan masyarakat akan layanan penitipan yang profesional, transparan, terpercaya, serta didukung oleh analisis bisnis yang komprehensif.

## 1.2 RUMUSAN MASALAH

Berdasarkan latar belakang yang telah diuraikan, rumusan masalah yang akan dijawab dalam proyek ini adalah:

1. Bagaimana merancang dan membangun sistem informasi berbasis website untuk mengelola layanan penitipan anjing dan kucing yang efisien dan terintegrasi?

2. Bagaimana implementasi arsitektur Model-View-Controller (MVC) dalam pengembangan sistem penitipan hewan untuk menghasilkan aplikasi yang terstruktur dan mudah dikembangkan?

3. Bagaimana merancang basis data yang optimal untuk mengelola data pelanggan, hewan, penitipan, pembayaran, dan laporan kondisi hewan?

4. Bagaimana membangun fitur pemantauan kondisi hewan secara real-time agar pemilik hewan dapat memantau kondisi hewan mereka selama penitipan?

5. Bagaimana merancang dan mengimplementasikan Data Warehouse untuk mendukung analisis bisnis dan pengambilan keputusan strategis?

6. Bagaimana menerapkan proses ETL (Extract, Transform, Load) untuk mengintegrasikan data transaksional ke dalam Data Warehouse?

7. Bagaimana mengimplementasikan operasi OLAP (Online Analytical Processing) untuk menghasilkan laporan analitik yang interaktif dan informatif?

8. Bagaimana mengintegrasikan dashboard analitik berbasis Data Warehouse ke dalam sistem website untuk memberikan insight bisnis kepada manajemen?

## 1.3 TUJUAN

Untuk mengatasi permasalahan terkait inefisiensi pencatatan manual serta kebutuhan akan transparansi informasi bagi pemilik hewan, proyek ini memiliki tujuan sebagai berikut:

1. Membangun sistem informasi berbasis website yang terintegrasi untuk menggantikan pencatatan manual yang rentan kesalahan.

2. Menerapkan metode Model-View-Controller (MVC) guna menghasilkan arsitektur aplikasi yang terstruktur dan mudah dikembangkan.

3. Merancang dan mengimplementasikan basis data relasional yang optimal untuk mengelola seluruh data operasional sistem penitipan.

4. Menyediakan fitur pemantauan real-time dan rekam medis digital untuk meningkatkan kepercayaan pelanggan.

5. Membangun Data Warehouse dengan skema star schema untuk mendukung analisis multidimensi terhadap data penitipan.

6. Mengimplementasikan proses ETL yang efisien untuk transformasi data dari sistem OLTP ke Data Warehouse.

7. Menyediakan operasi OLAP (drill-down, roll-up, slice, dice, pivot) untuk eksplorasi data analitik.

8. Mengintegrasikan dashboard analitik yang memberikan visualisasi data dan insight bisnis kepada owner dan manajemen.

## 1.4 MANFAAT

Proyek website penitipan anjing dan kucing ini diharapkan dapat memberikan manfaat sebagai berikut:

### Bagi Pemilik Hewan:

1. **Keamanan dan Kenyamanan**: Memperoleh jaminan keamanan dan kesehatan hewan, serta rasa tenang karena bisa memantau kondisi hewan mereka secara real-time.

2. **Akses Mudah**: Proses penitipan anjing dan kucing menjadi lebih mudah karena dapat dilakukan secara online kapan saja dan di mana saja.

3. **Informasi Lengkap**: Mendapatkan informasi yang lebih lengkap mengenai aktivitas dan kondisi hewan selama penitipan melalui laporan harian.

4. **Transparansi**: Memiliki akses ke riwayat penitipan dan pembayaran secara digital dan terorganisir.

### Bagi Penyedia Jasa (Pet Care):

1. **Efektivitas dan Efisiensi**: Peningkatan efektivitas dan efisiensi dalam pengelolaan layanan penitipan anjing dan kucing dengan sistem yang terstruktur dan terotomatisasi.

2. **Peningkatan Kualitas Layanan**: Menghindari kesalahan data dan memberikan layanan yang lebih baik dan profesional, yang dapat meningkatkan kepuasan dan kepercayaan pelanggan.

3. **Pengelolaan Data yang Terintegrasi**: Memiliki sistem informasi yang terintegrasi untuk mengelola data pelanggan, riwayat hewan, dan laporan harian secara komprehensif.

4. **Pengambilan Keputusan Berbasis Data**: Dengan adanya Data Warehouse dan dashboard analitik, manajemen dapat menganalisis tren bisnis, performa layanan, preferensi pelanggan, dan membuat keputusan strategis berdasarkan data faktual.

5. **Monitoring Kinerja**: Dapat memantau KPI (Key Performance Indicators) seperti tingkat okupansi, revenue, tingkat kepuasan pelanggan, dan performa per layanan.

### Bagi Pengembangan Ilmu Pengetahuan:

1. Memberikan kontribusi dalam penerapan konsep basis data, Data Warehouse, ETL, dan OLAP dalam domain jasa penitipan hewan.

2. Menjadi referensi bagi penelitian dan pengembangan sistem informasi sejenis di masa depan.

3. Menunjukkan implementasi praktis dari teori arsitektur MVC dalam pengembangan aplikasi web yang kompleks.

---

# BAB II TINJAUAN PUSTAKA

## 2.1 Database Management System (DBMS)

Database Management System (DBMS) adalah perangkat lunak yang digunakan untuk mengelola basis data, termasuk penyimpanan, pengambilan, dan manipulasi data. Menurut Elmasri dan Navathe (2021), DBMS menyediakan mekanisme untuk mendefinisikan struktur data, menyimpan data secara efisien, dan memastikan integritas serta keamanan data. DBMS modern seperti MySQL, PostgreSQL, dan Oracle menyediakan fitur-fitur seperti transaction management, concurrency control, dan recovery mechanism yang esensial untuk aplikasi bisnis.

Dalam konteks sistem penitipan hewan, DBMS berperan penting dalam mengelola data pelanggan, data hewan, transaksi penitipan, dan pembayaran. Penggunaan DBMS relasional memastikan data tersimpan dalam struktur yang terorganisir dan dapat diakses dengan query SQL yang efisien. Connolly dan Begg (2020) menekankan bahwa pemilihan DBMS yang tepat harus mempertimbangkan faktor skalabilitas, performa, dan kemudahan integrasi dengan teknologi lain.

### 2.1.1 Normalisasi Database

Normalisasi adalah proses mengorganisir data dalam database untuk mengurangi redundansi dan meningkatkan integritas data (Date, 2020). Proses normalisasi melibatkan dekomposisi tabel menjadi bentuk normal (1NF, 2NF, 3NF, BCNF) untuk menghilangkan anomali insert, update, dan delete. Dalam sistem PawsHotel, normalisasi diterapkan untuk memastikan setiap entitas (user, hewan, penitipan, pembayaran) memiliki tabel terpisah dengan relasi yang jelas.

### 2.1.2 Transaction Management

Transaction management adalah kemampuan DBMS untuk mengelola serangkaian operasi database sebagai satu unit kerja yang atomik (Garcia-Molina et al., 2020). Properti ACID (Atomicity, Consistency, Isolation, Durability) memastikan bahwa transaksi berjalan dengan benar bahkan dalam kondisi failure atau akses konkuren. Dalam sistem pembayaran penitipan, transaction management memastikan bahwa pembayaran dan update status penitipan terjadi secara konsisten.

## 2.2 Data Warehouse

Data Warehouse adalah repositori terpusat yang menyimpan data historis dari berbagai sumber untuk tujuan analisis dan pelaporan (Inmon, 2020). Berbeda dengan database transaksional (OLTP) yang dioptimalkan untuk operasi insert, update, dan delete, Data Warehouse dioptimalkan untuk query kompleks dan analisis multidimensi (OLAP).

Menurut Kimball dan Ross (2020), Data Warehouse dirancang dengan pendekatan dimensional modeling yang menggunakan skema star atau snowflake. Skema ini terdiri dari fact table yang menyimpan metrik kuantitatif dan dimension table yang menyimpan konteks deskriptif. Dalam sistem PawsHotel, Data Warehouse dapat digunakan untuk menganalisis tren penitipan, revenue per periode, preferensi layanan, dan demografi pelanggan.

### 2.2.1 Karakteristik Data Warehouse

Inmon (2020) mendefinisikan empat karakteristik utama Data Warehouse:

1. **Subject-Oriented**: Fokus pada subjek bisnis tertentu (contoh: penitipan, penjualan, pelanggan)
2. **Integrated**: Mengintegrasikan data dari berbagai sumber dengan format yang konsisten
3. **Time-Variant**: Menyimpan data historis untuk analisis tren temporal
4. **Non-Volatile**: Data yang sudah masuk tidak berubah, hanya bertambah

### 2.2.2 Skema Dimensional Modeling

Dimensional modeling adalah teknik desain database untuk Data Warehouse yang menggunakan fact dan dimension tables (Kimball & Ross, 2020). Terdapat dua skema utama:

1. **Star Schema**: Fact table di tengah dengan dimension tables yang terhubung langsung
2. **Snowflake Schema**: Dimension tables dinormalisasi ke dalam sub-dimension tables

Star schema lebih sederhana dan memiliki performa query yang lebih baik, sementara snowflake schema menghemat storage dengan menghilangkan redundansi.

## 2.3 Extract, Transform, Load (ETL)

ETL adalah proses mengekstrak data dari sumber, mentransformasikannya ke format yang sesuai, dan memuatnya ke Data Warehouse (Vassiliadis & Simitsis, 2021). Menurut Rainardi (2020), ETL merupakan komponen krusial dalam arsitektur Data Warehouse karena menentukan kualitas dan konsistensi data analitik.

### 2.3.1 Tahapan ETL

**Extract**: Mengambil data dari sumber seperti database OLTP, file CSV, API, atau sistem eksternal. Proses ini harus efisien dan tidak mengganggu performa sistem sumber.

**Transform**: Melakukan pembersihan data (data cleansing), konversi format, agregasi, denormalisasi, dan pengayaan data. Transformasi memastikan data konsisten dan siap untuk analisis.

**Load**: Memuat data yang sudah ditransformasi ke Data Warehouse. Dapat dilakukan secara full load atau incremental load tergantung volume data dan frekuensi update.

Dalam sistem PawsHotel, ETL mengekstrak data penitipan, pembayaran, dan pelanggan dari database transaksional, mentransformasikannya dengan menambahkan dimensi waktu dan agregasi, kemudian memuatnya ke Data Warehouse untuk analisis.

### 2.3.2 Data Quality Management

Kualitas data dalam ETL sangat penting untuk menghasilkan analisis yang akurat (Redman, 2020). Aspek kualitas data meliputi:
- **Accuracy**: Data harus mencerminkan realitas dengan benar
- **Completeness**: Tidak ada data yang hilang atau kosong
- **Consistency**: Data dari berbagai sumber harus konsisten
- **Timeliness**: Data harus up-to-date dan relevan

## 2.4 Online Analytical Processing (OLAP)

OLAP adalah teknologi yang memungkinkan analisis multidimensi terhadap data dalam Data Warehouse (Codd et al., 2021). OLAP menyediakan operasi seperti drill-down, roll-up, slice, dice, dan pivot untuk eksplorasi data interaktif. Menurut Thomsen (2020), OLAP memungkinkan pengguna bisnis untuk menganalisis data dari berbagai perspektif dan level detail tanpa memerlukan keahlian SQL yang mendalam.

### 2.4.1 Operasi OLAP

1. **Drill-Down**: Navigasi dari level agregasi tinggi ke detail lebih granular (contoh: dari tahun ke bulan ke hari)
2. **Roll-Up**: Agregasi dari level detail ke level summary yang lebih tinggi
3. **Slice**: Memilih satu nilai dari satu dimensi (contoh: hanya data bulan Januari)
4. **Dice**: Memilih subset dari cube dengan multiple dimensi
5. **Pivot**: Rotasi/reorganisasi dimensi untuk melihat data dari perspektif berbeda

### 2.4.2 Arsitektur OLAP

Terdapat tiga jenis arsitektur OLAP (Pendse, 2020):

1. **MOLAP (Multidimensional OLAP)**: Data disimpan dalam struktur multidimensi (cube), memberikan performa query tercepat
2. **ROLAP (Relational OLAP)**: Data disimpan dalam relational database, lebih scalable untuk data besar
3. **HOLAP (Hybrid OLAP)**: Kombinasi MOLAP dan ROLAP

Dalam sistem PawsHotel, OLAP digunakan untuk analisis seperti:
- Revenue per periode (harian, bulanan, tahunan)
- Okupansi kamar per jenis layanan
- Demografi pelanggan
- Performa layanan
- Tren musiman

## 2.5 Framework Pengembangan Website

Framework web adalah kerangka kerja yang menyediakan struktur dan tools untuk pengembangan aplikasi web (Leff & Rayfield, 2021). Framework mempercepat development dengan menyediakan komponen siap pakai seperti routing, authentication, ORM (Object-Relational Mapping), dan template engine.

### 2.5.1 Laravel Framework

Laravel adalah framework PHP yang populer dengan arsitektur MVC (Model-View-Controller). Menurut Stauffer (2020), Laravel menyediakan fitur-fitur seperti:

- **Eloquent ORM**: Object-Relational Mapping untuk interaksi database yang elegan
- **Blade Template Engine**: Sistem templating yang powerful dan intuitif
- **Artisan CLI**: Command-line interface untuk task automation
- **Migration**: Version control untuk database schema
- **Authentication & Authorization**: Sistem keamanan built-in
- **Middleware**: Request filtering mechanism

Kelebihan Laravel menurut Otwell (2021):
- Sintaks yang ekspresif dan elegan
- Dokumentasi lengkap dan komunitas besar
- Ekosistem yang matang (Laravel Forge, Laravel Nova, Laravel Vapor)
- Performance yang baik dengan cache mechanism

### 2.5.2 Python Web Frameworks

Alternatif untuk Laravel adalah framework Python seperti Django dan Flask (Grinberg, 2020). Django adalah full-stack framework dengan filosofi "batteries included", sementara Flask adalah microframework yang lebih fleksibel dan lightweight. Pilihan framework tergantung pada kompleksitas aplikasi, preferensi bahasa pemrograman, dan ekosistem yang diinginkan.

## 2.6 Arsitektur Model-View-Controller (MVC)

Model-View-Controller (MVC) adalah pola arsitektur yang memisahkan aplikasi menjadi tiga komponen utama (Reenskaug & Coplien, 2020):

1. **Model**: Merepresentasikan data dan business logic. Model berinteraksi dengan database dan menerapkan aturan bisnis.

2. **View**: Bertanggung jawab untuk presentasi data. View menampilkan informasi kepada user dalam format HTML, JSON, atau format lainnya.

3. **Controller**: Menangani request dari user, berinteraksi dengan Model, dan memilih View yang sesuai untuk response.

Menurut Fowler (2020), keuntungan arsitektur MVC meliputi:
- **Separation of Concerns**: Setiap komponen memiliki tanggung jawab yang jelas
- **Maintainability**: Perubahan pada satu komponen tidak mempengaruhi komponen lain
- **Testability**: Setiap komponen dapat diuji secara terpisah
- **Reusability**: Komponen dapat digunakan kembali dalam konteks berbeda
- **Parallel Development**: Tim dapat bekerja pada komponen berbeda secara bersamaan

Dalam sistem PawsHotel, arsitektur MVC diimplementasikan sebagai berikut:
- **Model**: User, Hewan, Penitipan, Pembayaran, PaketLayanan
- **View**: Blade templates untuk halaman web (landing page, form reservasi, dashboard)
- **Controller**: UserController, HewanController, PenitipanController, PembayaranController

### 2.6.1 REST API Architecture

RESTful API adalah arsitektur untuk web services yang menggunakan HTTP methods dan mengikuti prinsip REST (Representational State Transfer) (Fielding, 2020). Prinsip REST meliputi:
- **Stateless**: Setiap request independen dan self-contained
- **Client-Server**: Pemisahan antara client dan server
- **Cacheable**: Response dapat di-cache untuk meningkatkan performa
- **Uniform Interface**: Interface yang konsisten menggunakan HTTP methods (GET, POST, PUT, DELETE)

REST API penting untuk integrasi dengan aplikasi mobile atau third-party systems.

---

# BAB III ANALISIS KEBUTUHAN SISTEM

## 3.1 ANALISIS KEBUTUHAN FUNGSIONAL

Kebutuhan fungsional menjelaskan fitur-fitur yang harus ada dalam sistem website penitipan anjing dan kucing PawsHotel. Berikut adalah daftar lengkap kebutuhan fungsional:

| No | Fitur | Keterangan |
|----|-------|------------|
| 1 | **Registrasi dan Login User** | Sistem harus menyediakan fitur registrasi akun baru untuk pelanggan dengan validasi email dan password. Login menggunakan email dan password dengan session management. |
| 2 | **Manajemen Profil User** | User dapat melihat dan mengubah informasi profil seperti nama, alamat, nomor telepon, dan email. |
| 3 | **Manajemen Data Hewan** | User dapat menambah, melihat, mengubah, dan menghapus data hewan peliharaan mereka (nama, jenis, ras, usia, berat, kondisi kesehatan, foto). |
| 4 | **Pencarian dan Pemilihan Paket Layanan** | User dapat melihat daftar paket layanan yang tersedia dengan informasi lengkap (harga, fasilitas, durasi). Filter berdasarkan jenis hewan dan kategori layanan. |
| 5 | **Form Reservasi Penitipan** | User dapat melakukan reservasi dengan memilih hewan, paket layanan, tanggal check-in dan check-out, serta catatan khusus. |
| 6 | **Perhitungan Otomatis Biaya** | Sistem secara otomatis menghitung total biaya berdasarkan paket layanan dan durasi penitipan. |
| 7 | **Proses Pembayaran** | Sistem menyediakan berbagai metode pembayaran (transfer bank, e-wallet, kartu kredit). Upload bukti pembayaran dan konfirmasi dari admin. |
| 8 | **Riwayat Penitipan** | User dapat melihat riwayat penitipan sebelumnya dengan detail lengkap (tanggal, hewan, paket, status). |
| 9 | **Update Kondisi Hewan Real-Time** | Admin dapat memberikan update kondisi hewan (foto, catatan aktivitas, kondisi kesehatan) yang dapat dilihat oleh pemilik secara real-time. |
| 10 | **Notifikasi** | Sistem mengirimkan notifikasi via email atau in-app untuk update penting (konfirmasi reservasi, pembayaran berhasil, update kondisi hewan, reminder check-out). |
| 11 | **Dashboard Admin** | Admin memiliki dashboard untuk mengelola semua data (user, hewan, penitipan, pembayaran, paket layanan). |
| 12 | **Manajemen Penitipan** | Admin dapat melihat, mengubah status penitipan (pending, confirmed, ongoing, completed, cancelled), dan assign kamar. |
| 13 | **Manajemen Pembayaran** | Admin dapat verifikasi pembayaran, update status pembayaran, dan generate invoice. |
| 14 | **Manajemen Paket Layanan** | Admin dapat menambah, mengubah, dan menghapus paket layanan beserta harga dan fasilitas. |
| 15 | **Laporan Harian Penitipan** | Admin dapat membuat laporan harian untuk setiap hewan yang dititipkan (aktivitas, makan, kesehatan). |
| 16 | **Dashboard Owner/Analitik** | Owner memiliki akses ke dashboard analitik yang menampilkan KPI, revenue, okupansi, tren penitipan berdasarkan Data Warehouse. |
| 17 | **Visualisasi Data** | Dashboard menyediakan grafik dan chart interaktif (line chart, bar chart, pie chart) untuk analisis bisnis. |
| 18 | **Filter dan Drill-Down** | Dashboard analitik mendukung filter berdasarkan periode, layanan, dan drill-down untuk melihat detail data. |
| 19 | **Export Laporan** | Owner dan admin dapat export laporan dalam format PDF atau Excel. |
| 20 | **Rating dan Review** | User dapat memberikan rating dan review setelah penitipan selesai untuk meningkatkan kualitas layanan. |
| 21 | **FAQ dan Contact** | Halaman FAQ untuk pertanyaan umum dan form contact untuk komunikasi dengan customer service. |
| 22 | **Gallery Fasilitas** | Halaman yang menampilkan foto-foto fasilitas PawsHotel (kamar, area bermain, dapur). |

## 3.2 ANALISIS KEBUTUHAN NON-FUNGSIONAL

Kebutuhan non-fungsional menjelaskan karakteristik sistem yang tidak berhubungan langsung dengan fungsi spesifik, tetapi berpengaruh pada kualitas sistem secara keseluruhan:

### 3.2.1 Performance

- Waktu loading halaman web maksimal 3 detik dengan koneksi internet normal
- Sistem dapat menangani minimal 100 concurrent users tanpa penurunan performa signifikan
- Query database untuk operasi transaksional harus selesai dalam waktu kurang dari 1 detik
- Query OLAP pada Data Warehouse harus selesai dalam waktu kurang dari 5 detik untuk dataset normal

### 3.2.2 Security

- Implementasi HTTPS untuk semua komunikasi antara client dan server
- Password user harus di-hash menggunakan algoritma bcrypt atau argon2
- Proteksi terhadap SQL injection, XSS (Cross-Site Scripting), dan CSRF (Cross-Site Request Forgery)
- Authentication berbasis session dengan token yang secure
- Role-based access control (RBAC) untuk membedakan akses user, admin, dan owner
- Data sensitif (informasi pembayaran) harus dienkripsi

### 3.2.3 Reliability

- Sistem harus memiliki uptime minimal 99.5% (downtime maksimal 3.65 hari per tahun)
- Backup database dilakukan secara otomatis setiap hari
- Mechanism untuk recovery dari failure atau error
- Transaction management dengan ACID properties untuk konsistensi data

### 3.2.4 Usability

- Interface user-friendly dengan design yang clean dan modern
- Responsive design yang dapat diakses dari desktop, tablet, dan mobile
- Navigasi yang intuitif dan mudah dipahami
- Pesan error yang informatif dan membantu user
- Dukungan multiple browsers (Chrome, Firefox, Safari, Edge)

### 3.2.5 Scalability

- Arsitektur sistem harus mendukung penambahan fitur baru di masa depan
- Database dapat di-scale vertical (upgrade hardware) atau horizontal (sharding/replication)
- Code modular dan mengikuti best practices untuk maintainability

### 3.2.6 Compatibility

- Kompatibel dengan major web browsers versi terbaru
- Support responsive design untuk berbagai ukuran layar (320px - 1920px+)
- API dapat diintegrasikan dengan sistem eksternal atau aplikasi mobile di masa depan

### 3.2.7 Maintainability

- Code mengikuti coding standards dan conventions (PSR untuk PHP, PEP 8 untuk Python)
- Dokumentasi code yang lengkap dengan comments
- Version control menggunakan Git
- Logging untuk debugging dan monitoring

## 3.3 USE CASE DIAGRAM

Use Case Diagram menggambarkan interaksi antara aktor (user, admin, owner, system) dengan sistem PawsHotel. Diagram ini menunjukkan fungsionalitas yang dapat dilakukan oleh setiap aktor.

### Deskripsi Use Case:

**Aktor: Pelanggan (User)**
- Register dan Login
- Kelola Profil
- Kelola Data Hewan
- Browse Paket Layanan
- Buat Reservasi
- Lakukan Pembayaran
- Lihat Update Kondisi Hewan
- Lihat Riwayat Penitipan
- Berikan Rating dan Review

**Aktor: Admin**
- Login
- Kelola Data Pengguna
- Kelola Data Hewan
- Kelola Penitipan (verifikasi, assign kamar, update status)
- Verifikasi Pembayaran
- Update Kondisi Hewan (foto, catatan harian)
- Kelola Paket Layanan
- Generate Laporan Harian

**Aktor: Owner**
- Login
- Lihat Dashboard Analitik
- Lihat Laporan Bisnis dari Data Warehouse
- Analisis Tren dan KPI
- Export Laporan
- Kelola Paket Layanan
- Lihat Semua Transaksi

**Aktor: System**
- Kirim Notifikasi Email
- Hitung Total Biaya Otomatis
- Proses ETL (Extract, Transform, Load) ke Data Warehouse
- Generate Invoice
- Backup Database

**Relasi Use Case:**
- "Lakukan Pembayaran" extends "Buat Reservasi" (pembayaran dilakukan setelah reservasi)
- "Verifikasi Pembayaran" include "Update Status Penitipan" (setelah pembayaran diverifikasi, status penitipan berubah)
- "Proses ETL" extends "Kelola Penitipan" dan "Verifikasi Pembayaran" (data transaksional di-load ke DW)
- "Lihat Dashboard Analitik" include "Query Data Warehouse" (dashboard mengambil data dari DW)

**Gambar 3.1. Use Case Diagram Sistem PawsHotel**

```
[Use Case Diagram akan berisi:]
- 3 Actor: Pelanggan, Admin, Owner (ditambah System sebagai actor eksternal)
- Use cases dalam boundary sistem PawsHotel
- Relationships: association, include, extend
- Grouping untuk membedakan fungsi transaksional dan analitik
```

*Note: Gambar diagram dapat dibuat menggunakan tools seperti Draw.io, Lucidchart, atau StarUML*

---

# BAB IV PERANCANGAN SISTEM

## 4.1 PERANCANGAN BASIS DATA

Perancangan basis data merupakan tahap penting dalam membangun sistem informasi yang efisien dan scalable. Basis data PawsHotel dirancang menggunakan model relasional dengan normalisasi hingga 3NF (Third Normal Form) untuk menghindari redundansi dan anomali data.

### 4.1.1 Entity Relationship Diagram (ERD)

ERD menggambarkan entitas dalam sistem dan relasi antar entitas tersebut. Berikut adalah entitas utama dalam sistem PawsHotel:

**Entitas:**
1. **Users** (Pengguna sistem: pelanggan, admin, owner)
2. **Hewan** (Data hewan peliharaan yang dititipkan)
3. **Penitipan** (Transaksi penitipan hewan)
4. **Pembayaran** (Data pembayaran untuk penitipan)
5. **PaketLayanan** (Paket layanan yang ditawarkan)
6. **UpdateKondisi** (Update harian kondisi hewan selama penitipan)
7. **Review** (Rating dan review dari pelanggan)
8. **Notifications** (Notifikasi untuk user)

**Relasi:**
- Users (1) --- (M) Hewan: Satu user dapat memiliki banyak hewan
- Users (1) --- (M) Penitipan: Satu user dapat membuat banyak penitipan
- Hewan (1) --- (M) Penitipan: Satu hewan dapat memiliki banyak riwayat penitipan
- PaketLayanan (1) --- (M) Penitipan: Satu paket dapat digunakan banyak penitipan
- Penitipan (1) --- (1) Pembayaran: Satu penitipan memiliki satu pembayaran
- Penitipan (1) --- (M) UpdateKondisi: Satu penitipan memiliki banyak update kondisi
- Penitipan (1) --- (1) Review: Satu penitipan dapat memiliki satu review
- Users (1) --- (M) Notifications: Satu user menerima banyak notifikasi

**Gambar 4.1. Entity Relationship Diagram (ERD)**

### 4.1.2 Struktur Tabel Database

**Tabel 4.1: Struktur Tabel Users**

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik user |
| name | VARCHAR(100) | NOT NULL | Nama lengkap |
| email | VARCHAR(100) | UNIQUE, NOT NULL | Email (untuk login) |
| password | VARCHAR(255) | NOT NULL | Password (hashed) |
| phone | VARCHAR(20) | NULL | Nomor telepon |
| address | TEXT | NULL | Alamat lengkap |
| role | ENUM('user','admin','owner') | DEFAULT 'user' | Role akses |
| email_verified_at | TIMESTAMP | NULL | Waktu verifikasi email |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu registrasi |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu update terakhir |

**Tabel 4.2: Struktur Tabel Hewan**

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik hewan |
| user_id | INT | FOREIGN KEY (users.id), NOT NULL | Pemilik hewan |
| name | VARCHAR(100) | NOT NULL | Nama hewan |
| type | ENUM('anjing','kucing') | NOT NULL | Jenis hewan |
| breed | VARCHAR(100) | NULL | Ras hewan |
| age | INT | NULL | Usia (tahun) |
| weight | DECIMAL(5,2) | NULL | Berat (kg) |
| gender | ENUM('jantan','betina') | NULL | Jenis kelamin |
| health_condition | TEXT | NULL | Kondisi kesehatan/alergi |
| photo | VARCHAR(255) | NULL | Path foto hewan |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu update |

**Tabel 4.3: Struktur Tabel Penitipan**

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik penitipan |
| user_id | INT | FOREIGN KEY (users.id), NOT NULL | Pelanggan |
| hewan_id | INT | FOREIGN KEY (hewan.id), NOT NULL | Hewan yang dititipkan |
| paket_id | INT | FOREIGN KEY (paket_layanan.id), NOT NULL | Paket layanan |
| checkin_date | DATE | NOT NULL | Tanggal check-in |
| checkout_date | DATE | NOT NULL | Tanggal check-out |
| duration | INT | NOT NULL | Durasi (hari) |
| total_price | DECIMAL(10,2) | NOT NULL | Total biaya |
| special_request | TEXT | NULL | Permintaan khusus |
| room_number | VARCHAR(10) | NULL | Nomor kamar |
| status | ENUM('pending','confirmed','ongoing','completed','cancelled') | DEFAULT 'pending' | Status penitipan |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu reservasi |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu update |

**Tabel 4.4: Struktur Tabel Pembayaran**

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik pembayaran |
| penitipan_id | INT | FOREIGN KEY (penitipan.id), UNIQUE, NOT NULL | Penitipan terkait |
| amount | DECIMAL(10,2) | NOT NULL | Jumlah pembayaran |
| payment_method | ENUM('transfer','ewallet','credit_card') | NOT NULL | Metode pembayaran |
| payment_date | TIMESTAMP | NULL | Tanggal pembayaran |
| proof_image | VARCHAR(255) | NULL | Bukti transfer |
| status | ENUM('pending','verified','failed') | DEFAULT 'pending' | Status pembayaran |
| verified_by | INT | FOREIGN KEY (users.id), NULL | Admin yang verifikasi |
| verified_at | TIMESTAMP | NULL | Waktu verifikasi |
| notes | TEXT | NULL | Catatan |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu update |

**Tabel 4.5: Struktur Tabel PaketLayanan**

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik paket |
| name | VARCHAR(100) | NOT NULL | Nama paket |
| description | TEXT | NULL | Deskripsi paket |
| animal_type | ENUM('anjing','kucing','both') | DEFAULT 'both' | Jenis hewan |
| price_per_day | DECIMAL(10,2) | NOT NULL | Harga per hari |
| facilities | TEXT | NULL | Fasilitas (JSON format) |
| max_capacity | INT | DEFAULT 0 | Kapasitas maksimal |
| is_active | BOOLEAN | DEFAULT TRUE | Status aktif |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Waktu update |

**Tabel 4.6: Struktur Tabel UpdateKondisi**

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik update |
| penitipan_id | INT | FOREIGN KEY (penitipan.id), NOT NULL | Penitipan terkait |
| date | DATE | NOT NULL | Tanggal update |
| time | TIME | NOT NULL | Waktu update |
| activity | TEXT | NULL | Aktivitas hewan |
| food_consumed | TEXT | NULL | Makanan yang dikonsumsi |
| health_status | VARCHAR(255) | NULL | Status kesehatan |
| photo | VARCHAR(255) | NULL | Foto kondisi hewan |
| notes | TEXT | NULL | Catatan tambahan |
| updated_by | INT | FOREIGN KEY (users.id), NOT NULL | Admin yang update |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |

**Tabel 4.7: Struktur Tabel Review**

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik review |
| penitipan_id | INT | FOREIGN KEY (penitipan.id), UNIQUE, NOT NULL | Penitipan yang direview |
| rating | INT | CHECK (rating BETWEEN 1 AND 5), NOT NULL | Rating 1-5 |
| comment | TEXT | NULL | Komentar review |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu review |

**Tabel 4.8: Struktur Tabel Notifications**

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik notifikasi |
| user_id | INT | FOREIGN KEY (users.id), NOT NULL | Penerima notifikasi |
| title | VARCHAR(255) | NOT NULL | Judul notifikasi |
| message | TEXT | NOT NULL | Isi notifikasi |
| type | VARCHAR(50) | NULL | Tipe notifikasi |
| is_read | BOOLEAN | DEFAULT FALSE | Status baca |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu notifikasi |

### 4.1.3 Relasi Antar Tabel

**Gambar 4.2. Diagram Relasi Antar Tabel**

Diagram ini menunjukkan foreign key relationships dan cardinality antar tabel.

### 4.1.4 Index dan Optimasi

Untuk meningkatkan performa query, beberapa index ditambahkan:

```sql
-- Index untuk pencarian user berdasarkan email
CREATE INDEX idx_users_email ON users(email);

-- Index untuk filter penitipan berdasarkan user dan status
CREATE INDEX idx_penitipan_user_status ON penitipan(user_id, status);

-- Index untuk filter penitipan berdasarkan tanggal
CREATE INDEX idx_penitipan_dates ON penitipan(checkin_date, checkout_date);

-- Index untuk pencarian hewan berdasarkan pemilik
CREATE INDEX idx_hewan_user ON hewan(user_id);

-- Index untuk filter pembayaran berdasarkan status
CREATE INDEX idx_pembayaran_status ON pembayaran(status);

-- Index untuk update kondisi berdasarkan penitipan
CREATE INDEX idx_update_penitipan ON update_kondisi(penitipan_id, date);
```

## 4.2 PERANCANGAN SISTEM

### 4.2.1 Arsitektur Sistem

Sistem PawsHotel menggunakan arsitektur tiga lapis (three-tier architecture):

1. **Presentation Layer**: Frontend web interface yang diakses user melalui browser
2. **Application Layer**: Backend logic menggunakan Laravel framework dengan arsitektur MVC
3. **Data Layer**: MySQL database untuk OLTP dan Data Warehouse untuk OLAP

**Gambar 4.3. Arsitektur Sistem PawsHotel**

### 4.2.2 Arsitektur MVC

**Model Layer:**
- User Model: Mengelola data dan business logic terkait pengguna
- Hewan Model: Mengelola data hewan peliharaan
- Penitipan Model: Mengelola transaksi penitipan dengan business rules
- Pembayaran Model: Mengelola proses pembayaran
- PaketLayanan Model: Mengelola paket layanan
- UpdateKondisi Model: Mengelola update kondisi harian
- Review Model: Mengelola rating dan review

**View Layer:**
- Landing Page: Halaman utama dengan informasi PawsHotel
- Auth Views: Form login dan registrasi
- User Dashboard: Dashboard pelanggan dengan riwayat penitipan
- Reservation Forms: Form pemesanan dan pembayaran
- Admin Dashboard: Interface untuk admin mengelola data
- Owner Dashboard: Dashboard analitik untuk owner
- Detail Pages: Halaman detail untuk penitipan dan hewan

**Controller Layer:**
- AuthController: Menangani autentikasi (login, register, logout)
- UserController: Mengelola profil user
- HewanController: CRUD operations untuk data hewan
- PenitipanController: Mengelola reservasi dan penitipan
- PembayaranController: Menangani proses pembayaran dan verifikasi
- PaketLayananController: CRUD operations untuk paket layanan
- UpdateKondisiController: Mengelola update kondisi hewan
- ReviewController: Mengelola rating dan review
- AnalyticsController: Mengambil data dari Data Warehouse untuk dashboard analitik

### 4.2.3 Flow Diagram Sistem

**Flow Reservasi dan Pembayaran:**

1. User login/register
2. User browse paket layanan
3. User memilih paket dan mengisi form reservasi
4. Sistem menghitung total biaya
5. User melakukan pembayaran dan upload bukti
6. Admin verifikasi pembayaran
7. Status penitipan berubah menjadi "confirmed"
8. Notifikasi dikirim ke user
9. Admin assign kamar sebelum check-in
10. Saat check-in, status berubah "ongoing"
11. Admin memberikan update kondisi harian
12. User dapat melihat update real-time
13. Saat check-out, status berubah "completed"
14. User dapat memberikan review

**Flow ETL dan Analytics:**

1. Sistem transaksional menyimpan data operasional (OLTP)
2. Scheduler menjalankan ETL process secara periodik (misalnya setiap malam)
3. Extract: Data diambil dari tabel penitipan, pembayaran, user, paket_layanan
4. Transform: Data dibersihkan, diagregasi, dan ditambahkan dimensi waktu
5. Load: Data dimuat ke Data Warehouse (fact dan dimension tables)
6. Owner/Admin mengakses dashboard analitik
7. Query OLAP dijalankan pada Data Warehouse
8. Hasil divisualisasikan dalam grafik dan chart interaktif

### 4.2.4 Security Architecture

**Authentication:**
- Session-based authentication dengan Laravel Sanctum
- Password hashing menggunakan bcrypt
- Remember me token untuk persistent login

**Authorization:**
- Role-Based Access Control (RBAC)
- Middleware untuk proteksi route berdasarkan role
- Policy untuk fine-grained authorization

**Input Validation:**
- Form validation menggunakan Laravel Validation
- CSRF token untuk semua form submission
- Sanitization untuk mencegah XSS

**API Security:**
- Rate limiting untuk mencegah abuse
- API token authentication untuk future mobile app

### 4.2.5 Technology Stack

**Frontend:**
- HTML5, CSS3, JavaScript
- Bootstrap 5 untuk responsive design
- jQuery untuk interactive elements
- Chart.js untuk visualisasi data analitik

**Backend:**
- Laravel 10 (PHP 8.2+)
- Eloquent ORM untuk database interaction
- Blade Template Engine untuk views
- Laravel Mix untuk asset compilation

**Database:**
- MySQL 8.0 untuk OLTP database
- MySQL 8.0 untuk Data Warehouse (alternatif: PostgreSQL)

**DevOps:**
- Git untuk version control
- Composer untuk PHP dependency management
- npm untuk JavaScript dependency management
- Laravel Artisan untuk CLI commands

---

# BAB V IMPLEMENTASI DATA WAREHOUSE

## 5.1 PERANCANGAN DATA WAREHOUSE

Data Warehouse PawsHotel dirancang untuk mendukung analisis bisnis dan pengambilan keputusan strategis. Perancangan menggunakan **Star Schema** karena kesederhanaan query dan performa yang optimal untuk dataset berukuran sedang.

### 5.1.1 Dimensional Model

**Fact Table: fact_penitipan**

Fact table menyimpan metrik kuantitatif yang dapat diagregasi dan dianalisis. Setiap record mewakili satu transaksi penitipan.

**Tabel 5.1: Fact Table Penitipan**

| Field | Type | Constraint | Keterangan |
|-------|------|------------|------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | ID unik fact |
| penitipan_id | INT | NOT NULL | ID penitipan asli (OLTP) |
| waktu_key | INT | FOREIGN KEY (dim_waktu.id) | Dimensi waktu |
| pelanggan_key | INT | FOREIGN KEY (dim_pelanggan.id) | Dimensi pelanggan |
| hewan_key | INT | FOREIGN KEY (dim_hewan.id) | Dimensi hewan |
| layanan_key | INT | FOREIGN KEY (dim_layanan.id) | Dimensi layanan |
| duration_days | INT | NOT NULL | Durasi (hari) |
| total_revenue | DECIMAL(10,2) | NOT NULL | Total pendapatan |
| payment_method | VARCHAR(50) | NULL | Metode pembayaran |
| status | VARCHAR(20) | NOT NULL | Status penitipan |
| rating | INT | NULL | Rating (jika ada) |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Waktu created |

**Dimension Tables:**

**Tabel 5.2: Dimension Table Waktu (dim_waktu)**

| Field | Type | Keterangan |
|-------|------|------------|
| id | INT PRIMARY KEY | ID unik dimensi waktu |
| date | DATE | Tanggal lengkap |
| day | INT | Hari (1-31) |
| month | INT | Bulan (1-12) |
| month_name | VARCHAR(20) | Nama bulan |
| quarter | INT | Quarter (1-4) |
| year | INT | Tahun |
| day_of_week | INT | Hari dalam minggu (1-7) |
| day_name | VARCHAR(20) | Nama hari |
| week_of_year | INT | Minggu ke-n dalam tahun |
| is_weekend | BOOLEAN | Apakah weekend |
| is_holiday | BOOLEAN | Apakah hari libur |

**Tabel 5.3: Dimension Table Layanan (dim_layanan)**

| Field | Type | Keterangan |
|-------|------|------------|
| id | INT PRIMARY KEY | ID unik dimensi layanan |
| paket_id | INT | ID paket dari OLTP |
| paket_name | VARCHAR(100) | Nama paket |
| animal_type | VARCHAR(20) | Jenis hewan (anjing/kucing) |
| price_per_day | DECIMAL(10,2) | Harga per hari |
| category | VARCHAR(50) | Kategori (basic/premium/vip) |

**Tabel 5.4: Dimension Table Pelanggan (dim_pelanggan)**

| Field | Type | Keterangan |
|-------|------|------------|
| id | INT PRIMARY KEY | ID unik dimensi pelanggan |
| user_id | INT | ID user dari OLTP |
| name | VARCHAR(100) | Nama pelanggan |
| email | VARCHAR(100) | Email pelanggan |
| phone | VARCHAR(20) | Nomor telepon |
| city | VARCHAR(50) | Kota |
| registration_date | DATE | Tanggal registrasi |
| customer_segment | VARCHAR(50) | Segmen pelanggan (new/regular/vip) |

**Tabel 5.5: Dimension Table Hewan (dim_hewan)**

| Field | Type | Keterangan |
|-------|------|------------|
| id | INT PRIMARY KEY | ID unik dimensi hewan |
| hewan_id | INT | ID hewan dari OLTP |
| name | VARCHAR(100) | Nama hewan |
| type | VARCHAR(20) | Jenis (anjing/kucing) |
| breed | VARCHAR(100) | Ras |
| age_group | VARCHAR(20) | Kelompok usia (puppy/adult/senior) |
| weight_category | VARCHAR(20) | Kategori berat (small/medium/large) |

### 5.1.2 Star Schema Diagram

**Gambar 5.1. Skema Star Schema Data Warehouse**

Star Schema terdiri dari:
- **Center (Fact Table)**: fact_penitipan dengan measures (duration_days, total_revenue, rating)
- **Points (Dimension Tables)**: dim_waktu, dim_pelanggan, dim_hewan, dim_layanan

Keuntungan Star Schema:
- Query sederhana dengan join langsung antara fact dan dimension
- Performa query cepat karena tidak ada nested join
- Mudah dipahami oleh business user
- Optimal untuk tool BI dan visualization

### 5.1.3 Agregat dan Materialized Views

Untuk meningkatkan performa query yang sering digunakan, beberapa agregat pre-calculated disimpan:

```sql
-- Agregat revenue per bulan
CREATE TABLE agg_monthly_revenue AS
SELECT 
    w.year,
    w.month,
    w.month_name,
    SUM(f.total_revenue) as total_revenue,
    COUNT(*) as total_penitipan,
    AVG(f.duration_days) as avg_duration,
    AVG(f.rating) as avg_rating
FROM fact_penitipan f
JOIN dim_waktu w ON f.waktu_key = w.id
GROUP BY w.year, w.month, w.month_name;

-- Agregat revenue per layanan
CREATE TABLE agg_layanan_performance AS
SELECT 
    l.paket_name,
    l.category,
    l.animal_type,
    COUNT(*) as total_bookings,
    SUM(f.total_revenue) as total_revenue,
    AVG(f.rating) as avg_rating
FROM fact_penitipan f
JOIN dim_layanan l ON f.layanan_key = l.id
GROUP BY l.paket_name, l.category, l.animal_type;

-- Agregat pelanggan top
CREATE TABLE agg_top_customers AS
SELECT 
    p.name,
    p.email,
    p.customer_segment,
    COUNT(*) as total_visits,
    SUM(f.total_revenue) as lifetime_value,
    AVG(f.rating) as avg_rating
FROM fact_penitipan f
JOIN dim_pelanggan p ON f.pelanggan_key = p.id
GROUP BY p.name, p.email, p.customer_segment;
```

## 5.2 ETL (Extract, Transform, Load)

Proses ETL mengintegrasikan data dari sistem OLTP ke Data Warehouse. ETL dijalankan secara scheduled (misalnya setiap malam pukul 02:00) atau dapat di-trigger secara manual oleh admin.

### 5.2.1 Extract (Ekstraksi Data)

Data diekstrak dari tabel-tabel OLTP:

```sql
-- Extract penitipan yang sudah completed
SELECT 
    p.id,
    p.user_id,
    p.hewan_id,
    p.paket_id,
    p.checkin_date,
    p.checkout_date,
    p.duration,
    p.total_price,
    p.status,
    pb.payment_method,
    r.rating,
    u.name as user_name,
    u.email,
    u.phone,
    u.address,
    h.name as hewan_name,
    h.type,
    h.breed,
    h.age,
    h.weight,
    pk.name as paket_name,
    pk.animal_type,
    pk.price_per_day
FROM penitipan p
LEFT JOIN pembayaran pb ON p.id = pb.penitipan_id
LEFT JOIN review r ON p.id = r.penitipan_id
JOIN users u ON p.user_id = u.id
JOIN hewan h ON p.hewan_id = h.id
JOIN paket_layanan pk ON p.paket_id = pk.id
WHERE p.status = 'completed'
AND p.updated_at >= :last_etl_timestamp;
```

### 5.2.2 Transform (Transformasi Data)

Data yang diekstrak ditransformasi untuk memenuhi struktur Data Warehouse:

**1. Dimension Table Population:**

```sql
-- Populate dim_waktu (pre-populated untuk beberapa tahun)
INSERT INTO dim_waktu (date, day, month, month_name, quarter, year, 
                       day_of_week, day_name, week_of_year, is_weekend, is_holiday)
SELECT 
    date_value,
    DAY(date_value),
    MONTH(date_value),
    MONTHNAME(date_value),
    QUARTER(date_value),
    YEAR(date_value),
    DAYOFWEEK(date_value),
    DAYNAME(date_value),
    WEEK(date_value),
    DAYOFWEEK(date_value) IN (1,7) as is_weekend,
    FALSE as is_holiday
FROM date_sequence_table;

-- Populate dim_pelanggan dengan customer segmentation
INSERT INTO dim_pelanggan (user_id, name, email, phone, city, 
                           registration_date, customer_segment)
SELECT 
    u.id,
    u.name,
    u.email,
    u.phone,
    SUBSTRING_INDEX(u.address, ',', -1) as city,
    DATE(u.created_at),
    CASE 
        WHEN penitipan_count = 0 THEN 'new'
        WHEN penitipan_count BETWEEN 1 AND 3 THEN 'regular'
        ELSE 'vip'
    END as customer_segment
FROM users u
LEFT JOIN (
    SELECT user_id, COUNT(*) as penitipan_count
    FROM penitipan
    WHERE status = 'completed'
    GROUP BY user_id
) counts ON u.id = counts.user_id;

-- Populate dim_hewan dengan age_group dan weight_category
INSERT INTO dim_hewan (hewan_id, name, type, breed, age_group, weight_category)
SELECT 
    id,
    name,
    type,
    breed,
    CASE 
        WHEN age < 2 THEN 'puppy'
        WHEN age BETWEEN 2 AND 7 THEN 'adult'
        ELSE 'senior'
    END as age_group,
    CASE 
        WHEN weight < 5 THEN 'small'
        WHEN weight BETWEEN 5 AND 20 THEN 'medium'
        ELSE 'large'
    END as weight_category
FROM hewan;

-- Populate dim_layanan dengan category
INSERT INTO dim_layanan (paket_id, paket_name, animal_type, 
                         price_per_day, category)
SELECT 
    id,
    name,
    animal_type,
    price_per_day,
    CASE 
        WHEN price_per_day < 50000 THEN 'basic'
        WHEN price_per_day BETWEEN 50000 AND 100000 THEN 'premium'
        ELSE 'vip'
    END as category
FROM paket_layanan;
```

**2. Data Cleansing:**

- Menghapus duplikat data
- Meng