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

        return $statisticTableEntry->chart()->create($attributes);
    }
}