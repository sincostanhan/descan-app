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

        // $attributes['title'] = $attributes['title'] ?: $chart->statisticTableEntry->template->title;
        $attributes['title'] = $attributes['title'] ?: $chart->statisticTableEntry->template->title;

        // Kosong (misal semua checkbox baris kelewat tercentang lalu di-uncheck semua) = tampilkan semua baris.
        if (empty($attributes['included_rows'])) {
            // $attributes['included_rows'] = range(0, count($statisticTableEntry->content) - 1);
            $attributes['included_rows'] = range(0, count($chart->statisticTableEntry->content) - 1);
        }

        $chart->update($attributes);

        return $chart->fresh();
    }
}