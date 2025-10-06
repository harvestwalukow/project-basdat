<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hewan extends Model
{
    protected $table = 'hewan';
    protected $primaryKey = 'id_hewan';
    
    protected $fillable = [
        'id_pemilik',
        'nama_hewan',
        'jenis_hewan',
        'ras',
        'umur',
        'jenis_kelamin',
        'berat',
        'kondisi_khusus',
        'catatan_medis',
    ];

    protected $casts = [
        'berat' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function pemilik()
    {
        return $this->belongsTo(Pengguna::class, 'id_pemilik', 'id_pengguna');
    }

    public function penitipan()
    {
        return $this->hasMany(Penitipan::class, 'id_hewan', 'id_hewan');
    }
}

