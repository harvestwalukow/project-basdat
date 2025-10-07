<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'no_telepon',
        'alamat',
        'role',
        'specialization',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function hewans()
    {
        return $this->hasMany(Hewan::class, 'id_pemilik', 'id_pengguna');
    }

    public function penitipans()
    {
        return $this->hasMany(Penitipan::class, 'id_pemilik', 'id_pengguna');
    }

    public function staffPenitipans()
    {
        return $this->hasMany(Penitipan::class, 'id_staff', 'id_pengguna');
    }

    public function updateKondisis()
    {
        return $this->hasMany(UpdateKondisi::class, 'id_staff', 'id_pengguna');
    }
}

