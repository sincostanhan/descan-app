<?php

namespace App\Http\Controllers;

use App\Actions\CreateInfographic;
use App\Actions\DeleteInfographic;
use App\Actions\UpdateInfographic;
use App\Http\Requests\StoreInfographicRequest;
use App\Http\Requests\UpdateInfographicRequest;
use App\Models\Infographic;
use Illuminate\Http\Request;

class InfographicController extends Controller
{
    // Halaman Publik
    public function indexPublic()
    {
        $infographics = Infographic::latest()->get();
        return view('infographic', compact('infographics'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $infographics = Infographic::latest()->get();
        return view('admin.infographic.index', compact('infographics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.infographic.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInfographicRequest $request, CreateInfographic $createInfographic)
    {
        $createInfographic->handle($request->validated());
        
        return redirect()->route('admin.infographic.index');
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
    public function edit(Infographic $infographic)
    {
        return view('admin.infographic.edit', compact('infographic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInfographicRequest $request, Infographic $infographic, UpdateInfographic $updateInfographic)
    {
        $updateInfographic->handle($infographic, $request->validated());
        
        return redirect()->route('admin.infographic.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Infographic $infographic, DeleteInfographic $deleteInfographic)
    {
        $deleteInfographic->handle($infographic);
        
        return redirect()->route('admin.infographic.index');
    }
}
