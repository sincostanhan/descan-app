<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;

class MetadataStatistik extends Model
{
    use BelongsToVillage;

    protected $guarded = ['id'];

    protected $fillable = [
        'village_id',
        'title',
        'file_path',
        // cover
        'cover_path',
    ];
}