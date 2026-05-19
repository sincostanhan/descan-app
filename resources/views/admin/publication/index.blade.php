<x-layout-admin title="Kelola Publikasi">
    <x-hero
        title="Publikasi"
    />
    
    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <div class="flex justify-end mb-6">
            <a class="btn btn-sm md:btn-md 
            btn-secondary" 
            href="{{ route('admin.publication.create') }}">
                <x-lucide-plus class="w-5 h-5" /> Tambah Publikasi
            </a>
        </div>

        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Daftar Publikasi</h2>

                @if($publications->isEmpty())
                    <x-empty-alert message="Belum ada dokumen publikasi." />
                @else
                    <div class="overflow-x-auto 
                    rounded-box border-base-200 
                    border">
                        <table class="table table-zebra 
                        w-full">
                            <thead class="bg-base-200/50 text-base-content 
                            text-sm">
                                <tr>
                                    <th class="w-16">No</th>
                                    {{-- <th>Judul Publikasi</th> --}}
                                    <th>Publikasi</th>
                                    <th
                                        class="text-center"
                                    >File</th>
                                    <th>Tanggal Update</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($publications as $pub)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        {{-- <td class="font-medium">{{ $pub->title }}</td> --}}
                                        
                                        {{-- Penggabungan Cover dan Judul dengan Flexbox --}}
                                        <td>
                                            <div class="flex items-center gap-4">
                                                <div class="avatar">
                                                    <div class="w-12 h-12 
                                                    rounded border border-base-300 flex items-center justify-center bg-base-200">
                                                        {{-- Cek Cover Ekstrak --}}
                                                        @if($pub->cover_path)
                                                            <img src="{{ asset('storage/' . $pub->cover_path) }}" alt="Cover" class="object-cover" />
                                                        @else
                                                            <x-lucide-file-text class="w-6 h-6 text-base-content/40" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-medium max-w-50 md:max-w-xs text-wrap wrap-break-words">{{ $pub->title }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td
                                            class="text-center
                                            whitespace-nowrap"
                                        >
                                            <a 
                                                href="{{ asset('storage/' . $pub->file_path) }}" 
                                                target="_blank" 
                                                {{-- class="link link-primary" --}}
                                                class="btn btn-info btn-sm text-white"
                                            >
                                                {{-- Lihat PDF --}}
                                                <x-lucide-external-link class="w-4 h-4 mr-1"/> Lihat File
                                            </a>
                                        </td>
                                        <td>{{ $pub->updated_at->format('d M Y') }}</td>
                                        <td class="text-center space-x-1 
                                        whitespace-nowrap">
                                            <a href="{{ route('admin.publication.edit', $pub->id) }}" class="btn btn-warning btn-sm 
                                                text-white">Edit</a>
                                            <form action="{{ route('admin.publication.destroy', $pub->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus publikasi ini?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error btn-sm 
                                                text-white">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout-admin>


