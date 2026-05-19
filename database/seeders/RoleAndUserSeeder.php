<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // roles
        Role::create(['name' => 'bps']);
        Role::create(['name' => 'admin-kelurahan']);

        // akun master BPS
        $bpsUser = User::create([
            'name' => 'Petugas BPS',
            'username' => 'bps_admin',
            // 'email' => 'social@bps.go.id',
            'password' => Hash::make('password123'),
        ]);

        $bpsUser->assignRole('bps');
    }
}
