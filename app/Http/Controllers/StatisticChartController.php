<?php

namespace App\Http\Controllers;

use App\Actions\CreateStatisticChart;
use App\Actions\ParseExcelToArray;
use App\Http\Requests\StoreStatisticChartRequest;
use App\Models\StatisticalTable;
use Illuminate\Http\Request;

class StatisticChartController extends Controller
{
    /**
     * Show the form for creating a new chart.
     */
    public function create(StatisticalTable $statistical_table)
    {
        // KARENA data Excel sudah tersimpan rapi di DB saat tabel dibuat,
        // kita cukup memanggil field 'columns' untuk dijadikan pilihan Dropdown!
        $headers = $statistical_table->columns;

        return view('admin.statistic-chart.create', [
            'statisticalTable' => $statistical_table,
            'headers' => $headers,
            'chartTypes' => $this->getChartTypes()
        ]);
    }

    /**
     * Store a newly created chart in storage.
     */
    public function store(
        StoreStatisticChartRequest $request, 
        StatisticalTable $statistical_table, 
        CreateStatisticChart $createAction
    ) {
        // Panggil Action Class dan kirim data yang sudah tervalidasi
        $createAction->handle($statistical_table, $request->validated());

        // Arahkan kembali ke halaman index tabel statistik (atau halaman detail tabel jika ada)
        return redirect()->route('statistical-tables.index')
                         ->with('success', 'Visualisasi grafik berhasil ditambahkan!');
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
