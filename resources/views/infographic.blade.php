<x-layout title="Infografis Kelurahan">
    <x-hero
        title="Infografis"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 
    space-y-6
    {{-- mb-12 --}}
    ">
        @forelse($infographics as $info)
            <div class="card bg-base-100 
            card-border 
            shadow-lg
            md:card-side"
        >
            {{-- Logika Penentuan Cover Infografis --}}
            @php
                $isImage = \Illuminate\Support\Str::endsWith(strtolower($info->file_path), ['.jpg', '.jpeg', '.png']);
                $coverSrc = $info->cover_path ? asset('storage/' . $info->cover_path) : ($isImage ? asset('storage/' . $info->file_path) : null);
            @endphp

            <x-document-card 
                :title="$info->title"
                :description="$info->description"
                :date="$info->created_at->translatedFormat('d F Y')"
                :coverUrl="$coverSrc"
                :fileUrl="asset('storage/' . $info->file_path)"
                buttonText="Lihat Infografis"
            />
        @empty
            <x-empty-alert message="Belum ada data infografis." />
        @endforelse
    </div>
</x-layout>