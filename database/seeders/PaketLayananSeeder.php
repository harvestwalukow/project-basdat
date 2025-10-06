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
            // Paket Utama
            [
                'nama_paket' => 'Paket Basic',
                'deskripsi' => 'Paket dasar dengan fasilitas standar untuk penitipan hewan',
                'harga_per_hari' => 150000,
                'fasilitas' => "Kamar Ber-AC\nMakan 3x sehari\nArea bermain indoor/outdoor\nLaporan harian via WA (foto)",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Paket Premium',
                'deskripsi' => 'Paket premium dengan fasilitas lengkap dan layanan ekstra',
                'harga_per_hari' => 250000,
                'fasilitas' => "Kamar Ber-AC\nMakan 3x sehari\nArea bermain indoor/outdoor\nLaporan harian via WA + VC\nSnack & Treats",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Layanan Tambahan
            [
                'nama_paket' => 'Grooming Premium',
                'deskripsi' => 'Layanan spa lengkap untuk hewan kesayangan Anda',
                'harga_per_hari' => 150000,
                'fasilitas' => "Spa lengkap\nPotong kuku\nBersih telinga\nAromaterapi",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Kolam Renang',
                'deskripsi' => 'Layanan berenang untuk kesehatan dan kesenangan hewan',
                'harga_per_hari' => 100000,
                'fasilitas' => "Sesi berenang dengan pengawasan\nPeralatan keamanan standar\nHanduk dan perawatan setelah berenang",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Pick-up & Delivery',
                'deskripsi' => 'Layanan antar jemput hewan peliharaan Anda',
                'harga_per_hari' => 100000,
                'fasilitas' => "Layanan antar jemput dalam radius 10km\nKendaraan ber-AC\nPenanganan hewan yang aman",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Enrichment Extra',
                'deskripsi' => 'Sesi stimulasi mental dan fisik untuk hewan',
                'harga_per_hari' => 45000,
                'fasilitas' => "Sesi stimulasi 15–20 menit\nPuzzle feeder\nLick mat\nSniffing activities",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

