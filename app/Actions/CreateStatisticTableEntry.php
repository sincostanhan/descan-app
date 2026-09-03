<?php

namespace App\Actions;

use App\Models\StatisticTemplate;
use App\Models\StatisticTableEntry;
use Illuminate\Support\Facades\DB;

class CreateStatisticTableEntry
{
    public function handle(StatisticTemplate $template, array $attributes): StatisticTableEntry
    {
        return DB::transaction(function () use ($template, $attributes) {
            // village_id otomatis terisi oleh BelongsToVillage trait pada model StatisticTableEntry
            $entry = $template->entries()->create([
                'source' => $attributes['source'] ?? null,
                'description' => $attributes['description'] ?? null,
            ]);

            foreach ($attributes['values'] ?? [] as $cellId => $value) {
                if ($value === null || $value === '') {
                    continue; // sel kosong tidak perlu disimpan
                }

                $entry->values()->create([
                    'statistic_template_cell_id' => $cellId,
                    'value' => $value,
                ]);
            }

            return $entry;
        });
    }
}