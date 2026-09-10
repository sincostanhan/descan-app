<x-layout title="Galeri">
    <x-hero
        title="Galeri Kelurahan"
        subtitle="Dokumentasi berbagai acara, kegiatan, dan momen penting yang telah dilaksanakan di kelurahan kami."
    />

    {{-- <div class="max-w-6xl mx-auto px-4 lg:px-0 
    {{-- py-12 md:py-16  --}}
    {{-- space-y-20 --}}
    {{-- space-y-6 --}
    space-y-16
    ">  
        @forelse($galleries as $gallery)
            <div class="space-y-6">
                <div class="text-center md:text-left border-b 
                {{-- border-base-300  --}}
                {{-- border-secondary  --}}
                {{-- border-neutral-content  --}
                border-base-content/60
                pb-4">
                    <h2 class="text-3xl text-secondary
                    {{-- font-bold --}
                    font-semibold
                    {{-- ">{{ $gallery->nama_kegiatan }}</h2> --}
                    ">{{ $gallery->judul }}</h2>
                    {{-- <h2 class="text-2xl font-bold text-secondary">{{ $gallery->nama_kegiatan }}</h2> --}
                    <p class="text-base-content/60 mt-2 
                    text-sm 
                    flex items-center justify-center md:justify-start">
                        {{-- <x-lucide-calendar class="w-5 h-5 text-secondary" /> --}
                        <x-lucide-calendar class="w-4 h-4 mr-1" />
                        {{-- Dipublikasikan pada: {{ $gallery->created_at->format('d M Y') }} --}}
                        {{-- Dipublikasikan pada: {{ $gallery->created_at->translatedFormat('d F Y') }} --}
                        Dipublikasikan pada {{ $gallery->created_at->translatedFormat('d F Y') }}
                    </p>
                </div>

                @if($gallery->photos->isEmpty())
                    {{-- <div role="alert" class="alert alert-info alert-soft  --}
                    <div role="alert" class="alert alert-warning alert-soft 
                    h-20 flex items-center justify-center">
                        {{-- <span>Belum ada foto.</span> --}
                        <span class="italic justify-center">Belum ada foto.</span>
                    </div>
                @else
                    <div class="flex justify-center w-full">
                        <div class="carousel carousel-center 
                        {{-- bg-neutral  --}
                        bg-base-200
                        rounded-box max-w-full space-x-4 p-4 
                        w-fit 
                        shadow-xl">
                            @foreach($gallery->photos as $photo)
                                {{-- <div class="carousel-item"> --}}
                                {{-- <div class="carousel-item rounded-box overflow-hidden"> --}
                                <div class="carousel-item rounded-box overflow-hidden relative">
                                    <div class="skeleton absolute inset-0 rounded-box"></div>
                                    <img src="{{ asset('storage/' . $photo->foto_path) }}"
                                    {{-- <img src="{{ Storage::url($photo->foto_path) }}"  --}}
                                         {{-- alt="Foto {{ $gallery->nama_kegiatan }}" --}
                                         alt="Foto {{ $gallery->judul }}"
                                         {{-- class="h-72 md:h-96 object-cover hover:scale-105 transition-transform duration-500 cursor-pointer" /> --}
                                         class="h-40 md:h-96 
                                         {{-- object-cover  --}
                                         object-cover relative opacity-0 transition-opacity duration-300
                                         {{-- object-cover relative opacity-0 transition-opacity duration-3000 --}}
                                         {{-- hover:scale-105 transition-transform duration-500  --}}
                                         {{-- cursor-pointer --}}
                                         {{-- " />                                         --}
                                        "
                                        {{-- loading="lazy" decoding="async" /> --}
                                        loading="lazy" decoding="async"
                                        onload="this.classList.remove('opacity-0'); this.previousElementSibling.remove();"
                                        onerror="this.previousElementSibling.remove();" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <x-empty-alert message="Belum ada kegiatan di galeri." />
        @endforelse --}}
    {{-- </div> --}}
    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-20">
        @forelse($galleries as $gallery)
            <p></p>
        @empty
            <x-empty-alert message="Belum ada kegiatan di galeri." />
        @endforelse

        @if($galleries->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($galleries as $gallery)
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
                                Dipublikasikan pada {{ $gallery->created_at->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>