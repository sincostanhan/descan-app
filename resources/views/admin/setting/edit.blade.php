<x-layout-admin title="Pengaturan Web">
    <x-hero
        title="Pengaturan Web"
    />
    
    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <form action="{{ route('admin.setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
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
                            id="village_name" 
                            name="village_name" 
                            value="{{ old('village_name', $setting->village_name) }}" 
                            class="input w-full" 
                            placeholder="Masukkan nama kelurahan ..."
                            required />
                        <x-forms.error name="village_name" />
                    </fieldset>

                    <fieldset class="fieldset 
                    w-full mb-6">
                        <legend class="fieldset-legend">Logo Kelurahan</legend>
                        
                        @if($setting->village_logo)
                            <div class="mb-4">
                                <p class="text-sm text-base-content/70 mb-2">Logo saat ini:</p>
                                <img src="{{ asset('storage/' . $setting->village_logo) }}" alt="Logo Saat Ini" class="h-24 w-auto object-contain border p-2 rounded-box bg-base-200">
                            </div>
                        @endif
                        
                        <input 
                            type="file" 
                            id="village_logo" 
                            name="village_logo" 
                            class="file-input w-full" 
                            accept="image/*" />
                        <div class="fieldset-label mt-1 text-sm text-base-content/60">Biarkan kosong jika tidak ingin mengubah logo. Format yang didukung: jpg, jpeg, png (Maksimal 2MB).</div>
                        <x-forms.error name="village_logo" />
                    </fieldset>

                    <fieldset class="fieldset 
                    w-full mb-6">
                        <legend class="fieldset-legend">Tema Website</legend>
                        <select name="theme_name" id="theme_name" class="select w-full">
                            <option value="emerald" {{ old('theme_name', $setting->theme_name) == 'emerald' ? 'selected' : '' }}>Emerald</option>
                            <option value="light" {{ old('theme_name', $setting->theme_name) == 'light' ? 'selected' : '' }}>Light</option>
                            <option value="dark" {{ old('theme_name', $setting->theme_name) == 'dark' ? 'selected' : '' }}>Dark</option>
                            <option value="cupcake" {{ old('theme_name', $setting->theme_name) == 'cupcake' ? 'selected' : '' }}>Cupcake</option>
                            <option value="bumblebee" {{ old('theme_name', $setting->theme_name) == 'bumblebee' ? 'selected' : '' }}>Bumblebee</option>
                            <option value="corporate" {{ old('theme_name', $setting->theme_name) == 'corporate' ? 'selected' : '' }}>Corporate</option>
                            <option value="synthwave" {{ old('theme_name', $setting->theme_name) == 'synthwave' ? 'selected' : '' }}>synthwave</option>
                            <option value="retro" {{ old('theme_name', $setting->theme_name) == 'retro' ? 'selected' : '' }}>retro</option>
                            <option value="cyberpunk" {{ old('theme_name', $setting->theme_name) == 'cyberpunk' ? 'selected' : '' }}>cyberpunk</option>
                            <option value="halloween" {{ old('theme_name', $setting->theme_name) == 'halloween' ? 'selected' : '' }}>halloween</option>
                            <option value="garden" {{ old('theme_name', $setting->theme_name) == 'garden' ? 'selected' : '' }}>garden</option>
                            <option value="forest" {{ old('theme_name', $setting->theme_name) == 'forest' ? 'selected' : '' }}>forest</option>
                            <option value="aqua" {{ old('theme_name', $setting->theme_name) == 'aqua' ? 'selected' : '' }}>aqua</option>
                            <option value="lofi" {{ old('theme_name', $setting->theme_name) == 'lofi' ? 'selected' : '' }}>lofi</option>
                            <option value="pastel" {{ old('theme_name', $setting->theme_name) == 'pastel' ? 'selected' : '' }}>pastel</option>
                            <option value="fantasy" {{ old('theme_name', $setting->theme_name) == 'fantasy' ? 'selected' : '' }}>fantasy</option>
                            <option value="wireframe" {{ old('theme_name', $setting->theme_name) == 'wireframe' ? 'selected' : '' }}>wireframe</option>
                            <option value="black" {{ old('theme_name', $setting->theme_name) == 'black' ? 'selected' : '' }}>black</option>
                            <option value="luxury" {{ old('theme_name', $setting->theme_name) == 'luxury' ? 'selected' : '' }}>luxury</option>
                            <option value="dracula" {{ old('theme_name', $setting->theme_name) == 'dracula' ? 'selected' : '' }}>dracula</option>
                            <option value="cmyk" {{ old('theme_name', $setting->theme_name) == 'cmyk' ? 'selected' : '' }}>cmyk</option>
                            <option value="autumn" {{ old('theme_name', $setting->theme_name) == 'autumn' ? 'selected' : '' }}>autumn</option>
                            <option value="business" {{ old('theme_name', $setting->theme_name) == 'business' ? 'selected' : '' }}>business</option>
                            <option value="acid" {{ old('theme_name', $setting->theme_name) == 'acid' ? 'selected' : '' }}>acid</option>
                            <option value="lemonade" {{ old('theme_name', $setting->theme_name) == 'lemonade' ? 'selected' : '' }}>lemonade</option>
                            <option value="night" {{ old('theme_name', $setting->theme_name) == 'night' ? 'selected' : '' }}>night</option>
                            <option value="coffee" {{ old('theme_name', $setting->theme_name) == 'coffee' ? 'selected' : '' }}>coffee</option>
                            <option value="winter" {{ old('theme_name', $setting->theme_name) == 'winter' ? 'selected' : '' }}>winter</option>
                            <option value="dim" {{ old('theme_name', $setting->theme_name) == 'dim' ? 'selected' : '' }}>dim</option>
                            <option value="nord" {{ old('theme_name', $setting->theme_name) == 'nord' ? 'selected' : '' }}>nord</option>
                            <option value="sunset" {{ old('theme_name', $setting->theme_name) == 'sunset' ? 'selected' : '' }}>sunset</option>
                            <option value="caramellatte" {{ old('theme_name', $setting->theme_name) == 'caramellatte' ? 'selected' : '' }}>caramellatte</option>
                            <option value="abyss" {{ old('theme_name', $setting->theme_name) == 'abyss' ? 'selected' : '' }}>abyss</option>
                            <option value="silk" {{ old('theme_name', $setting->theme_name) == 'silk' ? 'selected' : '' }}>silk</option>
                        </select>
                        <div class="fieldset-label mt-1 text-sm text-base-content/60">Pilih tema warna untuk tampilan website.</div>
                        <x-forms.error name="theme_name" />
                    </fieldset>

                    <div class="card-actions justify-end mt-8 border-t pt-4">
                        {{-- <button type="reset" class="btn btn-ghost">Batal</button> --}}
                        <button type="submit" class="btn btn-secondary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout-admin>