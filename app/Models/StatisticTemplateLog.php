<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatisticTemplateLog extends Model
{
    protected $fillable = [
        'statistic_template_id',
        'affected_header_id',
        'changed_by',
        'change_type',
        'description',
    ];

    /**
     * withTrashed(): header yang dicatat log ini bisa saja sudah soft-deleted (kasus 'removed'),
     * tetap perlu bisa diakses untuk ditampilkan/dicek statusnya di modal riwayat.
     */
    public function affectedHeader(): BelongsTo
    {
        return $this->belongsTo(StatisticTemplateHeader::class, 'affected_header_id')->withTrashed();
    }

    /**
     * Tombol "Pulihkan" HANYA relevan untuk log jenis removed, DAN header-nya
     * masih benar-benar berstatus soft-deleted saat ini (belum pernah dipulihkan sebelumnya).
     */
    public function canBeRestored(): bool
    {
        if (!in_array($this->change_type, ['row_removed', 'column_removed'])) {
            return false;
        }

        return $this->affectedHeader?->trashed() ?? false;
    }

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