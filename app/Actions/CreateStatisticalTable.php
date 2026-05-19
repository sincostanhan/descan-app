<?php

namespace App\Actions;

use App\Models\StatisticalTable;

class CreateStatisticalTable
{
    public function handle(array $attributes)
    {
        // Decode JSON dari form sebelum disimpan
        $attributes['columns'] = json_decode($attributes['columns'], true);
        $attributes['content'] = json_decode($attributes['content'], true);

        return StatisticalTable::create($attributes);
    }
}
