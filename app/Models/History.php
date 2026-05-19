<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use BelongsToVillage; // Gunakan Trait

    // protected $guarded = ['id'];

    protected $fillable = [
        'penulis',
        'konten',
        'is_active',
    ];
}
