<x-layout-admin title="Pilih Template Tabel Statistik">
    <x-hero
        title="Pilih Template Tabel"
        subtitle="Pilih template yang sudah disediakan Admin BPS untuk mulai mengisi data"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        @if($templates->isEmpty())
            <x-empty-alert message="Belum ada template yang tersedia untuk diisi saat ini. Kemungkinan semua template aktif sudah Anda isi, atau Admin BPS belum menyediakan template baru." />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($templates as $template)
                    {{-- <div class="card bg-base-100 card-border shadow-lg">
                        <div class="card-body">
                            <h2 class="card-title text-secondary text-lg border-b pb-2 mb-2">
                                {{ $template->title }}
                            </h2> --}}
                    <x-section-card :title="$template->title" title-size="text-lg">

                            @if($template->description)
                                <p class="text-sm text-base-content/70 leading-relaxed line-clamp-3">
                                    {{ $template->description }}
                                </p>
                            @else
                                <p class="text-sm text-base-content/40 italic">Tidak ada deskripsi.</p>
                            @endif

                            <div class="mt-3">
                                @if($template->row_source === 'rt_rw')
                                    <div class="badge badge-outline badge-sm whitespace-nowrap">Baris Otomatis per RT/RW</div>
                                @else
                                    <div class="badge badge-outline badge-sm whitespace-nowrap">Baris Ditentukan BPS</div>
                                @endif
                            </div>

                            <div class="card-actions justify-end mt-4 pt-4 border-t">
                                <a href="{{ route('admin.statistic-table-entries.create', $template) }}" class="btn btn-secondary btn-sm">
                                    Isi Data <x-lucide-arrow-right class="w-4 h-4 ml-1" />
                                </a>
                            </div>
                        {{-- </div>
                    </div> --}}
                    </x-section-card>
                @endforeach
            </div>
        @endif
    </div>
</x-layout-admin>