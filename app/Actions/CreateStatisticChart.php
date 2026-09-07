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
        // $attributes['title'] = $attributes['title'] ?: $statisticTableEntry->template->title;

        // Kosong (misal semua checkbox baris kelewat tercentang lalu di-uncheck semua) = tampilkan semua baris.
        if (empty($attributes['included_rows'])) {
            $attributes['included_rows'] = range(0, count($statisticTableEntry->content) - 1);
        }

        return $statisticTableEntry->chart()->create($attributes);
    }
}