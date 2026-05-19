<?php

namespace App\Http\Controllers;

use App\Actions\UpdateHistory;
use App\Http\Requests\UpdateHistoryRequest;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $history = History::first();
    
        // Jika data tidak ada, atau ada tapi dinonaktifkan, lempar error 404
        if (!$history || !$history->is_active) {
            abort(404, 'Halaman sejarah belum tersedia.');
        }

        return view('history', compact('history'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(History $history)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(History $history)
    public function edit()
    {
        // Ambil data pertama, jika belum ada kirim instance model kosong
        $history = History::first() ?? new History();
        
        return view('admin.history.edit', compact('history'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateHistoryRequest $request, History $history)
    // public function update(Request $request)
    public function update(UpdateHistoryRequest $request, UpdateHistory $updater)
    {
        $updater->handle($request->validated());

        return redirect()->route('admin.history.edit')
            ->with('success', 'Pengaturan sejarah kelurahan berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(History $history)
    {
        //
    }
}
