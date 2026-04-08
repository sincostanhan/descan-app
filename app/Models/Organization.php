<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $guarded = ['id'];

    // Mengubah JSON menjadi Array secara otomatis
    protected $casts = [
        'daftar_rw' => 'array',
        'daftar_rt' => 'array',
    ];
}
