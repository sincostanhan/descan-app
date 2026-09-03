<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatisticTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'is_mapped',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_mapped' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Admin BPS yang membuat template ini.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Semua node header (row & column) milik template ini.
     */
    public function headers(): HasMany
    {
        return $this->hasMany(StatisticTemplateHeader::class);
    }

    /**
     * Hanya root header axis='row' (level teratas hierarki baris).
     */
    public function rowHeaders(): HasMany
    {
        return $this->headers()->where('axis', 'row')->whereNull('parent_id')->orderBy('order');
    }

    /**
     * Hanya root header axis='column' (level teratas hierarki kolom).
     */
    public function columnHeaders(): HasMany
    {
        return $this->headers()->where('axis', 'column')->whereNull('parent_id')->orderBy('order');
    }

    /**
     * Titik temu row-leaf x column-leaf yang boleh diisi Admin Kelurahan.
     */
    public function cells(): HasMany
    {
        return $this->hasMany(StatisticTemplateCell::class);
    }

    /**
     * Semua tabel yang sudah diisi Kelurahan berdasarkan template ini.
     */
    public function entries(): HasMany
    {
        return $this->hasMany(StatisticTableEntry::class);
    }

    /**
     * Riwayat perubahan struktur template.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(StatisticTemplateLog::class);
    }

    /**
     * Kedalaman maksimum hierarki header pada axis tertentu.
     * Dipakai frontend untuk menghitung rowspan sisa header yang leaf-nya "dangkal".
     */
    public function maxHeaderDepth(string $axis): int
    {
        $leaves = $this->headers()->where('axis', $axis)->where('is_leaf', true)->get();

        if ($leaves->isEmpty()) {
            return 1;
        }

        return $leaves->max(fn (StatisticTemplateHeader $leaf) => $leaf->depth()) + 1;
    }
}