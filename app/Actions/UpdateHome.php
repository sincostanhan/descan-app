<?php

namespace App\Actions;

use App\Models\Home;

class UpdateHome
{
    public function handle(array $validatedData): Home
    {
        $home = Home::first();

        if ($home) {
            $home->update($validatedData);
            return $home;
        }

        return Home::create($validatedData);
    }
}
