<?php

namespace App\Observers\DW;

use App\Models\Hewan;
use App\Models\DW\DimHewan;

class HewanObserver
{
    public function created(Hewan $hewan): void
    {
        $this->syncToDim($hewan);
    }

    public function updated(Hewan $hewan): void
    {
        $this->syncToDim($hewan);
    }

    private function syncToDim(Hewan $hewan)
    {
        DimHewan::updateOrCreate(
            ['id_hewan' => $hewan->id_hewan],
            [
                'nama_hewan' => $hewan->nama_hewan,
                'jenis_hewan' => $hewan->jenis_hewan,
                'ras' => $hewan->ras,
                'umur' => $hewan->umur,
                'jenis_kelamin' => $hewan->jenis_hewan, // wait, jenis_kelamin is mapped to jenis_hewan? No. 
                // In er_basdat.sql `hewan` table has `jenis_kelamin`.
                // My logic should map correctly.
                // Let's check `hewan` table structure from file view earlier.
                // `hewan`: id_hewan, id_pemilik, nama_hewan, jenis_hewan, ras, umur, jenis_kelamin, berat...
                // So yes, map directly.
            ]
        );
        
        // Use separate update because of array keys in updateOrCreate above...
        // Actually, updateOrCreate takes (search, values).
        // I need to be careful not to typo.
        
        DimHewan::updateOrCreate(
            ['id_hewan' => $hewan->id_hewan],
            [
                'nama_hewan' => $hewan->nama_hewan,
                'jenis_hewan' => $hewan->jenis_hewan,
                'ras' => $hewan->ras,
                'umur' => $hewan->umur,
                'jenis_kelamin' => $hewan->jenis_kelamin,
                'berat' => $hewan->berat
            ]
        );
    }
}
