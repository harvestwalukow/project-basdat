<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimCustomer extends Model
{
    protected $table = 'dim_customer';
    protected $primaryKey = 'customer_key';
    public $timestamps = false;
    
    protected $fillable = [
        'id_pengguna',
        'nama_lengkap',
        'email',
        'alamat',
        'no_telepon',
    ];
}
