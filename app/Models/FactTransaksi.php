<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactTransaksi extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'fact_transaksi';
    
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'waktu_key',
        'customer_key',
        'hewan_key',
        'paket_key',
        'staff_key',
        'status_key',
        'pembayaran_key',
        'jumlah_hari',
        'total_biaya',
        'jumlah_transaksi',
        'id_penitipan',
        'tanggal_masuk',
        'id_pemilik',
        'id_hewan',
        'id_paket',
        'id_staff',
        'status',
        'metode_pembayaran',
        'status_pembayaran',
    ];
    
    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'total_biaya' => 'decimal:2',
    ];

    /**
     * Get the customer dimension for this transaction.
     */
    public function dimCustomer()
    {
        return $this->belongsTo(DimCustomer::class, 'customer_key', 'customer_key');
    }

    /**
     * Get the hewan dimension for this transaction.
     */
    public function dimHewan()
    {
        return $this->belongsTo(DimHewan::class, 'hewan_key', 'hewan_key');
    }

    /**
     * Get the paket dimension for this transaction.
     */
    public function dimPaket()
    {
        return $this->belongsTo(DimPaket::class, 'paket_key', 'paket_key');
    }

    /**
     * Get the waktu dimension for this transaction.
     */
    public function dimWaktu()
    {
        return $this->belongsTo(DimWaktu::class, 'waktu_key', 'waktu_key');
    }

    /**
     * Get the status dimension for this transaction.
     */
    public function dimStatus()
    {
        return $this->belongsTo(DimStatusPenitipan::class, 'status_key', 'status_key');
    }
}
