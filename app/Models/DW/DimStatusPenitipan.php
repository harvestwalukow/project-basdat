<?php

namespace App\Models\DW;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimStatusPenitipan extends Model
{
    use HasFactory;

    protected $table = 'dim_status_penitipan';
    protected $primaryKey = 'status_key';
    
    protected $fillable = [
        'status'
    ];
}
