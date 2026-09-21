<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class StatisticTableExport implements FromArray, WithHeadings, WithTitle
{
    public function __construct(
        protected array $columns,
        protected array $rows,
        protected string $sheetTitle = 'Data',
    ) {}

    public function headings(): array
    {
        return $this->columns;
    }

    public function array(): array
    {
        // Pastikan urutan kolom tiap baris selalu konsisten dengan heading
        return collect($this->rows)
            ->map(fn ($row) => collect($this->columns)->map(fn ($col) => $row[$col] ?? null)->all())
            ->all();
    }

    public function title(): string
    {
        return substr($this->sheetTitle, 0, 31); // batas nama sheet Excel
    }
}