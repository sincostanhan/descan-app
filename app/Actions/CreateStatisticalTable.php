<?php

namespace App\Actions;

use App\Models\StatisticalTable;
use Illuminate\Support\Facades\DB;

class CreateStatisticalTable
{

    public function handle(array $attributes)
    {
        // 1. Decode JSON dari form sebelum disimpan (Tepat seperti kode asli Anda!)
        $attributes['columns'] = json_decode($attributes['columns'], true);
        $attributes['content'] = json_decode($attributes['content'], true);

        // 2. Menggunakan Database Transaction agar Tabel dan Grafik tersimpan bersamaan
        return DB::transaction(function () use ($attributes) {
            
            // Simpan Tabel Statistik terlebih dahulu
            $table = StatisticalTable::create([
                'publication' => $attributes['publication'],
                'chapter' => $attributes['chapter'],
                'title' => $attributes['title'],
                'source' => $attributes['source'] ?? null,
                'description' => $attributes['description'] ?? null,
                'columns' => $attributes['columns'], // Ini sudah berupa array hasil decode
                'content' => $attributes['content'], // Ini sudah berupa array hasil decode
            ]);

            // Cek apakah admin juga mengisi form pembuatan grafik dari halaman preview
            // Jika chart_type dan x_axis_column tidak kosong, buatkan grafiknya
            if (!empty($attributes['chart_type']) && !empty($attributes['x_axis_column'])) {
                $table->chart()->create([
                    'title' => $attributes['chart_title'] ?? $attributes['title'], // Default ke judul tabel jika kosong
                    'chart_type' => $attributes['chart_type'],
                    'x_axis_column' => $attributes['x_axis_column'],
                    'y_axis_columns' => $attributes['y_axis_columns'] ?? [],
                    'y_axis_colors' => $attributes['y_axis_colors'] ?? [],
                    'has_total_row' => $attributes['has_total_row'] ?? false,
                    'is_active' => $attributes['is_chart_active'] ?? true,
                ]);
            }

            return $table;
        });
    }
}
