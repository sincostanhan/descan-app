<?php

namespace App\Http\Controllers;

use App\Actions\CreateStatisticalTable;
use App\Actions\DeleteStatisticalTable;
use App\Actions\ParseExcelToArray;
use App\Actions\UpdateStatisticalTable;
use App\Http\Requests\PreviewStatisticalTableRequest;
use App\Http\Requests\StoreStatisticalTableRequest;
use App\Http\Requests\UpdateStatisticalTableRequest;
use App\Models\StatisticalTable;
use Illuminate\Http\Request;

class StatisticalTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = StatisticalTable::latest()->get();
        return view('admin.statistical-table.index', compact('tables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.statistical-table.create');
    }

    public function preview(PreviewStatisticalTableRequest $request, ParseExcelToArray $parseExcel)
    {
        // Panggil Action Class dan kirim file yang sudah tervalidasi
        $parsedData = $parseExcel->handle($request->file('excel_file'));

        return view('admin.statistical-table.preview', [
            'columns' => $parsedData['columns'],
            'content' => $parsedData['content'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatisticalTableRequest $request, CreateStatisticalTable $createStatisticalTable)
    {
        // Panggil Action Class dan kirim data yang sudah tervalidasi
        $createStatisticalTable->handle($request->validated());

        return redirect()->route('admin.statistical-table.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatisticalTable $statistical_table)
    {
        // Mengirim data tabel yang sudah ada ke view edit
        return view('admin.statistical-table.edit', [
            'table' => $statistical_table,
            'columns' => $statistical_table->columns,
            'content' => $statistical_table->content,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateStatisticalTableRequest $request, 
        StatisticalTable $statistical_table, 
        UpdateStatisticalTable $updateAction
    )
    {
        $updateAction->handle($statistical_table, $request->validated());

        return redirect()->route('admin.statistical-table.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        StatisticalTable $statistical_table, 
        DeleteStatisticalTable $deleteAction
    )
    {
        $deleteAction->handle($statistical_table);

        return redirect()->route('admin.statistical-table.index');
    }
}
