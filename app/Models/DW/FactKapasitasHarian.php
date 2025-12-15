<?php

namespace App\Models\DW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactKapasitasHarian extends Model
{
    use HasFactory;

    protected $table = 'fact_kapasitas_harian';
    
    protected $fillable = [
        'waktu_key',
        'jumlah_hewan',
        'tanggal_masuk'
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
    ];
}
