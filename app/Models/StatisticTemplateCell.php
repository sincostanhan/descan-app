<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatisticTemplateCell extends Model
{
    protected $fillable = [
        'statistic_template_id',
        'row_header_id',
        'column_header_id',
        'is_locked',
    ];

    protected function casts(): array
    {
        return [
            'is_locked' => 'boolean',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(StatisticTemplate::class, 'statistic_template_id');
    }

    public function rowHeader(): BelongsTo
    {
        return $this->belongsTo(StatisticTemplateHeader::class, 'row_header_id');
    }

    public function columnHeader(): BelongsTo
    {
        return $this->belongsTo(StatisticTemplateHeader::class, 'column_header_id');
    }

    /**
     * Semua nilai yang pernah diisi Kelurahan untuk sel ini (satu per entry/kelurahan).
     */
    public function values(): HasMany
    {
        return $this->hasMany(StatisticTableValue::class, 'statistic_template_cell_id');
    }
}