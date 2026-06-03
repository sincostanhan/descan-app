<?php

namespace App\Actions;

use App\Models\StatisticalTable;

class CreateStatisticChart
{
    public function handle(StatisticalTable $statisticalTable, array $attributes)
    {
        // Relasi charts() akan otomatis mengisi statistical_table_id.
        // Trait BelongsToVillage di model StatisticChart akan otomatis mengisi village_id.
        return $statisticalTable->charts()->create($attributes);
    }
}