<?php

namespace App\Actions;

use App\Models\StatisticalTable;
use Illuminate\Support\Facades\DB;

class UpdateStatisticalTable
{
    public function handle(StatisticalTable $table, array $attributes)
    {
        $attributes['columns'] = json_decode($attributes['columns'], true);
        $attributes['content'] = json_decode($attributes['content'], true);

        // return $table->update($attributes);

        return DB::transaction(function () use ($table, $attributes) {
            
            // 1. Update data tabel utama
            $table->update([
                'publication' => $attributes['publication'],
                'chapter' => $attributes['chapter'],
                'title' => $attributes['title'],
                'source' => $attributes['source'] ?? null,
                'description' => $attributes['description'] ?? null,
                'columns' => $attributes['columns'],
                'content' => $attributes['content'],
            ]);

            // 2. Update atau Buat Grafik
            if (!empty($attributes['chart_type']) && !empty($attributes['x_axis_column'])) {
                // updateOrCreate( [Kondisi Pencarian], [Data yang diupdate/dibuat] )
                $table->chart()->updateOrCreate(
                    ['statistical_table_id' => $table->id], 
                    [
                        'title' => $attributes['chart_title'] ?? $attributes['title'],
                        'chart_type' => $attributes['chart_type'],
                        'x_axis_column' => $attributes['x_axis_column'],
                        'y_axis_columns' => $attributes['y_axis_columns'] ?? [],
                        'y_axis_colors' => $attributes['y_axis_colors'] ?? [],
                        'has_total_row' => $attributes['has_total_row'] ?? false,
                        'is_active' => $attributes['is_chart_active'] ?? true,
                    ]
                );
            } elseif (empty($attributes['chart_type']) && $table->chart) {
                // Opsional: Jika saat edit admin mengosongkan tipe grafik, kita hapus grafik yang ada
                $table->chart()->delete();
            }

            return $table;
        });
    }
}
