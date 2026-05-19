<?php

namespace App\Actions;

use App\Models\Gallery;

class CreateGallery
{
    public function handle(array $attributes)
    {
        // dd($attributes);

        // 1. Simpan nama kegiatan (otomatis memicu Observer)
        $gallery = Gallery::create([
            // 'nama_kegiatan' => $attributes['nama_kegiatan']
            'judul' => $attributes['judul']
        ]);

        // 2. Logika upload fisik file gambar
        if (isset($attributes['photos'])) {
            $gallery->photos()->createMany(
                collect($attributes['photos'])->map(function ($photo) {
                    // Simpan file ke storage dan kembalikan array path-nya
                    return [
                        'foto_path' => $photo->store('gallery_photos', 'public')
                    ];
                })
            );
        }

        return $gallery;
    }
}