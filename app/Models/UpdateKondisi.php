<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateKondisi extends Model
{
    protected $table = 'update_kondisi';
    protected $primaryKey = 'id_update';
    
    public $timestamps = false;
    
    protected $fillable = [
        'id_penitipan',
        'id_staff',
        'kondisi_hewan',
        'aktivitas_hari_ini',
        'catatan_staff',
        'foto_hewan',
        'waktu_update',
    ];

    protected $casts = [
        'waktu_update' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Relations
    public function penitipan()
    {
        return $this->belongsTo(Penitipan::class, 'id_penitipan', 'id_penitipan');
    }

    public function staff()
    {
        return $this->belongsTo(Pengguna::class, 'id_staff', 'id_pengguna');
    }
}

