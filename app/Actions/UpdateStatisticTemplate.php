<?php

namespace App\Actions;

use App\Models\StatisticTemplate;
use App\Models\StatisticTemplateHeader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateStatisticTemplate
{
    public function __construct(
        private LogTemplateChange $logTemplateChange,
        private ValidateMappableStructure $validateMappable,
    ) {}

    public function handle(StatisticTemplate $template, array $attributes): StatisticTemplate
    {
        return DB::transaction(function () use ($template, $attributes) {
            $template->update([
                'title' => $attributes['title'],
                'description' => $attributes['description'] ?? null,
                'is_active' => $attributes['is_active'] ?? $template->is_active,
            ]);

            $keptHeaderIds = [];

            $rowLeafIds = $this->syncHeaderTree(
                $template, 'row', json_decode($attributes['row_headers'], true) ?? [], null, $keptHeaderIds
            );
            $columnLeafIds = $this->syncHeaderTree(
                $template, 'column', json_decode($attributes['column_headers'], true) ?? [], null, $keptHeaderIds
            );

            // Soft-delete header lama yang sudah tidak ada lagi di payload (BPS hapus dari form builder).
            // Soft delete (bukan hard delete) memastikan cell & value historis milik Kelurahan tetap utuh.
            $removedHeaders = $template->headers()->whereNotIn('id', $keptHeaderIds)->get();
            foreach ($removedHeaders as $header) {
                $header->delete();
                $this->logTemplateChange->handle(
                    $template,
                    $header->axis === 'row' ? 'row_removed' : 'column_removed',
                    "Menghapus header {$header->axis} \"{$header->label}\" dari template."
                );
            }

            // Generate cell BARU untuk kombinasi leaf yang belum pernah ada — INILAH auto-sync/cascade-nya.
            // Kombinasi leaf lama yang tidak berubah TIDAK disentuh sama sekali (value Kelurahan aman).
            $this->generateMissingCells($template, $rowLeafIds, $columnLeafIds);

            $wantsMapped = $attributes['is_mapped'] ?? false;
            if ($wantsMapped) {
                $this->validateMappable->handle($template); // lempar ValidationException jika struktur tidak layak
            }
            $template->update(['is_mapped' => $wantsMapped]);

            return $template->refresh();
        });
    }

    /**
     * Sinkronisasi tree header: node ber-'id' di-update di tempat, node baru dibuat,
     * lalu setiap ID (baru maupun lama) dicatat ke $keptHeaderIds agar tidak dianggap "dihapus".
     */
    private function syncHeaderTree(StatisticTemplate $template, string $axis, array $nodes, ?int $parentId, array &$keptHeaderIds): array
    {
        $leafIds = [];

        foreach ($nodes as $order => $node) {
            $children = $node['children'] ?? [];
            $isLeaf = empty($children);

            $payload = [
                'axis' => $axis,
                'parent_id' => $parentId,
                'label' => $node['label'],
                'data_type' => ($axis === 'column' && $isLeaf) ? ($node['data_type'] ?? 'text') : null,
                'rt_value' => ($axis === 'row') ? ($node['rt_value'] ?? null) : null,
                'is_leaf' => $isLeaf,
                'order' => $order,
            ];

            $existing = !empty($node['id']) ? $template->headers()->find($node['id']) : null;
            $isNewHeader = !$existing;

            if ($existing) {
                $existing->update($payload);
                $header = $existing;
            } else {
                $payload['key'] = $isLeaf ? Str::slug($node['label']) . '-' . Str::random(6) : null;
                $header = $template->headers()->create($payload);
            }

            $keptHeaderIds[] = $header->id;

            if ($isNewHeader) {
                $this->logTemplateChange->handle(
                    $template,
                    $axis === 'row' ? 'row_added' : 'column_added',
                    "Menambahkan header {$axis} baru \"{$header->label}\" pada template."
                );
            }

            if ($isLeaf) {
                $leafIds[] = $header->id;
            } else {
                $leafIds = array_merge($leafIds, $this->syncHeaderTree($template, $axis, $children, $header->id, $keptHeaderIds));
            }
        }

        return $leafIds;
    }

    private function generateMissingCells(StatisticTemplate $template, array $rowLeafIds, array $columnLeafIds): void
    {
        $existingPairs = $template->cells()
            ->whereIn('row_header_id', $rowLeafIds)
            ->whereIn('column_header_id', $columnLeafIds)
            ->get()
            ->map(fn ($c) => "{$c->row_header_id}-{$c->column_header_id}")
            ->flip();

        foreach ($rowLeafIds as $rowId) {
            foreach ($columnLeafIds as $columnId) {
                if (!isset($existingPairs["{$rowId}-{$columnId}"])) {
                    $template->cells()->create([
                        'row_header_id' => $rowId,
                        'column_header_id' => $columnId,
                        'is_locked' => false,
                    ]);
                }
            }
        }
    }
}