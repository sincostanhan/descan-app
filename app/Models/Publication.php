<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publication extends Model
{
    use BelongsToVillage; // Gunakan Trait

    protected $guarded = ['id'];

    protected $fillable = [
        'village_id',
        'title', 
        'description', 
        'file_path',
        // cover
        'cover_path'
    ];

    /**
     * Tabel statistik yang termasuk dalam publikasi ini.
     */
    public function statisticTableEntries(): HasMany
    {
        return $this->hasMany(StatisticTableEntry::class);
    }
}
