<?php

namespace App\Actions;

use App\Models\StatisticTemplate;
use App\Models\StatisticTemplateLog;

class LogTemplateChange
{
    public function handle(StatisticTemplate $template, string $changeType, string $description): StatisticTemplateLog
    {
        return $template->logs()->create([
            'changed_by' => auth()->id(),
            'change_type' => $changeType,
            'description' => $description,
        ]);
    }
}