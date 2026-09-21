<?php

namespace App\Actions;

use App\Models\RegionGeometry;
use App\Models\Setting;
use App\Models\StatisticTableEntry;
use App\Models\StatisticTableValue;
use App\Models\StatisticTemplate;
use App\Models\StatisticTemplateCell;
use App\Models\StatisticTemplateHeader;
use App\Models\Village;

class ResolveMapFeatureData
{
    /**
     * Rangkai data untuk 1 titik di Dashboard Peta Publik: geometri wilayah (GeoJSON) +
     * nilai statistik yang sudah diisi Kelurahan, untuk kolom & RT/RW terpilih.
     *
     * Scope: HANYA template row_source='rt_rw'. Baris hasil GenerateRtRowsForVillage
     * tersimpan flat (rt_value & rw_value langsung di leaf, tidak bertingkat), jadi di sini
     * cukup where() langsung — tidak perlu resolveRtValue()/resolveRwValue() rekursif.
     *
     * Return null kalau kombinasi RT/RW tidak valid ATAU belum punya geometri —
     * SENGAJA begitu, sesuai requirement "jangan tampilkan RT tanpa poligon".
     */
    public function handle(StatisticTemplate $template, StatisticTemplateHeader $columnHeader, string $rt, string $rw): ?array
    {
        $rowHeader = $template->headers()
            ->where('axis', 'row')
            ->where('is_leaf', true)
            ->where('rt_value', $rt)
            ->where('rw_value', $rw)
            ->first();

        if (!$rowHeader) {
            return null;
        }

        // RegionGeometry pakai trait BelongsToVillage → query ini otomatis ter-scope
        // ke kelurahan aktif (dari subdomain), tidak perlu filter village_id manual.
        // $geometry = RegionGeometry::where('rt', $rt)->where('rw', $rw)->first();
        // SESUDAH (normalisasi angka dulu):
        $geometry = RegionGeometry::all()->first(
            fn ($g) => (int) $g->rt === (int) $rt && (int) $g->rw === (int) $rw
        );

        if (!$geometry) {
            return null;
        }

        $cell = StatisticTemplateCell::where('statistic_template_id', $template->id)
            ->where('row_header_id', $rowHeader->id)
            ->where('column_header_id', $columnHeader->id)
            ->first();

        // StatisticTableEntry juga pakai BelongsToVillage → otomatis ter-scope ke kelurahan aktif.
        $entry = StatisticTableEntry::where('statistic_template_id', $template->id)->first();

        $value = ($cell && $entry)
            ? StatisticTableValue::where('statistic_table_entry_id', $entry->id)
                ->where('statistic_template_cell_id', $cell->id)
                ->value('value')
            : null;

        $village = app()->bound('current_village_id') ? Village::find(app('current_village_id')) : null;
        $setting = Setting::first();

        return [
            'geojson' => $geometry->geojson,
            'properties' => [
                'kelurahan' => $village?->name,
                'kecamatan' => $setting?->kecamatan,
                'rt' => $rt,
                'rw' => $rw,
                'column_label' => $columnHeader->label,
                'value' => $value,
            ],
        ];
    }
}