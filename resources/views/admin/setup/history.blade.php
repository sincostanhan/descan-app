<x-layout-admin 
    title="Setup Langkah 2: Sejarah Kelurahan"
    :showNav="false"
>
    <div class="hero bg-base-100">
        <div class="hero-content text-center py-8">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold mb-4">Inisiasi Data Kelurahan</h1>
                <p class="text-lg opacity-70">Mari masukan sejarah kelurahan kita.</p>
            </div>
        </div>
    </div>
  
    <div class="max-w-4xl mx-auto px-4 lg:px-0 
    {{-- mb-12 --}}
    ">
        
        <ul class="steps w-full mb-8">
            <li class="step step-primary">Tentang Kami</li>
            <li class="step step-primary font-medium">Sejarah</li>
            <li class="step">Organisasi</li>
        </ul>

        <x-flash-message />
    
        <form action="{{ route('admin.setup.storeHistory') }}" method="POST">
            @csrf

            <div class="card bg-base-100 
            card-border 
            shadow-lg">
                <div class="card-body">
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Konten Sejarah</h2>
                    
                    <fieldset class="fieldset 
                    w-full mb-6">
                        <legend class="fieldset-legend">Penulis (Opsional)</legend>
                        <input 
                            type="text" 
                            name="penulis" 
                            id="penulis" 
                            {{-- value="{{ old('penulis') }}"  --}}
                            value="{{ old('penulis', $history?->penulis) }}"
                            class="input w-full md:w-1/2" 
                            placeholder="Masukkan nama penulis ..." />
                        <x-forms.error name="penulis" />
                    </fieldset>

                    <fieldset class="fieldset 
                    w-full mb-6">
                        <legend class="fieldset-legend">Isi Sejarah</legend>
                        <textarea 
                            name="konten" 
                            id="konten" 
                            class="textarea h-90 w-full text-base leading-relaxed" 
                            placeholder="Tuliskan sejarah kelurahan di sini ..." 
                            required
                        {{-- >{{ old('konten') }}</textarea> --}}
                        >{{ old('konten', $history?->konten) }}</textarea>
                        <x-forms.error name="konten" />
                    </fieldset>
                    
                    <div class="card-actions 
                    justify-end mt-8 border-t pt-4">
                        <a href="{{ route('admin.setup.about') }}" class="btn btn-ghost mr-2"
                        ><x-lucide-move-left class="w-5 h-5 mr-1" /> Tentang Kami
                        </a>
                        <button type="submit" class="btn btn-secondary">Struktur Organisasi <x-lucide-move-right class="w-5 h-5 ml-1" /></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout-admin>