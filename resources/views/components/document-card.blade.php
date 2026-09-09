{{-- resources\views\components\document-card.blade.php --}}

@props(['title', 'description', 'date', 'coverUrl' => null, 'fileUrl', 'buttonText' => 'Lihat Dokumen'])

<div class="card bg-base-100 
    card-border 
    shadow-lg
    md:card-side"
>
    {{-- Bagian Cover (Kiri pada Desktop, Atas pada Mobile) --}}
    <figure class="md:w-1/4 shrink-0 
        bg-base-200/20 border-base-200 
        border-b md:border-b-0 md:border-r 
        flex items-center justify-center p-6"
    >
        @if($coverUrl)
            {{-- Tampilkan gambar (hasil ekstrak PDF atau file gambar asli) --}}
            {{-- <img src="{{ $coverUrl }}" 
                alt="Cover {{ $title }}" 
                class="
                    max-w-36 md:max-w-44 w-full 
                    rounded shadow-md border border-base-300 
                    object-cover
                "  
                loading="lazy" decoding="async"
            /> --}}
            <div class="relative w-full max-w-36 md:max-w-44">
                <div class="skeleton absolute inset-0 rounded"></div>
                <img src="{{ $coverUrl }}" 
                    alt="Cover {{ $title }}" 
                    class="
                        max-w-36 md:max-w-44 w-full 
                        rounded shadow-md border border-base-300 
                        object-cover relative opacity-0 transition-opacity duration-300
                        {{-- object-cover relative opacity-0 transition-opacity duration-3000 --}}
                    "
                    loading="lazy" decoding="async"
                    onload="this.classList.remove('opacity-0'); this.previousElementSibling.remove();"
                    onerror="this.previousElementSibling.remove();"
                />
            </div>
        @else
            {{-- Fallback: Jika data bermasalah/tidak ada cover --}}
            <div class="
                flex flex-col items-center justify-center text-base-content/40 py-8
            ">
                <x-lucide-file-text class="w-16 h-16 mb-2" />
                <span class="text-sm">Tidak ada sampul</span>
            </div>
        @endif
    </figure>

    {{-- Bagian Konten Teks (Kanan pada Desktop, Bawah pada Mobile) --}}
    <div class="
        card-body 
        md:w-3/4"
    >
        <h2 class="
            card-title text-secondary 
            text-2xl border-b pb-2"
        >{{ $title }}</h2>
        <p class="
            text-base-content/80 text-sm md:text-base leading-relaxed 
            mt-2 mb-4"
        >{{ $description }}</p>
                    
        {{-- mt-auto mendorong tombol ini selalu berada di paling bawah kartu --}}
        <div class="
            flex items-center justify-between 
            mt-auto pt-4"
        >
            <span class="text-xs text-base-content/60 flex items-center">
                <x-lucide-calendar class="w-4 h-4 mr-1" />
                Diunggah pada {{ $date }}
            </span>
            <a 
                href="{{ $fileUrl }}" 
                target="_blank" 
                {{-- class="btn btn-secondary btn-sm text-white" --}}
                class="btn btn-secondary btn-sm"
            >{{ $buttonText }}</a>
        </div>
    </div>
</div>