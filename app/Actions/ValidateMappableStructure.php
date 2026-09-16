<?php

namespace App\Actions;

use App\Models\StatisticTemplate;
use Illuminate\Validation\ValidationException;

class ValidateMappableStructure
{
    /**
     * Menolak toggle is_mapped=true jika struktur baris template
     * tidak punya jalur rt_value sama sekali (lihat resolveRtValue() di StatisticTemplateHeader).
     * 
     * Menolak toggle is_mapped=true jika struktur baris template tidak punya jalur
     * rt_value + rw_value sama sekali (lihat resolveRtValue()/resolveRwValue() di
     * StatisticTemplateHeader). Keduanya WAJIB terisi bersama pada leaf yang sama —
     * rt_value saja tidak cukup untuk JOIN presisi ke region_geometries karena nomor
     * RT bisa berulang lintas RW (lihat migration add_rw_value_to_statistic_template_headers_table).

     */
    public function handle(StatisticTemplate $template): void
    {
        $rowLeaves = $template->headers()->where('axis', 'row')->where('is_leaf', true)->get();

        if ($rowLeaves->isEmpty()) {
            throw ValidationException::withMessages([
                'is_mapped' => 'Template belum memiliki struktur baris, tidak bisa ditampilkan di Dashboard Peta.',
            ]);
        }

        // $hasRtValue = $rowLeaves->contains(fn ($leaf) => filled($leaf->resolveRtValue()));
        $hasCompletePair = $rowLeaves->contains(
            fn ($leaf) => filled($leaf->resolveRtValue()) && filled($leaf->resolveRwValue())
        );

        // if (!$hasRtValue) {
        if (!$hasCompletePair) {
            throw ValidationException::withMessages([
                // 'is_mapped' => 'Tandai minimal satu level header baris dengan nilai RT agar template ini bisa dipetakan.',
                'is_mapped' => 'Tandai minimal satu level header baris dengan nilai RT dan RW (keduanya) agar template ini bisa dipetakan.',
            ]);
        }
    }
}