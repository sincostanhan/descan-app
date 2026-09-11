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
            $jumlahTabelGrafik = \App\Models\StatisticTableEntry::whereHas('template', fn($q) => $q->where('is_active', true))->count();
            $jumlahPublikasi = \App\Models\Publication::count();
            $jumlahInfografis = \App\Models\Infographic::count();

            $publicationCovers = \App\Models\Publication::whereNotNull('cover_path')
                ->latest()->take(4)->pluck('cover_path');

            $infographicCovers = \App\Models\Infographic::latest()->take(8)->get()
                ->map(function ($item) {
                    if ($item->cover_path) return $item->cover_path;
                    if (\Illuminate\Support\Str::endsWith(strtolower($item->file_path), ['.jpg', '.jpeg', '.png'])) {
                        return $item->file_path;
                    }
                    return null;
                })
                ->filter()
                ->take(4);
        @endphp

        <section aria-labelledby="statistik-heading">
            <h2 id="statistik-heading" class="text-2xl font-bold mb-6 pt-10 border-t border-base-300">
                Ringkasan Data
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="stats bg-base-100 border-base-300 border w-full shadow">
                    <div class="stat">
                        <div class="stat-figure text-primary">
                            <x-lucide-table-2 class="w-8 h-8" />
                        </div>
                        <div class="stat-title">Tabel dan Grafik</div>
                        <div class="stat-value text-primary">{{ $jumlahTabelGrafik }}</div>
                        <div class="stat-actions">
                            <a href="{{ route('public.statistic.index') }}" class="btn btn-xs btn-primary">Lihat</a>
                        </div>
                    </div>
                </div>

                <div class="stats bg-base-100 border-base-300 border w-full shadow">
                    <div class="stat">
                        <div class="stat-figure text-secondary">
                            @if($publicationCovers->isNotEmpty())
                                <figure class="hover-gallery w-16">
                                    @foreach($publicationCovers as $cover)
                                        <img src="{{ asset('storage/' . $cover) }}" alt="Cover Publikasi" loading="lazy" />
                                    @endforeach
                                </figure>
                            @else
                                <x-lucide-file-text class="w-8 h-8" />
                            @endif
                        </div>
                        <div class="stat-title">Publikasi</div>
                        <div class="stat-value text-secondary">{{ $jumlahPublikasi }}</div>
                        <div class="stat-actions">
                            <a href="{{ route('publication.index') }}" class="btn btn-xs btn-secondary">Lihat</a>
                        </div>
                    </div>
                </div>

                <div class="stats bg-base-100 border-base-300 border w-full shadow">
                    <div class="stat">
                        <div class="stat-figure text-accent">
                            @if($infographicCovers->isNotEmpty())
                                <figure class="hover-gallery w-16">
                                    @foreach($infographicCovers as $cover)
                                        <img src="{{ asset('storage/' . $cover) }}" alt="Cover Infografis" loading="lazy" />
                                    @endforeach
                                </figure>
                            @else
                                <x-lucide-image class="w-8 h-8" />
                            @endif
                        </div>
                        <div class="stat-title">Infografis</div>
                        <div class="stat-value text-accent">{{ $jumlahInfografis }}</div>
                        <div class="stat-actions">
                            <a href="{{ route('infographic.index') }}" class="btn btn-xs btn-accent">Lihat</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @php
            $galleryItems = \App\Models\Gallery::whereHas('photos', fn($q) => $q->where('tampil_beranda', true))
                ->with(['photos' => fn($q) => $q->where('tampil_beranda', true)])
                ->orderBy('id')->get();
            if ($galleryItems->isEmpty()) {
                $galleryItems = \App\Models\Gallery::with('photos')->orderBy('id')->take(5)->get();
            }

            $wisataItems = \App\Models\PotensiWisata::whereHas('photos', fn($q) => $q->where('tampil_beranda', true))
                ->with(['photos' => fn($q) => $q->where('tampil_beranda', true)])
                ->orderBy('id')->get();
            if ($wisataItems->isEmpty()) {
                $wisataItems = \App\Models\PotensiWisata::with('photos')->orderBy('id')->take(5)->get();
            }
        @endphp

        <section aria-labelledby="feature-heading">
            {{-- <h2 id="feature-heading" class="feature-headline text-3xl font-bold mb-6 border-b pb-2 text-center"> --}}
            <h2 id="feature-heading" class="feature-headline text-3xl font-bold mb-6 pt-10 border-t border-base-300 text-center">
                Galeri Kelurahan
            </h2>

            <div class="space-y-10">
                {{-- GALERI — marquee kiri ke kanan --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-stretch">
                    <div class="md:col-span-3 order-2 md:order-1 overflow-hidden">
                        <div class="border border-base-300 rounded-2xl p-4 bg-base-100">
                            @if($galleryItems->isEmpty())
                                <x-empty-alert message="Belum ada Galeri Kegiatan." />
                            @else
                                <div class="relative marquee-wrapper">
                                    <div class="absolute left-0 top-0 bottom-0 w-12 md:w-16 bg-gradient-to-r from-base-100 to-transparent z-10 pointer-events-none"></div>
                                    <div class="absolute right-0 top-0 bottom-0 w-12 md:w-16 bg-gradient-to-l from-base-100 to-transparent z-10 pointer-events-none"></div>

                                    <div class="flex gap-4 overflow-x-auto marquee-track cursor-grab active:cursor-grabbing" style="scrollbar-width: none;">
                                        @foreach($galleryItems as $gallery)
                                            <div class="carousel-item flex-shrink-0">
                                                <div class="card bg-base-100 border border-base-300 shadow-sm rounded-2xl overflow-hidden w-72">
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
                                                                         {{-- class="w-full h-40 object-contain bg-base-200" loading="lazy" decoding="async" draggable="false" /> --}}
                                                                         class="w-full h-40 object-contain" loading="lazy" decoding="async" draggable="false" />
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <div class="card-body p-3">
                                                        <h3 class="font-semibold text-sm truncate">{{ $gallery->judul }}</h3>
                                                        <p class="text-xs text-base-content/60">{{ $gallery->created_at->translatedFormat('d F Y') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div data-marquee-reset class="w-0 h-0 flex-shrink-0"></div>
                                        @foreach($galleryItems as $gallery)
                                            <div class="carousel-item flex-shrink-0" aria-hidden="true">
                                                <div class="card bg-base-100 border border-base-300 shadow-sm rounded-2xl overflow-hidden w-72">
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
                                                                         {{-- class="w-full h-40 object-contain bg-base-200" loading="lazy" decoding="async" draggable="false" /> --}}
                                                                         class="w-full h-40 object-contain" loading="lazy" decoding="async" draggable="false" />
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <div class="card-body p-3">
                                                        <h3 class="font-semibold text-sm truncate">{{ $gallery->judul }}</h3>
                                                        <p class="text-xs text-base-content/60">{{ $gallery->created_at->translatedFormat('d F Y') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('gallery.index') }}"
                       {{-- class="md:col-span-1 order-1 md:order-2 h-full flex flex-col items-center justify-center text-center p-6 rounded-2xl bg-base-200/60 hover:bg-base-200 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary group"> --}}
                       class="md:col-span-1 order-1 md:order-2 h-full flex flex-col items-center justify-center text-center p-6 rounded-2xl bg-base-200/60 hover:bg-base-200 hover:scale-105 transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-primary group">
                        <span class="text-xl font-bold group-hover:text-primary transition-colors">Galeri</span>
                        <span class="text-sm text-base-content/60 mt-1">Lihat semua kegiatan</span>
                    </a>
                </div>

                {{-- POTENSI WISATA — marquee kanan ke kiri --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-stretch">
                    <a href="{{ route('potensi-wisata.index') }}"
                       {{-- class="md:col-span-1 order-1 h-full flex flex-col items-center justify-center text-center p-6 rounded-2xl bg-base-200/60 hover:bg-base-200 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary group"> --}}
                       class="md:col-span-1 order-1 h-full flex flex-col items-center justify-center text-center p-6 rounded-2xl bg-base-200/60 hover:bg-base-200 hover:scale-105 transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-primary group">
                        <span class="text-xl font-bold group-hover:text-primary transition-colors">Potensi Wisata</span>
                        <span class="text-sm text-base-content/60 mt-1">Jelajahi destinasi</span>
                    </a>
                    <div class="md:col-span-3 order-2 overflow-hidden">
                        <div class="border border-base-300 rounded-2xl p-4 bg-base-100">
                            @if($wisataItems->isEmpty())
                                <x-empty-alert message="Belum ada Potensi Wisata." />
                            @else
                                <div class="relative marquee-wrapper">
                                    <div class="absolute left-0 top-0 bottom-0 w-12 md:w-16 bg-gradient-to-r from-base-100 to-transparent z-10 pointer-events-none"></div>
                                    <div class="absolute right-0 top-0 bottom-0 w-12 md:w-16 bg-gradient-to-l from-base-100 to-transparent z-10 pointer-events-none"></div>

                                    <div class="flex gap-4 overflow-x-auto marquee-track cursor-grab active:cursor-grabbing" data-direction="reverse" style="scrollbar-width: none;">
                                        @foreach($wisataItems as $item)
                                            <div class="carousel-item flex-shrink-0">
                                                <div class="card bg-base-100 border border-base-300 shadow-sm rounded-2xl overflow-hidden w-72">
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
                                                                         {{-- class="w-full h-40 object-contain bg-base-200" loading="lazy" decoding="async" draggable="false" /> --}}
                                                                         class="w-full h-40 object-contain" loading="lazy" decoding="async" draggable="false" />
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <div class="card-body p-3">
                                                        <h3 class="font-semibold text-sm truncate">{{ $item->nama }}</h3>
                                                        <p class="text-xs text-base-content/60">{{ $item->created_at->translatedFormat('d F Y') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div data-marquee-reset class="w-0 h-0 flex-shrink-0"></div>
                                        @foreach($wisataItems as $item)
                                            <div class="carousel-item flex-shrink-0" aria-hidden="true">
                                                <div class="card bg-base-100 border border-base-300 shadow-sm rounded-2xl overflow-hidden w-72">
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
                                                                         {{-- class="w-full h-40 object-contain bg-base-200" loading="lazy" decoding="async" draggable="false" /> --}}
                                                                         class="w-full h-40 object-contain" loading="lazy" decoding="async" draggable="false" />
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <div class="card-body p-3">
                                                        <h3 class="font-semibold text-sm truncate">{{ $item->nama }}</h3>
                                                        <p class="text-xs text-base-content/60">{{ $item->created_at->translatedFormat('d F Y') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
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
            .marquee-track::-webkit-scrollbar {
                display: none;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const el = document.querySelector('.feature-headline');
                if (el) {
                    const wordToHighlight = 'Kelurahan';
                    const originalText = el.textContent.trim();
                    const words = originalText.split(' ');
                    el.innerHTML = words.map((word) => {
                        if (word === wordToHighlight) {
                            return '<span class="highlight text-primary font-extrabold">' + word + '</span>';
                        }
                        return word.split('').map((ch) => '<span class="char">' + ch + '</span>').join('');
                    }).join(' ');
                }

                document.querySelectorAll('.marquee-track').forEach((track) => {
                    const reverse = track.dataset.direction === 'reverse';
                    const baseSpeed = 0.6;
                    const speed = reverse ? -baseSpeed : baseSpeed;

                    let autoPlay = true;
                    let isDragging = false;
                    let startX = 0;
                    let scrollLeftStart = 0;
                    let moved = false;

                    const resetMarker = track.querySelector('[data-marquee-reset]');
                    const resetPoint = () => resetMarker ? resetMarker.offsetLeft : track.scrollWidth / 2;

                    function wrapIfNeeded() {
                        const rp = resetPoint();
                        if (track.scrollLeft >= rp) {
                            track.scrollLeft -= rp;
                            scrollLeftStart -= rp;
                        } else if (reverse && track.scrollLeft <= 0) {
                            track.scrollLeft += rp;
                            scrollLeftStart += rp;
                        }
                    }

                    function tick() {
                        if (autoPlay && !isDragging) {
                            track.scrollLeft += speed;
                            wrapIfNeeded();
                        }
                        requestAnimationFrame(tick);
                    }

                    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                    if (reverse) {
                        requestAnimationFrame(() => { track.scrollLeft = resetPoint(); });
                    }
                    if (!prefersReducedMotion) {
                        requestAnimationFrame(tick);
                    }

                    track.addEventListener('mouseenter', () => { autoPlay = false; });
                    track.addEventListener('mouseleave', () => { autoPlay = true; isDragging = false; });

                    track.addEventListener('mousedown', (e) => {
                        isDragging = true;
                        moved = false;
                        startX = e.pageX;
                        scrollLeftStart = track.scrollLeft;
                    });
                    window.addEventListener('mouseup', () => { isDragging = false; });
                    track.addEventListener('mousemove', (e) => {
                        if (!isDragging) return;
                        const delta = e.pageX - startX;
                        if (Math.abs(delta) > 5) moved = true;
                        if (moved) {
                            e.preventDefault();
                            track.scrollLeft = scrollLeftStart - delta;
                            wrapIfNeeded();
                        }
                    });
                    track.addEventListener('click', (e) => {
                        if (moved) { e.preventDefault(); e.stopPropagation(); }
                    }, true);

                    track.addEventListener('scroll', () => {
                        if (!isDragging) wrapIfNeeded();
                    });
                });
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
            {{-- <h2 id="tentang-heading" class="text-2xl font-bold mb-6 border-b pb-2"> --}}
            <h2 id="tentang-heading" class="text-2xl font-bold mb-6 pt-10 border-t border-base-300">
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