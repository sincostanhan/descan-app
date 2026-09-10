<x-layout title="Beranda">
    <x-hero 
        title="Kelurahan Cantik" 
    />

    @php
        // Menggunakan Anonymous Function agar aman dan rapi di dalam view
        $renderFormattedList = function($text) {
            $lines = explode("\n", trim($text));
            $html = '';
            $lastMargin = ''; // Menyimpan indentasi terakhir untuk baris lanjutan yang di-enter manual
            
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                // 1. Cek Romawi (i., ii., iii., dst) -> Menjorok Paling Kanan
                if (preg_match('/^((?:i|ii|iii|iv|v|vi)[\.\)])\s+(.*)$/i', $line, $matches)) {
                    $lastMargin = 'ml-12 pl-8'; 
                    $html .= '<div class="flex items-start ml-12 mb-1"><span class="w-8 shrink-0 font-medium">' . e($matches[1]) . '</span><div class="flex-1">' . e($matches[2]) . '</div></div>';
                }
                // 2. Cek Alfabet (a., b., c., a), b), c), dst) -> Menjorok Sedang
                elseif (preg_match('/^([a-z][\.\)])\s+(.*)$/i', $line, $matches)) {
                    $lastMargin = 'ml-6 pl-6'; 
                    $html .= '<div class="flex items-start ml-6 mb-1"><span class="w-6 shrink-0 font-medium">' . e($matches[1]) . '</span><div class="flex-1">' . e($matches[2]) . '</div></div>';
                }
                // 3. Cek Angka (1., 2., 1), 2), dst) -> Menjorok Sedikit/Normal
                elseif (preg_match('/^(\d+[\.\)])\s+(.*)$/', $line, $matches)) {
                    $lastMargin = 'pl-6'; 
                    $html .= '<div class="flex items-start mb-1"><span class="w-6 shrink-0 font-medium">' . e($matches[1]) . '</span><div class="flex-1">' . e($matches[2]) . '</div></div>';
                }
                // 4. Cek baris judul kelompok yang diakhiri titik dua (:) -> Reset indentasi + tebal
                //    Contoh: "Agen Data Pengolah:", "Agen Data Lapangan:"
                elseif (preg_match('/:$/', $line)) {
                    $lastMargin = '';
                    $html .= '<div class="mb-1 font-semibold">' . e($line) . '</div>';
                }
                // 4. Baris Normal (Contoh: Lanjutan kalimat jika ditekan Enter secara manual)
                // 5. Baris Normal (Contoh: Lanjutan kalimat jika ditekan Enter secara manual)
                else {
                    $html .= '<div class="mb-1 ' . $lastMargin . '">' . e($line) . '</div>';
                }
            }
            return $html;
        };
    @endphp

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-20 space-y-16">

        @php
            // $jumlahTabelGrafik = ...;
            // $jumlahPublikasi = ...;
            // $jumlahInfografis = ...;
            // $latestGallery = ...;
            // $latestGalleryPhoto = ...;
        @endphp

        <section aria-labelledby="statistik-heading"> ... 3 DaisyUI stats ... </section>

        {{-- @php
            $galleryPhoto = $home->featuredGalleryPhoto ?? \App\Models\GalleryPhoto::latest()->first();
            $wisataPhoto = $home->featuredPotensiWisataPhoto ?? \App\Models\PotensiWisataPhoto::latest()->first();
        @endphp --}}

        {{-- <section aria-labelledby="feature-heading">
            <h2 id="feature-heading" class="text-2xl font-bold mb-6 border-b pb-2">
                Kegiatan & Potensi Kelurahan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 justify-items-center md:justify-items-start">

                {{-- Card 1: Galeri Kegiatan --}
                <a href="{{ route('gallery.index') }}" aria-label="Lihat Galeri Kegiatan"
                   class="w-60 sm:w-72 block group rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                    <div class="hover-3d w-full">
                        <figure class="w-full rounded-2xl overflow-hidden aspect-[4/3] bg-base-200">
                            @if($galleryPhoto)
                                <img src="{{ asset('storage/' . $galleryPhoto->foto_path) }}"
                                     alt="Foto kegiatan: {{ $galleryPhoto->gallery->judul ?? '' }}"
                                     class="w-full h-full object-cover" loading="lazy" decoding="async" />
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-base-content/40">
                                    <x-lucide-image class="w-10 h-10 mb-2" />
                                    <span class="text-sm text-center px-2">Belum ada foto kegiatan</span>
                                </div>
                            @endif
                        </figure>
                        <div></div><div></div><div></div><div></div>
                        <div></div><div></div><div></div><div></div>
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-2">
                        <h3 class="font-semibold text-base sm:text-lg group-hover:text-primary transition-colors">
                            Galeri Kegiatan
                        </h3>
                        <span class="btn btn-xs sm:btn-sm btn-outline btn-primary pointer-events-none shrink-0">
                            Lihat <x-lucide-arrow-right class="w-4 h-4" />
                        </span>
                    </div>
                </a>

                {{-- Card 2: Potensi Wisata --}
                <a href="{{ route('potensi-wisata.index') }}" aria-label="Lihat Potensi Wisata"
                   class="w-60 sm:w-72 block group rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                    <div class="hover-3d w-full">
                        <figure class="w-full rounded-2xl overflow-hidden aspect-[4/3] bg-base-200">
                            @if($wisataPhoto)
                                <img src="{{ asset('storage/' . $wisataPhoto->foto_path) }}"
                                     alt="Potensi Wisata: {{ $wisataPhoto->potensiWisata->nama ?? '' }}"
                                     class="w-full h-full object-cover" loading="lazy" decoding="async" />
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-base-content/40">
                                    <x-lucide-image class="w-10 h-10 mb-2" />
                                    <span class="text-sm text-center px-2">Belum ada foto Potensi Wisata</span>
                                </div>
                            @endif
                        </figure>
                        <div></div><div></div><div></div><div></div>
                        <div></div><div></div><div></div><div></div>
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-2">
                        <h3 class="font-semibold text-base sm:text-lg group-hover:text-primary transition-colors">
                            Potensi Wisata
                        </h3>
                        <span class="btn btn-xs sm:btn-sm btn-outline btn-primary pointer-events-none shrink-0">
                            Lihat <x-lucide-arrow-right class="w-4 h-4" />
                        </span>
                    </div>
                </a>
            </div>
        </section> --}}
        @php
            $galleryFeatured = \App\Models\GalleryPhoto::where('tampil_beranda', true)->with('gallery')->latest()->get();
            if ($galleryFeatured->isEmpty()) {
                $galleryFeatured = \App\Models\GalleryPhoto::with('gallery')->latest()->take(5)->get();
            }
            $wisataFeatured = \App\Models\PotensiWisataPhoto::where('tampil_beranda', true)->with('potensiWisata')->latest()->get();
            if ($wisataFeatured->isEmpty()) {
                $wisataFeatured = \App\Models\PotensiWisataPhoto::with('potensiWisata')->latest()->take(5)->get();
            }
        @endphp

        <section aria-labelledby="feature-heading">
            {{-- <h2 id="feature-heading" class="text-2xl font-bold mb-6 border-b pb-2">
                Kegiatan &amp; Potensi Kelurahan
            </h2> --}}
            {{-- <h2 id="feature-heading" class="feature-headline text-2xl font-bold mb-6 border-b pb-2"> --}}
            <h2 id="feature-heading" class="feature-headline text-3xl font-bold mb-6 border-b pb-2 text-center">
                Galeri Kelurahan
             </h2>

            <div class="space-y-10">
                {{-- GALERI — carousel (3) di kiri, label (1) di kanan --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                    <div class="md:col-span-3 order-2 md:order-1 overflow-hidden">
                        {{-- @if($galleryFeatured->isEmpty())
                            <x-empty-alert message="Belum ada foto Galeri Kegiatan." />
                        @else
                            <div class="carousel carousel-end rounded-box gap-3">
                            {{-- <div class="carousel carousel-end rounded-box
                            bg-base-200/60 hover:bg-base-200"> --}
                                @foreach($galleryFeatured as $photo)
                                    <div class="carousel-item">
                                        <figure class="flex flex-col items-center">
                                            <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                                 alt="{{ $photo->gallery->judul ?? 'Galeri Kegiatan' }}"
                                                 class="w-64 h-48 object-cover rounded-box" loading="lazy" decoding="async" />
                                                 {{-- class="w-64 h-48 object-cover" loading="lazy" decoding="async" /> --}
                                            <figcaption class="text-sm text-center mt-2 text-base-content/70 max-w-64 truncate">
                                                {{ $photo->gallery->judul ?? '' }}
                                            </figcaption>
                                        </figure>
                                    </div>
                                @endforeach
                            </div>
                        @endif --}}
                        <div class="border border-base-300 rounded-2xl p-4 bg-base-100">
                            @if($galleryFeatured->isEmpty())
                                <x-empty-alert message="Belum ada foto Galeri Kegiatan." />
                            @else
                                <div class="carousel carousel-end rounded-box gap-3">
                                    @foreach($galleryFeatured as $photo)
                                        <div class="carousel-item">
                                            <figure class="flex flex-col items-center">
                                                {{-- <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                                     alt="{{ $photo->gallery->judul ?? 'Galeri Kegiatan' }}"
                                                     class="w-64 h-48 object-cover rounded-box" loading="lazy" decoding="async" /> --}}
                                                <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                                     alt="{{ $photo->gallery->judul ?? 'Galeri Kegiatan' }}"
                                                     class="w-64 h-48 object-contain bg-base-200 rounded-box" loading="lazy" decoding="async" />
                                                     {{-- class="w-64 h-48 object-contain rounded-box" loading="lazy" decoding="async" /> --}}
                                                <figcaption class="text-sm text-center mt-2 text-base-content/70 max-w-64 truncate">
                                                    {{ $photo->gallery->judul ?? '' }}
                                                </figcaption>
                                            </figure>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>                        
                    </div>
                    <a href="{{ route('gallery.index') }}"
                       class="md:col-span-1 order-1 md:order-2 flex flex-col items-center justify-center text-center p-6 rounded-2xl bg-base-200/60 hover:bg-base-200 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary group">
                        <span class="text-xl font-bold group-hover:text-primary transition-colors">Galeri</span>
                        <span class="text-sm text-base-content/60 mt-1">Lihat semua kegiatan</span>
                    </a>
                </div>

                {{-- POTENSI WISATA — label (1) di kiri, carousel (3) di kanan --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                    <a href="{{ route('potensi-wisata.index') }}"
                       class="md:col-span-1 order-1 flex flex-col items-center justify-center text-center p-6 rounded-2xl bg-base-200/60 hover:bg-base-200 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary group">
                        <span class="text-xl font-bold group-hover:text-primary transition-colors">Potensi Wisata</span>
                        <span class="text-sm text-base-content/60 mt-1">Jelajahi destinasi</span>
                    </a>
                    <div class="md:col-span-3 order-2 overflow-hidden">
                        {{-- @if($wisataFeatured->isEmpty())
                            <x-empty-alert message="Belum ada foto Potensi Wisata." />
                        @else
                            <div class="carousel carousel-end rounded-box gap-3">
                            {{-- <div class="carousel carousel-end rounded-box
                            bg-base-200/60 hover:bg-base-200"> --}
                                @foreach($wisataFeatured as $photo)
                                    <div class="carousel-item">
                                        <figure class="flex flex-col items-center">
                                            <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                                 alt="{{ $photo->potensiWisata->nama ?? 'Potensi Wisata' }}"
                                                 class="w-64 h-48 object-cover rounded-box" loading="lazy" decoding="async" />
                                                 {{-- class="w-64 h-48 object-cover" loading="lazy" decoding="async" /> --}
                                            <figcaption class="text-sm text-center mt-2 text-base-content/70 max-w-64 truncate">
                                                {{ $photo->potensiWisata->nama ?? '' }}
                                            </figcaption>
                                        </figure>
                                    </div>
                                @endforeach
                            </div>
                        @endif --}}
                        <div class="border border-base-300 rounded-2xl p-4 bg-base-100">
                            @if($wisataFeatured->isEmpty())
                                <x-empty-alert message="Belum ada foto Potensi Wisata." />
                            @else
                                <div class="carousel carousel-end rounded-box gap-3">
                                    @foreach($wisataFeatured as $photo)
                                        <div class="carousel-item">
                                            <figure class="flex flex-col items-center">
                                                {{-- <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                                     alt="{{ $photo->potensiWisata->nama ?? 'Potensi Wisata' }}"
                                                     class="w-64 h-48 object-cover rounded-box" loading="lazy" decoding="async" /> --}}
                                                <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                                     alt="{{ $photo->potensiWisata->nama ?? 'Potensi Wisata' }}"
                                                     class="w-64 h-48 object-contain bg-base-200 rounded-box" loading="lazy" decoding="async" />
                                                <figcaption class="text-sm text-center mt-2 text-base-content/70 max-w-64 truncate">
                                                    {{ $photo->potensiWisata->nama ?? '' }}
                                                </figcaption>
                                            </figure>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <style>
            .feature-headline .char {
                display: inline-block;
                transition: transform 0.2s cubic-bezier(0.68, -0.55, 0.27, 1.55);
                cursor: default;
            }
            .feature-headline .char:hover {
                transform: translateY(-6px) scale(1.1) rotate(4deg);
            }
            .feature-headline .highlight {
                display: inline-block;
                transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
                cursor: default;
            }
            .feature-headline .highlight:hover {
                transform: scale(1.08) rotate(-2deg);
            }
            @media (prefers-reduced-motion: reduce) {
                .feature-headline .char,
                .feature-headline .highlight {
                    transition: none !important;
                }
                .feature-headline .char:hover,
                .feature-headline .highlight:hover {
                    transform: none !important;
                }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const el = document.querySelector('.feature-headline');
                if (!el) return;

                // const wordToHighlight = 'Galeri'; // ganti ke 'Kelurahan' kalau ingin kata itu yang di-highlight
                const wordToHighlight = 'Kelurahan'; // ganti ke 'Kelurahan' kalau ingin kata itu yang di-highlight
                const originalText = el.textContent.trim();
                const words = originalText.split(' ');

                el.innerHTML = words.map((word) => {
                    if (word === wordToHighlight) {
                        return '<span class="highlight text-primary font-extrabold">' + word + '</span>';
                    }
                    return word.split('').map((ch) => '<span class="char">' + ch + '</span>').join('');
                }).join(' ');
            });
        </script>

        {{-- Bagian 1: Latar Belakang
        <div class="card bg-base-100 
        card-border 
        shadow-lg 
        border-t-4">
            <div class="card-body">
                <h2 class="card-title 
                text-2xl font-bold mb-4 flex items-center gap-2 border-b pb-2">
                    <x-lucide-book-open class="w-6 h-6 mr-1 text-primary" />Latar Belakang
                </h2>
                {{-- <div class="text-lg leading-relaxed text-justify"> --}
                <div class="text-base leading-relaxed text-justify">
                    {{-- {!! nl2br(e($home->latar_belakang)) !!} --}

                    @php
                        // Memecah latar belakang per baris (enter)
                        $paragraphs = explode("\n", $home->latar_belakang);
                    @endphp
                    
                    @foreach($paragraphs as $paragraph)
                        @if(trim($paragraph))
                            {{-- Class 'indent-8' akan membuat HANYA baris pertama di paragraf ini menjorok ke kanan --}
                            <p class="indent-8 mb-4">{{ trim($paragraph) }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Bagian 2 & 3: Tujuan dan Output (Grid System) --}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            {{-- Bagian 2: Tujuan --}
            <div class="card bg-base-100 
            card-border 
            shadow-lg 
            {{-- border-t-4 border-t-primary"> --}
            border-t-4">
                <div class="card-body">
                    <h2 class="card-title 
                    text-2xl font-bold mb-4 flex items-center gap-2 border-b pb-2">
                        <x-lucide-target class="w-6 h-6 mr-1 text-primary" />Tujuan Program
                    </h2>
                    <div class="text-base leading-relaxed">
                        {{-- {!! nl2br(e($home->tujuan)) !!} --}
                        {!! $renderFormattedList($home->tujuan) !!}
                    </div>
                </div>
            </div>

            {{-- Bagian 3: Output --}
            <div class="card bg-base-100 
            card-border 
            shadow-lg 
            {{-- border-t-4 border-t-secondary"> --}
            border-t-4">
                <div class="card-body">
                    <h2 class="card-title 
                    text-2xl font-bold mb-4 flex items-center gap-2 border-b pb-2">
                        <x-lucide-award class="w-6 h-6 mr-1 text-secondary" />Output Kelurahan Cantik
                    </h2>
                    <div class="text-base leading-relaxed">
                        {{-- {!! nl2br(e($home->output)) !!} --}
                        {!! $renderFormattedList($home->output) !!}
                    </div>
                </div>
            </div>

        </div> --}}

        {{-- Section Tambahan: CTA (Call to Action) --}}
        {{-- <div class="bg-primary/20 rounded-3xl p-8 md:p-12 text-center">
            <h3 class="text-2xl font-bold mb-4 text-primary">Ingin tahu lebih lanjut tentang Baadia?</h3>
            <p class="mb-8 opacity-70">Lihat profil lengkap, struktur organisasi, dan data statistik terbaru kelurahan kami.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('about.index') }}" class="btn btn-primary">Tentang Kami</a>
                <a href="{{ route('statistical-table.index') }}" class="btn btn-outline btn-primary">Data Statistik</a>
            </div>
        </div> --}}

        <section aria-labelledby="tentang-heading">
            {{-- <h2 id="tentang-heading" class="text-2xl font-bold mb-6 flex items-center gap-2 border-b pb-2">
                <x-lucide-info class="w-6 h-6 text-primary" /> Apa itu Desa Cantik
            </h2> --}}
            <h2 id="tentang-heading" class="text-2xl font-bold mb-6 border-b pb-2">
                Apa itu Kelurahan Cantik?
            </h2>

            {{-- <div class="space-y-2">
                {{-- 1. Latar Belakang --}
                <div class="collapse collapse-plus bg-base-100 border border-base-300">
                    <input type="radio" name="accordion-tentang-descan" checked="checked" />
                    <div class="collapse-title font-semibold">
                        <h3 class="flex items-center gap-2">
                            <x-lucide-book-open class="w-5 h-5 text-primary" /> Latar Belakang
                        </h3>
                    </div>
                    <div class="collapse-content text-sm">
                        @php
                            $paragraphs = explode("\n", $home->latar_belakang);
                        @endphp
                        @foreach($paragraphs as $paragraph)
                            @if(trim($paragraph))
                                <p class="indent-8 mb-4">{{ trim($paragraph) }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- 2. Tujuan Program --}
                <div class="collapse collapse-plus bg-base-100 border border-base-300">
                    <input type="radio" name="accordion-tentang-descan" />
                    <div class="collapse-title font-semibold">
                        <h3 class="flex items-center gap-2">
                            <x-lucide-target class="w-5 h-5 text-primary" /> Tujuan Program
                        </h3>
                    </div>
                    <div class="collapse-content text-sm">
                        {!! $renderFormattedList($home->tujuan) !!}
                    </div>
                </div>

                {{-- 3. Output Kelurahan Cantik --}
                <div class="collapse collapse-plus bg-base-100 border border-base-300">
                    <input type="radio" name="accordion-tentang-descan" />
                    <div class="collapse-title font-semibold">
                        <h3 class="flex items-center gap-2">
                            <x-lucide-award class="w-5 h-5 text-secondary" /> Output Kelurahan Cantik
                        </h3>
                    </div>
                    <div class="collapse-content text-sm">
                        {!! $renderFormattedList($home->output) !!}
                    </div>
                </div>
            </div> --}}
            <div class="space-y-2">
                @php $firstOpened = false; @endphp

                {{-- 1. Latar Belakang --}}
                @if($home->show_latar_belakang ?? true)
                    @php $isFirst = !$firstOpened; $firstOpened = true; @endphp
                    <div class="collapse collapse-plus bg-base-100 border border-base-300">
                        <input type="radio" name="accordion-tentang-descan" {{ $isFirst ? 'checked="checked"' : '' }} />
                        <div class="collapse-title font-semibold">
                            <h3 class="flex items-center gap-2">
                                <x-lucide-book-open class="w-5 h-5 text-primary" /> Latar Belakang
                            </h3>
                        </div>
                        {{-- <div class="collapse-content text-sm"> --}}
                        {{-- <div class="collapse-content text-sm pl-7"> --}}
                        <div class="collapse-content text-sm pl-11">
                            @php $paragraphs = explode("\n", $home->latar_belakang); @endphp
                            @foreach($paragraphs as $paragraph)
                                @if(trim($paragraph))
                                    <p class="indent-8 mb-4">{{ trim($paragraph) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- 2. Tujuan Program --}}
                @if($home->show_tujuan ?? true)
                    @php $isFirst = !$firstOpened; $firstOpened = true; @endphp
                    <div class="collapse collapse-plus bg-base-100 border border-base-300">
                        <input type="radio" name="accordion-tentang-descan" {{ $isFirst ? 'checked="checked"' : '' }} />
                        <div class="collapse-title font-semibold">
                            <h3 class="flex items-center gap-2">
                                <x-lucide-target class="w-5 h-5 text-primary" /> Tujuan Program
                            </h3>
                        </div>
                        {{-- <div class="collapse-content text-sm"> --}}
                        {{-- <div class="collapse-content text-sm pl-7"> --}}
                        <div class="collapse-content text-sm pl-11">
                            {!! $renderFormattedList($home->tujuan) !!}
                        </div>
                    </div>
                @endif

                {{-- 3. Output Kelurahan Cantik --}}
                @if($home->show_output ?? true)
                    @php $isFirst = !$firstOpened; $firstOpened = true; @endphp
                    <div class="collapse collapse-plus bg-base-100 border border-base-300">
                        <input type="radio" name="accordion-tentang-descan" {{ $isFirst ? 'checked="checked"' : '' }} />
                        <div class="collapse-title font-semibold">
                            <h3 class="flex items-center gap-2">
                                <x-lucide-award class="w-5 h-5 text-secondary" /> Output Kelurahan Cantik
                            </h3>
                        </div>
                        {{-- <div class="collapse-content text-sm"> --}}
                        {{-- <div class="collapse-content text-sm pl-7"> --}}
                        <div class="collapse-content text-sm pl-11">
                            {!! $renderFormattedList($home->output) !!}
                        </div>
                    </div>
                @endif

                {{-- 4. Tim Kelurahan Cantik (baru, dinamis) --}}
                @if($home->show_tim ?? true)
                    @php $isFirst = !$firstOpened; $firstOpened = true; @endphp
                    <div class="collapse collapse-plus bg-base-100 border border-base-300">
                        <input type="radio" name="accordion-tentang-descan" {{ $isFirst ? 'checked="checked"' : '' }} />
                        <div class="collapse-title font-semibold">
                            <h3 class="flex items-center gap-2">
                                <x-lucide-users class="w-5 h-5 text-secondary" /> Tim Kelurahan Cantik
                            </h3>
                        </div>
                        {{-- <div class="collapse-content text-sm"> --}}
                        {{-- <div class="collapse-content text-sm pl-7"> --}}
                        <div class="collapse-content text-sm pl-11">
                            @if(trim($home->tim_kelurahan ?? ''))
                                {!! $renderFormattedList($home->tim_kelurahan) !!}
                            @else
                                <x-empty-alert message="Data Tim Kelurahan Cantik belum diisi." />
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-layout>