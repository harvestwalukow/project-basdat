<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('pengguna')->get();
        if ($users->isEmpty()) {
            $this->command->warn("No users found in key 'pengguna' table.");
        }
        foreach ($users as $u) {
            $this->command->info("User: {$u->email} - Role: {$u->role}");
        }
    }
}
