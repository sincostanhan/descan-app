<x-layout-admin 
    title="Setup Langkah 1: Profil Kelurahan" 
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
    
    <div class="max-w-4xl mx-auto px-4 lg:px-0">
        <ul class="steps w-full mb-8">
            <li class="step step-primary font-medium">Kelurahan</li>
            <li class="step">Organisasi</li>
            <li class="step">Tentang Kami</li>
        </ul>

        <x-flash-message />

        <form action="{{ route('admin.setup.storeSetting') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card bg-base-100 
            card-border 
            shadow-lg">
                <div class="card-body">
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Identitas Kelurahan</h2>
                    
                    <fieldset class="fieldset 
                    w-full mb-6">
                        <legend class="fieldset-legend">Nama Kelurahan</legend>
                        <input 
                            type="text" 
                            name="village_name" 
                            value="{{ old('village_name', $setting?->village_name ?? 'Kelurahan ') }}" 
                            class="input w-full" 
                            placeholder="Masukkan nama kelurahan ..."
                            required />
                        <x-forms.error name="village_name" />
                    </fieldset>

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend">Logo Kelurahan (Opsional)</legend>
                        <input 
                            type="file" 
                            name="village_logo" 
                            {{-- class="file-input file-input-bordered w-full max-w-xs"  --}}
                            class="file-input w-full" 
                            accept="image/*" />
                        <x-forms.error name="village_logo" />
                    </fieldset>

                    <div class="card-actions justify-end mt-8 border-t pt-4">
                        <button type="submit" class="btn btn-secondary">Organisasi <x-lucide-move-right class="w-5 h-5 ml-1" /></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout-admin>