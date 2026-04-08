<?php

namespace App\Actions;

use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

class DeleteGallery
{
    public function handle(Gallery $gallery)
    {
        // 1. Hapus file fisik foto dari storage folder
        foreach ($gallery->photos as $photo) {
            if (Storage::disk('public')->exists($photo->foto_path)) {
                Storage::disk('public')->delete($photo->foto_path);
            }
        }

        // 2. Hapus data dari database (Memicu event 'deleted' di Observer)
        // Catatan: Data di tabel gallery_photos juga akan terhapus otomatis 
        // karena pemakaian cascadeOnDelete() di file migration.
        $gallery->delete();
    }
}