<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MasterDemoSeeder extends Seeder
{
    /**
     * Run all seeders in the correct order for a complete demo
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Master Demo Seeding...');
        $this->command->newLine();

        // Step 1: Operational Data
        $this->command->info('📦 Step 1: Seeding Operational Data');
        $this->call([
            AdminSeeder::class,              // Admin user
            PaketLayananSeeder::class,       // Service packages
            ComprehensiveDemoSeeder::class,  // Users, Pets, Bookings, Payments, Updates
        ]);
        $this->command->newLine();

        // Step 2: Data Warehouse - Dimensions & Facts
        $this->command->info('🏢 Step 2: Populating Data Warehouse');
        $this->call([
            DWSeeder::class,                 // Dims + FactTransaksi + FactKeuangan
            AdditionalFactsSeeder::class,    // FactCustomer + FactLayananPeriodik
            FactKapasitasHarianSeeder::class,// FactKapasitasHarian
        ]);
        $this->command->newLine();

        $this->command->info('✅ Master Demo Seeding Completed!');
        $this->command->newLine();
        
        $this->printSummary();
    }

    private function printSummary()
    {
        $this->command->info('📊 Database Summary:');
        $this->command->table(
            ['Table', 'Count'],
            [
                ['Users (Pengguna)', \App\Models\Pengguna::count()],
                ['Pets (Hewan)', \App\Models\Hewan::count()],
                ['Packages (PaketLayanan)', \App\Models\PaketLayanan::count()],
                ['Bookings (Penitipan)', \App\Models\Penitipan::count()],
                ['Payments (Pembayaran)', \App\Models\Pembayaran::count()],
                ['Updates (UpdateKondisi)', \App\Models\UpdateKondisi::count()],
                ['---', '---'],
                ['FactTransaksi', \App\Models\DW\FactTransaksi::count()],
                ['FactKeuangan', \App\Models\DW\FactKeuangan::count()],
                ['FactCustomer', \App\Models\DW\FactCustomer::count()],
                ['FactLayananPeriodik', \App\Models\DW\FactLayananPeriodik::count()],
                ['FactKapasitasHarian', \App\Models\DW\FactKapasitasHarian::count()],
            ]
        );
        
        $this->command->newLine();
        $this->command->info('🔑 Login Credentials:');
        $this->command->line('   Email: admin@gmail.com');
        $this->command->line('   Password: 123456');
    }
}
