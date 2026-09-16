<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use BelongsToVillage; // Gunakan Trait

    // protected $guarded = ['id'];

    protected $fillable = [
        'village_id',
        'village_name',
        'kecamatan',
        'village_logo',
        'theme_name',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }
}
