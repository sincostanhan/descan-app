<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $villages = [
            'Bukit Wolio Indah',
            'Ngkari-ngkari',
            'Baadia',
        ];

        foreach ($villages as $name) {
            // 1. Buat Subdomain (Slug) untuk URL (contoh: bukit-wolio-indah)
            $subdomain = Str::slug($name);

            // 2. Buat Data Kelurahan
            $village = Village::create([
                'name' => $name,
                'subdomain' => $subdomain,
            ]);

            // 3. Buat User Admin Kelurahan (Default)
            // Menghapus spasi dan tanda strip, lalu diubah ke huruf kecil
            // Contoh: "Bukit Wolio Indah" -> "bukitwolioindah"
            // Contoh: "Ngkari-ngkari" -> "ngkaringkari"
            $cleanName = Str::lower(str_replace([' ', '-'], '', $name));
            $username = "admin_" . $cleanName;
            
            $user = User::create([
                'name' => "Admin " . $name,
                'username' => $username, // Menggunakan kolom username sesuai skema Anda
                'password' => bcrypt('q1212121'),
                'village_id' => $village->id,
            ]);

            // 4. Tugaskan Role
            // $user->assignRole('admin kelurahan');
            $user->assignRole('admin-kelurahan');
        }
    }
}
