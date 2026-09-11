{{-- resources\views\admin\home\edit.blade.php --}}

<x-layout-admin title="Beranda">
    <x-hero 
        title="Kelurahan Cantik" 
        subtitle="Kelola informasi Latar Belakang, Tujuan, dan Output Kelurahan Cantik" 
    />

        <div class="max-w-5xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <div class="tabs tabs-border">
            <input type="radio" name="home_tabs" class="tab" aria-label="Galeri Kelurahan" checked="checked" />
            <div class="tab-content border-base-300 bg-base-50 p-6">
                <form action="{{ route('admin.home.updateFeaturedGallery') }}" method="POST" id="form-featured-home">
                    @csrf

                    <div class="mb-10">
                        <h3 class="font-semibold text-lg mb-1">Galeri untuk Beranda</h3>
                        <p class="text-sm text-base-content/60 mb-1">Centang minimal <strong>5 galeri</strong> yang ingin ditampilkan di beranda publik.</p>
                        <p class="text-sm mb-3">
                            <span id="gallery-selected-count" class="font-semibold text-primary">0</span> dipilih
                            <span class="text-base-content/60">(minimal 5)</span>
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($galleries as $gallery)
                                <div class="relative">
                                    <label class="absolute top-3 right-3 z-10 cursor-pointer">
                                        <input type="checkbox" name="featured_galleries[]" value="{{ $gallery->id }}"
                                            class="peer hidden gallery-featured-checkbox"
                                            {{ $gallery->photos->contains('tampil_beranda', true) ? 'checked' : '' }} />
                                        <x-lucide-square class="w-6 h-6 text-base-content/50 bg-base-100 rounded peer-checked:hidden" />
                                        <x-lucide-square-check class="w-6 h-6 text-primary bg-base-100 rounded hidden peer-checked:block" />
                                    </label>
                                    <label for="" class="block">
                                        <div class="card bg-base-100 border border-base-300 shadow-sm rounded-2xl overflow-hidden w-full">
                                            @if($gallery->photos->isEmpty())
                                                <div class="aspect-[4/3] bg-base-200 flex items-center justify-center text-base-content/40">
                                                    <x-lucide-image class="w-8 h-8" />
                                                </div>
                                            @else
                                                <div class="carousel w-full">
                                                    @foreach($gallery->photos as $photo)
                                                        <div class="carousel-item w-full">
                                                            <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                                                 alt="Foto {{ $gallery->judul }}"
                                                                 class="w-full h-48 object-contain bg-base-200" loading="lazy" decoding="async" />
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="card-body p-4">
                                                <h3 class="font-semibold">{{ $gallery->judul }}</h3>
                                                <p class="text-xs text-base-content/60 flex items-center">
                                                    <x-lucide-calendar class="w-3.5 h-3.5 mr-1" />
                                                    Dipublikasikan pada tanggal {{ $gallery->created_at->translatedFormat('d F Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @empty
                                <x-empty-alert message="Belum ada data Galeri." />
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-3">Potensi Wisata untuk Beranda</h3>
                        {{-- <p class="text-sm text-base-content/60 mb-3">Centang destinasi yang ingin ditampilkan di beranda publik.</p> --}}
                        <p class="text-sm text-base-content/60 mb-1">Centang minimal <strong>5 destinasi</strong> yang ingin ditampilkan di beranda publik.</p>
                        <p class="text-sm mb-3">
                            <span id="wisata-selected-count" class="font-semibold text-primary">0</span> dipilih
                            <span class="text-base-content/60">(minimal 5)</span>
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($potensiWisatas as $item)
                                <div class="relative">
                                    <label class="absolute top-3 right-3 z-10 cursor-pointer">
                                        <input type="checkbox" name="featured_potensi_wisata[]" value="{{ $item->id }}"
                                            {{-- class="peer hidden" --}}
                                            class="peer hidden wisata-featured-checkbox"
                                            {{ $item->photos->contains('tampil_beranda', true) ? 'checked' : '' }} />
                                        <x-lucide-square class="w-6 h-6 text-base-content/50 bg-base-100 rounded peer-checked:hidden" />
                                        <x-lucide-square-check class="w-6 h-6 text-primary bg-base-100 rounded hidden peer-checked:block" />
                                    </label>
                                    <label class="block">
                                        <div class="card bg-base-100 border border-base-300 shadow-sm rounded-2xl overflow-hidden w-full">
                                            @if($item->photos->isEmpty())
                                                <div class="aspect-[4/3] bg-base-200 flex items-center justify-center text-base-content/40">
                                                    <x-lucide-image class="w-8 h-8" />
                                                </div>
                                            @else
                                                <div class="carousel w-full">
                                                    @foreach($item->photos as $photo)
                                                        <div class="carousel-item w-full">
                                                            <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                                                 alt="{{ $item->nama }}"
                                                                 class="w-full h-48 object-contain bg-base-200" loading="lazy" decoding="async" />
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="card-body p-4">
                                                <h3 class="font-semibold">{{ $item->nama }}</h3>
                                                <p class="text-xs text-base-content/60 flex items-center">
                                                    <x-lucide-calendar class="w-3.5 h-3.5 mr-1" />
                                                    Dipublikasikan pada tanggal {{ $item->created_at->translatedFormat('d F Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @empty
                                <x-empty-alert message="Belum ada data Potensi Wisata." />
                            @endforelse
                        </div>
                    </div>

                    {{-- <div class="flex justify-end items-center gap-3 pt-4 border-t border-base-200">
                        <p id="gallery-min-warning" class="text-error text-sm hidden">Pilih minimal 5 galeri.</p>
                        <p id="wisata-min-warning" class="text-error text-sm hidden">Pilih minimal 5 Potensi Wisata.</p>
                        <button type="submit" class="btn btn-secondary">
                            <x-lucide-save class="w-4 h-4 mr-1" /> Simpan Pilihan
                        </button>
                    </div>
                    @error('featured_galleries')
                        <p class="text-error text-sm mt-2">{{ $message }}</p>
                    @enderror
                    @error('featured_potensi_wisata')
                        <p class="text-error text-sm mt-2">{{ $message }}</p>
                    @enderror --}}
                                        <div class="flex justify-end items-center gap-3 pt-4 border-t border-base-200">
                        <button type="submit" class="btn btn-secondary">
                            <x-lucide-save class="w-4 h-4 mr-1" /> Simpan Pilihan
                        </button>
                    </div>
                    @error('featured_galleries')
                        <p class="text-error text-sm mt-2">{{ $message }}</p>
                    @enderror
                    @error('featured_potensi_wisata')
                        <p class="text-error text-sm mt-2">{{ $message }}</p>
                    @enderror
                </form>
                <dialog id="modal_warning_featured_home" class="modal">
                        <div class="modal-box">
                            <div class="flex flex-col items-center text-center">
                                <x-lucide-triangle-alert class="w-14 h-14 text-warning mb-4" />
                                <h3 class="font-bold text-xl text-base-content">Belum Bisa Disimpan</h3>
                                <ul id="modal_warning_list" class="py-4 text-base-content/80 list-disc text-left space-y-1"></ul>
                            </div>
                            <div class="modal-action justify-center">
                                <form method="dialog">
                                    <button class="btn btn-warning">Mengerti</button>
                                </form>
                            </div>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
            </div>

            <input type="radio" name="home_tabs" class="tab" aria-label="Kelurahan Cantik" />
            <div class="tab-content border-base-300 bg-base-50 p-6">
                <form action="{{ route('admin.home.update', $home->id ?? 1) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-3">
                        {{-- 1. Latar Belakang --}}
                        <div class="collapse collapse-plus bg-base-100 border border-base-300">
                            <input type="checkbox" checked="checked" />
                            <div class="collapse-title font-semibold text-lg text-secondary">
                                Latar Belakang
                            </div>
                            <div class="collapse-content text-sm space-y-3">
                                <label class="label cursor-pointer justify-start gap-3">
                                    <input type="checkbox" name="show_latar_belakang" class="toggle toggle-primary toggle-sm"
                                        {{ old('show_latar_belakang', $home->show_latar_belakang ?? true) ? 'checked' : '' }} />
                                    <span class="label-text">Tampilkan di beranda publik</span>
                                </label>
                                <textarea
                                    name="latar_belakang"
                                    class="textarea h-64 w-full text-base leading-relaxed"
                                    required>{{ old('latar_belakang', $home->latar_belakang) }}</textarea>
                                <x-forms.error name="latar_belakang" />
                            </div>
                        </div>

                        {{-- 2. Tujuan --}}
                        <div class="collapse collapse-plus bg-base-100 border border-base-300">
                            <input type="checkbox" />
                            <div class="collapse-title font-semibold text-lg text-secondary">
                                Tujuan Program
                            </div>
                            <div class="collapse-content text-sm space-y-3">
                                <label class="label cursor-pointer justify-start gap-3">
                                    <input type="checkbox" name="show_tujuan" class="toggle toggle-primary toggle-sm"
                                        {{ old('show_tujuan', $home->show_tujuan ?? true) ? 'checked' : '' }} />
                                    <span class="label-text">Tampilkan di beranda publik</span>
                                </label>
                                <textarea
                                    name="tujuan"
                                    class="textarea h-48 w-full text-base leading-relaxed"
                                    required>{{ old('tujuan', $home->tujuan) }}</textarea>
                                <x-forms.error name="tujuan" />
                            </div>
                        </div>

                        {{-- 3. Output --}}
                        <div class="collapse collapse-plus bg-base-100 border border-base-300">
                            <input type="checkbox" />
                            <div class="collapse-title font-semibold text-lg text-secondary">
                                Output Kelurahan Cantik
                            </div>
                            <div class="collapse-content text-sm space-y-3">
                                <label class="label cursor-pointer justify-start gap-3">
                                    <input type="checkbox" name="show_output" class="toggle toggle-primary toggle-sm"
                                        {{ old('show_output', $home->show_output ?? true) ? 'checked' : '' }} />
                                    <span class="label-text">Tampilkan di beranda publik</span>
                                </label>
                                <textarea
                                    name="output"
                                    class="textarea h-64 w-full text-base leading-relaxed"
                                    required>{{ old('output', $home->output) }}</textarea>
                                <x-forms.error name="output" />
                            </div>
                        </div>

                        {{-- 4. Tim Kelurahan Cantik (baru) --}}
                        <div class="collapse collapse-plus bg-base-100 border border-base-300">
                            <input type="checkbox" />
                            <div class="collapse-title font-semibold text-lg text-secondary">
                                Tim Kelurahan Cantik
                            </div>
                            <div class="collapse-content text-sm space-y-3">
                                <label class="label cursor-pointer justify-start gap-3">
                                    <input type="checkbox" name="show_tim" class="toggle toggle-primary toggle-sm"
                                        {{ old('show_tim', $home->show_tim ?? true) ? 'checked' : '' }} />
                                    <span class="label-text">Tampilkan di beranda publik</span>
                                </label>
                                <textarea
                                    name="tim_kelurahan"
                                    class="textarea h-48 w-full text-base leading-relaxed"
                                    placeholder="Contoh:&#10;Agen Data Koordinator:&#10;USMAN JAFAR, S.IP&#10;&#10;Agen Data Pengolah:&#10;1. RASFIA USI, S.Kom.&#10;2. BERI SAPUTRA&#10;&#10;Agen Data Lapangan:&#10;1. M. FITRIADI&#10;2. NURHAYATI&#10;3. ROSIDA H.D."
                                >{{ old('tim_kelurahan', $home->tim_kelurahan) }}</textarea>
                                <x-forms.error name="tim_kelurahan" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 pt-6 mt-6 border-t border-base-200">
                        <button type="submit" class="btn btn-secondary">
                            <x-lucide-save class="w-5 h-5 mr-1" /> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
            <script>
            document.addEventListener('DOMContentLoaded', () => {
                // const checkboxes = document.querySelectorAll('.gallery-featured-checkbox');
                // const counter = document.getElementById('gallery-selected-count');
                // const warning = document.getElementById('gallery-min-warning');
                const galleryCheckboxes = document.querySelectorAll('.gallery-featured-checkbox');
                const galleryCounter = document.getElementById('gallery-selected-count');
                // const galleryWarning = document.getElementById('gallery-min-warning');
                const wisataCheckboxes = document.querySelectorAll('.wisata-featured-checkbox');
                const wisataCounter = document.getElementById('wisata-selected-count');
                // const wisataWarning = document.getElementById('wisata-min-warning');
                const form = document.getElementById('form-featured-home');
                const warningModal = document.getElementById('modal_warning_featured_home');
                const warningList = document.getElementById('modal_warning_list');

                // function updateCount() {
                //     const checkedCount = document.querySelectorAll('.gallery-featured-checkbox:checked').length;
                //     counter.textContent = checkedCount;
                //     counter.classList.toggle('text-error', checkedCount < 5);
                //     counter.classList.toggle('text-primary', checkedCount >= 5);
                // }
                function updateCount(checkboxSelector, counterEl) {
                    const checkedCount = document.querySelectorAll(checkboxSelector + ':checked').length;
                    counterEl.textContent = checkedCount;
                    counterEl.classList.toggle('text-error', checkedCount < 5);
                    counterEl.classList.toggle('text-primary', checkedCount >= 5);
                    return checkedCount;
                }

                // checkboxes.forEach((cb) => cb.addEventListener('change', updateCount));
                // updateCount();
                galleryCheckboxes.forEach((cb) => cb.addEventListener('change', () => updateCount('.gallery-featured-checkbox', galleryCounter)));
                wisataCheckboxes.forEach((cb) => cb.addEventListener('change', () => updateCount('.wisata-featured-checkbox', wisataCounter)));
                updateCount('.gallery-featured-checkbox', galleryCounter);
                updateCount('.wisata-featured-checkbox', wisataCounter);

                form.addEventListener('submit', (e) => {
                    // const checkedCount = document.querySelectorAll('.gallery-featured-checkbox:checked').length;
                    // if (checkedCount < 5) {
                    const galleryChecked = document.querySelectorAll('.gallery-featured-checkbox:checked').length;
                    const wisataChecked = document.querySelectorAll('.wisata-featured-checkbox:checked').length;
                    // let valid = true;
                    const messages = [];

                    if (galleryChecked < 5) {
                        e.preventDefault();
                        // warning.classList.remove('hidden');
                    //     galleryWarning.classList.remove('hidden');
                    //     valid = false;
                    // } else {
                        // warning.classList.add('hidden');
                    // }
                        // galleryWarning.classList.add('hidden');
                        messages.push('Pilih minimal 5 Galeri (saat ini baru ' + galleryChecked + ').');
                     }

                    if (wisataChecked < 5) {
                        e.preventDefault();
                    //     wisataWarning.classList.remove('hidden');
                    //     valid = false;
                    // } else {
                    //     wisataWarning.classList.add('hidden');
                        messages.push('Pilih minimal 5 Potensi Wisata (saat ini baru ' + wisataChecked + ').');
                    }

                    if (messages.length > 0) {
                        warningList.innerHTML = messages.map((msg) => '<li>' + msg + '</li>').join('');
                        warningModal.showModal();
                    }
                });
            });
        </script>
</x-layout-admin>