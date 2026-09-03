<?php

namespace App\Actions;

use App\Models\StatisticTemplate;
use App\Models\StatisticTemplateHeader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateStatisticTemplate
{
    public function handle(array $attributes): StatisticTemplate
    {
        return DB::transaction(function () use ($attributes) {
            $template = StatisticTemplate::create([
                'title' => $attributes['title'],
                'description' => $attributes['description'] ?? null,
                'is_active' => $attributes['is_active'] ?? true,
                // is_mapped sengaja selalu false saat create.
                // Toggle ini hanya boleh dinyalakan lewat Update, setelah struktur tervalidasi ValidateMappableStructure.
                'is_mapped' => false,
                'created_by' => auth()->id(),
            ]);

            $rowLeafIds = $this->buildHeaderTree($template, 'row', json_decode($attributes['row_headers'], true) ?? [], null);
            $columnLeafIds = $this->buildHeaderTree($template, 'column', json_decode($attributes['column_headers'], true) ?? [], null);

            $this->generateCells($template, $rowLeafIds, $columnLeafIds);

            return $template;
        });
    }

    /**
     * Rekursif membuat node header dari tree JSON. Node tanpa 'children' dianggap leaf.
     * Return array ID leaf yang terbentuk pada axis tsb.
     */
    private function buildHeaderTree(StatisticTemplate $template, string $axis, array $nodes, ?int $parentId): array
    {
        $leafIds = [];

        foreach ($nodes as $order => $node) {
            $children = $node['children'] ?? [];
            $isLeaf = empty($children);

            $header = $template->headers()->create([
                'axis' => $axis,
                'parent_id' => $parentId,
                'label' => $node['label'],
                'key' => $isLeaf ? Str::slug($node['label']) . '-' . Str::random(6) : null,
                'data_type' => ($axis === 'column' && $isLeaf) ? ($node['data_type'] ?? 'text') : null,
                'rt_value' => ($axis === 'row') ? ($node['rt_value'] ?? null) : null,
                'is_leaf' => $isLeaf,
                'order' => $order,
            ]);

            if ($isLeaf) {
                $leafIds[] = $header->id;
            } else {
                $leafIds = array_merge($leafIds, $this->buildHeaderTree($template, $axis, $children, $header->id));
            }
        }

        return $leafIds;
    }

    /**
     * Cross-join semua leaf baris x leaf kolom menjadi sel yang boleh diisi Kelurahan.
     */
    private function generateCells(StatisticTemplate $template, array $rowLeafIds, array $columnLeafIds): void
    {
        foreach ($rowLeafIds as $rowId) {
            foreach ($columnLeafIds as $columnId) {
                $template->cells()->create([
                    'row_header_id' => $rowId,
                    'column_header_id' => $columnId,
                    'is_locked' => false,
                ]);
            }
        }
    }
}