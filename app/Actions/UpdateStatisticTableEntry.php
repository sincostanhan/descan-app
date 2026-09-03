<?php

namespace App\Actions;

use App\Models\StatisticTableEntry;
use Illuminate\Support\Facades\DB;

class UpdateStatisticTableEntry
{
    public function handle(StatisticTableEntry $entry, array $attributes): StatisticTableEntry
    {
        return DB::transaction(function () use ($entry, $attributes) {
            $entry->update([
                'source' => $attributes['source'] ?? null,
                'description' => $attributes['description'] ?? null,
            ]);

            foreach ($attributes['values'] ?? [] as $cellId => $value) {
                // updateOrCreate: sel yang sebelumnya kosong (belum ada baris value) otomatis dibuat,
                // sel yang sudah ada tinggal di-update — inilah yang membuat cascade dari BPS "terasa" aman di sisi Kelurahan.
                $entry->values()->updateOrCreate(
                    ['statistic_template_cell_id' => $cellId],
                    ['value' => $value]
                );
            }

            return $entry->refresh();
        });
    }
}