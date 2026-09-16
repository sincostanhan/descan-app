<?php

namespace App\Actions;

use App\Models\About;

class UpdateAbout
{
    public function handle(array $validatedData): About
    {
        $about = About::first();

        if ($about) {
            $about->update($validatedData);
            return $about;
        }

        return About::create($validatedData);
    }
}