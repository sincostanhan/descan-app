<x-layout-admin title="Edit Sejarah Kelurahan">
    <div class="hero bg-base-100">
        <div class="hero-content text-center py-8">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold">Sejarah Kelurahan</h1>
            </div>
        </div>
    </div>
  
    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />
    
        <form action="{{ route('admin.history.update', $history->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="card bg-base-100 
            card-border 
            shadow-lg">
                <div class="card-body">
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Konten Sejarah</h2>
                    
                    <fieldset class="fieldset 
                    w-full mb-6">
                        <legend class="fieldset-legend">Penulis (Opsional)</legend>
                        <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $history->penulis) }}" class="input w-full md:w-1/2" placeholder="Masukkan nama penulis..." />
                        <x-forms.error name="penulis" />
                    </fieldset>

                    <fieldset class="fieldset 
                    w-full mb-6">
                        <legend class="fieldset-legend">Isi Sejarah</legend>
                        <textarea name="konten" id="konten" class="textarea h-90 w-full text-base leading-relaxed" placeholder="Tuliskan sejarah kelurahan di sini..." required>{{ old('konten', $history->konten) }}</textarea>
                        <x-forms.error name="konten" />
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