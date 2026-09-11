<?php

namespace App\Http\Controllers;

use App\Actions\UpdateHome;
use App\Http\Requests\UpdateHomeRequest;
use App\Models\GalleryPhoto;
use App\Models\Home;
use App\Models\PotensiWisataPhoto;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Halaman Publik
    public function index()
    {
        $home = Home::first();
        return view('welcome', compact('home'));
    }

    // Halaman Edit Admin
    public function edit()
    {
        $home = Home::first() ?? new Home();
        // return view('admin.home.edit', compact('home'));
        // $galleryPhotos = GalleryPhoto::with('gallery')->latest()->get();
        // $potensiWisataPhotos = PotensiWisataPhoto::with('potensiWisata')->latest()->get();
        // return view('admin.home.edit', compact('home', 'galleryPhotos', 'potensiWisataPhotos'));
        $galleries = \App\Models\Gallery::with('photos')->orderBy('id')->get();
        $potensiWisatas = \App\Models\PotensiWisata::with('photos')->orderBy('id')->get();
        return view('admin.home.edit', compact('home', 'galleries', 'potensiWisatas'));
    }

    // Proses Simpan Admin
    public function update(UpdateHomeRequest $request, UpdateHome $updater)
    {
        $updater->handle($request->validated());

        return redirect()->route('admin.home.edit')
            ->with('success', 'Konten Halaman Beranda berhasil diperbarui!');
    }

    public function updateFeaturedGallery(Request $request)
    {
        $validated = $request->validate([
            'featured_galleries' => ['required', 'array', 'min:5'],
            'featured_galleries.*' => ['exists:galleries,id'],
            // 'featured_potensi_wisata' => ['nullable', 'array'],
            'featured_potensi_wisata' => ['required', 'array', 'min:5'],
            'featured_potensi_wisata.*' => ['exists:potensi_wisatas,id'],
        ], [
            'featured_galleries.required' => 'Pilih minimal 5 Galeri untuk ditampilkan di beranda.',
            'featured_galleries.min' => 'Pilih minimal 5 Galeri untuk ditampilkan di beranda.',
            'featured_potensi_wisata.required' => 'Pilih minimal 5 Potensi Wisata untuk ditampilkan di beranda.',
            'featured_potensi_wisata.min' => 'Pilih minimal 5 Potensi Wisata untuk ditampilkan di beranda.',
        ]);

        $galleryIds = $validated['featured_galleries'] ?? [];
        \App\Models\GalleryPhoto::whereHas('gallery')->update(['tampil_beranda' => false]);
        \App\Models\GalleryPhoto::whereHas('gallery', fn ($q) => $q->whereIn('id', $galleryIds))
            ->update(['tampil_beranda' => true]);

        $wisataIds = $validated['featured_potensi_wisata'] ?? [];
        \App\Models\PotensiWisataPhoto::whereHas('potensiWisata')->update(['tampil_beranda' => false]);
        \App\Models\PotensiWisataPhoto::whereHas('potensiWisata', fn ($q) => $q->whereIn('id', $wisataIds))
            ->update(['tampil_beranda' => true]);

        return redirect()->route('admin.home.edit')->with('success', 'Galeri & Potensi Wisata untuk beranda berhasil diperbarui!');
    }
}
