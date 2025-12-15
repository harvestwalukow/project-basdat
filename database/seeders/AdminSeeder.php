<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update admin user with id 9999
        DB::table('pengguna')->updateOrInsert(
            ['id_pengguna' => 9999],
            [
                'nama_lengkap' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'no_telepon' => '081234567890',
                'alamat' => 'Alamat Admin',
                'role' => 'admin',
                'specialization' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}

