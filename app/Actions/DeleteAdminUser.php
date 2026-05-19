<?php

namespace App\Actions;

use App\Models\User;

class DeleteAdminUser
{
    public function handle(User $user): bool
    {
        return $user->delete();
    }
}
