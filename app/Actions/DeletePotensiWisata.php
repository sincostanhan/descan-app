<?php

namespace App\Actions;

use App\Models\PotensiWisata;
use Illuminate\Support\Facades\Storage;

class DeletePotensiWisata
{
    public function handle(PotensiWisata $item)
    {
        foreach ($item->photos as $photo) {
            if (Storage::disk('public')->exists($photo->foto_path)) {
                Storage::disk('public')->delete($photo->foto_path);
            }
        }

        $item->delete();
    }
}