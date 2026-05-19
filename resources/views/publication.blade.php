<x-layout title="Publikasi Kelurahan">
    <x-hero
        title="Publikasi"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 
    space-y-6">
        @forelse($publications as $pub)
            <x-document-card 
                :title="$pub->title"
                :description="$pub->description"
                :date="$pub->created_at->translatedFormat('d F Y')"
                :coverUrl="$pub->cover_path ? asset('storage/' . $pub->cover_path) : null"
                :fileUrl="asset('storage/' . $pub->file_path)"
                buttonText="Lihat Publikasi"
            />
        @empty
            <x-empty-alert message="Belum ada data publikasi." />
        @endforelse
    </div>
</x-layout>