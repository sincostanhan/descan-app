<?php

namespace App\Actions;

use App\Models\PotensiWisata;

class UpdatePotensiWisata
{
    public function handle(PotensiWisata $item, array $attributes)
    {
        $item->update([
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