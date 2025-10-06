<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penitipan extends Model
{
    protected $table = 'penitipan';
    protected $primaryKey = 'id_penitipan';
    
    protected $fillable = [
        'id_hewan',
        'id_pemilik',
        'id_staff',
        'tanggal_masuk',
        'tanggal_keluar',
        'status',
        'catatan_khusus',
        'total_biaya',
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'tanggal_keluar' => 'datetime',
        'total_biaya' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function hewan()
    {
        return $this->belongsTo(Hewan::class, 'id_hewan', 'id_hewan');
    }

    public function pemilik()
    {
        return $this->belongsTo(Pengguna::class, 'id_pemilik', 'id_pengguna');
    }

    public function staff()
    {
        return $this->belongsTo(Pengguna::class, 'id_staff', 'id_pengguna');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_penitipan', 'id_penitipan');
    }

    public function detailPenitipan()
    {
        return $this->hasMany(DetailPenitipan::class, 'id_penitipan', 'id_penitipan');
    }

    public function updateKondisi()
    {
        return $this->hasMany(UpdateKondisi::class, 'id_penitipan', 'id_penitipan');
    }
}

