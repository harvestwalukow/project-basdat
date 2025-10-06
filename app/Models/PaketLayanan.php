<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketLayanan extends Model
{
    protected $table = 'paket_layanan';
    protected $primaryKey = 'id_paket';
    
    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga_per_hari',
        'fasilitas',
        'is_active',
    ];

    protected $casts = [
        'harga_per_hari' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function detailPenitipan()
    {
        return $this->hasMany(DetailPenitipan::class, 'id_paket', 'id_paket');
    }
}

