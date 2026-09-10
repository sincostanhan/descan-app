<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;

class PotensiWisata extends Model
{
    use BelongsToVillage;

    protected $fillable = ['nama', 'kategori'];

    public function photos()
    {
        return $this->hasMany(PotensiWisataPhoto::class);
    }

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori) {
            'situs_bersejarah' => 'Situs Bersejarah',
            default => 'Potensi Wisata',
        };
    }
}