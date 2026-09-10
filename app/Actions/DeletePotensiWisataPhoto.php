<?php

namespace App\Actions;

use App\Models\PotensiWisataPhoto;
use Illuminate\Support\Facades\Storage;

class DeletePotensiWisataPhoto
{
    public function handle(PotensiWisataPhoto $photo)
    {
        if (Storage::disk('public')->exists($photo->foto_path)) {
            Storage::disk('public')->delete($photo->foto_path);
        }

        $photo->delete();
    }
}