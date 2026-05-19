<?php

namespace App\Actions;

use App\Models\StatisticalTable;

class UpdateStatisticalTable
{
    public function handle(StatisticalTable $table, array $attributes)
    {
        $attributes['columns'] = json_decode($attributes['columns'], true);
        $attributes['content'] = json_decode($attributes['content'], true);

        return $table->update($attributes);
    }
}
