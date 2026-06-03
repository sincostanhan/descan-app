<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    // use BelongsToVillage; // Gunakan Trait

    // protected $guarded = ['id'];
    protected $fillable = ['gallery_id', 'foto_path'];

    // Relasi: Satu Photo milik satu Gallery
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
