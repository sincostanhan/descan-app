<x-layout title="Struktur Organisasi">
    <x-hero
        title="Struktur Organisasi"
    />

    {{-- <div class="max-w-6xl mx-auto px-4 lg:px-0 space-y-6 mb-8"> --}}
    <div class="max-w-6xl mx-auto px-4 lg:px-0 space-y-6">
        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-primary 
                text-2xl mb-4 border-b pb-2">Perangkat Kelurahan</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="col-span-full flex justify-center mb-2">
                        {{-- <div class="bg-base-200 border-secondary  --}}
                        <div class="bg-base-200 border-primary 
                        p-4 rounded-lg border-l-4 
                        w-full md:w-1/2 lg:w-1/3 text-center shadow-sm">
                        {{-- w-full md:w-1/2 lg:w-1/3 shadow-sm"> --}}
                            <h4 class="text-base-content/70 
                            text-sm font-medium">Lurah</h4>
                            <p class="text-xl font-bold">{{ $organization->lurah ?: '-' }}</p>
                            {{-- <p class="text-xl">{{ $organization->lurah ?: '-' }}</p> --}}
                        </div>
                    </div>

                    <div class="col-span-full flex justify-center mb-6">
                        <div class="bg-base-200 border-primary 
                        p-4 rounded-lg border-l-4 
                        w-full md:w-1/2 lg:w-1/3 text-center shadow-sm">
                            <h4 class="text-base-content/70 
                            text-sm font-medium">Sekretaris Lurah</h4>
                            <p class="text-lg font-bold">{{ $organization->sekretaris_lurah ?: '-' }}</p>
                        </div>
                    </div>

                    <div class="p-4 bg-base-200 rounded-lg">
                        <h4 class="text-base-content/70 
                        text-sm font-medium">Kasi Pemerintahan</h4>
                        <p class="text-base font-bold">{{ $organization->kasi_pemerintahan ?: '-' }}</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-lg">
                        <h4 class="text-base-content/70 
                        text-sm font-medium">Kasi Ekonomi & Pembangunan</h4>
                        <p class="text-base font-bold">{{ $organization->kasi_ekonomi ?: '-' }}</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-lg">
                        <h4 class="text-base-content/70 
                        text-sm font-medium">Kasi Ketentraman & Ketertiban</h4>
                        <p class="text-base font-bold">{{ $organization->kasi_ketentraman ?: '-' }}</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-lg">
                        <h4 class="text-base-content/70 
                        text-sm font-medium">Analis Pembangunan</h4>
                        <p class="text-base font-bold">{{ $organization->analis_pembangunan ?: '-' }}</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-lg">
                        <h4 class="text-base-content/70 
                        text-sm font-medium">Pranata Barang dan Jasa</h4>
                        <p class="text-base font-bold">{{ $organization->pranata_barang ?: '-' }}</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-lg">
                        <h4 class="text-base-content/70 
                        text-sm font-medium">Pengelola Keamanan</h4>
                        <p class="text-base font-bold">{{ $organization->pengelola_keamanan ?: '-' }}</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-lg">
                        <h4 class="text-base-content/70 
                        text-sm font-medium">Pengadministrasian Umum</h4>
                        <p class="text-base font-bold">{{ $organization->pengadministrasian_umum ?: '-' }}</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-lg">
                        <h4 class="text-base-content/70 
                        text-sm font-medium">Pengadministrasian Pemerintahan</h4>
                        <p class="text-base font-bold">{{ $organization->pengadministrasian_pemerintahan ?: '-' }}</p>
                    </div>
                    <div class="p-4 bg-base-200 rounded-lg">
                        <h4 class="text-base-content/70 
                        text-sm font-medium">Pengelola Surat</h4>
                        <p class="text-base font-bold">{{ $organization->pengelola_surat ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card bg-base-100 
            card-border 
            shadow-lg">
                {{-- <div class="card-body overflow-hidden"> --}}
                <div class="card-body">
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Daftar Ketua RW</h2>
                    
                    @if(empty($organization->daftar_rw))
                        <p class="text-base-content/70 
                        italic">Belum ada data Ketua RW.</p>
                    @else
                        <div class="overflow-x-auto 
                        rounded-box border-base-200 
                        border">
                            <table class="table table-zebra 
                            w-full">
                                {{-- <thead> --}}
                                <thead class="bg-base-200/50 text-base-content 
                                text-sm">
                                    <tr>
                                        <th>Nama</th>
                                        <th class="w-24">RW</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($organization->daftar_rw as $rw)
                                        <tr>
                                            <td class="font-medium">{{ $rw['nama'] }}</td>
                                            <td>
                                                <div class="badge badge-outline badge-secondary">{{ $rw['rw'] }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card bg-base-100 
            card-border 
            shadow-lg">
                <div class="card-body">
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Daftar Ketua RT</h2>
                    
                    @if(empty($organization->daftar_rt))
                        <p class="text-base-content/70 
                        italic">Belum ada data Ketua RT.</p>
                    @else
                        <div class="overflow-x-auto 
                        rounded-box border-base-200 
                        border">
                            <table class="table table-zebra 
                            w-full">
                                <thead class="bg-base-200/50 text-base-content 
                                text-sm">
                                    <tr>
                                        <th>Nama</th>
                                        <th class="w-20">RT</th>
                                        <th class="w-20">RW</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($organization->daftar_rt as $rt)
                                        <tr>
                                            <td class="font-medium">{{ $rt['nama'] }}</td>
                                            <td>
                                                <div class="badge badge-outline badge-primary 
                                                text-xs">{{ $rt['rt'] }}</div>
                                            </td>
                                            <td>
                                                <div class="badge badge-outline badge-secondary 
                                                text-xs">{{ $rt['rw'] }}</div>
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
    </div>
</x-layout>