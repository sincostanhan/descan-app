<?php

namespace App\Actions;

use App\Models\StatisticTemplateHeader;

class RestoreTemplateHeader
{
    public function handle(int $headerId): StatisticTemplateHeader
    {
        $header = StatisticTemplateHeader::withTrashed()->findOrFail($headerId);
        $header->restore();

        return $header;
    }
}