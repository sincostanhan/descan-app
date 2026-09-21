<?php

namespace App\Http\Controllers;

use App\Exports\StatisticTableExport;
use App\Models\StatisticalTable;
use App\Models\StatisticTableEntry;
use App\Traits\HasPaginationLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class PublicStatisticController extends Controller
{
    use HasPaginationLimit;

    // public function index(Request $request)
    // {
    //     $perPage = $this->getPaginationLimit($request);

    //     // Ambil data tabel, urutkan berdasarkan Bab (Chapter) lalu data terbaru
    //     $tables = StatisticalTable::orderBy('chapter', 'asc')
    //         ->orderBy('created_at', 'desc')
    //         ->paginate($perPage);
    //         // ->paginate(1);

    //     return view('statistic.index', compact('tables', 'perPage'));
    // }
    public function index(Request $request)
    {
        $perPage = $this->getPaginationLimit($request);

        // Ambil parameter sorting (Akan bernilai null jika belum ada kolom yang diklik)
        $sortBy = $request->get('sort_by');
        $sortDir = strtolower($request->get('sort_dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        // $search = $request->get('search');

        // // Bangun query
        // $query = StatisticalTable::query();

        // if ($search) {
        //     $query->where('title', 'like', '%' . $search . '%');
        // }

        // $query = StatisticalTable::query()
        //     ->when($request->get('search'), function ($query, $search) {
        //         $query->where('title', 'like', '%' . $search . '%');
        //     });
        // $query = StatisticTableEntry::query()
        //     ->whereHas('template', fn ($q) => $q->where('is_active', true))
        //     ->with('template')
        //     ->when($request->get('search'), function ($q, $search) {
        //         $q->where('title', 'like', "%{$search}%")
        //           ->orWhereHas('template', fn ($t) => $t->where('title', 'like', "%{$search}%"));
        //     });
        $query = StatisticTableEntry::query()
            ->whereHas('template', fn ($q) => $q->where('is_active', true))
            ->with(['template', 'chart'])   // tambah 'chart', dipakai badge status Grafik
            ->when($request->get('search'), function ($q, $search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhereHas('template', fn ($t) => $t->where('title', 'like', "%{$search}%"));
            });

        if ($sortBy && in_array($sortBy, ['title', 'updated_at'])) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // // Terapkan urutan jika ada kolom yang sedang aktif diklik
        // if ($sortBy) {
        //     $allowedSorts = ['publication', 'title', 'updated_at'];
        //     if (in_array($sortBy, $allowedSorts)) {
        //         if ($sortBy === 'publication') {
        //             $query->orderBy('publication', $sortDir)->orderBy('chapter', 'asc');
        //         } else {
        //             $query->orderBy($sortBy, $sortDir);
        //         }
        //     }
        // } else {
        //     // Default sorting saat kondisi awal (semua panah arrow-up-down)
        //     $query->orderBy('chapter', 'asc')->orderBy('created_at', 'desc');
        // }

        $tables = $query->paginate($perPage);

        return view('statistic.index', compact('tables', 'perPage'));
    }

    // public function show(StatisticTableEntry $statistic)
    // {
    //     // // Muat relasi chart agar bisa ditampilkan
    //     // $statistic->load('chart');
    //     $statistic->load(['chart', 'template.headers', 'values.templateCell.columnHeader']);

    //     return view('statistic.show', compact('statistic'));
    // }
    public function show(StatisticTableEntry $statistic)
    {
        $villageId = $statistic->village_id;

        // Sama seperti eager-load StatisticTableEntryController::edit() — dibutuhkan supaya
        // <x-statistic-table-readonly> bisa render header bertingkat tanpa N+1.
        // 'values.templateCell.columnHeader' tetap dipertahankan karena masih dipakai
        // accessor legacy $statistic->columns / $statistic->content untuk skrip Chart.js di bawah.
        $statistic->load([
            'chart',
            'template.rowHeaders' => fn ($q) => $q->where(fn ($qq) => $qq->whereNull('village_id')->orWhere('village_id', $villageId)),
            'template.rowHeaders.children',
            'template.rowHeaders.children.children',
            'template.columnHeaders.children.children',
            'template.cells' => fn ($q) => $q->where(fn ($qq) => $qq->whereNull('village_id')->orWhere('village_id', $villageId)),
            'values.templateCell.columnHeader',
        ]);

        return view('statistic.show', compact('statistic'));
    }

    /**
     * Download data tabel statistik publik dalam format xlsx, csv, atau json.
     */
    public function download(StatisticTableEntry $statistic, string $format)
    {
        abort_unless(in_array($format, ['xlsx', 'csv', 'json']), 404);

        // Eager-load minimal yang dibutuhkan accessor columns/content (sama seperti show())
        $statistic->load(['template.headers', 'values.templateCell.columnHeader']);

        $title = $statistic->title ?: $statistic->template->title;
        $filename = Str::slug($title) ?: 'tabel-statistik';
        $columns = $statistic->columns;
        $rows = $statistic->content;

        if ($format === 'json') {
            return response()->streamDownload(function () use ($title, $statistic, $columns, $rows) {
                echo json_encode([
                    'title' => $title,
                    'source' => $statistic->source,
                    'columns' => $columns,
                    'data' => $rows,
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }, "{$filename}.json", ['Content-Type' => 'application/json']);
        }

        $exportFormat = $format === 'csv' ? ExcelFormat::CSV : ExcelFormat::XLSX;

        return Excel::download(
            new StatisticTableExport($columns, $rows, $title),
            "{$filename}.{$format}",
            $exportFormat
        );
    }
}
