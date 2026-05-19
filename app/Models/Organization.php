<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use BelongsToVillage; // Gunakan Trait

    protected $guarded = ['id'];

    // Mengubah JSON menjadi Array secara otomatis
    protected $casts = [
        'daftar_rw' => 'array',
        'daftar_rt' => 'array',
    ];
}
