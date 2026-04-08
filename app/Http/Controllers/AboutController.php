<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAboutRequest;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $about = About::first();

        // Pastikan $about tidak null sebelum di-explode untuk menghindari error
        $misiRaw = $about->misi ?? ''; 
        // Pecah jadi array, lalu hapus angka/titik di awal baris menggunakan Regex
        $daftarMisi = array_map(function($item) {
            return preg_replace('/^\d+[\.\s]*/', '', trim($item));
        }, array_filter(explode("\n", $misiRaw)));
        // $daftarMisi = array_filter(explode("\n", $misiRaw));
        
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
     */
    public function edit(About $about)
    {
        // return view('admin.about.edit', [
        //     'about' => $about
        // ]);
        return view('admin.about.edit', compact('about'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAboutRequest $request, About $about)
    {
        // $validatedData = $request->validated();
        
        // $about->update($validatedData);
        $about->update($request->validated());

        // return redirect("/admin/tentang-kami/{$about->id}/edit");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about)
    {
        //
    }
}
