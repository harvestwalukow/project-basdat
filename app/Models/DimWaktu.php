<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DimWaktu extends Model
{    
    /**
     * The table associated with the model.
     */
    protected $table = 'dim_waktu';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'waktu_key';
    
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tanggal',
        'hari',
        'bulan',
        'tahun',
        'quarter',
    ];
    
    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get waktu_key for a given date.
     */
    public static function getKeyForDate($date)
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        
        return static::where('tanggal', $date->format('Y-m-d'))->value('waktu_key');
    }

    /**
     * Get waktu_key for today.
     */
    public static function getTodayKey()
    {
        return static::getKeyForDate(Carbon::today());
    }

    /**
     * Relationship to fact_transaksi.
     */
    public function factTransaksi()
    {
        return $this->hasMany(FactTransaksi::class, 'waktu_key', 'waktu_key');
    }

    /**
     * Relationship to fact_kapasitas_harian.
     */
    public function factKapasitasHarian()
    {
        return $this->hasMany(FactKapasitasHarian::class, 'waktu_key', 'waktu_key');
    }
}
