<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use BelongsToVillage; // Gunakan Trait

    // protected $guarded = ['id'];

    protected $fillable = [
        'village_name',
        'village_logo',
        'theme_name',
    ];
}
