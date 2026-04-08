<?php

namespace App\Actions;

use App\Models\GalleryPhoto;
use Illuminate\Support\Facades\Storage;

class DeleteGalleryPhoto
{
    public function handle(GalleryPhoto $photo)
    {
        // Hapus file fisik dari storage
        if (Storage::disk('public')->exists($photo->foto_path)) {
            Storage::disk('public')->delete($photo->foto_path);
        }

        // Hapus data foto dari database
        $photo->delete();
    }
}