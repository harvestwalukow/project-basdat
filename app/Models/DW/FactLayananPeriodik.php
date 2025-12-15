<?php

namespace App\Models\DW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactLayananPeriodik extends Model
{
    use HasFactory;

    protected $table = 'fact_layanan_periodik';
    
    protected $fillable = [
        'id_paket',
        'jumlah_paket',
        'total_pendapatan',
        'paket_key'
    ];

    public function dimPaket()
    {
        return $this->belongsTo(DimPaket::class, 'paket_key', 'paket_key');
    }
}
