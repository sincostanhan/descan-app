<?php

namespace App\Http\Controllers;

use App\Actions\CreatePotensiWisata;
use App\Actions\DeletePotensiWisata;
use App\Actions\DeletePotensiWisataPhoto;
use App\Actions\UpdatePotensiWisata;
use App\Http\Requests\StorePotensiWisataRequest;
use App\Http\Requests\UpdatePotensiWisataRequest;
use App\Models\PotensiWisata;
use App\Models\PotensiWisataPhoto;
use Illuminate\Http\Request;

class PotensiWisataController extends Controller
{
    // Halaman Publik
    public function indexPublik()
    {
        $items = PotensiWisata::with('photos')->latest()->get();
        $situsBersejarah = $items->where('kategori', 'situs_bersejarah');
        $umum = $items->where('kategori', 'umum');

        return view('potensi-wisata', compact('situsBersejarah', 'umum'));
    }

    public function index()
    {
        $items = PotensiWisata::with('photos')->latest()->get();
        return view('admin.potensi-wisata.index', compact('items'));
    }

    public function create()
    {
        return view('admin.potensi-wisata.create');
    }

    public function store(StorePotensiWisataRequest $request, CreatePotensiWisata $action)
    {
        $action->handle($request->validated());
        return redirect()->route('admin.potensi-wisata.index')->with('success', 'Potensi Wisata berhasil ditambahkan!');
    }

    public function edit(PotensiWisata $potensi_wisata)
    {
        $potensi_wisata->load('photos');
        return view('admin.potensi-wisata.edit', ['item' => $potensi_wisata]);
    }

    public function update(UpdatePotensiWisataRequest $request, PotensiWisata $potensi_wisata, UpdatePotensiWisata $action)
    {
        $action->handle($potensi_wisata, $request->validated());
        return redirect()->route('admin.potensi-wisata.index')->with('success', 'Potensi Wisata berhasil diperbarui!');
    }

    public function destroyPhoto(PotensiWisataPhoto $photo, DeletePotensiWisataPhoto $action)
    {
        $action->handle($photo);
        return back()->with('success', 'Foto berhasil dihapus!');
    }

    public function destroy(PotensiWisata $potensi_wisata, DeletePotensiWisata $action)
    {
        $action->handle($potensi_wisata);
        return redirect()->route('admin.potensi-wisata.index')->with('success', 'Potensi Wisata berhasil dihapus!');
    }

    public function updateFeatured(Request $request)
    {
        $validated = $request->validate([
            'featured_photos' => ['nullable', 'array'],
            'featured_photos.*' => ['exists:potensi_wisata_photos,id'],
        ]);

        PotensiWisataPhoto::whereHas('potensiWisata')->update(['tampil_beranda' => false]);
        PotensiWisataPhoto::whereHas('potensiWisata')
            ->whereIn('id', $validated['featured_photos'] ?? [])
            ->update(['tampil_beranda' => true]);

        return redirect()->route('admin.home.edit')->with('success', 'Foto Potensi Wisata untuk beranda berhasil diperbarui!');
    }
}