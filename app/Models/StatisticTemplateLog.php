<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatisticTemplateLog extends Model
{
    protected $fillable = [
        'statistic_template_id',
        'changed_by',
        'change_type',
        'description',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(StatisticTemplate::class, 'statistic_template_id');
    }

    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(StatisticTemplateLogRead::class, 'statistic_template_log_id');
    }
}