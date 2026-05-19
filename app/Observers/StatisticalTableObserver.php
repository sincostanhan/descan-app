<?php

namespace App\Observers;

use App\Models\StatisticalTable;

class StatisticalTableObserver
{
    /**
     * Handle the StatisticalTable "created" event.
     */
    public function created(StatisticalTable $statisticalTable): void
    {
        session()->flash('success', 'Tabel statistik berhasil ditambahkan dan disimpan ke database!');
    }

    /**
     * Handle the StatisticalTable "updated" event.
     */
    public function updated(StatisticalTable $statisticalTable): void
    {
        session()->flash('success', 'Data tabel statistik berhasil diperbarui!');
    }

    /**
     * Handle the StatisticalTable "deleted" event.
     */
    public function deleted(StatisticalTable $statisticalTable): void
    {
        session()->flash('success', 'Tabel statistik berhasil dihapus!');
    }

    /**
     * Handle the StatisticalTable "restored" event.
     */
    public function restored(StatisticalTable $statisticalTable): void
    {
        //
    }

    /**
     * Handle the StatisticalTable "force deleted" event.
     */
    public function forceDeleted(StatisticalTable $statisticalTable): void
    {
        //
    }
}
