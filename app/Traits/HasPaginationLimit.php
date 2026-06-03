<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HasPaginationLimit
{
    protected function getPaginationLimit(Request $request, int $default = 20): int
    {
        $perPage = (int) $request->query('per_page', $default);
        // $allowed = [10, 20, 50, 100];
        $allowed = [1, 10, 20, 50, 100];
        
        return in_array($perPage, $allowed) ? $perPage : $default;
    }
}