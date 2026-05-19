<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser
{
    public function handle(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'village_id' => $data['village_id'],
        ]);

        // Otomatis berikan role admin-kelurahan
        $user->assignRole('admin-kelurahan');

        return $user;
    }
}