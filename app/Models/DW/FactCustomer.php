<?php

namespace App\Models\DW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactCustomer extends Model
{
    use HasFactory;

    protected $table = 'fact_customer';
    
    protected $fillable = [
        'id_pemilik',
        'total_transaksi',
        'total_pengeluaran',
        'customer_key'
    ];

    public function dimCustomer()
    {
        return $this->belongsTo(DimCustomer::class, 'customer_key', 'customer_key');
    }
}
