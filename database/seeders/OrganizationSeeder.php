<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Organization::create([
            'lurah' => 'La Ode Muhamad Baharudin, S.Pd.',
            'sekretaris_lurah' => 'Murifa, S.IP.',
            'kasi_pemerintahan' => 'La Ode Husni, S.Sos.',
            'kasi_ekonomi' => 'Nasrun, S.H.',
            'kasi_ketentraman' => 'La Saidu',

            'analis_pembangunan' => 'Rusyati',
            'pranata_barang' => 'Samida',
            'pengelola_keamanan' => 'Idris Ardi',
            'pengadministrasian_umum' => 'Marlin',
            'pengadministrasian_pemerintahan' => 'Sarni',
            'pengelola_surat' => 'Safarina Isram',

            // Daftar RW (Format Array)
            'daftar_rw' => [
                ['rw' => '1', 'nama' => 'M. Nur Intan Ode, S.Pd., M.Pd.'],
                ['rw' => '2', 'nama' => 'Hanisa'],
                ['rw' => '3', 'nama' => 'Alun Ondi'],
            ],
            
            // Daftar RT (Format Array)
            'daftar_rt' => [
                ['rt' => '1', 'rw' => '1', 'nama' => 'Ratna Zuudu'],
                ['rt' => '2', 'rw' => '1', 'nama' => 'Muzia'],
                ['rt' => '3', 'rw' => '1', 'nama' => 'Sanaya'],
                ['rt' => '1', 'rw' => '2', 'nama' => 'Mariati'],
                ['rt' => '2', 'rw' => '2', 'nama' => 'Asrin'],
                ['rt' => '3', 'rw' => '2', 'nama' => "La Ode Aidi Para'a"],
                ['rt' => '1', 'rw' => '3', 'nama' => 'Gusnawati'],
                ['rt' => '2', 'rw' => '3', 'nama' => 'Yurlin'],
                ['rt' => '3', 'rw' => '3', 'nama' => 'Armin Tuany'],
                ['rt' => '4', 'rw' => '3', 'nama' => 'Wa Nia'],
            ],
        ]);
    }
}
