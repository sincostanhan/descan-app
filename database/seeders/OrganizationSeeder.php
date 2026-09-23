<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Village;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Skema per 2026-09-22: kolom kasi_pemerintahan/analis_pembangunan/dst SUDAH di-drop
     * dari tabel `organizations` (lihat drop_jabatan_columns_from_organizations_table).
     * Hanya `lurah` & `sekretaris_lurah` yang tetap kolom fix (selalu ada di semua Kelurahan).
     * Semua jabatan lain (Kasi, Bendahara, PPPK, dst) disimpan via relasi
     * Organization::positions() ke tabel `organization_positions`. Semua disamakan di
     * level=3 (Lurah=1, Sekretaris=2 tetap kolom fix di atas) — "jabatan lainnya" cuma
     * 1 tingkat, tidak dipecah lagi jadi beberapa level. `order` yang menentukan urutan tampil.
     */
    public function run(): void
    {
        foreach ($this->data() as $villageName => $entry) {
            $village = Village::where('name', $villageName)->first();

            if (!$village) {
                $this->command?->warn("OrganizationSeeder: Kelurahan \"{$villageName}\" tidak ditemukan (cek VillageSeeder), dilewati.");
                continue;
            }

            $positions = $entry['positions'] ?? [];
            unset($entry['positions']);

            $organization = Organization::create([
                'village_id' => $village->id,
                ...$entry,
            ]);

            foreach ($positions as $order => $position) {
                $organization->positions()->create([
                    'level' => $position['level'],
                    'label' => $position['label'],
                    'name'  => $position['name'] ?? null,
                    'order' => $order,
                ]);
            }
        }
    }

    /**
     * Data struktur organisasi per Kelurahan.
     * rt/rw disimpan sebagai angka polos ("1", bukan "001") — konsisten dengan format yang
     * sudah dipakai region_geometries & Organization::daftar_rt yang ada.
     */
    private function data(): array
    {
        return [
            'Baadia' => [
                'lurah' => 'La Ode Muhamad Baharudin, S.Pd.',
                'sekretaris_lurah' => 'Murifa, S.IP.',

                'positions' => [
                    ['level' => 3, 'label' => 'Kasi Pemerintahan', 'name' => 'La Ode Husni, S.Sos.'],
                    ['level' => 3, 'label' => 'Kasi Ekonomi, Pembangunan, dan Kesejahteraan Rakyat', 'name' => 'Nasrun, S.H.'],
                    ['level' => 3, 'label' => 'Kasi Ketentraman dan Ketertiban', 'name' => 'La Saidu'],
                    ['level' => 3, 'label' => 'Analis Pembangunan', 'name' => 'Rusyati'],
                    ['level' => 3, 'label' => 'Pranata Barang dan Jasa', 'name' => 'Samida'],
                    ['level' => 3, 'label' => 'Pengelola Keamanan dan Ketertiban', 'name' => 'Idris Ardi'],
                    ['level' => 3, 'label' => 'Pengadministrasian Umum', 'name' => 'Marlin'],
                    ['level' => 3, 'label' => 'Pengadministrasian Pemerintahan', 'name' => 'Sarni'],
                    ['level' => 3, 'label' => 'Pengelola Surat', 'name' => 'Safarina Isram'],
                ],

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

                'positions' => [
                    ['level' => 3, 'label' => 'Bendahara Barang', 'name' => 'Ika Wildayani'],
                    ['level' => 3, 'label' => 'Bendahara Pembantu Pengeluaran', 'name' => 'Wa Rani, S.IP'],

                    ['level' => 3, 'label' => 'Kasi Pemerintahan', 'name' => 'Wa Ode Syafriah, A.Md.'],
                    ['level' => 3, 'label' => 'Kasi Ekonomi, Pembangunan, dan Kesejahteraan Rakyat', 'name' => 'Usman Jafar, S.IP'],
                    ['level' => 3, 'label' => 'Kasi Ketentraman dan Ketertiban', 'name' => 'Edy Marwan Djamil, S.IP'],
                    ['level' => 3, 'label' => 'PPPK Paruh Waktu - Staf Kasi Pemerintahan', 'name' => 'Meilani Bakri'],
                    ['level' => 3, 'label' => 'PPPK Paruh Waktu - Staf Kasi Kesra', 'name' => 'Rasfia Usi, S.Kom'],
                    ['level' => 3, 'label' => 'PPPK Paruh Waktu - Staf Kasi Trantib', 'name' => 'Bari Saputra'],
                    ['level' => 3, 'label' => 'PPPK Paruh Waktu - Staf Kasi Kesra', 'name' => 'Fadilal Hayya Iza'],
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

                'positions' => [
                    ['level' => 3, 'label' => 'Bendahara Barang', 'name' => 'Rahmiar Patu'],

                    ['level' => 3, 'label' => 'Kasi Pemerintahan', 'name' => 'Moh. Hamim Sahiddin, S.E.'],
                    ['level' => 3, 'label' => 'Kasi Ekonomi, Pembangunan, dan Kesejahteraan Rakyat', 'name' => 'Mirawati Yaka, S.IP.'],
                    ['level' => 3, 'label' => 'Kasi Ketentraman dan Ketertiban', 'name' => 'Adi Mardiya, S.S.'],
                    ['level' => 3, 'label' => 'Analis Pengawasan Masyarakat', 'name' => 'Ona Rosana'],
                    ['level' => 3, 'label' => 'PPPK Paruh Waktu', 'name' => 'Sitti Safianah'],
                    ['level' => 3, 'label' => 'PPPK Paruh Waktu', 'name' => 'Suriani'],
                    ['level' => 3, 'label' => 'PPPK Paruh Waktu', 'name' => 'Saniati'],
                    ['level' => 3, 'label' => 'PPPK Paruh Waktu', 'name' => 'Idrus, S.H.'],
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

                'positions' => [
                    ['level' => 3, 'label' => 'Pengurus Barang', 'name' => 'Islamiah'],

                    // Kasi Pemerintahan: belum ada pejabatnya (dinyatakan eksplisit di sumber)
                    // -> tetap dicatat jabatannya, nama dikosongkan (tampil "-" di halaman publik).
                    ['level' => 3, 'label' => 'Kasi Pemerintahan', 'name' => null],
                    ['level' => 3, 'label' => 'Kasi Ekonomi, Pembangunan, dan Kesejahteraan Rakyat', 'name' => 'Fitria, S.E.'],
                    ['level' => 3, 'label' => 'Kasi Ketentraman dan Ketertiban', 'name' => 'Jamaliah, S.M.'],
                    ['level' => 3, 'label' => 'Pengadministrasian Pemerintahan', 'name' => 'Deavy Arsy Anwar, A.Md.'],
                    ['level' => 3, 'label' => 'PPPK Paruh Waktu', 'name' => 'Indra'],
                    ['level' => 3, 'label' => 'PPPK Paruh Waktu', 'name' => 'Mulyadi'],
                    ['level' => 3, 'label' => 'PPPK Paruh Waktu', 'name' => 'Muliana'],
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