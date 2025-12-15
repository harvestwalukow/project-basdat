<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use App\Models\Hewan;
use App\Models\PaketLayanan;
use App\Models\Penitipan;
use App\Models\DetailPenitipan;
use App\Models\Pembayaran;
use App\Models\UpdateKondisi;
use Carbon\Carbon;

class ComprehensiveDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Memulai seeding data demo...');

        // 1. Create Users (Customers & Staff)
        $this->seedUsers();
        
        // 2. Create Pets
        $this->seedPets();
        
        // 3. Ensure Packages exist
        $this->ensurePackages();
        
        // 4. Create Bookings (Penitipan)
        $this->seedBookings();
        
        // 5. Create Payments
        $this->seedPayments();
        
        // 6. Create Condition Updates
        $this->seedConditionUpdates();

        $this->command->info('✅ Seeding data demo selesai!');
    }

    private function seedUsers()
    {
        $this->command->info('Membuat data pengguna...');

        // Staff Members
        $staffData = [
            [
                'nama_lengkap' => 'Budi Santoso',
                'email' => 'budi@pethotel.com',
                'no_telepon' => '081234567891',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'role' => 'staff',
                'specialization' => 'groomer'
            ],
            [
                'nama_lengkap' => 'Siti Rahayu',
                'email' => 'siti@pethotel.com',
                'no_telepon' => '081234567892',
                'alamat' => 'Jl. Sudirman No. 20, Jakarta',
                'role' => 'staff',
                'specialization' => 'handler'
            ],
            [
                'nama_lengkap' => 'Ahmad Fauzi',
                'email' => 'ahmad@pethotel.com',
                'no_telepon' => '081234567893',
                'alamat' => 'Jl. Gatot Subroto No. 30, Jakarta',
                'role' => 'staff',
                'specialization' => 'trainer'
            ],
        ];

        foreach ($staffData as $staff) {
            Pengguna::firstOrCreate(
                ['email' => $staff['email']],
                array_merge($staff, ['password' => Hash::make('password123')])
            );
        }

        // Pet Owners (Indonesian names)
        $customerData = [
            ['nama_lengkap' => 'Andi Wijaya', 'email' => 'andi@example.com', 'no_telepon' => '081234567894', 'alamat' => 'Jl. Kebon Jeruk No. 5, Jakarta Barat'],
            ['nama_lengkap' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'no_telepon' => '081234567895', 'alamat' => 'Jl. Cempaka Putih No. 15, Jakarta Pusat'],
            ['nama_lengkap' => 'Rudi Hartono', 'email' => 'rudi@example.com', 'no_telepon' => '081234567896', 'alamat' => 'Jl. Melati No. 25, Jakarta Selatan'],
            ['nama_lengkap' => 'Lina Marlina', 'email' => 'lina@example.com', 'no_telepon' => '081234567897', 'alamat' => 'Jl. Mawar No. 35, Tangerang'],
            ['nama_lengkap' => 'Bambang Suryadi', 'email' => 'bambang@example.com', 'no_telepon' => '081234567898', 'alamat' => 'Jl. Anggrek No. 45, Bekasi'],
            ['nama_lengkap' => 'Sari Indah Permata', 'email' => 'sari@example.com', 'no_telepon' => '081234567899', 'alamat' => 'Jl. Dahlia No. 55, Depok'],
            ['nama_lengkap' => 'Hendra Kusuma', 'email' => 'hendra@example.com', 'no_telepon' => '081234567800', 'alamat' => 'Jl. Kenanga No. 65, Bogor'],
            ['nama_lengkap' => 'Maya Sari Dewi', 'email' => 'maya@example.com', 'no_telepon' => '081234567801', 'alamat' => 'Jl. Tulip No. 75, Jakarta Timur'],
            ['nama_lengkap' => 'Fajar Nugroho', 'email' => 'fajar@example.com', 'no_telepon' => '081234567802', 'alamat' => 'Jl. Sakura No. 85, Jakarta Utara'],
            ['nama_lengkap' => 'Rina Wulandari', 'email' => 'rina@example.com', 'no_telepon' => '081234567803', 'alamat' => 'Jl. Flamboyan No. 95, Tangerang Selatan'],
        ];

        foreach ($customerData as $customer) {
            Pengguna::firstOrCreate(
                ['email' => $customer['email']],
                array_merge($customer, [
                    'password' => Hash::make('customer123'),
                    'role' => 'pet_owner',
                    'specialization' => null
                ])
            );
        }

        $this->command->info('✓ Data pengguna dibuat');
    }

    private function seedPets()
    {
        $this->command->info('Membuat data hewan peliharaan...');

        $customers = Pengguna::where('role', 'pet_owner')->get();

        $petData = [
            ['nama_hewan' => 'Max', 'jenis_hewan' => 'anjing', 'ras' => 'Golden Retriever', 'umur' => 24, 'berat' => 30.5, 'jenis_kelamin' => 'jantan'],
            ['nama_hewan' => 'Bella', 'jenis_hewan' => 'kucing', 'ras' => 'Persian', 'umur' => 18, 'berat' => 4.2, 'jenis_kelamin' => 'betina'],
            ['nama_hewan' => 'Charlie', 'jenis_hewan' => 'anjing', 'ras' => 'Beagle', 'umur' => 36, 'berat' => 12.8, 'jenis_kelamin' => 'jantan'],
            ['nama_hewan' => 'Luna', 'jenis_hewan' => 'kucing', 'ras' => 'Maine Coon', 'umur' => 12, 'berat' => 5.5, 'jenis_kelamin' => 'betina'],
            ['nama_hewan' => 'Rocky', 'jenis_hewan' => 'anjing', 'ras' => 'Bulldog', 'umur' => 48, 'berat' => 25.0, 'jenis_kelamin' => 'jantan'],
            ['nama_hewan' => 'Milo', 'jenis_hewan' => 'kucing', 'ras' => 'Siamese', 'umur' => 20, 'berat' => 4.0, 'jenis_kelamin' => 'jantan'],
            ['nama_hewan' => 'Daisy', 'jenis_hewan' => 'anjing', 'ras' => 'Poodle', 'umur' => 30, 'berat' => 8.5, 'jenis_kelamin' => 'betina'],
            ['nama_hewan' => 'Simba', 'jenis_hewan' => 'kucing', 'ras' => 'British Shorthair', 'umur' => 15, 'berat' => 6.0, 'jenis_kelamin' => 'jantan'],
            ['nama_hewan' => 'Buddy', 'jenis_hewan' => 'anjing', 'ras' => 'Labrador', 'umur' => 42, 'berat' => 32.0, 'jenis_kelamin' => 'jantan'],
            ['nama_hewan' => 'Coco', 'jenis_hewan' => 'kucing', 'ras' => 'Ragdoll', 'umur' => 10, 'berat' => 4.8, 'jenis_kelamin' => 'betina'],
            ['nama_hewan' => 'Duke', 'jenis_hewan' => 'anjing', 'ras' => 'German Shepherd', 'umur' => 60, 'berat' => 35.0, 'jenis_kelamin' => 'jantan'],
            ['nama_hewan' => 'Whiskers', 'jenis_hewan' => 'kucing', 'ras' => 'Domestic Shorthair', 'umur' => 24, 'berat' => 4.5, 'jenis_kelamin' => 'jantan'],
            ['nama_hewan' => 'Rex', 'jenis_hewan' => 'anjing', 'ras' => 'Siberian Husky', 'umur' => 36, 'berat' => 28.0, 'jenis_kelamin' => 'jantan'],
            ['nama_hewan' => 'Nala', 'jenis_hewan' => 'kucing', 'ras' => 'Scottish Fold', 'umur' => 14, 'berat' => 4.3, 'jenis_kelamin' => 'betina'],
        ];

        foreach ($petData as $index => $pet) {
            $owner = $customers[$index % $customers->count()];
            
            Hewan::firstOrCreate(
                [
                    'nama_hewan' => $pet['nama_hewan'],
                    'id_pemilik' => $owner->id_pengguna
                ],
                array_merge($pet, [
                    'id_pemilik' => $owner->id_pengguna,
                    'kondisi_khusus' => rand(0, 2) ? null : (rand(0, 1) ? 'Alergi makanan tertentu' : 'Sensitif terhadap cuaca dingin'),
                    'catatan_medis' => rand(0, 2) ? null : 'Vaksinasi lengkap, kondisi sehat'
                ])
            );
        }

        $this->command->info('✓ Data hewan peliharaan dibuat');
    }

    private function ensurePackages()
    {
        $this->command->info('Memastikan paket layanan tersedia...');
        
        // Check if packages already exist
        if (PaketLayanan::count() > 0) {
            $this->command->info('✓ Paket layanan sudah ada');
            return;
        }
        
        // If not, call the PaketLayananSeeder
        $this->call(PaketLayananSeeder::class);
    }

    private function seedBookings()
    {
        $this->command->info('Membuat data penitipan...');

        $pets = Hewan::all();
        $packages = PaketLayanan::where('is_active', true)->get();
        $staff = Pengguna::where('role', 'staff')->get();

        $bookingCount = 0;
        
        // Create more active bookings (last 7 days with higher frequency)
        for ($i = 7; $i >= 0; $i--) {
            $numBookingsToday = rand(2, 4); // 2-4 bookings per day
            
            for ($j = 0; $j < $numBookingsToday; $j++) {
                $tanggalMasuk = Carbon::now()->subDays($i)->setHour(rand(9, 16))->setMinute(0)->setSecond(0);
                $jumlahHari = rand(5, 14); // Longer stays
                $tanggalKeluar = $tanggalMasuk->copy()->addDays($jumlahHari);
                
                // Random pet and staff
                $pet = $pets->random();
                $staffMember = $staff->random();
                
                // Determine status based on dates
                $now = Carbon::now();
                if ($tanggalKeluar->lt($now)) {
                    $status = 'selesai';
                } elseif ($tanggalMasuk->lte($now) && $tanggalKeluar->gte($now)) {
                    $status = 'aktif';
                } else {
                    $status = 'pending';
                }

                // Select packages (1-2 packages per booking, prefer basic packages)
                $numPackages = rand(1, 2);
                $selectedPackages = $packages->random(min($numPackages, $packages->count()));
                
                // Calculate total cost
                $totalBiaya = 0;
                foreach ($selectedPackages as $package) {
                    $totalBiaya += $package->harga_per_hari * $jumlahHari;
                }

                $penitipan = Penitipan::create([
                    'id_pemilik' => $pet->id_pemilik,
                    'id_hewan' => $pet->id_hewan,
                    'id_staff' => $staffMember->id_pengguna,
                    'tanggal_masuk' => $tanggalMasuk,
                    'tanggal_keluar' => $tanggalKeluar,
                    'status' => $status,
                    'catatan_khusus' => rand(0, 1) ? 'Penitipan ' . $pet->nama_hewan : null,
                    'total_biaya' => $totalBiaya,
                ]);

                // Add package details
                foreach ($selectedPackages as $package) {
                    $subtotal = $package->harga_per_hari * $jumlahHari;
                    
                    DetailPenitipan::create([
                        'id_penitipan' => $penitipan->id_penitipan,
                        'id_paket' => $package->id_paket,
                        'jumlah_hari' => $jumlahHari,
                        'subtotal' => $subtotal,
                    ]);
                }

                $bookingCount++;
            }
        }
        
        // Create some older bookings (30-60 days ago)
        for ($i = 60; $i >= 30; $i -= rand(5, 10)) {
            $tanggalMasuk = Carbon::now()->subDays($i)->setHour(rand(9, 16))->setMinute(0)->setSecond(0);
            $jumlahHari = rand(3, 10);
            $tanggalKeluar = $tanggalMasuk->copy()->addDays($jumlahHari);
            
            $pet = $pets->random();
            $staffMember = $staff->random();
            $status = 'selesai'; // All old bookings are completed
            
            $numPackages = rand(1, 2);
            $selectedPackages = $packages->random(min($numPackages, $packages->count()));
            
            $totalBiaya = 0;
            foreach ($selectedPackages as $package) {
                $totalBiaya += $package->harga_per_hari * $jumlahHari;
            }

            $penitipan = Penitipan::create([
                'id_pemilik' => $pet->id_pemilik,
                'id_hewan' => $pet->id_hewan,
                'id_staff' => $staffMember->id_pengguna,
                'tanggal_masuk' => $tanggalMasuk,
                'tanggal_keluar' => $tanggalKeluar,
                'status' => $status,
                'catatan_khusus' => null,
                'total_biaya' => $totalBiaya,
            ]);

            foreach ($selectedPackages as $package) {
                $subtotal = $package->harga_per_hari * $jumlahHari;
                
                DetailPenitipan::create([
                    'id_penitipan' => $penitipan->id_penitipan,
                    'id_paket' => $package->id_paket,
                    'jumlah_hari' => $jumlahHari,
                    'subtotal' => $subtotal,
                ]);
            }

            $bookingCount++;
        }

        $this->command->info("✓ {$bookingCount} data penitipan dibuat");
    }

    private function seedPayments()
    {
        $this->command->info('Membuat data pembayaran...');

        $penitipans = Penitipan::all();
        $paymentMethods = ['cash', 'transfer', 'e_wallet', 'qris', 'kartu_kredit'];

        foreach ($penitipans as $index => $penitipan) {
            // Determine payment status based on booking status
            if ($penitipan->status === 'selesai') {
                $statusPembayaran = 'lunas';
                $tanggalBayar = $penitipan->tanggal_masuk->copy()->addDays(rand(0, 2));
            } elseif ($penitipan->status === 'aktif') {
                $statusPembayaran = rand(0, 1) ? 'lunas' : 'pending';
                $tanggalBayar = $statusPembayaran === 'lunas' ? $penitipan->tanggal_masuk : null;
            } else {
                $statusPembayaran = 'pending';
                $tanggalBayar = null;
            }

            // Generate unique transaction number with timestamp
            $nomorTransaksi = 'TRX' . date('YmdHis') . strtoupper(substr(md5(uniqid()), 0, 4));

            Pembayaran::create([
                'id_penitipan' => $penitipan->id_penitipan,
                'nomor_transaksi' => $nomorTransaksi,
                'jumlah_bayar' => $penitipan->total_biaya,
                'metode_pembayaran' => $paymentMethods[array_rand($paymentMethods)],
                'status_pembayaran' => $statusPembayaran,
                'tanggal_bayar' => $tanggalBayar,
            ]);
        }

        $this->command->info('✓ Data pembayaran dibuat');
    }

    private function seedConditionUpdates()
    {
        $this->command->info('Membuat data update kondisi...');

        $activePenitipans = Penitipan::where('status', 'aktif')->get();
        $staff = Pengguna::where('role', 'staff')->get();
        $activities = ['Makan pagi dan siang', 'Mandi dan grooming', 'Bermain di taman', 'Istirahat siang', 'Grooming lengkap', 'Training dasar', 'Jalan-jalan sore'];
        $conditions = ['Sehat', 'Sehat', 'Sehat', 'Perlu Perhatian', 'Sakit']; // Mostly healthy

        $updateCount = 0;
        foreach ($activePenitipans as $penitipan) {
            // Create 2-5 updates per active booking
            $numUpdates = rand(2, 5);
            
            for ($i = 0; $i < $numUpdates; $i++) {
                $daysOffset = rand(0, min(3, Carbon::parse($penitipan->tanggal_keluar)->diffInDays(Carbon::now())));
                $waktuUpdate = $penitipan->tanggal_masuk->copy()->addDays($daysOffset)->addHours(rand(8, 18));
                
                if ($waktuUpdate->lte(Carbon::now())) {
                    UpdateKondisi::create([
                        'id_penitipan' => $penitipan->id_penitipan,
                        'id_staff' => $staff->random()->id_pengguna,
                        'waktu_update' => $waktuUpdate,
                        'aktivitas_hari_ini' => $activities[array_rand($activities)],
                        'kondisi_hewan' => $conditions[array_rand($conditions)],
                        'catatan_staff' => rand(0, 1) ? 'Hewan dalam kondisi baik dan responsif' : null,
                    ]);
                    $updateCount++;
                }
            }
        }

        $this->command->info("✓ {$updateCount} data update kondisi dibuat");
    }
}
