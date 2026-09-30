<?php

namespace App\Actions;

use App\Models\StatisticTableEntry;

class CreateStatisticChart
{
    public function handle(StatisticTableEntry $statisticTableEntry, array $attributes)
    {
        // // Relasi charts() akan otomatis mengisi statistical_table_id.
        // // Trait BelongsToVillage di model StatisticChart akan otomatis mengisi village_id.
        // return $statisticalTable->charts()->create($attributes);
        // Relasi chart() (hasOne) otomatis mengisi statistic_table_entry_id.
        // Trait BelongsToVillage di model StatisticChart otomatis mengisi village_id.

        // Judul grafik opsional — kalau dikosongkan BPS/Kelurahan, ikut judul template (sesuai desain lama).
        $attributes['title'] = $attributes['title'] ?: $statisticTableEntry->template->title;

        // Samakan format category_columns dengan UpdateStatisticChart: [{column, chart_type}].
        // Tanpa ini, data tersimpan sebagai ["Kolom"] dan halaman publik (statistic/show.blade.php)
        // tidak bisa membaca cat.column, sehingga grafik kategori tidak digambar.
        $attributes['category_columns'] = $this->normalizeCategoryColumns($attributes);
        unset($attributes['category_chart_types']);

        // Kosong (misal semua checkbox baris kelewat tercentang lalu di-uncheck semua) = tampilkan semua baris.
        if (empty($attributes['included_rows'])) {
            $attributes['included_rows'] = range(0, count($statisticTableEntry->content) - 1);
        }

        return $statisticTableEntry->chart()->create($attributes);
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