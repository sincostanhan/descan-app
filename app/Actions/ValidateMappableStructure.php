<?php

namespace App\Actions;

use App\Models\StatisticTemplate;
use Illuminate\Validation\ValidationException;

class ValidateMappableStructure
{
    /**
     * Menolak toggle is_mapped=true jika struktur baris template
     * tidak punya jalur rt_value sama sekali (lihat resolveRtValue() di StatisticTemplateHeader).
     */
    public function handle(StatisticTemplate $template): void
    {
        $rowLeaves = $template->headers()->where('axis', 'row')->where('is_leaf', true)->get();

        if ($rowLeaves->isEmpty()) {
            throw ValidationException::withMessages([
                'is_mapped' => 'Template belum memiliki struktur baris, tidak bisa ditampilkan di Dashboard Peta.',
            ]);
        }

        $hasRtValue = $rowLeaves->contains(fn ($leaf) => filled($leaf->resolveRtValue()));

        if (!$hasRtValue) {
            throw ValidationException::withMessages([
                'is_mapped' => 'Tandai minimal satu level header baris dengan nilai RT agar template ini bisa dipetakan.',
            ]);
        }
    }
}