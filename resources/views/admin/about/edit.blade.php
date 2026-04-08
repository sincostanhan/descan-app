<x-layout-admin title="Edit Tentang Kami">
    <div class="hero bg-base-100">
        <div class="hero-content text-center py-8">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold">Tentang Kami</h1>
            </div>
        </div>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 lg:px-0">
        {{-- <div class="border">a</div> --}}
        
        <x-flash-message />

        <form action="{{ route('admin.about.update', $about->id) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="card bg-base-100 
            card-border 
            shadow-lg">
                <div class="card-body">
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Deskripsi Umum</h2>
                    
                    <fieldset class="fieldset 
                    w-full mb-6">
                        {{-- <legend class="fieldset-legend">Deskripsi Kelurahan</legend> --}}
                        <legend class="fieldset-legend">Deskripsi Kelurahan</legend>
                        {{-- <textarea class="textarea h-24" placeholder="Masukkan deskripsi kelurahan..."></textarea> --}}
                        <textarea id="deskripsi" name="deskripsi" class="textarea  
                        h-22 w-full text-base" placeholder="Masukkan deskripsi kelurahan ...">{{ old('deskripsi', $about->deskripsi) }}</textarea>
                        <x-forms.error name="deskripsi" />
                        {{-- <div class="label">Optional</div> --}}
                    </fieldset>
                    
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Batas Wilayah</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Utara</legend>
                            <input type="text" id="utara" name="batas_utara" value="{{ old('batas_utara', $about->batas_utara) }}" class="input w-full" />
                            <x-forms.error name="batas_utara" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Barat</legend>
                            <input type="text" id="barat" name="batas_barat" value="{{ old('batas_barat', $about->batas_barat) }}" class="input w-full" />
                            <x-forms.error name="batas_barat" />
                        </fieldset>
                        
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Selatan</legend>
                            <input type="text" id="selatan" name="batas_selatan" value="{{ old('batas_selatan', $about->batas_selatan) }}" class="input w-full" />
                            <x-forms.error name="batas_selatan" />
                        </fieldset>
                        
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Timur</legend>
                            <input type="text" id="timur" name="batas_timur" value="{{ old('batas_timur', $about->batas_timur) }}" class="input w-full" />
                            <x-forms.error name="batas_timur" />
                        </fieldset>
                    </div>
                    
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Visi & Misi</h2>
                    
                    <fieldset class="fieldset w-full mb-4">
                        <legend class="fieldset-legend">Visi</legend>
                        <input type="text" id="visi" name="visi" value="{{ old('visi', $about->visi) }}" class="input w-full" />
                        <x-forms.error name="visi" />
                    </fieldset>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend">Misi (Pisahkan dengan baris baru)</legend>
                        <textarea id="misi" name="misi" class="textarea h-48 w-full text-base leading-relaxed" placeholder="Masukkan misi ...">{{ old('misi', $about->misi) }}</textarea>
                        <x-forms.error name="misi" />
                    </fieldset>

                    <div class="card-actions 
                    justify-end mt-8 border-t pt-4">
                        <button type="reset" class="btn btn-ghost">Batal</button>
                        <button type="submit" class="btn btn-secondary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout-admin>