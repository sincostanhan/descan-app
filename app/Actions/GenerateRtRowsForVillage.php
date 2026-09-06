<?php

namespace App\Actions;

use App\Models\StatisticTemplate;
use App\Models\Village;
use Illuminate\Support\Facades\DB;

class GenerateRtRowsForVillage
{
    /**
     * Generate baris "RT 00X RW 00X" khusus untuk 1 Kelurahan, dari data organizations.daftar_rt
     * milik kelurahan tsb. Idempotent — kalau kelurahan ini sudah pernah punya baris di template
     * ini, tidak digenerate ulang (supaya isian nilai yang sudah ada aman).
     *
     * Label & rt_value SENGAJA tidak pernah bisa diedit dari sisi manapun (BPS maupun Kelurahan) —
     * satu-satunya cara mengubahnya adalah mengubah data RT/RW di modul Organisasi kelurahan.
     */
    public function handle(StatisticTemplate $template, Village $village): void
    {
        $alreadyGenerated = $template->headers()
            ->where('axis', 'row')
            ->where('village_id', $village->id)
            ->exists();

        if ($alreadyGenerated) {
            return;
        }

        $daftarRt = collect($village->organization?->daftar_rt ?? []);

        if ($daftarRt->isEmpty()) {
            return; // Kelurahan belum mengisi data RT/RW di modul Organisasi
        }

        $columnLeafIds = $template->headers()
            ->where('axis', 'column')
            ->where('is_leaf', true)
            ->pluck('id');

        DB::transaction(function () use ($template, $village, $daftarRt, $columnLeafIds) {
            foreach ($daftarRt->values() as $order => $rt) {
                $header = $template->headers()->create([
                    'axis' => 'row',
                    'parent_id' => null,
                    'label' => sprintf('RT %03d RW %03d', (int) $rt['rt'], (int) $rt['rw']),
                    'key' => "rt-{$rt['rt']}-rw-{$rt['rw']}-village-{$village->id}",
                    'is_leaf' => true,
                    'rt_value' => $rt['rt'], // nilai mentah, dipakai JOIN ke region_geometries
                    'village_id' => $village->id,
                    'order' => $order,
                ]);

                foreach ($columnLeafIds as $columnLeafId) {
                    $template->cells()->create([
                        'row_header_id' => $header->id,
                        'column_header_id' => $columnLeafId,
                        'village_id' => $village->id,
                        'is_locked' => false,
                    ]);
                }
            }
        });
    }
}