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
use App\Traits\HasPaginationLimit;
use Illuminate\Http\Request;

class StatisticalTableController extends Controller
{
    use HasPaginationLimit;

    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $perPage = $this->getPaginationLimit($request);

    //     $tables = StatisticalTable::latest()
    //         ->paginate($perPage);
        
    //     return view('admin.statistical-table.index', compact('tables', 'perPage'));
    // }
    public function index(Request $request)
    {
        $perPage = $this->getPaginationLimit($request);

        $sortBy = $request->get('sort_by');
        $sortDir = strtolower($request->get('sort_dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        // $search = $request->get('search');

        // $query = StatisticalTable::query();

        // if ($search) {
        //     $query->where('title', 'like', '%' . $search . '%');
        // }

        $query = StatisticalTable::query()
            ->when($request->get('search'), function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            });

        if ($sortBy) {
            $allowedSorts = ['publication', 'title', 'updated_at'];
            if (in_array($sortBy, $allowedSorts)) {
                if ($sortBy === 'publication') {
                    $query->orderBy('publication', $sortDir)->orderBy('chapter', 'asc');
                } else {
                    $query->orderBy($sortBy, $sortDir);
                }
            }
        } else {
            // Default sorting
            $query->orderBy('chapter', 'asc')->orderBy('created_at', 'desc');
        }

        $tables = $query->paginate($perPage);

        return view('admin.statistical-table.index', compact('tables', 'perPage'));
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
        // Muat relasi chart agar bisa dipakai di form
        $statistical_table->load('chart');

        // Mengirim data tabel yang sudah ada ke view edit
        return view('admin.statistical-table.edit', [
            'table' => $statistical_table,
            'columns' => $statistical_table->columns,
            'content' => $statistical_table->content,
            'chart' => $statistical_table->chart,
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
