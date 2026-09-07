<?php

namespace App\Actions;

use App\Models\StatisticTemplate;
use App\Models\StatisticTemplateLog;

class LogTemplateChange
{
    // public function handle(StatisticTemplate $template, string $changeType, string $description): StatisticTemplateLog
    public function handle(StatisticTemplate $template, string $changeType, string $description, ?int $affectedHeaderId = null): StatisticTemplateLog
    {
        return $template->logs()->create([
            'affected_header_id' => $affectedHeaderId,
            'changed_by' => auth()->id(),
            'change_type' => $changeType,
            'description' => $description,
        ]);
    }
}