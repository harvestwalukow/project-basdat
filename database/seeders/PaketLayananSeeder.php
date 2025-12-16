<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaketLayananSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = Carbon::parse('2025-10-06 08:38:35');

        $pakets = [
            [
                'id_paket' => 1,
                'nama_paket' => 'Paket Basic',
                'deskripsi' => 'Paket dasar dengan fasilitas standar untuk penitipan hewan',
                'harga_per_hari' => 150000.00,
                'fasilitas' => "Kamar Ber-AC\nMakan 3x sehari\nArea bermain indoor/outdoor\nLaporan harian via WA (foto)",
                'is_active' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'id_paket' => 2,
                'nama_paket' => 'Paket Premium',
                'deskripsi' => 'Paket premium dengan fasilitas lengkap dan layanan ekstra',
                'harga_per_hari' => 250000.00,
                'fasilitas' => "Kamar Ber-AC\nMakan 3x sehari\nArea bermain indoor/outdoor\nLaporan harian via WA + VC\nSnack & Treats",
                'is_active' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'id_paket' => 3,
                'nama_paket' => 'Grooming Premium',
                'deskripsi' => 'Layanan spa lengkap untuk hewan kesayangan Anda',
                'harga_per_hari' => 150000.00,
                'fasilitas' => "Spa lengkap\nPotong kuku\nBersih telinga\nAromaterapi",
                'is_active' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'id_paket' => 4,
                'nama_paket' => 'Kolam Renang',
                'deskripsi' => 'Layanan berenang untuk kesehatan dan kesenangan hewan',
                'harga_per_hari' => 100000.00,
                'fasilitas' => "Sesi berenang dengan pengawasan\nPeralatan keamanan standar\nHanduk dan perawatan setelah berenang",
                'is_active' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'id_paket' => 5,
                'nama_paket' => 'Pick-up & Delivery',
                'deskripsi' => 'Layanan antar jemput hewan peliharaan Anda',
                'harga_per_hari' => 100000.00,
                'fasilitas' => "Layanan antar jemput dalam radius 10km\nKendaraan ber-AC\nPenanganan hewan yang aman",
                'is_active' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'id_paket' => 6,
                'nama_paket' => 'Enrichment Extra',
                'deskripsi' => 'Sesi stimulasi mental dan fisik untuk hewan',
                'harga_per_hari' => 45000.00,
                'fasilitas' => "Sesi stimulasi 15–20 menit\nPuzzle feeder\nLick mat\nSniffing activities",
                'is_active' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ];

        foreach ($pakets as $paket) {
            DB::table('paket_layanan')->updateOrInsert(
                ['id_paket' => $paket['id_paket']],
                $paket
            );
        }
    }
}
