<?php

namespace App\Models\DW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactTransaksi extends Model
{
    use HasFactory;

    protected $table = 'fact_transaksi';
    
    protected $fillable = [
        'id_penitipan',
        'tanggal_masuk',
        'jumlah_hari',
        'total_biaya',
        'id_pemilik',
        'id_hewan',
        'id_paket',
        'id_staff',
        'status',
        'metode_pembayaran',
        'status_pembayaran',
        'waktu_key',
        'customer_key',
        'hewan_key',
        'paket_key',
        'staff_key',
        'status_key',
        'pembayaran_key',
        'jumlah_transaksi'
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
    ];

    // Relationships to Operational/Transactional Tables
    public function pemilik()
    {
        return $this->belongsTo(\App\Models\Pengguna::class, 'id_pemilik', 'id_pengguna');
    }

    public function hewan()
    {
        return $this->belongsTo(\App\Models\Hewan::class, 'id_hewan', 'id_hewan');
    }

    public function paket()
    {
        return $this->belongsTo(\App\Models\PaketLayanan::class, 'id_paket', 'id_paket');
    }
    
    public function staff()
    {
        return $this->belongsTo(\App\Models\Pengguna::class, 'id_staff', 'id_pengguna');
    }

    public function detailPenitipan()
    {
        return $this->hasMany(\App\Models\DetailPenitipan::class, 'id_penitipan', 'id_penitipan');
    }
    
    public function penitipan()
    {
        return $this->belongsTo(\App\Models\Penitipan::class, 'id_penitipan', 'id_penitipan');
    }

    // Accessors
    public function getTanggalKeluarAttribute()
    {
        if ($this->tanggal_masuk && $this->jumlah_hari) {
            return $this->tanggal_masuk->copy()->addDays($this->jumlah_hari);
        }
        return null;
    }
}
