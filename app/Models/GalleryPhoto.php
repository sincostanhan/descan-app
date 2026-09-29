<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    // use BelongsToVillage; // Gunakan Trait

    // protected $guarded = ['id'];
    // protected $fillable = ['gallery_id', 'foto_path'];
    protected $fillable = ['gallery_id', 'foto_path', 'tampil_beranda'];

    protected function casts(): array
    {
        return ['tampil_beranda' => 'boolean'];
    }

    // Relasi: Satu Photo milik satu Gallery
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    /**
     * Foto tidak punya village_id sendiri, jadi dibatasi lewat induknya (Gallery),
     * yang sudah otomatis terfilter oleh global scope BelongsToVillage.
     * Foto milik kelurahan lain → 404.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->whereHas('gallery')
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->firstOrFail();
    }
}
