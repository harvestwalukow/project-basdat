<?php

namespace App\Models\DW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimHewan extends Model
{
    use HasFactory;

    protected $table = 'dim_hewan';
    protected $primaryKey = 'hewan_key';
    
    protected $fillable = [
        'id_hewan',
        'nama_hewan',
        'jenis_hewan',
        'ras',
        'umur',
        'jenis_kelamin',
        'berat'
    ];
}
