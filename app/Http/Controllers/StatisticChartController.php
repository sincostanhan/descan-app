<?php

namespace App\Http\Controllers;

use App\Actions\CreateStatisticChart;
use App\Actions\ParseExcelToArray;
use App\Http\Requests\StoreStatisticChartRequest;
use App\Models\StatisticalTable;
use App\Models\StatisticTableEntry;
use Illuminate\Http\Request;

class StatisticChartController extends Controller
{
    /**
     * Show the form for creating a new chart.
     */
    public function create(StatisticTableEntry $statistic_table_entry)
    {
        // // KARENA data Excel sudah tersimpan rapi di DB saat tabel dibuat,
        // // kita cukup memanggil field 'columns' untuk dijadikan pilihan Dropdown!
        // $headers = $statistical_table->columns;
        // $headers diambil dari accessor legacy-shape (getColumnsAttribute) di StatisticTableEntry,
        // sehingga elemen pertamanya tetap label kolom "kunci" (dulu dari Excel), diikuti kolom data.
        $headers = $statistic_table_entry->columns;

        return view('admin.statistic-chart.create', [
            // 'statisticalTable' => $statistical_table,
            'statisticalTableEntry' => $statistic_table_entry,
            'headers' => $headers,
            'chartTypes' => $this->getChartTypes()
        ]);
    }

    /**
     * Store a newly created chart in storage.
     */
    public function store(
        StoreStatisticChartRequest $request, 
        // StatisticalTable $statistical_table, 
        StatisticTableEntry $statistic_table_entry, 
        CreateStatisticChart $createAction
    ) {
        // Panggil Action Class dan kirim data yang sudah tervalidasi
        // $createAction->handle($statistical_table, $request->validated());
        $createAction->handle($statistic_table_entry, $request->validated());

        // // Arahkan kembali ke halaman index tabel statistik (atau halaman detail tabel jika ada)
        // return redirect()->route('statistical-tables.index')
        //                  ->with('success', 'Visualisasi grafik berhasil ditambahkan!');
        // TODO(step Controller Kelurahan): ganti ke route('admin.statistic-table-entries.index')
        // setelah controller pengganti StatisticalTableController dibuat di step berikutnya.
        return back()->with('success', 'Visualisasi grafik berhasil ditambahkan!');
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
