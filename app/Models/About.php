<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

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
