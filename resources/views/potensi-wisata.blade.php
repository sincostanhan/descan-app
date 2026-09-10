<x-layout title="Potensi Wisata">
    <x-hero title="Potensi Wisata" />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-20">
        <div class="tabs tabs-border">
            <input type="radio" name="potensi_wisata_tabs" class="tab" aria-label="Potensi Wisata" checked="checked" />
            <div class="tab-content border-base-300 bg-base-100 p-6">
                @if($umum->isEmpty())
                    <x-empty-alert message="Belum ada data Potensi Wisata." />
                @else
                    {{-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"> --}}
                    <div class="carousel rounded-box gap-4">
                        @foreach($umum as $item)
                            {{-- <div class="card bg-base-100 border border-base-300 shadow-sm">
                                <figure class="aspect-[4/3] bg-base-200"> --}}
                            <div class="carousel-item">
                                <div class="card bg-base-100 border border-base-300 shadow-sm w-64">
                                <figure class="aspect-[4/3] bg-base-200 w-64">
                                    @if($item->photos->first())
                                        <img src="{{ asset('storage/' . $item->photos->first()->foto_path) }}"
                                             alt="{{ $item->nama }}" class="w-full h-full object-cover" loading="lazy" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-base-content/40">
                                            <x-lucide-image class="w-8 h-8" />
                                        </div>
                                    @endif
                                </figure>
                                <div class="card-body p-4">
                                    <h3 class="font-semibold">{{ $item->nama }}</h3>
                                </div>
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
                    {{-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"> --}}
                    <div class="carousel rounded-box gap-4">
                        @foreach($situsBersejarah as $item)
                            {{-- <div class="card bg-base-100 border border-base-300 shadow-sm">
                                <figure class="aspect-[4/3] bg-base-200"> --}}
                            <div class="carousel-item">
                                <div class="card bg-base-100 border border-base-300 shadow-sm w-64">
                                <figure class="aspect-[4/3] bg-base-200 w-64">
                                    @if($item->photos->first())
                                        <img src="{{ asset('storage/' . $item->photos->first()->foto_path) }}"
                                             alt="{{ $item->nama }}" class="w-full h-full object-cover" loading="lazy" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-base-content/40">
                                            <x-lucide-image class="w-8 h-8" />
                                        </div>
                                    @endif
                                </figure>
                                <div class="card-body p-4">
                                    <h3 class="font-semibold">{{ $item->nama }}</h3>
                                </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>