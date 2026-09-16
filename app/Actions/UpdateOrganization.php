<?php

namespace App\Actions;

use App\Models\Organization;

class UpdateOrganization
{
    public function handle(array $validatedData): Organization
    {
        $organization = Organization::first();

        if ($organization) {
            $organization->update($validatedData);
            return $organization;
        }

        return Organization::create($validatedData);
    }
}