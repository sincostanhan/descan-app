<x-layout title="Tentang Kami">
    {{--o Centered hero --}}
    {{-- <div class="hero bg-base-200 min-h-screen"> --}}
    {{-- <div class="hero bg-base-200 mb-8"> --}}
    <div class="hero bg-base-100">
        {{-- <div class="hero-content text-center"> --}}
        <div class="hero-content text-center py-8">
            {{-- <div class="max-w-md"> --}}
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold">Tentang Kami</h1>
                <p class="py-6">
                    {{ $about->deskripsi }}
                </p>
            </div>
        </div>
    </div>

    {{-- <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8"> --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="card bg-base-100 text-primary-content card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title 
                text-2xl mb-4 border-b pb-2">Batas Wilayah</h2>
                
                <div class="mb-4">
                    <h3 class="text-secondary
                    font-bold text-lg mb-2">Kelurahan Baadia memiliki batas wilayah:</h3>

                    {{--o Table --}}
                    <div class="overflow-x-auto">
                        <table class="table">
                            <!-- head -->
                            {{-- <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Job</th>
                            </tr>
                            </thead> --}}
                            <tbody>
                            <!-- row 1 -->
                            <tr>
                                <th class="font-medium w-px">1</th>
                                <td class="text-base-content/80 w-px whitespace-nowrap">Sebelah <span class="font-bold text-secondary">Utara</span></td>
                                <td>{{ $about->batas_utara }}</td>
                            </tr>
                            <!-- row 2 -->
                            <tr>
                                <th class="font-medium w-px">2</th>
                                <td class="text-base-content/80 w-px whitespace-nowrap">Sebelah <span class="font-bold text-secondary">Barat</span></td>
                                <td>{{ $about->batas_barat }}</td>
                            </tr>
                            <!-- row 3 -->
                            <tr>
                                <th class="font-medium w-px">3</th>
                                <td class="text-base-content/80 w-px whitespace-nowrap">Sebelah <span class="font-bold text-secondary">Selatan</span></td>
                                <td>{{ $about->batas_selatan }}</td>
                            </tr>
                            <!-- row 4 -->
                            <tr>
                                <th class="font-medium w-px">4</th>
                                <td class="text-base-content/80 w-px whitespace-nowrap">Sebelah <span class="font-bold text-secondary">Timur</span></td>
                                <td>{{ $about->batas_timur }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        {{--o Card with custom color --}}
        {{--o Card with a card-border --}}
        {{-- <div class="card bg-primary text-primary-content w-96"> --}}
        {{-- <div class="card bg-base-100 text-primary-content w-96 --}}
        {{-- <div class="card bg-base-100 text-primary-content --}}
        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                {{-- <h2 class="card-title">Card title!</h2> --}}
                <h2 class="card-title 
                text-2xl mb-4 border-b pb-2">Visi & Misi Kota Baubau</h2>
                
                <div class="mb-4">
                    <h3 class="text-secondary
                    font-bold text-lg mb-2">Visi</h3>
                    <p class="bg-base-200 border-secondary text-base-content/90 
                    p-4 rounded-lg border-l-4 italic ">
                        "{{ $about->visi }}"
                    </p>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-secondary
                    font-bold text-lg mb-2">Misi</h3>
                    <ul class="list bg-base-100 card-border
                    rounded-box shadow-sm">
                        @foreach($daftarMisi as $misi)
                            <li class="list-row 
                            items-start py-4">
                                <div class="text-secondary 
                                font-bold pt-0.5">
                                    {{ $loop->iteration }}.
                                </div>
                                
                                <!-- Konten Utama Misi -->
                                <div>
                                    <div class="text-base-content/80 
                                    text-sm md:text-base leading-relaxed">
                                        {{ trim($misi) }}
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layout>