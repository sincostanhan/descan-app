<?php

namespace App\Http\Controllers;

use App\Actions\CreateGallery;
use App\Actions\DeleteGallery;
use App\Actions\DeleteGalleryPhoto;
use App\Actions\UpdateGallery;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{

    // Halaman Publik
    public function indexPublik()
    {
        $galleries = Gallery::with('photos')->latest()->get();
        return view('gallery', compact('galleries'));
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $galleries = Gallery::latest()->get();
        $galleries = Gallery::with('photos')->latest()->get();
        return view('admin.gallery.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGalleryRequest $request, CreateGallery $createGallery)
    {
        // dd($request->all());

        // Panggil Action Class dan kirim data yang sudah tervalidasi
        $createGallery->handle($request->validated());
        
        return redirect()->route('admin.gallery.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        // dd($gallery);
        // Load relasi foto agar bisa ditampilkan di halaman edit
        $gallery->load('photos');
        return view('admin.gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGalleryRequest $request, Gallery $gallery, UpdateGallery $updateGallery)
    {
        // Panggil Action Class Update
        $updateGallery->handle($gallery, $request->validated());

        return redirect()->route('admin.gallery.index');
    }

    /**
     * Remove SPECIFIC photo from storage. (Method Baru)
     */
    public function destroyPhoto(GalleryPhoto $photo, DeleteGalleryPhoto $deleteGalleryPhoto)
    {
        // Panggil Action Class untuk hapus 1 foto fisik & database
        $deleteGalleryPhoto->handle($photo);

        // Kembali ke halaman edit yang sama (refresh)
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery, DeleteGallery $deleteGallery)
    {
        $deleteGallery->handle($gallery);

        return redirect()->route('admin.gallery.index');
    }
}
