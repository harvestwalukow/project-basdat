<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimPaket extends Model
{
    protected $table = 'dim_paket';
    protected $primaryKey = 'paket_key';
    public $timestamps = false;
    
    protected $fillable = [
        'id_paket',
        'nama_paket',
        'harga_per_hari',
        'is_active',
    ];
}
