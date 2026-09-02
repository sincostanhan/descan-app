<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari kelurahan Baadia
        $village = Village::where('name', 'Baadia')->first();

        if ($village) {
            Setting::create([
                'village_id'   => $village->id,
                'village_name' => 'Kelurahan Baadia',
                'village_logo' => null, // Bisa dikosongkan karena nullable
                'theme_name'   => 'emerald',
            ]);
        }
    }
}
