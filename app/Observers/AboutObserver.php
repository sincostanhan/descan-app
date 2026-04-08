<?php

namespace App\Observers;

use App\Models\About;

class AboutObserver
{
    /**
     * Handle the About "created" event.
     */
    public function created(About $about): void
    {
        //
    }

    /**
     * Handle the About "updated" event.
     */
    public function updated(About $about): void
    {
        session()->flash('success', 'Perubahan pada halaman Tentang Kami telah berhasil disimpan.');
    }

    /**
     * Handle the About "deleted" event.
     */
    public function deleted(About $about): void
    {
        //
    }

    /**
     * Handle the About "restored" event.
     */
    public function restored(About $about): void
    {
        //
    }

    /**
     * Handle the About "force deleted" event.
     */
    public function forceDeleted(About $about): void
    {
        //
    }
}
