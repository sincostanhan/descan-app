<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use BelongsToVillage; // Gunakan Trait

    // protected $guarded = ['id'];
    // protected $fillable = ['nama_kegiatan'];
    protected $fillable = ['judul'];

    // Relasi: Satu Gallery punya banyak Photo
    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class);
    }
}
