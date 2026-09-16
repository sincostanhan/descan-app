<?php

namespace App\Http\Controllers;

use App\Actions\UpdateOrganization;
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
     *
     * TIDAK pakai route-model-binding lagi (konsisten dengan HomeController/HistoryController) —
     * supaya Kelurahan yang BELUM PERNAH punya baris Organization sekalipun tetap bisa buka form ini.
     */
    public function edit(Organization $organization)
    {
        $organization = Organization::first() ?? new Organization();

        return view('admin.organization.edit', compact('organization'));
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(UpdateOrganizationRequest $request, Organization $organization)
    // {
    //     $validatedData = $request->validated();

    //     $organization->update($validatedData);

    //     return back();
    // }
    /**
     * Update the specified resource in storage.
     * Create-or-update lewat Action Class, bukan update() langsung ke instance ter-bind.
     */
    public function update(UpdateOrganizationRequest $request, UpdateOrganization $updater)
    {
        $updater->handle($request->validated());

        return back()->with('success', 'Data Organisasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        //
    }
}