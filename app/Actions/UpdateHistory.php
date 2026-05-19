<?php

namespace App\Actions;

use App\Models\History;

class UpdateHistory
{
    public function handle(array $validatedData): History
    {
        $history = History::first();

        if ($history) {
            $history->update($validatedData);
            return $history;
        }

        return History::create($validatedData);
    }
}
