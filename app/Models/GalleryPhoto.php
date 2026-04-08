<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    protected $fillable = ['gallery_id', 'foto_path'];

    // Relasi: Satu Photo milik satu Gallery
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
