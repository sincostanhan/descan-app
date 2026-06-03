<?php

namespace App\Http\Controllers;

use App\Models\StatisticalTable;
use App\Traits\HasPaginationLimit;
use Illuminate\Http\Request;

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

        $query = StatisticalTable::query()
            ->when($request->get('search'), function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            });

        // Terapkan urutan jika ada kolom yang sedang aktif diklik
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
            // Default sorting saat kondisi awal (semua panah arrow-up-down)
            $query->orderBy('chapter', 'asc')->orderBy('created_at', 'desc');
        }

        $tables = $query->paginate($perPage);

        return view('statistic.index', compact('tables', 'perPage'));
    }

    public function show(StatisticalTable $statistic)
    {
        // Muat relasi chart agar bisa ditampilkan
        $statistic->load('chart');

        return view('statistic.show', compact('statistic'));
    }
}
