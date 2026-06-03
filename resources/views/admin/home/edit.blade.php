<x-layout-admin title="Beranda">
    <x-hero 
        title="Kelurahan Cantik" 
        subtitle="Kelola informasi Latar Belakang, Tujuan, dan Output Kelurahan Cantik" 
    />

    <div class="max-w-5xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <form action="{{ route('admin.home.update') }}" method="POST">
            @csrf

            <div class="card bg-base-100 
            card-border 
            shadow-lg">
                <div class="card-body">
                    
                    {{-- Latar Belakang --}}
                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend 
                        {{-- text-lg text-secondary border-b w-full pb-2 mb-2">Latar Belakang</legend> --}}
                        font-bold text-lg text-secondary border-b w-full pb-2 mb-2">Latar Belakang</legend>
                        <textarea 
                            name="latar_belakang" 
                            class="textarea h-64 w-full text-base leading-relaxed" 
                            required>{{ old('latar_belakang', $home->latar_belakang) }}</textarea>
                        <x-forms.error name="latar_belakang" />
                    </fieldset>

                    {{-- Tujuan --}}
                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend 
                        {{-- font-bold text-lg text-secondary border-b w-full pb-2 mb-2">Tujuan</legend> --}}
                        text-lg text-secondary border-b w-full pb-2 mb-2">Tujuan</legend>
                        <textarea 
                            name="tujuan" 
                            {{-- class="textarea h-64 w-full text-base leading-relaxed"  --}}
                            class="textarea h-48 w-full text-base leading-relaxed" 
                            required>{{ old('tujuan', $home->tujuan) }}</textarea>
                        <x-forms.error name="tujuan" />
                    </fieldset>

                    {{-- Output --}}
                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend 
                        {{-- font-bold text-lg text-secondary border-b w-full pb-2 mb-2">Output</legend> --}}
                        text-lg text-secondary border-b w-full pb-2 mb-2">Output</legend>
                        <textarea 
                            name="output" 
                            class="textarea h-64 w-full text-base leading-relaxed" 
                            required>{{ old('output', $home->output) }}</textarea>
                        <x-forms.error name="output" />
                    </fieldset>

                    <div class="card-actions 
                    justify-end mt-8 border-t pt-4">
                        <button type="submit" class="btn btn-secondary">
                            <x-lucide-save class="w-5 h-5 mr-1" /> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout-admin>