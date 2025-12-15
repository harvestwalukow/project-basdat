<?php

namespace App\Models\DW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimCustomer extends Model
{
    use HasFactory;

    protected $table = 'dim_customer';
    protected $primaryKey = 'customer_key';
    
    protected $fillable = [
        'id_pengguna',
        'nama_lengkap',
        'email',
        'alamat',
        'no_telepon'
    ];
}
