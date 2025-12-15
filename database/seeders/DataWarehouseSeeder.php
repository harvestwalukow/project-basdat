<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DataWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedDimWaktu();
        $this->seedDimStatusPenitipan();
        $this->seedDimPembayaran();
        
        $this->command->info('✅ Data Warehouse seeded successfully!');
    }
    
    private function seedDimWaktu()
    {
        $this->command->info('Seeding dim_waktu...');
        
        // Generate dates for 2 years (past 1 year + future 1 year)
        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now()->addYear();
        
        $currentDate = $startDate->copy();
        $records = [];
        
        while ($currentDate <= $endDate) {
            $namaBulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            
            $namaHari = [
                0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
                4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'
            ];
            
            $dayOfWeek = $currentDate->dayOfWeek;
            $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6) ? 1 : 0;
            
            $records[] = [
                'tanggal' => $currentDate->format('Y-m-d'),
                'tahun' => $currentDate->year,
                'bulan' => $currentDate->month,
                'nama_bulan' => $namaBulan[$currentDate->month],
                'kuartal' => ceil($currentDate->month / 3),
                'hari' => $currentDate->day,
                'nama_hari' => $namaHari[$dayOfWeek],
                'minggu_ke' => $currentDate->weekOfYear,
                'is_weekend' => $isWeekend
            ];
            
            // Insert in batches of 100
            if (count($records) >= 100) {
                DB::table('dim_waktu')->insert($records);
                $records = [];
            }
            
            $currentDate->addDay();
        }
        
        // Insert remaining records
        if (count($records) > 0) {
            DB::table('dim_waktu')->insert($records);
        }
        
        $this->command->info('  → dim_waktu seeded with ' . DB::table('dim_waktu')->count() . ' records');
    }
    
    private function seedDimStatusPenitipan()
    {
        $this->command->info('Seeding dim_status_penitipan...');
        
        $statuses = [
            ['status' => 'pending', 'deskripsi' => 'Penitipan menunggu konfirmasi'],
            ['status' => 'aktif', 'deskripsi' => 'Penitipan sedang berlangsung'],
            ['status' => 'selesai', 'deskripsi' => 'Penitipan telah selesai'],
            ['status' => 'dibatalkan', 'deskripsi' => 'Penitipan dibatalkan']
        ];
        
        DB::table('dim_status_penitipan')->insert($statuses);
        
        $this->command->info('  → dim_status_penitipan seeded with 4 records');
    }
    
    private function seedDimPembayaran()
    {
        $this->command->info('Seeding dim_pembayaran...');
        
        $metodes = ['cash', 'transfer', 'e_wallet', 'qris', 'kartu_kredit'];
        $statuses = ['pending', 'lunas', 'gagal'];
        
        $records = [];
        foreach ($metodes as $metode) {
            foreach ($statuses as $status) {
                $deskripsi = ucfirst($metode) . ' - ' . ucfirst($status);
                $records[] = [
                    'metode_pembayaran' => $metode,
                    'status_pembayaran' => $status,
                    'deskripsi' => $deskripsi
                ];
            }
        }
        
        DB::table('dim_pembayaran')->insert($records);
        
        $this->command->info('  → dim_pembayaran seeded with 15 records');
    }
}
