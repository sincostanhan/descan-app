<?php

namespace App\Http\Controllers;

use App\Actions\UpdateAbout;
use App\Http\Requests\UpdateAboutRequest;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $about = About::first();

    //     // Pastikan $about tidak null sebelum di-explode untuk menghindari error
    //     $misiRaw = $about->misi ?? ''; 
    //     // Pecah jadi array, lalu hapus angka/titik di awal baris menggunakan Regex
    //     $daftarMisi = array_map(function($item) {
    //         return preg_replace('/^\d+[\.\s]*/', '', trim($item));
    //     }, array_filter(explode("\n", $misiRaw)));
    //     // $daftarMisi = array_filter(explode("\n", $misiRaw));
        
    //     return view('about', compact('about', 'daftarMisi'));
    // }
    public function index()
    {
        $about = About::first() ?? new About();

        $misiRaw = $about->misi ?? '';
        $daftarMisi = array_map(function($item) {
            return preg_replace('/^\d+[\.\s]*/', '', trim($item));
        }, array_filter(explode("\n", $misiRaw)));

        return view('about', compact('about', 'daftarMisi'));
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
    public function show(About $about)
    {
        // return view('about', [
        //     'about' => $about
        // ]);
        // return view('about', compact('about'));
    }

    /**
     * Show the form for editing the specified resource.     
     * 
     * TIDAK pakai route-model-binding lagi (konsisten dengan HomeController/HistoryController) —
     * supaya Kelurahan yang BELUM PERNAH punya baris About sekalipun tetap bisa buka form ini.
     */
    // public function edit(About $about)
    public function edit()
    {
        $about = About::first() ?? new About();

        // return view('admin.about.edit', [
        //     'about' => $about
        // ]);
        return view('admin.about.edit', compact('about'));
    }

    /**
     * Update the specified resource in storage.
     * Create-or-update lewat Action Class, bukan update() langsung ke instance ter-bind.
     */
    // public function update(UpdateAboutRequest $request, About $about)
    public function update(UpdateAboutRequest $request, UpdateAbout $updater)
    {
        // $validatedData = $request->validated();
        
        // $about->update($validatedData);
        // $about->update($request->validated());
        $updater->handle($request->validated());

        // return redirect("/admin/tentang-kami/{$about->id}/edit");
        // return back();
        return back()->with('success', 'Konten Tentang Kami berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about)
    {
        //
    }
}
