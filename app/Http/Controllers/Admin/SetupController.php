<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAboutRequest;
use App\Http\Requests\StoreOrganizationRequest;
use App\Models\About;
use App\Models\Organization;
use App\Models\Setting;
use Illuminate\Http\Request;

// use Illuminate\Http\Request;

class SetupController extends Controller
{
    public function index()
    {
        // Lempar ke step yang belum selesai secara berurutan
        if (Setting::count() === 0) return redirect()->route('admin.setup.setting');
        if (Organization::count() === 0) return redirect()->route('admin.setup.organization');
        if (About::count() === 0) return redirect()->route('admin.setup.about');

        return redirect()->route('admin.home.edit');
    }

    // --- STEP 1: KELURAHAN (SETTING) ---
    public function setting()
    {
        $setting = Setting::first();
        return view('admin.setup.setting', compact('setting'));
    }

    public function storeSetting(Request $request)
    {
        $validated = $request->validate([
            'village_name' => 'required|string|max:255',
            'village_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('village_logo')) {
            $validated['village_logo'] = $request->file('village_logo')->store('logos', 'public');
        }

        $setting = Setting::first();
        if ($setting) {
            $setting->update($validated);
        } else {
            Setting::create($validated);
        }
        
        return redirect()->route('admin.setup.organization'); // Lanjut ke Step 2
    }

    // --- STEP 2: ORGANIZATION ---
    public function organization()
    {
        $organization = Organization::first();
        return view('admin.setup.organization', compact('organization'));
    }

    public function storeOrganization(StoreOrganizationRequest $request)
    {
        $organization = Organization::first();
        
        if ($organization) {
            $organization->update($request->validated());
        } else {
            Organization::create($request->validated());
        }
        
        return redirect()->route('admin.setup.about'); // Lanjut ke Step 3
    }

    // --- STEP 3: ABOUT ---
    public function about()
    {
        $about = About::first();
        return view('admin.setup.about', compact('about'));
    }

    public function storeAbout(StoreAboutRequest $request)
    {
        $about = About::first();
        
        if ($about) {
            $about->update($request->validated());
        } else {
            About::create($request->validated());
        }
        
        // return redirect()->route('admin.setup.index')->with('success', 'Setup Kelurahan Berhasil!'); // Selesai
        return redirect()->route('admin.home.edit')->with('success', 'Setup Kelurahan Berhasil!'); // Selesai
    }
}
