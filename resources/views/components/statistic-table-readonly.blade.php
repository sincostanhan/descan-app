@php
    // Logika matrix (rowspan/colspan header bertingkat) SAMA PERSIS dengan
    // admin/statistic-table-entries/partials/_spreadsheet-table.blade.php,
    // supaya struktur tabel yang tampil ke publik identik dengan yang dilihat
    // Admin Kelurahan — bedanya di sini read-only, tanpa <input>.
    $values = $values ?? [];

    $treeHeight = function ($nodes) use (&$treeHeight) {
        if ($nodes->isEmpty()) return 0;
        return $nodes->max(fn ($node) => $node->is_leaf ? 1 : 1 + $treeHeight($node->children));
    };
    $maxColDepth = max($treeHeight($template->columnHeaders), 1);
    $maxRowDepth = max($treeHeight($template->rowHeaders), 1);

    $columnLevels = [];
    $collectColumnLevels = function ($nodes, $depth) use (&$collectColumnLevels, &$columnLevels) {
        foreach ($nodes as $node) {
            $columnLevels[$depth][] = $node;
            if (!$node->is_leaf) $collectColumnLevels($node->children, $depth + 1);
        }
    };
    $collectColumnLevels($template->columnHeaders, 0);
    for ($d = 0; $d < $maxColDepth; $d++) { $columnLevels[$d] = $columnLevels[$d] ?? []; }

    $columnLeaves = collect();
    $collectColumnLeaves = function ($nodes) use (&$collectColumnLeaves, &$columnLeaves) {
        foreach ($nodes as $node) {
            $node->is_leaf ? $columnLeaves->push($node) : $collectColumnLeaves($node->children);
        }
    };
    $collectColumnLeaves($template->columnHeaders);

    $rowLeaves = collect();
    $rowMatrix = [];
    $walkRow = function ($node, $depth) use (&$walkRow, &$rowLeaves, &$rowMatrix, $maxRowDepth) {
        if ($node->is_leaf) {
            $idx = $rowLeaves->count();
            $rowLeaves->push($node);
            $rowMatrix[$idx][$depth] = ['node' => $node, 'rowspan' => 1, 'colspan' => $maxRowDepth - $depth];
            return;
        }
        $startIdx = $rowLeaves->count();
        foreach ($node->children as $child) $walkRow($child, $depth + 1);
        $rowMatrix[$startIdx][$depth] = ['node' => $node, 'rowspan' => $node->leaf_span, 'colspan' => 1];
    };
    foreach ($template->rowHeaders as $rootRow) $walkRow($rootRow, 0);

    $cellsByRowCol = [];
    foreach ($template->cells as $cell) {
        $cellsByRowCol[$cell->row_header_id][$cell->column_header_id] = $cell;
    }
@endphp

<div class="overflow-x-auto rounded-box border-base-200 border">
    <table class="table table-zebra table-pin-rows w-full">
        <thead>
            @for ($d = 0; $d < $maxColDepth; $d++)
                <tr>
                    @if ($d === 0)
                        <th rowspan="{{ $maxColDepth }}" colspan="{{ $maxRowDepth }}" class="bg-base-200"></th>
                    @endif
                    @foreach ($columnLevels[$d] as $node)
                        <th colspan="{{ $node->leaf_span }}"
                            rowspan="{{ $node->is_leaf ? ($maxColDepth - $d) : 1 }}"
                            class="bg-base-200 text-center whitespace-nowrap">
                            {{ $node->label }}
                        </th>
                    @endforeach
                </tr>
            @endfor
        </thead>
        <tbody>
            @forelse ($rowLeaves as $i => $leaf)
                <tr>
                    @for ($d = 0; $d < $maxRowDepth; $d++)
                        @if (isset($rowMatrix[$i][$d]))
                            <th rowspan="{{ $rowMatrix[$i][$d]['rowspan'] }}"
                                colspan="{{ $rowMatrix[$i][$d]['colspan'] }}"
                                class="bg-base-100 text-left whitespace-nowrap">
                                {{ $rowMatrix[$i][$d]['node']->label }}
                            </th>
                        @endif
                    @endfor

                    @foreach ($columnLeaves as $colLeaf)
                        @php
                            $cell = $cellsByRowCol[$leaf->id][$colLeaf->id] ?? null;
                            $value = $cell ? ($values[$cell->id] ?? null) : null;
                        @endphp
                        <td class="whitespace-nowrap">
                            @if (!$cell)
                                <span class="text-base-content/30 text-xs">—</span>
                            @elseif (is_null($value) || $value === '')
                                <span class="text-base-content/40 text-xs italic">Belum diisi</span>
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $maxRowDepth + $columnLeaves->count() }}" class="text-center italic text-base-content/50 py-6">
                        Data tabel masih kosong.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>