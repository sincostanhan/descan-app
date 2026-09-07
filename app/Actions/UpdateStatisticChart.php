<?php

namespace App\Actions;

use App\Models\StatisticChart;

class UpdateStatisticChart
{
    /**
     * Jika chart_type dikosongkan di form, grafik dihapus sepenuhnya (bukan di-update jadi kosong) —
     * meniru tombol "-- Kosongkan Jika Ingin Menghapus Grafik --" pada versi lama.
     */
    public function handle(StatisticChart $chart, array $attributes): ?StatisticChart
    {
        if (empty($attributes['chart_type'])) {
            $chart->delete();
            return null;
        }

        $attributes['title'] = $attributes['title'] ?: $chart->statisticTableEntry->template->title;
        $chart->update($attributes);

        return $chart->fresh();
    }
}