<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatisticTemplateLogRead extends Model
{
    use BelongsToVillage;

    /**
     * Tabel ini sengaja tidak punya created_at/updated_at — 'read_at' sudah cukup
     * merepresentasikan kapan log ditandai terbaca, jadi timestamp bawaan Eloquent dimatikan.
     */
    public $timestamps = false;

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