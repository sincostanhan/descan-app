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

        <section aria-labelledby="feature-heading"> ... 2 hover-3d card ... </section>

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