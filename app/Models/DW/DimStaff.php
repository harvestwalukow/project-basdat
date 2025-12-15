<?php

namespace App\Models\DW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimStaff extends Model
{
    use HasFactory;

    protected $table = 'dim_staff';
    protected $primaryKey = 'staff_key';
    
    protected $fillable = [
        'id_pengguna',
        'nama_lengkap',
        'email',
        'role',
        'specialization'
    ];

    public function operationalUser()
    {
        return $this->belongsTo(\App\Models\Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function getNoTeleponAttribute()
    {
        return $this->operationalUser ? $this->operationalUser->no_telepon : '-';
    }
}
