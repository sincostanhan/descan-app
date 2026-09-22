<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->data() as $villageName => $attributes) {
            $village = Village::where('name', $villageName)->first();

            if (!$village) {
                $this->command?->warn("OrganizationSeeder: Kelurahan \"{$villageName}\" tidak ditemukan (cek VillageSeeder), dilewati.");
                continue;
            }

            Organization::create([
                'village_id' => $village->id,
                ...$attributes,
            ]);
        }
    }

    /**
     * Data struktur organisasi per Kelurahan.
     *
     * Kolom staf TETAP (lurah, sekretaris_lurah, kasi_pemerintahan, kasi_ekonomi,
     * kasi_ketentraman, analis_pembangunan, pranata_barang, pengelola_keamanan,
     * pengadministrasian_umum, pengadministrasian_pemerintahan, pengelola_surat) awalnya
     * dibentuk mengikuti struktur staf Kelurahan Baadia, jadi kelurahan lain wajar kalau
     * banyak yang null di kolom-kolom itu (memang tidak punya jabatan tsb).
     *
     * Jabatan yang TIDAK mengikuti struktur tetap Baadia (Bendahara Barang, Bendahara
     * Pembantu Pengeluaran, Pengurus Barang, Analis Pengawasan Masyarakat, PPPK Paruh Waktu,
     * dst) masuk ke `staf_tambahan` (kolom JSON, lihat migration
     * 2026_09_10_000000_add_staf_tambahan_to_organizations_table) — SENGAJA tidak dipetakan
     * ke kolom staf tetap yang labelnya beda arti (mis. "Bendahara Barang" ke pranata_barang),
     * karena itu akan salah tampil di form admin/organization/edit.blade.php.
     *
     * rt/rw disimpan sebagai angka polos ("1", bukan "001") — konsisten dengan format yang sudah
     * dipakai region_geometries & Organization::daftar_rt yang ada.
     */
    private function data(): array
    {
        return [
            'Baadia' => [
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

                'daftar_rw' => [
                    ['rw' => '1', 'nama' => 'M. Nur Intan Ode, S.Pd., M.Pd.'],
                    ['rw' => '2', 'nama' => 'Hanisa'],
                    ['rw' => '3', 'nama' => 'Alun Ondi'],
                ],

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
            ],

            'Bataraguru' => [
                'lurah' => 'Jenny, S.IP.',
                'sekretaris_lurah' => 'Hj. Nuraini',
                'kasi_pemerintahan' => 'Wa Ode Syafriah, A.Md.',
                'kasi_ekonomi' => 'Usman Jafar, S.IP',
                'kasi_ketentraman' => 'Edy Marwan Djamil, S.IP',

                // Bataraguru tidak punya jabatan Analis Pembangunan / Pranata Barang /
                // Pengelola Keamanan / Pengadministrasian Umum / Pengadministrasian
                // Pemerintahan / Pengelola Surat seperti Baadia — dibiarkan null (default kolom).

                'staf_tambahan' => [
                    ['jabatan' => 'Bendahara Barang', 'nama' => 'Ika Wildayani'],
                    ['jabatan' => 'Bendahara Pembantu Pengeluaran', 'nama' => 'Wa Rani, S.IP'],
                    ['jabatan' => 'PPPK Paruh Waktu - Staf Kasi Pemerintahan', 'nama' => 'Meilani Bakri'],
                    ['jabatan' => 'PPPK Paruh Waktu - Staf Kasi Kesra', 'nama' => 'Rasfia Usi, S.Kom'],
                    ['jabatan' => 'PPPK Paruh Waktu - Staf Kasi Trantib', 'nama' => 'Bari Saputra'],
                    ['jabatan' => 'PPPK Paruh Waktu - Staf Kasi Kesra', 'nama' => 'Fadilal Hayya Iza'],
                ],

                'daftar_rw' => [
                    ['rw' => '1', 'nama' => 'Fajrin Amrun'],
                    ['rw' => '2', 'nama' => 'Agus Salim'],
                    ['rw' => '3', 'nama' => 'Syarif Mando'],
                    ['rw' => '4', 'nama' => 'Wa Ia'],
                    ['rw' => '5', 'nama' => 'La Saadu, S.Ag'],
                    ['rw' => '6', 'nama' => 'Wa Ode Zahadia Bau'],
                    ['rw' => '7', 'nama' => 'Herwandi M. Said'],
                    ['rw' => '8', 'nama' => 'Hariyanti, S.IP'],
                    ['rw' => '9', 'nama' => 'Maria'],
                ],

                'daftar_rt' => [
                    ['rt' => '1', 'rw' => '1', 'nama' => 'Erniyanti'],
                    ['rt' => '2', 'rw' => '1', 'nama' => 'Andhika Putra'],
                    ['rt' => '3', 'rw' => '1', 'nama' => 'Abdul Samad'],

                    ['rt' => '1', 'rw' => '2', 'nama' => 'Dedi Setiyadi'],
                    ['rt' => '2', 'rw' => '2', 'nama' => 'Sadam Iskandar, SP'],
                    ['rt' => '3', 'rw' => '2', 'nama' => 'Kamaluddin'],

                    ['rt' => '1', 'rw' => '3', 'nama' => 'Langkaito'],
                    ['rt' => '2', 'rw' => '3', 'nama' => 'Sahril'],
                    ['rt' => '3', 'rw' => '3', 'nama' => 'Munir B.'],
                    ['rt' => '4', 'rw' => '3', 'nama' => 'La Maludi'],

                    ['rt' => '1', 'rw' => '4', 'nama' => 'Farida'],
                    ['rt' => '2', 'rw' => '4', 'nama' => 'Rabiatul Adawiah'],
                    ['rt' => '3', 'rw' => '4', 'nama' => 'Hamiruddin'],

                    ['rt' => '1', 'rw' => '5', 'nama' => 'Mohammad Said'],
                    ['rt' => '2', 'rw' => '5', 'nama' => 'Idris'],
                    ['rt' => '3', 'rw' => '5', 'nama' => 'Muhlisin'],

                    ['rt' => '1', 'rw' => '6', 'nama' => 'Burhanuddin'],
                    ['rt' => '2', 'rw' => '6', 'nama' => 'Wa Ode Rita Rustandi'],
                    ['rt' => '3', 'rw' => '6', 'nama' => 'Sumiati Amin'],
                    ['rt' => '4', 'rw' => '6', 'nama' => 'Raswiyati'],

                    ['rt' => '1', 'rw' => '7', 'nama' => 'Nisa Yulianingsih S.'],
                    ['rt' => '2', 'rw' => '7', 'nama' => 'Samna'],
                    ['rt' => '3', 'rw' => '7', 'nama' => 'Jumiati'],
                    ['rt' => '4', 'rw' => '7', 'nama' => 'Rosida H.D'],

                    ['rt' => '1', 'rw' => '8', 'nama' => 'Safiuddin'],
                    ['rt' => '2', 'rw' => '8', 'nama' => 'M. Fitriadi, SH'],
                    ['rt' => '3', 'rw' => '8', 'nama' => 'Hariyani'],

                    ['rt' => '1', 'rw' => '9', 'nama' => 'Satna'],
                    ['rt' => '2', 'rw' => '9', 'nama' => 'Erna'],
                    ['rt' => '3', 'rw' => '9', 'nama' => 'Rezki Febrianti'],
                ],
            ],

            'Batulo' => [
                'lurah' => 'Yunizal Nisaid, S.IP.',
                'sekretaris_lurah' => 'Verawati, S.Pi.',
                'kasi_pemerintahan' => 'Moh. Hamim Sahiddin, S.E.',
                'kasi_ekonomi' => 'Mirawati Yaka, S.IP.',
                'kasi_ketentraman' => 'Adi Mardiya, S.S.',

                // Batulo tidak punya jabatan Analis Pembangunan / Pranata Barang / Pengelola
                // Keamanan / Pengadministrasian Umum / Pengadministrasian Pemerintahan /
                // Pengelola Surat seperti Baadia — dibiarkan null (default kolom).

                'staf_tambahan' => [
                    ['jabatan' => 'Bendahara Barang', 'nama' => 'Rahmiar Patu'],
                    ['jabatan' => 'Analis Pengawasan Masyarakat', 'nama' => 'Ona Rosana'],
                    ['jabatan' => 'PPPK Paruh Waktu', 'nama' => 'Sitti Safianah'],
                    ['jabatan' => 'PPPK Paruh Waktu', 'nama' => 'Suriani'],
                    ['jabatan' => 'PPPK Paruh Waktu', 'nama' => 'Saniati'],
                    ['jabatan' => 'PPPK Paruh Waktu', 'nama' => 'Idrus, S.H.'],
                ],

                'daftar_rw' => [
                    ['rw' => '1', 'nama' => 'H. Maondu'],
                    ['rw' => '2', 'nama' => 'Musria'],
                    ['rw' => '3', 'nama' => 'Rasyid Sehong'],
                    ['rw' => '4', 'nama' => 'Kurnia'],
                    ['rw' => '5', 'nama' => 'Syarifuddin'],
                    ['rw' => '6', 'nama' => 'H. Zakari'],
                ],

                'daftar_rt' => [
                    ['rt' => '1', 'rw' => '1', 'nama' => 'Darmawangsyah'],
                    ['rt' => '2', 'rw' => '1', 'nama' => 'Napsia'],
                    ['rt' => '3', 'rw' => '1', 'nama' => 'Toding'],
                    ['rt' => '4', 'rw' => '1', 'nama' => 'La Ode Lumesa'],

                    ['rt' => '1', 'rw' => '2', 'nama' => 'Sitti Safianah'],
                    ['rt' => '2', 'rw' => '2', 'nama' => 'Herlin'],
                    ['rt' => '3', 'rw' => '2', 'nama' => 'Sunarsih'],

                    ['rt' => '1', 'rw' => '3', 'nama' => 'Munsia'],
                    ['rt' => '2', 'rw' => '3', 'nama' => 'Muliadi Samiun'],
                    ['rt' => '3', 'rw' => '3', 'nama' => 'Ardianto Amadi'],

                    ['rt' => '1', 'rw' => '4', 'nama' => 'Muhammad Nur Itsar'],
                    ['rt' => '2', 'rw' => '4', 'nama' => 'Waode Siti Masyita'],
                    ['rt' => '3', 'rw' => '4', 'nama' => 'Waode Zuliani'],

                    ['rt' => '1', 'rw' => '5', 'nama' => 'Kamiludin'],
                    ['rt' => '2', 'rw' => '5', 'nama' => 'La Ode Abdul Rajab'],
                    ['rt' => '3', 'rw' => '5', 'nama' => 'Feni Sisma Rahim'],

                    ['rt' => '1', 'rw' => '6', 'nama' => 'Fitriani'],
                    ['rt' => '2', 'rw' => '6', 'nama' => 'La Ode Zia'],
                    ['rt' => '3', 'rw' => '6', 'nama' => 'La Ode Jarubali'],
                ],
            ],

            'Wale' => [
                'lurah' => 'Laode Sudarna, S.H.',
                'sekretaris_lurah' => 'Hatta Subhan, S.IP.',
                // Kasi Pemerintahan: belum ada pejabatnya (dinyatakan eksplisit di sumber) -> null.
                'kasi_ekonomi' => 'Fitria, S.E.',
                'kasi_ketentraman' => 'Jamaliah, S.M.',

                // Satu-satunya kecocokan langsung ke skema Baadia untuk Wale:
                'pengadministrasian_pemerintahan' => 'Deavy Arsy Anwar, A.Md.',

                'staf_tambahan' => [
                    ['jabatan' => 'Pengurus Barang', 'nama' => 'Islamiah'],
                    ['jabatan' => 'PPPK Paruh Waktu', 'nama' => 'Indra'],
                    ['jabatan' => 'PPPK Paruh Waktu', 'nama' => 'Mulyadi'],
                    ['jabatan' => 'PPPK Paruh Waktu', 'nama' => 'Muliana'],
                ],

                'daftar_rw' => [
                    ['rw' => '1', 'nama' => 'Muh. Yusri Metah'],
                    ['rw' => '2', 'nama' => 'Hj. Hajrah HB'],
                ],

                'daftar_rt' => [
                    ['rt' => '1', 'rw' => '1', 'nama' => 'Wa Cobe'],
                    ['rt' => '2', 'rw' => '1', 'nama' => 'Wa Ode Yeni Wahdaniah Baisu'],
                    ['rt' => '3', 'rw' => '1', 'nama' => 'Yenny R.'],

                    ['rt' => '1', 'rw' => '2', 'nama' => 'Moeh. Abdoeh Sy.'],
                    ['rt' => '2', 'rw' => '2', 'nama' => 'Andi Darwis Patengngai'],
                    ['rt' => '3', 'rw' => '2', 'nama' => 'Anwar'],
                ],
            ],
        ];
    }
}