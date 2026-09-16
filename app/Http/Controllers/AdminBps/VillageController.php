<?php

namespace App\Http\Controllers\AdminBps;

use App\Actions\CreateVillage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVillageRequest;
use App\Http\Requests\UpdateVillageRequest;
use App\Models\Village;

class VillageController extends Controller
{
    public function index()
    {
        $villages = Village::withCount(['users', 'statisticTableEntries'])
            ->with('setting')
            ->orderBy('name')
            ->get();

        return view('admin-bps.villages.index', compact('villages'));
    }

    public function create()
    {
        return view('admin-bps.villages.create');
    }

    public function store(StoreVillageRequest $request, CreateVillage $action)
    {
        $action->handle($request->validated());

        return redirect()->route('admin-bps.villages.index')
            ->with('success', 'Kelurahan baru berhasil dibuat. Silakan daftarkan Admin Kelurahan-nya di menu Admin Kelurahan.');
    }

    public function edit(Village $village)
    {
        return view('admin-bps.villages.edit', compact('village'));
    }

    public function update(UpdateVillageRequest $request, Village $village)
    {
        $village->update($request->validated());

        return redirect()->route('admin-bps.villages.index')
            ->with('success', 'Data Kelurahan berhasil diperbarui.');
    }

    public function destroy(Village $village)
    {
        // Proteksi manual: cegah hapus Kelurahan yang masih punya Admin atau data statistik,
        // supaya tidak ada data "menggantung" akibat penghapusan tidak sengaja.
        if ($village->users()->exists() || $village->statisticTableEntries()->exists()) {
            return back()->withErrors([
                'village' => 'Kelurahan tidak bisa dihapus karena masih memiliki Admin atau data statistik. Hapus/pindahkan data tersebut terlebih dahulu.',
            ]);
        }

        $village->delete();

        return redirect()->route('admin-bps.villages.index')
            ->with('success', 'Kelurahan berhasil dihapus.');
    }
}