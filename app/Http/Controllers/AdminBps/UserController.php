<?php

namespace App\Http\Controllers\AdminBps;

use App\Actions\CreateAdminUser;
use App\Actions\DeleteAdminUser;
use App\Actions\UpdateAdminUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Hanya menampilkan user dengan role admin-kelurahan
        $admins = User::role('admin-kelurahan')->get();
        return view('admin-bps.users.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $villages = Village::orderBy('name', 'asc')->get();
        return view('admin-bps.users.create', compact('villages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request, CreateAdminUser $createAdminUser)
    {
        $createAdminUser->handle($request->validated());

        return redirect()->route('admin-bps.users.index')->with('success', 'Admin Kelurahan berhasil didaftarkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $villages = Village::orderBy('name', 'asc')->get();
        return view('admin-bps.users.edit', compact('user', 'villages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, User $user, UpdateAdminUser $action)
    {
        // Kirim user_id ke request untuk validasi unique
        $request->merge(['user_id' => $user->id]);
        
        $action->handle($user, $request->validated());
        return redirect()->route('admin-bps.users.index')->with('success', 'Data Admin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, DeleteAdminUser $action)
    {
        $action->handle($user);
        return redirect()->route('admin-bps.users.index')->with('success', 'Admin berhasil dihapus.');
    }
}
