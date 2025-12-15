<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use App\Models\Hewan;
use App\Models\Penitipan;
use App\Models\DetailPenitipan;
use App\Models\Pembayaran;
use App\Models\PaketLayanan;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Seed Data Warehouse lookup tables FIRST
        $this->call(DataWarehouseSeeder::class);
        
        // 1. Create Paket Layanan first
        $paketBasicKucing = PaketLayanan::create([
            'nama_paket' => 'Paket Basic Kucing',
            'deskripsi' => 'Paket penitipan basic untuk kucing dengan fasilitas standar',
            'harga_per_hari' => 150000,
            'fasilitas' => 'Kandang nyaman, Makanan 2x sehari, Air minum',
            'is_active' => true
        ]);

        $paketPremiumKucing = PaketLayanan::create([
            'nama_paket' => 'Paket Premium Kucing',
            'deskripsi' => 'Paket penitipan premium untuk kucing dengan fasilitas lengkap',
            'harga_per_hari' => 250000,
            'fasilitas' => 'Kandang premium, Makanan premium 3x sehari, Grooming, Play time',
            'is_active' => true
        ]);

        $paketBasicAnjing = PaketLayanan::create([
            'nama_paket' => 'Paket Basic Anjing',
            'deskripsi' => 'Paket penitipan basic untuk anjing dengan fasilitas standar',
            'harga_per_hari' => 200000,
            'fasilitas' => 'Kandang nyaman, Makanan 2x sehari, Air minum, Jalan pagi',
            'is_active' => true
        ]);

        $paketPremiumAnjing = PaketLayanan::create([
            'nama_paket' => 'Paket Premium Anjing',
            'deskripsi' => 'Paket penitipan premium untuk anjing dengan fasilitas lengkap',
            'harga_per_hari' => 350000,
            'fasilitas' => 'Kandang premium, Makanan premium 3x sehari, Grooming, Training, Play area',
            'is_active' => true
        ]);

        // 2. Create Staff Users
        $harvest = Pengguna::create([
            'nama_lengkap' => 'Harvest Walukow',
            'email' => 'harvest@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'no_telepon' => '081234567890',
            'alamat' => 'Jl. Admin No. 1, Jakarta',
            'specialization' => 'handler'
        ]);

        $fatma = Pengguna::create([
            'nama_lengkap' => 'Fatma Staffina',
            'email' => 'fatma@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'staff',
            'no_telepon' => '081234567891',
            'alamat' => 'Jl. Staff No. 2, Jakarta',
            'specialization' => 'groomer'
        ]);

        // 3. Create Pet Owners and Their Pets
        
        // Pet Owner 1: Baim (Kucing - Payment Lunas)
        $baim = Pengguna::create([
            'nama_lengkap' => 'Baim Wong',
            'email' => 'baim@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'pet_owner',
            'no_telepon' => '081234567801',
            'alamat' => 'Jl. Baim No. 10, Jakarta Selatan'
        ]);

        $hewanBaim = Hewan::create([
            'id_pemilik' => $baim->id_pengguna,
            'nama_hewan' => 'Luna',
            'jenis_hewan' => 'kucing',
            'ras' => 'Persian',
            'umur' => 2,
            'berat' => 4.5,
            'jenis_kelamin' => 'betina',
            'kondisi_khusus' => 'Alergi makanan tertentu',
            'catatan_medis' => 'Sudah vaksin lengkap'
        ]);

        $penitipanBaim = Penitipan::create([
            'id_hewan' => $hewanBaim->id_hewan,
            'id_pemilik' => $baim->id_pengguna,
            'id_staff' => $harvest->id_pengguna,
            'tanggal_masuk' => Carbon::now()->subDays(5),
            'tanggal_keluar' => Carbon::now()->addDays(2),
            'status' => 'aktif',
            'catatan_khusus' => 'Mohon berikan makanan khusus yang sudah dibawa',
            'total_biaya' => 1050000 // 7 days * 150000
        ]);

        DetailPenitipan::create([
            'id_penitipan' => $penitipanBaim->id_penitipan,
            'id_paket' => $paketBasicKucing->id_paket,
            'jumlah_hari' => 7,
            'subtotal' => 1050000 // 7 days * 150000
        ]);

        Pembayaran::create([
            'id_penitipan' => $penitipanBaim->id_penitipan,
            'nomor_transaksi' => 'TRX-' . Carbon::now()->format('Ymd') . '-000001',
            'jumlah_bayar' => 1050000,
            'metode_pembayaran' => 'transfer',
            'status_pembayaran' => 'lunas',
            'tanggal_bayar' => Carbon::now()->subDays(5)
        ]);

        // Pet Owner 2: Hanny (Anjing - Payment Pending)
        $hanny = Pengguna::create([
            'nama_lengkap' => 'Hanny Puspita',
            'email' => 'hanny@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'pet_owner',
            'no_telepon' => '081234567802',
            'alamat' => 'Jl. Hanny No. 20, Jakarta Barat'
        ]);

        $hewanHanny = Hewan::create([
            'id_pemilik' => $hanny->id_pengguna,
            'nama_hewan' => 'Max',
            'jenis_hewan' => 'anjing',
            'ras' => 'Golden Retriever',
            'umur' => 3,
            'berat' => 28.0,
            'jenis_kelamin' => 'jantan',
            'kondisi_khusus' => null,
            'catatan_medis' => 'Sehat, vaksin lengkap'
        ]);

        $penitipanHanny = Penitipan::create([
            'id_hewan' => $hewanHanny->id_hewan,
            'id_pemilik' => $hanny->id_pengguna,
            'id_staff' => $fatma->id_pengguna,
            'tanggal_masuk' => Carbon::now()->subDays(2),
            'tanggal_keluar' => Carbon::now()->addDays(3),
            'status' => 'pending',
            'catatan_khusus' => 'Suka bermain di taman',
            'total_biaya' => 1000000 // 5 days * 200000
        ]);

        DetailPenitipan::create([
            'id_penitipan' => $penitipanHanny->id_penitipan,
            'id_paket' => $paketBasicAnjing->id_paket,
            'jumlah_hari' => 5,
            'subtotal' => 1000000 // 5 days * 200000
        ]);

        Pembayaran::create([
            'id_penitipan' => $penitipanHanny->id_penitipan,
            'nomor_transaksi' => 'TRX-' . Carbon::now()->format('Ymd') . '-000002',
            'jumlah_bayar' => 1000000,
            'metode_pembayaran' => 'cash',
            'status_pembayaran' => 'pending',
            'tanggal_bayar' => null
        ]);

        // Pet Owner 3: Salwa (Kucing - Payment Lunas)
        $salwa = Pengguna::create([
            'nama_lengkap' => 'Salwa Azzahra',
            'email' => 'salwa@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'pet_owner',
            'no_telepon' => '081234567803',
            'alamat' => 'Jl. Salwa No. 30, Jakarta Utara'
        ]);

        $hewanSalwa = Hewan::create([
            'id_pemilik' => $salwa->id_pengguna,
            'nama_hewan' => 'Mochi',
            'jenis_hewan' => 'kucing',
            'ras' => 'British Shorthair',
            'umur' => 1,
            'berat' => 3.8,
            'jenis_kelamin' => 'jantan',
            'kondisi_khusus' => 'Pemalu',
            'catatan_medis' => 'Vaksin up to date'
        ]);

        $penitipanSalwa = Penitipan::create([
            'id_hewan' => $hewanSalwa->id_hewan,
            'id_pemilik' => $salwa->id_pengguna,
            'id_staff' => $harvest->id_pengguna,
            'tanggal_masuk' => Carbon::now()->subDays(1),
            'tanggal_keluar' => Carbon::now()->addDays(6),
            'status' => 'aktif',
            'catatan_khusus' => 'Butuh perhatian ekstra karena pemalu',
            'total_biaya' => 1750000 // 7 days * 250000
        ]);

        DetailPenitipan::create([
            'id_penitipan' => $penitipanSalwa->id_penitipan,
            'id_paket' => $paketPremiumKucing->id_paket,
            'jumlah_hari' => 7,
            'subtotal' => 1750000 // 7 days * 250000
        ]);

        Pembayaran::create([
            'id_penitipan' => $penitipanSalwa->id_penitipan,
            'nomor_transaksi' => 'TRX-' . Carbon::now()->format('Ymd') . '-000003',
            'jumlah_bayar' => 1750000,
            'metode_pembayaran' => 'e_wallet',
            'status_pembayaran' => 'lunas',
            'tanggal_bayar' => Carbon::now()->subDays(1)
        ]);

        // Pet Owner 4: Mayla (Anjing - Payment Gagal)
        $mayla = Pengguna::create([
            'nama_lengkap' => 'Mayla Cantika',
            'email' => 'mayla@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'pet_owner',
            'no_telepon' => '081234567804',
            'alamat' => 'Jl. Mayla No. 40, Jakarta Timur'
        ]);

        $hewanMayla = Hewan::create([
            'id_pemilik' => $mayla->id_pengguna,
            'nama_hewan' => 'Rocky',
            'jenis_hewan' => 'anjing',
            'ras' => 'German Shepherd',
            'umur' => 4,
            'berat' => 32.5,
            'jenis_kelamin' => 'jantan',
            'kondisi_khusus' => 'Energik',
            'catatan_medis' => 'Sehat, sudah dikebiri'
        ]);

        $penitipanMayla = Penitipan::create([
            'id_hewan' => $hewanMayla->id_hewan,
            'id_pemilik' => $mayla->id_pengguna,
            'id_staff' => $fatma->id_pengguna,
            'tanggal_masuk' => Carbon::now()->subDays(3),
            'tanggal_keluar' => Carbon::now()->addDays(4),
            'status' => 'pending',
            'catatan_khusus' => 'Perlu banyak aktivitas fisik',
            'total_biaya' => 2450000 // 7 days * 350000
        ]);

        DetailPenitipan::create([
            'id_penitipan' => $penitipanMayla->id_penitipan,
            'id_paket' => $paketPremiumAnjing->id_paket,
            'jumlah_hari' => 7,
            'subtotal' => 2450000 // 7 days * 350000
        ]);

        Pembayaran::create([
            'id_penitipan' => $penitipanMayla->id_penitipan,
            'nomor_transaksi' => 'TRX-' . Carbon::now()->format('Ymd') . '-000004',
            'jumlah_bayar' => 2450000,
            'metode_pembayaran' => 'qris',
            'status_pembayaran' => 'gagal',
            'tanggal_bayar' => null
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('=== USERS CREATED ===');
        $this->command->info('Admin: harvest@gmail.com / 123456');
        $this->command->info('Staff: fatma@gmail.com / 123456');
        $this->command->info('Pet Owners:');
        $this->command->info('  - baim@gmail.com / 123456 (Luna - Kucing - Lunas)');
        $this->command->info('  - hanny@gmail.com / 123456 (Max - Anjing - Pending)');
        $this->command->info('  - salwa@gmail.com / 123456 (Mochi - Kucing - Lunas)');
        $this->command->info('  - mayla@gmail.com / 123456 (Rocky - Anjing - Gagal)');
    }
}
