<?php

namespace App\Http\Controllers;

use App\Actions\CreatePublication;
use App\Actions\DeletePublication;
use App\Actions\UpdatePublication;
use App\Http\Requests\StorePublicationRequest;
use App\Http\Requests\UpdatePublicationRequest;
use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{

    // Halaman Publik
    public function indexPublic()
    {
        $publications = Publication::latest()->get();
        return view('publication', compact('publications'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publications = Publication::latest()->get();
        return view('admin.publication.index', compact('publications'));
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
