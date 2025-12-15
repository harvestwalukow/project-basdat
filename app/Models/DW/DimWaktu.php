<?php

namespace App\Models\DW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimWaktu extends Model
{
    use HasFactory;

    protected $table = 'dim_waktu';
    protected $primaryKey = 'waktu_key';
    
    protected $fillable = [
        'tanggal',
        'hari',
        'bulan',
        'tahun',
        'quarter'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
