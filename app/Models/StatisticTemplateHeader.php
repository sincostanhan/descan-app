<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatisticTemplateHeader extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'statistic_template_id',
        'axis',
        'parent_id',
        'label',
        'key',
        'data_type',
        'is_leaf',
        'rt_value',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_leaf' => 'boolean',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(StatisticTemplate::class, 'statistic_template_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(StatisticTemplateHeader::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(StatisticTemplateHeader::class, 'parent_id')->orderBy('order');
    }

    /**
     * Jumlah leaf descendant di bawah node ini.
     * Dipakai sebagai colspan (axis='column') atau rowspan (axis='row') saat render tabel.
     */
    public function getLeafSpanAttribute(): int
    {
        if ($this->is_leaf) {
            return 1;
        }

        // Hindari N+1: pastikan relasi 'children' sudah di-eager-load di controller/action
        // lewat load('headers.children') sebelum accessor ini dipanggil dalam loop.
        return $this->children->sum(fn (StatisticTemplateHeader $child) => $child->leaf_span) ?: 1;
    }

    /**
     * Kedalaman node ini dari root (root = 0).
     */
    public function depth(): int
    {
        return $this->parent ? $this->parent->depth() + 1 : 0;
    }

    /**
     * Telusuri node ini ke atas (termasuk diri sendiri) sampai menemukan rt_value terisi.
     * Return null jika sepanjang jalur tidak ada rt_value sama sekali (berarti bukan baris representasi RT).
     */
    public function resolveRtValue(): ?string
    {
        if (!empty($this->rt_value)) {
            return $this->rt_value;
        }

        return $this->parent ? $this->parent->resolveRtValue() : null;
    }
}