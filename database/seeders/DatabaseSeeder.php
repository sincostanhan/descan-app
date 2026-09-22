<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'username' => 'test_user',
        //     // 'email' => 'test@example.com',
        // ]);

        $this->call([
            // HomeSeeder::class,
            AboutSeeder::class,
            HistorySeeder::class,
            HomeSeeder::class,
            OrganizationSeeder::class,
            RoleAndUserSeeder::class,
            SettingSeeder::class,
            StatisticTablesSeeder::class,
            VillageSeeder::class,
            ]);
    }
}
