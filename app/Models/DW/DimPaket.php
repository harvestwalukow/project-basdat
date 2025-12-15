<?php

namespace App\Models\DW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimPaket extends Model
{
    use HasFactory;

    protected $table = 'dim_paket';
    protected $primaryKey = 'paket_key';
    
    protected $fillable = [
        'id_paket',
        'nama_paket',
        'harga_per_hari',
        'is_active'
    ];

    public function operationalPaket()
    {
        return $this->belongsTo(\App\Models\PaketLayanan::class, 'id_paket', 'id_paket');
    }

    public function getDeskripsiAttribute($value)
    {
        // If 'deskripsi' column exists in table and is not null, return it.
        if ($value) return $value;
        // Fallback to operational table using id_paket
        return $this->operationalPaket ? $this->operationalPaket->deskripsi : '-';
    }
}
