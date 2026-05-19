<x-layout-admin 
    title="Setup Langkah 1: Tentang Kami"
    :showNav="false"
>
    <div class="hero bg-base-100">
        <div class="hero-content text-center py-8">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold mb-4">Inisiasi Data Kelurahan</h1>
                <p class="text-lg opacity-70">Silakan lengkapi profil awal kelurahan sebelum masuk ke halaman utama.</p>
            </div>
        </div>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 lg:px-0 
    {{-- mb-12 --}}
    ">
        
        <ul class="steps w-full mb-8">
            <li class="step step-primary">Kelurahan</li>
            <li class="step step-primary">Organisasi</li>
            <li class="step step-primary font-medium">Tentang Kami</li>
        </ul>

        <x-flash-message />

        <form action="{{ route('admin.setup.storeAbout') }}" method="POST">
            @csrf
            
            <div class="card bg-base-100 
            card-border 
            shadow-lg">
                <div class="card-body">
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Deskripsi Umum</h2>
                    
                    <fieldset class="fieldset 
                    w-full mb-6">
                        <legend class="fieldset-legend">Deskripsi Kelurahan</legend>
                        <textarea 
                            id="deskripsi" 
                            name="deskripsi" 
                            class="textarea 
                                h-22 w-full text-base" 
                            placeholder="Masukkan deskripsi kelurahan ..."
                        {{-- >{{ old('deskripsi') }}</textarea> --}}
                        >{{ old('deskripsi', $about?->deskripsi) }}</textarea>
                        <x-forms.error name="deskripsi" />
                    </fieldset>
                    
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Batas Wilayah</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Utara</legend>
                            <input 
                                type="text" 
                                id="utara" 
                                name="batas_utara" 
                                {{-- value="{{ old('batas_utara') }}"  --}}
                                value="{{ old('batas_utara', $about?->batas_utara) }}"
                                class="input w-full" />
                            <x-forms.error name="batas_utara" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Barat</legend>
                            <input 
                                type="text" 
                                id="barat" 
                                name="batas_barat" 
                                {{-- value="{{ old('batas_barat') }}"  --}}
                                value="{{ old('batas_barat', $about?->batas_barat) }}" 
                                class="input w-full" />
                            <x-forms.error name="batas_barat" />
                        </fieldset>
                        
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Selatan</legend>
                            <input 
                                type="text" 
                                id="selatan" 
                                name="batas_selatan" 
                                {{-- value="{{ old('batas_selatan') }}" --}}
                                value="{{ old('batas_selatan', $about?->batas_selatan) }}" 
                                class="input w-full" />
                            <x-forms.error name="batas_selatan" />
                        </fieldset>
                        
                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Timur</legend>
                            <input 
                                type="text" 
                                id="timur" 
                                name="batas_timur" 
                                {{-- value="{{ old('batas_timur') }}"  --}}
                                value="{{ old('batas_timur', $about?->batas_timur) }}" 
                                class="input w-full" />
                            <x-forms.error name="batas_timur" />
                        </fieldset>
                    </div>
                    
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Visi & Misi</h2>
                    
                    <fieldset class="fieldset w-full mb-4">
                        <legend class="fieldset-legend">Visi</legend>
                        <input 
                            type="text" 
                            id="visi" 
                            name="visi" 
                            {{-- value="{{ old('visi') }}"  --}}
                            value="{{ old('visi', $about?->visi) }}" 
                            class="input w-full" />
                        <x-forms.error name="visi" />
                    </fieldset>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend">Misi (Pisahkan dengan baris baru)</legend>
                        <textarea 
                            id="misi" 
                            name="misi" 
                            class="textarea h-48 w-full text-base leading-relaxed" 
                            placeholder="Masukkan misi ..."
                        {{-- >{{ old('misi') }}</textarea> --}}
                        >{{ old('misi', $about?->misi) }}</textarea>
                        <x-forms.error name="misi" />
                    </fieldset>

                    <div class="card-actions 
                    justify-end mt-8 border-t pt-4">
                        <a href="{{ route('admin.setup.organization') }}" class="btn btn-ghost">
                            <x-lucide-move-left class="w-5 h-5 mr-1" /> Kembali
                        </a>
                        <button type="submit" class="btn btn-success text-white">Selesai <x-lucide-check-circle class="w-5 h-5 ml-1" /></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout-admin>