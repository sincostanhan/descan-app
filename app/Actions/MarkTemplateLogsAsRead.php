<?php

namespace App\Actions;

use App\Models\StatisticTemplate;
use App\Models\StatisticTemplateLog;

class MarkTemplateLogsAsRead
{
    /**
     * Tandai semua log milik template ini yang BELUM pernah dibaca Kelurahan tertentu.
     * Idempotent — log yang sudah pernah ditandai tidak diproses ulang.
     */
    public function handle(StatisticTemplate $template, int $villageId): void
    {
        $unreadLogIds = $template->logs()
            ->whereDoesntHave('reads', fn ($q) => $q->where('village_id', $villageId))
            ->pluck('id');

        foreach ($unreadLogIds as $logId) {
            StatisticTemplateLog::find($logId)->reads()->create([
                'village_id' => $villageId,
                'read_at' => now(),
            ]);
        }
    }
}