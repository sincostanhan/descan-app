<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;
    use BelongsToVillage; // Gunakan Trait

    // protected $guarded = ['id'];


    protected $fillable = [
        'deskripsi',
        'batas_utara',
        'batas_barat',
        'batas_selatan',
        'batas_timur',
        'visi',
        'misi'
    ];
}
