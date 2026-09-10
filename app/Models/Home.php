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
        'tim_kelurahan',
        'show_latar_belakang',
        'show_tujuan',
        'show_output',
        'show_tim',
    ];

    protected function casts(): array
    {
        return [
            'show_latar_belakang' => 'boolean',
            'show_tujuan' => 'boolean',
            'show_output' => 'boolean',
            'show_tim' => 'boolean',
        ];
    }
}
