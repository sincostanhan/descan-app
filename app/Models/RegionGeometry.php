<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;

class RegionGeometry extends Model
{
    use BelongsToVillage;

    protected $fillable = [
        'village_id',
        'rt',
        'rw',
        'geojson',
    ];

    protected function casts(): array
    {
        return [
            'geojson' => 'array',
        ];
    }
}