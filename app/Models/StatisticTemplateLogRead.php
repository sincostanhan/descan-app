<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatisticTemplateLogRead extends Model
{
    use BelongsToVillage;

    protected $fillable = [
        'statistic_template_log_id',
        'village_id',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function log(): BelongsTo
    {
        return $this->belongsTo(StatisticTemplateLog::class, 'statistic_template_log_id');
    }
}