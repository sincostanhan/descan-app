<?php

namespace App\Actions;

use App\Models\StatisticalTable;

class DeleteStatisticalTable
{
    public function handle(StatisticalTable $table)
    {
        return $table->delete();
    }
}
