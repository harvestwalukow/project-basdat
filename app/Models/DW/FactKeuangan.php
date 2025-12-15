<?php

namespace App\Models\DW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactKeuangan extends Model
{
    use HasFactory;

    protected $table = 'fact_keuangan';
    
    protected $fillable = [
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_pembayaran',
        'status_pembayaran',
        'tanggal_lookup',
        'waktu_key',
        'pembayaran_key',
        'jumlah_transaksi'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'tanggal_lookup' => 'datetime',
    ];

    public function dimPembayaran()
    {
        return $this->belongsTo(DimPembayaran::class, 'pembayaran_key', 'pembayaran_key');
    }
    
    public function dimWaktu()
    {
        return $this->belongsTo(DimWaktu::class, 'waktu_key', 'waktu_key');
    }
}
