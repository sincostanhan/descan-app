<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatisticChart extends Model
{
    use HasFactory, BelongsToVillage;

    protected $fillable = [
        'village_id',
        // 'statistical_table_id',
        'statistic_table_entry_id',
        'title',
        'chart_type',
        'x_axis_column',
        'y_axis_columns',
        'y_axis_colors',
        'has_total_row',
        'is_active',
    ];

    protected $casts = [
        'y_axis_columns' => 'array',
        'y_axis_colors' => 'array',
        'has_total_row' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke model StatisticalTable
     * Satu grafik dimiliki oleh satu tabel statistik
     */
    // public function statisticalTable(): BelongsTo
    // {
    //     return $this->belongsTo(StatisticalTable::class);
    // }
    /**
     * Relasi ke model StatisticTableEntry (menggantikan StatisticalTable yang sudah dihapus)
     * Satu grafik dimiliki oleh satu entry tabel statistik.
     */
    public function statisticTableEntry(): BelongsTo
    {
        return $this->belongsTo(StatisticTableEntry::class, 'statistic_table_entry_id');
    }
}
