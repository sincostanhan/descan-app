<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    use HasFactory;
    use BelongsToVillage; // Gunakan Trait

    // protected $guarded = ['id'];


    protected $fillable = [
        'latar_belakang',
        'tujuan',
        'output',
    ];
}
