<?php

namespace App\Models\DW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimPembayaran extends Model
{
    use HasFactory;

    protected $table = 'dim_pembayaran';
    protected $primaryKey = 'pembayaran_key';
    
    protected $fillable = [
        'metode_pembayaran',
        'status_pembayaran'
    ];
}
