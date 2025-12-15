<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactKeuanganPeriodik extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'fact_keuangan_periodik';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'keuangan_key';
    
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'periode_yyyymm',
        'tahun',
        'bulan',
        'total_revenue',
        'jumlah_transaksi',
        'avg_transaksi',
    ];
    
    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'total_revenue' => 'decimal:2',
        'avg_transaksi' => 'decimal:2',
        'jumlah_transaksi' => 'integer',
    ];

    /**
     * Get revenue data for the last N months.
     */
    public static function getRevenueForLastMonths($months = 12)
    {
        return static::orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->limit($months)
            ->get()
            ->sortBy(function ($item) {
                return $item->tahun * 100 + $item->bulan;
            })
            ->values();
    }

    /**
     * Get revenue for a specific month and year.
     */
    public static function getRevenueForMonth($year, $month)
    {
        return static::where('tahun', $year)
            ->where('bulan', $month)
            ->first();
    }

    /**
     * Get total revenue within a date range.
     */
    public static function getTotalRevenueBetween($startYear, $startMonth, $endYear, $endMonth)
    {
        return static::where(function ($query) use ($startYear, $startMonth, $endYear, $endMonth) {
            $query->where(function ($q) use ($startYear, $startMonth) {
                $q->where('tahun', '>', $startYear)
                  ->orWhere(function ($q2) use ($startYear, $startMonth) {
                      $q2->where('tahun', $startYear)
                         ->where('bulan', '>=', $startMonth);
                  });
            })
            ->where(function ($q) use ($endYear, $endMonth) {
                $q->where('tahun', '<', $endYear)
                  ->orWhere(function ($q2) use ($endYear, $endMonth) {
                      $q2->where('tahun', $endYear)
                         ->where('bulan', '<=', $endMonth);
                  });
            });
        })
        ->sum('total_revenue');
    }
}
