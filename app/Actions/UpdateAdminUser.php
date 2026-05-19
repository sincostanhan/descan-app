<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateAdminUser
{
    public function handle(User $user, array $data): bool
    {
        $updateData = [
            'name' => $data['name'],
            'username' => $data['username'],
            'village_id' => $data['village_id'],
        ];

        // Hanya update password jika diisi
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        return $user->update($updateData);
    }
}
