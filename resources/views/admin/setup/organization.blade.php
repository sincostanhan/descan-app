<x-layout-admin 
    title="Setup Langkah 3: Struktur Organisasi"
    :showNav="false"
>
    <div class="hero bg-base-100">
        <div class="hero-content text-center py-8">
            <div class="max-w-4xl">
                <h1 class="text-5xl font-bold mb-4">Inisiasi Data Kelurahan</h1>
                <p class="text-lg opacity-70">Masukan struktur organisasi kelurahan.</p>
            </div>
        </div>
    </div>
    
    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        
        <ul class="steps w-full mb-8">
            <li class="step step-primary">Kelurahan</li>
            <li class="step step-primary font-medium">Organisasi</li>
            <li class="step">Tentang Kami</li>
        </ul>

        <x-flash-message />

        <form action="{{ route('admin.setup.storeOrganization') }}" method="POST">
            @csrf

            <div class="card bg-base-100 
            card-border 
            shadow-lg">
                <div class="card-body">  
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Jabatan Inti & Staf</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <div class="col-span-full flex justify-center mb-2">
                            <fieldset class="fieldset w-full 
                            md:w-1/2 lg:w-1/3">
                                <legend class="fieldset-legend">Lurah</legend>
                                <input 
                                    type="text" 
                                    name="lurah" 
                                    value="{{ old('lurah') }}" 
                                    {{-- value="{{ old('lurah', $organization?->lurah) }}" --}}
                                    class="input w-full">
                                <x-forms.error name="lurah" />
                            </fieldset>
                        </div>

                        <div class="col-span-full flex justify-center mb-2">
                            <fieldset class="fieldset w-full 
                            md:w-1/2 lg:w-1/3">
                                <legend class="fieldset-legend">Sekretaris Lurah</legend>
                                <input 
                                    type="text" 
                                    name="sekretaris_lurah" 
                                    value="{{ old('sekretaris_lurah') }}" 
                                    {{-- value="{{ old('sekretaris_lurah', $organization->sekretaris_lurah) }}"  --}}
                                    class="input w-full">
                                <x-forms.error name="sekretaris_lurah" />
                            </fieldset>
                        </div>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Kasi Pemerintahan</legend>
                            <input 
                                type="text" 
                                name="kasi_pemerintahan" 
                                value="{{ old('kasi_pemerintahan') }}" 
                                {{-- value="{{ old('kasi_pemerintahan', $organization->kasi_pemerintahan) }}"  --}}
                                class="input w-full">
                            <x-forms.error name="kasi_pemerintahan" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Kasi Ekonomi, Pembangunan, & Kesra</legend>
                            <input 
                                type="text" 
                                name="kasi_ekonomi" 
                                value="{{ old('kasi_ekonomi') }}" 
                                {{-- value="{{ old('kasi_ekonomi', $organization->kasi_ekonomi) }}"  --}}
                                class="input w-full">
                            <x-forms.error name="kasi_ekonomi" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Kasi Ketentraman & Ketertiban</legend>
                            <input 
                                type="text" 
                                name="kasi_ketentraman" 
                                value="{{ old('kasi_ketentraman') }}" 
                                {{-- value="{{ old('kasi_ketentraman', $organization->kasi_ketentraman) }}"  --}}
                                class="input w-full">
                            <x-forms.error name="kasi_ketentraman" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Analis Pembangunan</legend>
                            <input 
                                type="text" 
                                name="analis_pembangunan" 
                                value="{{ old('analis_pembangunan') }}" 
                                {{-- value="{{ old('analis_pembangunan', $organization->analis_pembangunan) }}"  --}}
                                class="input w-full">
                            <x-forms.error name="analis_pembangunan" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Pranata Barang dan Jasa</legend>
                            <input 
                                type="text" 
                                name="pranata_barang" 
                                value="{{ old('pranata_barang') }}" 
                                {{-- value="{{ old('pranata_barang', $organization->pranata_barang) }}"  --}}
                                class="input w-full">
                            <x-forms.error name="pranata_barang" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Pengelola Keamanan dan Ketertiban</legend>
                            <input 
                                type="text" 
                                name="pengelola_keamanan" 
                                value="{{ old('pengelola_keamanan') }}" 
                                {{-- value="{{ old('pengelola_keamanan', $organization->pengelola_keamanan) }}"  --}}
                                class="input w-full">
                            <x-forms.error name="pengelola_keamanan" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Pengadministrasian Umum</legend>
                            <input 
                                type="text" 
                                name="pengadministrasian_umum" 
                                value="{{ old('pengadministrasian_umum') }}" 
                                {{-- value="{{ old('pengadministrasian_umum', $organization->pengadministrasian_umum) }}"  --}}
                                class="input w-full">
                            <x-forms.error name="pengadministrasian_umum" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Pengadministrasian Pemerintahan</legend>
                            <input 
                                type="text" 
                                name="pengadministrasian_pemerintahan" 
                                value="{{ old('pengadministrasian_pemerintahan') }}" 
                                {{-- value="{{ old('pengadministrasian_pemerintahan', $organization->pengadministrasian_pemerintahan) }}"  --}}
                                class="input w-full">
                            <x-forms.error name="pengadministrasian_pemerintahan" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Pengelola Surat</legend>
                            <input 
                                type="text" 
                                name="pengelola_surat" 
                                value="{{ old('pengelola_surat') }}" 
                                {{-- value="{{ old('pengelola_surat', $organization->pengelola_surat) }}"  --}}
                                class="input w-full">
                            <x-forms.error name="pengelola_surat" />
                        </fieldset>
                    </div>

                    {{-- ================= DAFTAR RW ================= --}}
                    <div class="flex 
                        flex-col sm:flex-row 
                        justify-center sm:justify-between 
                        items-center sm:items-end 
                        mb-2 border-b pb-2">
                        <h2 class="card-title text-secondary 
                        text-xl">Daftar Ketua RW</h2>
                        <div class="flex items-end gap-2 mt-2 sm:mt-0">
                            <fieldset class="fieldset w-32">
                                <legend class="fieldset-legend">Total RW</legend>
                                <input 
                                    type="number" 
                                    id="input_jumlah_rw" 
                                    min="1" 
                                    placeholder="Misal: 5" 
                                    class="input w-full bg-base-200">
                            </fieldset>
                            <button type="button" onclick="generateRW()" class="btn btn-primary">Generate Baris RW</button>
                        </div>
                    </div>

                    <div id="rw-container" class="mb-8 space-y-3">
                        @if(old('daftar_rw'))
                            @foreach(old('daftar_rw') as $index => $rw)
                                <div class="bg-base-200/60 rounded-box 
                                flex flex-col md:flex-row gap-4 p-4 items-start rw-row">
                                    <fieldset class="fieldset w-full md:w-1/4">
                                        <legend class="fieldset-legend">Nomor RW</legend>
                                        <input 
                                            type="text" 
                                            name="daftar_rw[{{ $index }}][rw]" value="{{ $rw['rw'] ?? '' }}" 
                                            class="input w-full rw-number" 
                                            readonly>
                                        <x-forms.error name="daftar_rw.{{ $index }}.rw" />
                                    </fieldset>
                                    
                                    <fieldset class="fieldset w-full md:w-2/4">
                                        <legend class="fieldset-legend">Nama Ketua RW</legend>
                                        <input 
                                            type="text" 
                                            name="daftar_rw[{{ $index }}][nama]" 
                                            value="{{ $rw['nama'] ?? '' }}" 
                                            placeholder="Masukkan nama ..." 
                                            class="input w-full">
                                        <x-forms.error name="daftar_rw.{{ $index }}.nama" />
                                    </fieldset>

                                    <fieldset class="fieldset w-full md:w-1/4">
                                        <legend class="fieldset-legend">Jumlah RT</legend>
                                        <input 
                                            type="number" 
                                            min="1" 
                                            class="input w-full input-rt-count" 
                                            placeholder="Misal: 4">
                                    </fieldset>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center p-4 border-2 border-dashed rounded-box opacity-60">
                                Silakan masukkan jumlah total RW dan klik "Generate Baris RW"
                            </div>
                        @endif
                    </div>

                    {{-- ================= DAFTAR RT ================= --}}
                    <div class="flex 
                        flex-col sm:flex-row 
                        justify-center sm:justify-between 
                        items-center sm:items-end 
                        mb-2 border-b pb-2">
                        <h2 class="card-title text-secondary 
                        text-xl">Daftar Ketua RT</h2>
                        <div class="flex items-end gap-2 mt-2 sm:mt-0">
                            <button 
                                type="button" 
                                onclick="generateRT()" 
                                class="btn btn-primary">Generate Baris RT</button>
                        </div>
                    </div>

                    <div id="rt-container" class="mb-6 space-y-3">
                        @if(old('daftar_rt'))
                            @foreach(old('daftar_rt') as $index => $rt)
                                <div class="bg-base-200/40 rounded-box 
                                flex flex-col md:flex-row gap-4 p-4 items-start">
                                    <fieldset class="fieldset w-full md:w-1/4">
                                        <legend class="fieldset-legend">Nomor RT</legend>
                                        <input 
                                            type="text" 
                                            name="daftar_rt[{{ $index }}][rt]" 
                                            value="{{ $rt['rt'] ?? '' }}" 
                                            class="input w-full">
                                        <x-forms.error name="daftar_rt.{{ $index }}.rt" />
                                    </fieldset>
                                    
                                    <fieldset class="fieldset w-full md:w-1/4">
                                        <legend class="fieldset-legend">Nomor RW</legend>
                                        <input 
                                            type="text" 
                                            name="daftar_rt[{{ $index }}][rw]" 
                                            value="{{ $rt['rw'] ?? '' }}" 
                                            class="input w-full" 
                                            readonly>
                                        <x-forms.error name="daftar_rt.{{ $index }}.rw" />
                                    </fieldset>
                                    
                                    <fieldset class="fieldset w-full md:w-2/4">
                                        <legend class="fieldset-legend">Nama Ketua RT</legend>
                                        <input 
                                            type="text" 
                                            name="daftar_rt[{{ $index }}][nama]" 
                                            value="{{ $rt['nama'] ?? '' }}" 
                                            placeholder="Masukkan nama ..." 
                                            class="input w-full">
                                        <x-forms.error name="daftar_rt.{{ $index }}.nama" />
                                    </fieldset>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center p-4 border-2 border-dashed rounded-box opacity-60">
                                Isi kolom "Jumlah RT" di masing-masing RW, lalu klik "Generate Baris RT"
                            </div>
                        @endif
                    </div>

                    <div class="card-actions 
                    justify-end mt-8 border-t pt-4">
                        <a href="{{ route('admin.setup.setting') }}" class="btn btn-ghost">
                            <x-lucide-move-left class="w-5 h-5 mr-1" /> Kembali
                        </a>
                        <button type="submit" class="btn btn-secondary">Tentang Kami <x-lucide-move-right class="w-5 h-5 ml-1" /></button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function generateRW() {
                const count = document.getElementById('input_jumlah_rw').value;
                const container = document.getElementById('rw-container');
                
                if (!count || count <= 0) return;
                
                // Konfirmasi jika sudah ada isinya agar tidak terhapus tidak sengaja
                if (container.innerHTML.includes('rw-row') && !confirm('Ini akan mereset baris RW yang ada. Lanjut?')) {
                    return;
                }
                
                container.innerHTML = ''; // Bersihkan container
                
                for(let i = 0; i < count; i++) {
                    const rwNum = String(i + 1).padStart(2, '0'); // Format 01, 02, dst
                    container.innerHTML += `
                    <div class="bg-base-200/60 rounded-box flex flex-col md:flex-row gap-4 p-4 items-start rw-row">
                        <fieldset class="fieldset w-full md:w-1/4">
                            <legend class="fieldset-legend">Nomor RW</legend>
                            <input type="text" name="daftar_rw[${i}][rw]" value="${rwNum}" class="input w-full rw-number" readonly>
                        </fieldset>
                        
                        <fieldset class="fieldset w-full md:w-2/4">
                            <legend class="fieldset-legend">Nama Ketua RW</legend>
                            <input type="text" name="daftar_rw[${i}][nama]" placeholder="Masukkan nama ..." class="input w-full">
                        </fieldset>

                        <fieldset class="fieldset w-full md:w-1/4">
                            <legend class="fieldset-legend">Jumlah RT</legend>
                            <input type="number" min="0" class="input w-full input-rt-count" placeholder="Misal: 4">
                        </fieldset>
                    </div>`;
                }
            }

            function generateRT() {
                const rwRows = document.querySelectorAll('.rw-row');
                const rtContainer = document.getElementById('rt-container');
                
                if (rwRows.length === 0) {
                    alert('Silakan generate baris RW terlebih dahulu!');
                    return;
                }

                if (rtContainer.innerHTML.includes('name="daftar_rt') && !confirm('Ini akan mereset baris RT yang ada. Lanjut?')) {
                    return;
                }

                rtContainer.innerHTML = ''; // Bersihkan container
                let globalRtIndex = 0; // Index array berurutan untuk Laravel

                rwRows.forEach((row) => {
                    const rwInput = row.querySelector('.rw-number');
                    const rtCountInput = row.querySelector('.input-rt-count');
                    
                    const rwNum = rwInput ? rwInput.value : '';
                    const rtCount = parseInt(rtCountInput.value) || 0;

                    for(let i = 0; i < rtCount; i++) {
                        const rtNum = String(i + 1).padStart(3, '0'); // Format 001, 002, dst
                        
                        rtContainer.innerHTML += `
                        <div class="bg-base-200/40 rounded-box flex flex-col md:flex-row gap-4 p-4 items-start">
                            <fieldset class="fieldset w-full md:w-1/4">
                                <legend class="fieldset-legend">Nomor RT</legend>
                                <input type="text" name="daftar_rt[${globalRtIndex}][rt]" value="${rtNum}" class="input w-full">
                            </fieldset>
                            
                            <fieldset class="fieldset w-full md:w-1/4">
                                <legend class="fieldset-legend">Nomor RW</legend>
                                <input type="text" name="daftar_rt[${globalRtIndex}][rw]" value="${rwNum}" class="input w-full" readonly>
                            </fieldset>
                            
                            <fieldset class="fieldset w-full md:w-2/4">
                                <legend class="fieldset-legend">Nama Ketua RT</legend>
                                <input type="text" name="daftar_rt[${globalRtIndex}][nama]" placeholder="Masukkan nama ..." class="input w-full">
                            </fieldset>
                        </div>`;
                        
                        globalRtIndex++;
                    }
                });

                if(globalRtIndex === 0) {
                    rtContainer.innerHTML = '<div class="text-center p-4 border-2 border-dashed rounded-box opacity-60 text-error">Anda belum mengisi Jumlah RT di tabel RW.</div>';
                }
            }
        </script>
    @endpush
</x-layout-admin>