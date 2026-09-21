<?php

namespace App\Actions;

use App\Models\StatisticChart;

class UpdateStatisticChart
{
    /**
     * Chart dihapus SEPENUHNYA hanya kalau KEDUA bagian dikosongkan (numerik DAN kategori) —
     * beda dari sebelumnya yang menghapus chart begitu chart_type numerik dikosongkan,
     * padahal grafik kategorinya mungkin masih diisi.
     */
    public function handle(StatisticChart $chart, array $attributes): ?StatisticChart
    {
        $attributes['category_columns'] = $this->normalizeCategoryColumns($attributes);
        unset($attributes['category_chart_types']);

        $hasNumeric = !empty($attributes['chart_type']);
        $hasCategory = !empty($attributes['category_columns']);

        if (!$hasNumeric && !$hasCategory) {
            $chart->delete();
            return null;
        }

        $attributes['title'] = $attributes['title'] ?: $chart->statisticTableEntry->template->title;

        // included_rows cuma relevan untuk grafik numerik — lihat catatan sama di CreateStatisticChart.
        $attributes['included_rows'] = $hasNumeric
            ? (empty($attributes['included_rows'])
                ? range(0, count($chart->statisticTableEntry->content) - 1)
                : array_map('intval', $attributes['included_rows']))
            : [];

        $chart->update($attributes);

        return $chart->fresh();
    }

    private function normalizeCategoryColumns(array $attributes): array
    {
        return collect($attributes['category_columns'] ?? [])
            ->map(fn ($col) => [
                'column' => $col,
                'chart_type' => $attributes['category_chart_types'][$col] ?? 'pie',
            ])
            ->values()
            ->all();
    }
}