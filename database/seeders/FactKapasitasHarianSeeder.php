<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DW\FactKapasitasHarian;
use App\Models\DW\FactTransaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FactKapasitasHarianSeeder extends Seeder
{
    /**
     * Seed FactKapasitasHarian with daily capacity data
     */
    public function run(): void
    {
        $this->command->info('Populating FactKapasitasHarian...');

        // Focus on last 7 days for the chart
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(7);

        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            // Count animals that checked in on this date from FactTransaksi
            $jumlahHewan = FactTransaksi::whereDate('tanggal_masuk', $currentDate->format('Y-m-d'))
                ->distinct('id_hewan')
                ->count('id_hewan');

            // Get waktu_key from FactTransaksi for this date (if exists)
            $waktuKey = FactTransaksi::whereDate('tanggal_masuk', $currentDate->format('Y-m-d'))
                ->value('waktu_key');

            // Create entry even if jumlahHewan is 0 (for chart continuity)
            FactKapasitasHarian::updateOrCreate(
                ['tanggal_masuk' => $currentDate->format('Y-m-d')],
                [
                    'waktu_key' => $waktuKey,
                    'jumlah_hewan' => $jumlahHewan,
                ]
            );

            $currentDate->addDay();
        }

        $count = FactKapasitasHarian::count();
        $this->command->info("✓ FactKapasitasHarian populated ({$count} records)");
    }
}
