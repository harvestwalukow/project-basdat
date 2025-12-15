<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimHewan extends Model
{
    protected $table = 'dim_hewan';
    protected $primaryKey = 'hewan_key';
    public $timestamps = false;
    
    protected $fillable = [
        'id_hewan',
        'nama_hewan',
        'jenis_hewan',
        'ras',
        'umur',
        'jenis_kelamin',
        'berat',
    ];
}
