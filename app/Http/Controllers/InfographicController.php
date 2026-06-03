<?php

namespace App\Http\Controllers;

use App\Actions\CreateInfographic;
use App\Actions\DeleteInfographic;
use App\Actions\UpdateInfographic;
use App\Http\Requests\StoreInfographicRequest;
use App\Http\Requests\UpdateInfographicRequest;
use App\Models\Infographic;
use App\Traits\HasPaginationLimit;
use Illuminate\Http\Request;

class InfographicController extends Controller
{
    use HasPaginationLimit;

    // Halaman Publik
    public function indexPublic(Request $request)
    {
        $perPage = $this->getPaginationLimit($request);
        // $search = $request->get('search');

        // $query = Infographic::query();

        // if ($search) {
            // $query->where('title', 'like', '%' . $search . '%');
        // }

        $query = Infographic::query()
            ->when($request->get('search'), function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            });        

        // $infographics = Infographic::latest()
        $infographics = $query
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        
        return view('infographic', compact('infographics', 'perPage'));
    }

    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $perPage = $this->getPaginationLimit($request);

    //     $infographics = Infographic::latest()
    //         ->orderBy('id', 'desc')
    //         ->paginate($perPage);

    //     return view('admin.infographic.index', compact('infographics', 'perPage'));
    // }
    public function index(Request $request)
    {
        $perPage = $this->getPaginationLimit($request);

        $sortBy = $request->get('sort_by');
        $sortDir = strtolower($request->get('sort_dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        // $search = $request->get('search');

        // $query = Infographic::query();

        // if ($search) {
            // $query->where('title', 'like', '%' . $search . '%');
        // }

        $query = Infographic::query()
            ->when($request->get('search'), function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            });    

        if ($sortBy) {
            $allowedSorts = ['title', 'updated_at'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDir);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $infographics = $query->paginate($perPage);

        return view('admin.infographic.index', compact('infographics', 'perPage'));
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
