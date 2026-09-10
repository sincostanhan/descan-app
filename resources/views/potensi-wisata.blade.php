<x-layout title="Potensi Wisata">
    <x-hero title="Potensi Wisata" />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-20">
        <div class="tabs tabs-border">
            <input type="radio" name="potensi_wisata_tabs" class="tab" aria-label="Potensi Wisata" checked="checked" />
            <div class="tab-content border-base-300 bg-base-100 p-6">
                @if($umum->isEmpty())
                    <x-empty-alert message="Belum ada data Potensi Wisata." />
                @else
                    {{-- <div class="space-y-8"> --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($umum as $item)
                            {{-- <div class="card bg-base-100 border border-base-300 shadow-sm rounded-2xl overflow-hidden lg:w-[20vw]"> --}}
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
                                                     {{-- class="w-full h-48 object-cover" loading="lazy" decoding="async" /> --}}
                                                     class="w-full h-48 object-contain bg-base-200" loading="lazy" decoding="async" />
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="card-body p-4">
                                    <h3 class="font-semibold">{{ $item->nama }}</h3>
                                    {{-- <p class="text-xs text-base-content/60">Diperbarui pada: {{ $item->updated_at->format('d F Y, H:i') }}</p> --}}
                                    <p class="text-xs text-base-content/60 flex items-center">
                                        <x-lucide-calendar class="w-3.5 h-3.5 mr-1" />
                                        Dipublikasikan pada tanggal {{ $item->created_at->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <input type="radio" name="potensi_wisata_tabs" class="tab" aria-label="Situs Bersejarah" />
            <div class="tab-content border-base-300 bg-base-100 p-6">
                @if($situsBersejarah->isEmpty())
                    <x-empty-alert message="Belum ada data Situs Bersejarah." />
                @else
                    {{-- <div class="space-y-8"> --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($situsBersejarah as $item)
                            {{-- <div class="card bg-base-100 border border-base-300 shadow-sm rounded-2xl overflow-hidden lg:w-[20vw]"> --}}
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
                                                     {{-- class="w-full h-48 object-cover" loading="lazy" decoding="async" /> --}}
                                                     {{-- class="w-full h-48 object-contain " loading="lazy" decoding="async" /> --}}
                                                     class="w-full h-48 object-contain bg-base-200" loading="lazy" decoding="async" />
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="card-body p-4">
                                    <h3 class="font-semibold">{{ $item->nama }}</h3>
                                    {{-- <p class="text-xs text-base-content/60">Diperbarui pada: {{ $item->updated_at->format('d F Y, H:i') }}</p> --}}
                                    <p class="text-xs text-base-content/60 flex items-center">
                                        <x-lucide-calendar class="w-3.5 h-3.5 mr-1" />
                                        Dipublikasikan pada tanggal {{ $item->created_at->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>