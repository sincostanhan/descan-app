<x-layout-admin title="Edit Struktur Organisasi">
    <div class="hero bg-base-100">
        <div class="hero-content text-center py-8">
            <div class="max-w-4xl">
                <h1 class="text-5xl font-bold">Struktur Organisasi</h1>
            </div>
        </div>
    </div>
    
    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />

        <form action="{{ route('admin.organization.update', $organization->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="card bg-base-100 
            card-border 
            shadow-lg">
                <div class="card-body">  
                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Jabatan Inti & Staf</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <div class="col-span-full flex justify-center mb-2"">
                            <fieldset class="fieldset w-full
                            md:w-1/2 lg:w-1/3">
                                <legend class="fieldset-legend">Lurah</legend>
                                <input type="text" name="lurah" value="{{ old('lurah', $organization->lurah) }}" class="input w-full">
                                <x-forms.error name="lurah" />
                            </fieldset>
                        </div>

                        <div class="col-span-full flex justify-center mb-2"">
                            {{-- <fieldset class="fieldset w-full"> --}}
                            <fieldset class="fieldset w-full
                            md:w-1/2 lg:w-1/3">
                                <legend class="fieldset-legend">Sekretaris Lurah</legend>
                                <input type="text" name="sekretaris_lurah" value="{{ old('sekretaris_lurah', $organization->sekretaris_lurah) }}" class="input w-full">
                                <x-forms.error name="sekretaris_lurah" />
                            </fieldset>
                        </div>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Kasi Pemerintahan</legend>
                            <input type="text" name="kasi_pemerintahan" value="{{ old('kasi_pemerintahan', $organization->kasi_pemerintahan) }}" class="input w-full">
                            <x-forms.error name="kasi_pemerintahan" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Kasi Ekonomi, Pembangunan, dan Kesejahteraan Rakyat</legend>
                            <input type="text" name="kasi_ekonomi" value="{{ old('kasi_ekonomi', $organization->kasi_ekonomi) }}" class="input w-full">
                            <x-forms.error name="kasi_ekonomi" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Kasi Ketentraman & Ketertiban</legend>
                            <input type="text" name="kasi_ketentraman" value="{{ old('kasi_ketentraman', $organization->kasi_ketentraman) }}" class="input w-full">
                            <x-forms.error name="kasi_ketentraman" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Analis Pembangunan</legend>
                            <input type="text" name="analis_pembangunan" value="{{ old('analis_pembangunan', $organization->analis_pembangunan) }}" class="input w-full">
                            <x-forms.error name="analis_pembangunan" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Pranata Barang dan Jasa</legend>
                            <input type="text" name="pranata_barang" value="{{ old('pranata_barang', $organization->pranata_barang) }}" class="input w-full">
                            <x-forms.error name="pranata_barang" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Pengelola Keamanan dan Ketertiban</legend>
                            <input type="text" name="pengelola_keamanan" value="{{ old('pengelola_keamanan', $organization->pengelola_keamanan) }}" class="input w-full">
                            <x-forms.error name="pengelola_keamanan" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Pengadministrasian Umum</legend>
                            <input type="text" name="pengadministrasian_umum" value="{{ old('pengadministrasian_umum', $organization->pengadministrasian_umum) }}" class="input w-full">
                            <x-forms.error name="pengadministrasian_umum" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Pengadministrasian Pemerintahan</legend>
                            <input type="text" name="pengadministrasian_pemerintahan" value="{{ old('pengadministrasian_pemerintahan', $organization->pengadministrasian_pemerintahan) }}" class="input w-full">
                            <x-forms.error name="pengadministrasian_pemerintahan" />
                        </fieldset>

                        <fieldset class="fieldset w-full">
                            <legend class="fieldset-legend">Pengelola Surat</legend>
                            <input type="text" name="pengelola_surat" value="{{ old('pengelola_surat', $organization->pengelola_surat) }}" class="input w-full">
                            <x-forms.error name="pengelola_surat" />
                        </fieldset>
                    </div>

                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Daftar Ketua RW</h2>
                    <div id="rw-container" class="mb-6 space-y-3">
                        @php $rws = old('daftar_rw', $organization->daftar_rw ?? [['rw' => '', 'nama' => '']]); @endphp
                        
                        @foreach($rws as $index => $rw)
                            <div class="bg-base-200/60 rounded-box 
                            flex flex-col md:flex-row gap-4 p-4 items-start">
                                <fieldset class="fieldset w-full md:w-1/4">
                                    <legend class="fieldset-legend">Nomor RW</legend>
                                    <input type="text" name="daftar_rw[{{ $index }}][rw]" placeholder="Contoh: 1" value="{{ $rw['rw'] ?? '' }}" class="input w-full">
                                    <x-forms.error name="daftar_rw.{{ $index }}.rw" />
                                </fieldset>
                                
                                <fieldset class="fieldset w-full md:w-3/4">
                                    <legend class="fieldset-legend">Nama Ketua RW</legend>
                                    <input type="text" name="daftar_rw[{{ $index }}][nama]" placeholder="Masukkan nama ..." value="{{ $rw['nama'] ?? '' }}" class="input w-full">
                                    <x-forms.error name="daftar_rw.{{ $index }}.nama" />
                                </fieldset>
                            </div>
                        @endforeach
                    </div>

                    <h2 class="card-title text-secondary 
                    text-xl mb-4 border-b pb-2">Daftar Ketua RT</h2>
                    <div id="rt-container" class="mb-6 space-y-3">
                        @php $rts = old('daftar_rt', $organization->daftar_rt ?? [['rt' => '', 'rw' => '', 'nama' => '']]); @endphp            
                        
                        @foreach($rts as $index => $rt)
                            <div class="bg-base-200/40 rounded-box 
                            flex flex-col md:flex-row gap-4 p-4 items-start">
                                <fieldset class="fieldset w-full md:w-1/4">
                                    <legend class="fieldset-legend">Nomor RT</legend>
                                    <input type="text" name="daftar_rt[{{ $index }}][rt]" placeholder="Contoh: 1" value="{{ $rt['rt'] ?? '' }}" class="input w-full">
                                    <x-forms.error name="daftar_rt.{{ $index }}.rt" />
                                </fieldset>
                                
                                <fieldset class="fieldset w-full md:w-1/4">
                                    <legend class="fieldset-legend">Nomor RW</legend>
                                    <input type="text" name="daftar_rt[{{ $index }}][rw]" placeholder="Contoh: 1" value="{{ $rt['rw'] ?? '' }}" class="input w-full">
                                    <x-forms.error name="daftar_rt.{{ $index }}.rw" />
                                </fieldset>
                                
                                <fieldset class="fieldset w-full md:w-2/4">
                                    <legend class="fieldset-legend">Nama Ketua RT</legend>
                                    <input type="text" name="daftar_rt[{{ $index }}][nama]" placeholder="Masukkan nama ..." value="{{ $rt['nama'] ?? '' }}" class="input w-full">
                                    <x-forms.error name="daftar_rt.{{ $index }}.nama" />
                                </fieldset>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-actions 
                    justify-end mt-8 border-t pt-4">
                        <button type="reset" class="btn btn-ghost">Batal</button>
                        <button type="submit" class="btn btn-secondary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout-admin>