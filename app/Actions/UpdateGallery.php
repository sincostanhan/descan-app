<?php

namespace App\Actions;

use App\Models\Gallery;

class UpdateGallery
{
    public function handle(Gallery $gallery, array $attributes)
    {
        // 1. Update nama kegiatan
        $gallery->update([
            'nama_kegiatan' => $attributes['nama_kegiatan']
        ]);

        // 2. Jika ada tambahan foto baru, simpan seperti biasa
        if (isset($attributes['photos'])) {
            $gallery->photos()->createMany(
                collect($attributes['photos'])->map(function ($photo) {
                    return [
                        'foto_path' => $photo->store('gallery_photos', 'public')
                    ];
                })
            );
        }

        return $gallery;
    }
}