<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatisticTableValue extends Model
{
    protected $fillable = [
        'statistic_table_entry_id',
        'statistic_template_cell_id',
        'value',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(StatisticTableEntry::class, 'statistic_table_entry_id');
    }

    public function templateCell(): BelongsTo
    {
        return $this->belongsTo(StatisticTemplateCell::class, 'statistic_template_cell_id');
    }
}