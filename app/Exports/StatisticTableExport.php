<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StatisticTableExport implements FromArray, WithTitle, WithStyles, WithEvents, ShouldAutoSize
{
    protected int $headerRow = 1;
    protected int $lastDataRow = 1;
    protected ?int $sourceRow = null;
    /** Nomor baris sheet yang berisi keterangan (untuk styling & merge). */
    protected array $descriptionRows = [];

    public function __construct(
        protected array $columns,
        protected array $rows,
        protected string $sheetTitle = 'Data',
        protected ?string $source = null,
        // false untuk CSV: biar tetap tabular murni (header + data saja), aman untuk di-import ulang
        protected bool $richLayout = true,
        // Keterangan per baris (dari StatisticTableEntry::description_lines)
        protected array $descriptionLines = [],
    ) {}

    public function title(): string
    {
        return substr($this->sheetTitle, 0, 31); // batas nama sheet Excel
    }

    // public function array(): array
    // {
    //     $grid = [];

    //     if ($this->richLayout) {
    //         // Baris 1: Judul tabel
    //         $grid[] = [$this->sheetTitle];
    //         // Baris 2: pemisah kosong
    //         $grid[] = [];
    //     }

    //     // Baris header kolom (otomatis jadi baris 3)
    //     $this->headerRow = count($grid) + 1;
    //     $grid[] = $this->columns;

    //     // Baris-baris data
    //     foreach ($this->rows as $row) {
    //         $grid[] = collect($this->columns)->map(fn ($col) => $row[$col] ?? null)->all();
    //     }
    //     $this->lastDataRow = count($grid);

    //     // Baris sumber data (di bawah tabel)
    //     if ($this->richLayout && !empty($this->source)) {
    //         $grid[] = []; // pemisah kosong
    //         $this->sourceRow = count($grid) + 1;
    //         $grid[] = ["Sumber Data: {$this->source}"];
    //     }

    //     return $grid;
    // }
    public function array(): array
    {
        $grid = [];

        if ($this->richLayout) {
            // Baris 1: Judul tabel
            $grid[] = [$this->sheetTitle];
            // Baris 2: pemisah kosong — pakai [''] bukan [], agar baris tetap "tertulis" fisik
            $grid[] = [''];
        }

        // Baris header kolom
        $this->headerRow = count($grid) + 1;
        $grid[] = $this->columns;

        // Baris-baris data
        foreach ($this->rows as $row) {
            $grid[] = collect($this->columns)->map(fn ($col) => $row[$col] ?? null)->all();
        }
        $this->lastDataRow = count($grid);

        // // Baris sumber data (di bawah tabel)
        // if ($this->richLayout && !empty($this->source)) {
        //     $grid[] = ['']; // pemisah kosong — sama, pakai [''] bukan []
        //     $this->sourceRow = count($grid) + 1;
        //     $grid[] = ["Sumber Data: {$this->source}"];
        // }

        // // Baris keterangan: 1 baris → inline "Keterangan: ...",
        // // >1 baris → judul "Keterangan:" lalu tiap baris di baris sheet sendiri
        // if ($this->richLayout && !empty($this->descriptionLines)) {
        //     if ($this->sourceRow === null) {
        //         $grid[] = ['']; // pemisah kosong hanya jika belum ada baris sumber
        //     }

        //     if (count($this->descriptionLines) === 1) {

        // Baris keterangan (di bawah tabel): 1 baris → inline "Keterangan: ...",
        // >1 baris → judul "Keterangan:" lalu tiap baris di baris sheet sendiri
        if ($this->richLayout && !empty($this->descriptionLines)) {
            $grid[] = ['']; // pemisah kosong antara tabel dan keterangan

            if (count($this->descriptionLines) === 1) {
                $this->descriptionRows[] = count($grid) + 1;
                $grid[] = ["Keterangan: {$this->descriptionLines[0]}"];
            } else {
                $this->descriptionRows[] = count($grid) + 1;
                $grid[] = ['Keterangan:'];

                foreach ($this->descriptionLines as $line) {
                    $this->descriptionRows[] = count($grid) + 1;
                    $grid[] = [$line];
                }
            }
        }

        // Baris sumber data (setelah keterangan). Pemisah kosong di sini sekaligus
        // menjadi pemisah antara keterangan dan sumber jika keduanya ada.
        if ($this->richLayout && !empty($this->source)) {
            $grid[] = ['']; // pemisah kosong — pakai [''] bukan [], agar baris tetap tertulis fisik
            $this->sourceRow = count($grid) + 1;
            $grid[] = ["Sumber Data: {$this->source}"];
        }

        return $grid;
    }

    /**
     * Semua styling di sini otomatis diabaikan oleh writer CSV — tidak error, hanya tidak berefek.
     */
    public function styles(Worksheet $sheet): array
    {
        $lastColumn = Coordinate::stringFromColumnIndex(count($this->columns));

        if ($this->richLayout) {
            $sheet->getStyle('A1')->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
            ]);
        }

        // Border tipis untuk area tabel (header + data) saja — judul & sumber tidak diborder
        $tableRange = "A{$this->headerRow}:{$lastColumn}{$this->lastDataRow}";
        $sheet->getStyle($tableRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF999999'],
                ],
            ],
        ]);

        // Baris header kolom: bold + background abu-abu muda + rata tengah
        $sheet->getStyle("A{$this->headerRow}:{$lastColumn}{$this->headerRow}")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE5E7EB'],
            ],
        ]);

        if ($this->sourceRow) {
            $sheet->getStyle("A{$this->sourceRow}")->applyFromArray([
                'font' => ['italic' => true, 'size' => 10],
            ]);
        }

        foreach ($this->descriptionRows as $row) {
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => ['size' => 10],
            ]);
        }

        return [];
    }

    /**
     * Gabungkan sel judul & sumber data agar melebar penuh mengikuti lebar tabel (khusus XLSX).
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                if (!$this->richLayout || count($this->columns) <= 1) {
                    return;
                }

                $lastColumn = Coordinate::stringFromColumnIndex(count($this->columns));

                $event->sheet->mergeCells("A1:{$lastColumn}1");

                if ($this->sourceRow) {
                    $event->sheet->mergeCells("A{$this->sourceRow}:{$lastColumn}{$this->sourceRow}");
                }

                foreach ($this->descriptionRows as $row) {
                    $event->sheet->mergeCells("A{$row}:{$lastColumn}{$row}");
                }
            },
        ];
    }

    // public function getCsvSettings(): array
    // {
    //     return [
    //         'use_bom' => true, // paksa Excel baca file CSV ini sebagai UTF-8
    //     ];
    // }
}