<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimStatusPenitipan extends Model
{
    protected $table = 'dim_status_penitipan';
    protected $primaryKey = 'status_key';
    public $timestamps = false;
    
    protected $fillable = [
        'status',
    ];
}
