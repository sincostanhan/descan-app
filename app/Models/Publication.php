<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use BelongsToVillage; // Gunakan Trait

    protected $guarded = ['id'];

    protected $fillable = [
        'title', 
        'description', 
        'file_path',
        // cover
        'cover_path'
    ];
}
