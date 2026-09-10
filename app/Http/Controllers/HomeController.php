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
        $galleryPhotos = GalleryPhoto::with('gallery')->latest()->get();
        $potensiWisataPhotos = PotensiWisataPhoto::with('potensiWisata')->latest()->get();
        return view('admin.home.edit', compact('home', 'galleryPhotos', 'potensiWisataPhotos'));
    }

    // Proses Simpan Admin
    public function update(UpdateHomeRequest $request, UpdateHome $updater)
    {
        $updater->handle($request->validated());

        return redirect()->route('admin.home.edit')
            ->with('success', 'Konten Halaman Beranda berhasil diperbarui!');
    }
}
