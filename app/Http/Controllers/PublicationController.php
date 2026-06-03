<?php

namespace App\Http\Controllers;

use App\Actions\CreatePublication;
use App\Actions\DeletePublication;
use App\Actions\UpdatePublication;
use App\Http\Requests\StorePublicationRequest;
use App\Http\Requests\UpdatePublicationRequest;
use App\Models\Publication;
use App\Traits\HasPaginationLimit;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    use HasPaginationLimit;

    // Halaman Publik
    public function indexPublic(Request $request)
    {
        $perPage = $this->getPaginationLimit($request);
        // $search = $request->get('search');

        // $query = Publication::query();

        // if ($search) {
        //     $query->where('title', 'like', '%' . $search . '%');
        // }

        $query = Publication::query()
            ->when($request->get('search'), function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            });

        // $publications = Publication::latest()
        $publications = $query
            ->orderBy('id', 'desc')
            ->paginate($perPage);
            
        return view('publication', compact('publications', 'perPage'));
    }

    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $perPage = $this->getPaginationLimit($request);

    //     $publications = Publication::latest()
    //         ->orderBy('id', 'desc')
    //         ->paginate($perPage);

    //     return view('admin.publication.index', compact('publications', 'perPage'));
    // }
    public function index(Request $request)
    {
        $perPage = $this->getPaginationLimit($request);

        $sortBy = $request->get('sort_by');
        $sortDir = strtolower($request->get('sort_dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        // $search = $request->get('search');

        // $query = Publication::query();

        // if ($search) {
        //     $query->where('title', 'like', '%' . $search . '%');
        // }

        $query = Publication::query()
            ->when($request->get('search'), function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%');
            });

        if ($sortBy) {
            $allowedSorts = ['title', 'updated_at'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDir);
            }
        } else {
            // Default urutan terbaru
            $query->orderBy('created_at', 'desc');
        }

        $publications = $query->paginate($perPage);

        return view('admin.publication.index', compact('publications', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.publication.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePublicationRequest $request, CreatePublication $createPublication)
    {
        $createPublication->handle($request->validated());
        return redirect()->route('admin.publication.index');
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
    public function edit(Publication $publication)
    {
        return view('admin.publication.edit', compact('publication'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublicationRequest $request, Publication $publication,UpdatePublication $updatePublication)
    {
        $updatePublication->handle($publication, $request->validated());
        return redirect()->route('admin.publication.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication, DeletePublication $deletePublication)
    {
        $deletePublication->handle($publication);
        return redirect()->route('admin.publication.index');
    }
}
