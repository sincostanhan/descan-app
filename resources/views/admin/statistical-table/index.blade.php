<x-layout-admin title="Kelola Tabel Statistik">
    <x-hero
        title="Tabel Statistik"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <div class="flex justify-end mb-6">
            <a 
                href="{{ route('admin.statistical-table.create') }}" 
                class="btn btn-sm md:btn-md 
                    btn-secondary">
                {{-- <x-lucide-plus class="w-5 h-5" /> --}}
                <x-lucide-plus class="w-5 h-5 mr-1" />
                Tambah Tabel
            </a>
        </div>

        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Daftar Tabel Statistik</h2>

                @if($tables->isEmpty())
                    <x-empty-alert message="Belum ada tabel statistik yang ditambahkan." />
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
                                    <th>Tabel Statistik</th>
                                    <th>Publikasi & Bab</th>
                                    <th>Tanggal Update</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tables as $table)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        
                                        {{-- <td>
                                            <div class="font-medium max-w-50 md:max-w-xs text-wrap wrap-break-words">
                                                {{ $table->title }}
                                            </div>
                                        </td> --}}
                                        <td class="font-medium
                                        whitespace-nowrap">{{ $table->title }}</td>
                                        
                                        <td>
                                            <div class="font-medium
                                            whitespace-nowrap">{{ $table->publication }}</div>
                                            <div class="badge badge-secondary badge-outline badge-sm mt-1">Bab {{ $table->chapter }}</div>
                                        </td>

                                        <td>{{ $table->updated_at->format('d M Y') }}</td>

                                        <td class="text-center space-x-1 
                                        whitespace-nowrap">
                                            <a href="{{ route('admin.statistical-table.edit', $table->id) }}" class="btn btn-warning btn-sm 
                                                text-white">Edit</a>
                                            <form action="{{ route('admin.statistical-table.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tabel statistik ini?');" class="inline-block">
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