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
        return $statisticTableEntry->chart()->create($attributes);
    }
}