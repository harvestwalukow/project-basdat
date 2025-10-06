<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenitipan extends Model
{
    protected $table = 'detail_penitipan';
    protected $primaryKey = 'id_detail';
    
    public $timestamps = false;
    
    protected $fillable = [
        'id_penitipan',
        'id_paket',
        'jumlah_hari',
        'subtotal',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    // Relations
    public function penitipan()
    {
        return $this->belongsTo(Penitipan::class, 'id_penitipan', 'id_penitipan');
    }

    public function paketLayanan()
    {
        return $this->belongsTo(PaketLayanan::class, 'id_paket', 'id_paket');
    }
}

