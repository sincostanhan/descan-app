<?php

namespace App\Actions;

use App\Models\PotensiWisata;

class CreatePotensiWisata
{
    public function handle(array $attributes)
    {
        $item = PotensiWisata::create([
            'nama' => $attributes['nama'],
            'kategori' => $attributes['kategori'],
        ]);

        if (isset($attributes['photos'])) {
            $item->photos()->createMany(
                collect($attributes['photos'])->map(function ($photo) {
                    return ['foto_path' => $photo->store('potensi_wisata_photos', 'public')];
                })
            );
        }

        return $item;
    }
}