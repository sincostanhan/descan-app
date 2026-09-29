<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotensiWisataPhoto extends Model
{
    // protected $fillable = ['potensi_wisata_id', 'foto_path'];
    protected $fillable = ['potensi_wisata_id', 'foto_path', 'tampil_beranda'];

    protected function casts(): array
    {
        return ['tampil_beranda' => 'boolean'];
    }

    public function potensiWisata()
    {
        return $this->belongsTo(PotensiWisata::class);
    }

    /**
     * Sama seperti GalleryPhoto: dibatasi lewat induknya (PotensiWisata)
     * yang sudah terfilter global scope BelongsToVillage.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->whereHas('potensiWisata')
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->firstOrFail();
    }
}