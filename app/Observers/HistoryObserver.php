<?php

namespace App\Observers;

use App\Models\History;

class HistoryObserver
{
    /**
     * Handle the History "created" event.
     */
    public function created(History $history): void
    {
        //
    }

    /**
     * Handle the History "updated" event.
     */
    public function updated(History $history): void
    {
        session()->flash('success', 'Data sejarah berhasil diperbarui!');
    }

    /**
     * Handle the History "deleted" event.
     */
    public function deleted(History $history): void
    {
        //
    }

    /**
     * Handle the History "restored" event.
     */
    public function restored(History $history): void
    {
        //
    }

    /**
     * Handle the History "force deleted" event.
     */
    public function forceDeleted(History $history): void
    {
        //
    }
}
