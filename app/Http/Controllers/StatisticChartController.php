<?php

namespace App\Http\Controllers;

use App\Actions\CreateStatisticChart;
use App\Actions\ParseExcelToArray;
use App\Actions\UpdateStatisticChart;
use App\Http\Requests\StoreStatisticChartRequest;
use App\Http\Requests\UpdateStatisticChartRequest;
use App\Models\StatisticalTable;
use App\Models\StatisticChart;
use App\Models\StatisticTableEntry;
use Illuminate\Http\Request;

class StatisticChartController extends Controller
{
    /**
     * Show the form for creating a new chart.
     */
    public function create(StatisticTableEntry $statistic_table_entry)
    {
        $allColumns = $statistic_table_entry->columns; // ganti nama, biar jelas ini yang MENTAH

        $columnTypes = $statistic_table_entry->template->headers()
            ->where('axis', 'column')->where('is_leaf', true)
            ->pluck('data_type', 'label');

        $numericHeaders = collect($allColumns)->reject(fn ($h) => ($columnTypes[$h] ?? null) === 'text')->values()->all();

        return view('admin.statistic-chart.create', [
            'statisticalTableEntry' => $statistic_table_entry,
            'headers' => $numericHeaders,
            'textColumns' => collect($allColumns)->filter(fn ($h) => ($columnTypes[$h] ?? null) === 'text')->values(),
            'chartTypes' => $this->getChartTypes(),
            'hasNumericColumns' => count($numericHeaders) > 1, // sekarang hitung yang SUDAH difilter
        ]);
    }

    /**
     * Store a newly created chart in storage.
     */
    // public function store(
    //     StoreStatisticChartRequest $request, 
    //     // StatisticalTable $statistical_table, 
    //     StatisticTableEntry $statistic_table_entry, 
    //     CreateStatisticChart $createAction
    // ) {
    //     // Panggil Action Class dan kirim data yang sudah tervalidasi
    //     // $createAction->handle($statistical_table, $request->validated());
    //     $createAction->handle($statistic_table_entry, $request->validated());

    //     // // Arahkan kembali ke halaman index tabel statistik (atau halaman detail tabel jika ada)
    //     // return redirect()->route('statistical-tables.index')
    //     //                  ->with('success', 'Visualisasi grafik berhasil ditambahkan!');
    //     // TODO(step Controller Kelurahan): ganti ke route('admin.statistic-table-entries.index')
    //     // setelah controller pengganti StatisticalTableController dibuat di step berikutnya.
    //     // return back()->with('success', 'Visualisasi grafik berhasil ditambahkan!');
    //     redirect()->route('admin.statistic-table-entries.index')
    //         ->with('success', 'Visualisasi grafik berhasil ditambahkan!');
    // }
    public function store(
        StoreStatisticChartRequest $request, 
        StatisticTableEntry $statistic_table_entry, 
        CreateStatisticChart $createAction
    ) {
        $createAction->handle($statistic_table_entry, $request->validated());

        return redirect()->route('admin.statistic-table-entries.index')
            ->with('success', 'Visualisasi grafik berhasil ditambahkan!');
    }

    public function edit(StatisticTableEntry $statistic_table_entry, StatisticChart $statistic_chart)
    {
        $allColumns = $statistic_table_entry->columns;

        $columnTypes = $statistic_table_entry->template->headers()
            ->where('axis', 'column')->where('is_leaf', true)
            ->pluck('data_type', 'label');

        $numericHeaders = collect($allColumns)->reject(fn ($h) => ($columnTypes[$h] ?? null) === 'text')->values()->all();

        return view('admin.statistic-chart.edit', [
            'statisticalTableEntry' => $statistic_table_entry,
            'chart' => $statistic_chart,
            'headers' => $numericHeaders,
            'textColumns' => collect($allColumns)->filter(fn ($h) => ($columnTypes[$h] ?? null) === 'text')->values(),
            'chartTypes' => $this->getChartTypes(),
            'hasNumericColumns' => count($numericHeaders) > 1,
        ]);
    }

    public function update(
        UpdateStatisticChartRequest $request,
        StatisticTableEntry $statistic_table_entry,
        StatisticChart $statistic_chart,
        UpdateStatisticChart $updateAction
    ) {
        $updateAction->handle($statistic_chart, $request->validated());

        return redirect()->route('admin.statistic-table-entries.index')
            ->with('success', 'Konfigurasi grafik berhasil diperbarui.');
    }

    /**
     * Menyiapkan opsi tipe grafik.
     */
    private function getChartTypes(): array
    {
        return [
            'pie' => 'Pie Chart',
            'doughnut' => 'Doughnut Chart',
            'bar_clustered' => 'Bar Chart (Clustered)',
            'bar_stacked' => 'Bar Chart (Stacked)',
            'bar_stacked_100' => 'Bar Chart (100% Stacked)',
            'column_clustered' => 'Column Chart (Clustered)',
            'column_stacked' => 'Column Chart (Stacked)',
            'column_stacked_100' => 'Column Chart (100% Stacked)',
            'line_markers' => 'Line Chart with Markers',
            'line_stacked' => 'Stacked Line Chart with Markers',
            'line_stacked_100' => '100% Stacked Line Chart with Markers',
        ];
    }
}
