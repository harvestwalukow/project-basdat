<?php

namespace App\Observers\DW;

use App\Models\Pengguna;
use App\Models\DW\DimCustomer;
use App\Models\DW\DimStaff;

class PenggunaObserver
{
    public function created(Pengguna $pengguna): void
    {
        $this->syncToDim($pengguna);
    }

    public function updated(Pengguna $pengguna): void
    {
        $this->syncToDim($pengguna);
    }
    
    // Deleting user: usually keep dim record or mark inactive.
    // We'll leave it as is.

    private function syncToDim(Pengguna $pengguna)
    {
        if ($pengguna->role === 'pet_owner') {
             DimCustomer::updateOrCreate(
                ['id_pengguna' => $pengguna->id_pengguna],
                [
                    'nama_lengkap' => $pengguna->nama_lengkap,
                    'email' => $pengguna->email,
                    'alamat' => $pengguna->alamat,
                    'no_telepon' => $pengguna->no_telepon
                ]
            );
        } elseif ($pengguna->role === 'staff' || $pengguna->role === 'admin') {
            DimStaff::updateOrCreate(
                ['id_pengguna' => $pengguna->id_pengguna],
                [
                    'nama_lengkap' => $pengguna->nama_lengkap,
                    'email' => $pengguna->email,
                    'role' => $pengguna->role,
                    'specialization' => $pengguna->specialization
                ]
            );
        }
    }
}
