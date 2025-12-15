<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactKapasitasHarian extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'fact_kapasitas_harian';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'kapasitas_key';
    
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'waktu_key',
        'total_penitipan',
        'penitipan_aktif',
        'penitipan_pending',
        'penitipan_selesai',
        'penitipan_dibatalkan',
        'total_hewan',
    ];
    
    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'total_penitipan' => 'integer',
        'penitipan_aktif' => 'integer',
        'penitipan_pending' => 'integer',
        'penitipan_selesai' => 'integer',
        'penitipan_dibatalkan' => 'integer',
        'total_hewan' => 'integer',
    ];

    /**
     * Get the waktu dimension for this capacity record.
     */
    public function dimWaktu()
    {
        return $this->belongsTo(DimWaktu::class, 'waktu_key', 'waktu_key');
    }

    /**
     * Get capacity for a specific date.
     */
    public static function getCapacityForDate($date)
    {
        $waktuKey = DimWaktu::where('tanggal', $date)->value('waktu_key');
        
        if (!$waktuKey) {
            return null;
        }
        
        return static::where('waktu_key', $waktuKey)->first();
    }

    /**
     * Get latest capacity data.
     */
    public static function getLatestCapacity()
    {
        return static::join('dim_waktu', 'fact_kapasitas_harian.waktu_key', '=', 'dim_waktu.waktu_key')
            ->orderBy('dim_waktu.tanggal', 'desc')
            ->select('fact_kapasitas_harian.*')
            ->first();
    }
}
