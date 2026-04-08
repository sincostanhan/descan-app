<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organization = Organization::first();
        return view('organization', compact('organization'));
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
    public function show(Organization $organization)
    {
        // return view('organization', compact('organization'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        return view('admin.organization.edit', compact('organization'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $validatedData = $request->validated();

        $organization->update($validatedData);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        //
    }
}
