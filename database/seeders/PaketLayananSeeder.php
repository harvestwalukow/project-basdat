<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaketLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('paket_layanan')->insert([
            [
                'nama_paket' => 'Paket Basic',
                'deskripsi' => 'Paket dasar dengan fasilitas standar untuk penitipan hewan',
                'harga_per_hari' => 150000,
                'fasilitas' => 'Kamar Ber-AC, Makan 3x sehari, Area bermain indoor/outdoor, Laporan harian via WA (foto)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Paket Premium',
                'deskripsi' => 'Paket premium dengan fasilitas lengkap dan layanan ekstra',
                'harga_per_hari' => 250000,
                'fasilitas' => 'Kamar Ber-AC, Makan 3x sehari, Area bermain indoor/outdoor, Laporan harian via WA + VC, Snack & Treats',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

