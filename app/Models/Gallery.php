<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['nama_kegiatan'];

    // Relasi: Satu Gallery punya banyak Photo
    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class);
    }
}
