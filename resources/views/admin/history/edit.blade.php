<x-layout-admin title="Pengaturan Sejarah Kelurahan">
    <x-hero
        title="Sejarah Kelurahan"
    />
  
    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />
    
        <form action="{{ route('admin.history.update') }}" method="POST">
            @csrf

            {{-- TOGGLE STATUS SEJARAH --}}
            <div class="card bg-base-100 
            card-border 
            shadow-sm 
            {{-- mb-6 border-l-4 border-l-secondary"> --}}
            {{-- mb-6 border-l-4 border-secondary"> --}}
            mb-6 border-l-4">
                <div class="card-body 
                py-4 flex flex-row justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg">Status Halaman Sejarah</h3>
                        <p class="text-sm opacity-70">Jika dinonaktifkan, halaman sejarah tidak akan bisa diakses oleh publik.</p>
                    </div>
                    <label class="cursor-pointer label">
                        <input 
                            name="is_active" 
                            type="checkbox" 
                            class="toggle toggle-success toggle-lg" 
                            {{ old('is_active', $history->is_active) ? 'checked' : '' }} 
                        />
                    </label>
                </div>
            </div>

            {{-- FORM EDITOR SEJARAH --}}
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
                            value="{{ old('penulis', $history->penulis) }}" 
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
                        >{{ old('konten', $history->konten) }}</textarea>
                        <x-forms.error name="konten" />
                    </fieldset>
                    
                    <div class="card-actions 
                    justify-end mt-8 border-t pt-4">
                        {{-- <button type="reset" class="btn btn-ghost">Batal</button> --}}
                        <button type="submit" class="btn btn-secondary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout-admin>