<?php

namespace App\Observers;

use App\Models\Gallery;

class GalleryObserver
{
    /**
     * Handle the Gallery "created" event.
     */
    public function created(Gallery $gallery): void
    {
        session()->flash('success', 'Galeri kegiatan berhasil ditambahkan!');
    }

    /**
     * Handle the Gallery "updated" event.
     */
    public function updated(Gallery $gallery): void
    {
        //
    }

    /**
     * Handle the Gallery "deleted" event.
     */
    public function deleted(Gallery $gallery): void
    {
        session()->flash('success', 'Galeri kegiatan beserta fotonya berhasil dihapus!');
    }

    /**
     * Handle the Gallery "restored" event.
     */
    public function restored(Gallery $gallery): void
    {
        //
    }

    /**
     * Handle the Gallery "force deleted" event.
     */
    public function forceDeleted(Gallery $gallery): void
    {
        //
    }
}
