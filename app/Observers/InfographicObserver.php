<?php

namespace App\Observers;

use App\Models\Infographic;

class InfographicObserver
{
    /**
     * Handle the Infographic "created" event.
     */
    public function created(Infographic $infographic): void
    {
        session()->flash('success', 'Infografis berhasil ditambahkan!');
    }

    /**
     * Handle the Infographic "updated" event.
     */
    public function updated(Infographic $infographic): void
    {
        session()->flash('success', 'Infografis berhasil diperbarui!');
    }

    /**
     * Handle the Infographic "deleted" event.
     */
    public function deleted(Infographic $infographic): void
    {
        session()->flash('success', 'Infografis berhasil dihapus!');
    }

    /**
     * Handle the Infographic "restored" event.
     */
    public function restored(Infographic $infographic): void
    {
        //
    }

    /**
     * Handle the Infographic "force deleted" event.
     */
    public function forceDeleted(Infographic $infographic): void
    {
        //
    }
}
