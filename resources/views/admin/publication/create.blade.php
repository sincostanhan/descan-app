<x-layout-admin title="Tambah Publikasi Baru">
    <x-hero
        title="Tambah Publikasi Baru"
    />

    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Tambah Publikasi</h2>
            
                <form action="{{ route('admin.publication.store') }}" method="POST" 
                enctype="multipart/form-data">
                    @csrf
                    
                    {{-- cover --}}
                    <input type="hidden" name="cover_base64" id="cover_base64">

                    <fieldset class="fieldset w-full 
                    mb-6">
                        <legend class="fieldset-legend 
                        text-base">Judul Publikasi</legend>
                        <input type="text" 
                            id="title" 
                            name="title" 
                            value="{{ old('title') }}" 
                            required 
                            placeholder="Masukkan judul publikasi ..." 
                            class="input w-full"/>
                        <x-forms.error name="title" />
                    </fieldset>

                    <fieldset class="fieldset w-full 
                    mb-6">
                        <legend class="fieldset-legend 
                        text-base">Deskripsi Singkat</legend>
                        <textarea 
                            id="description" 
                            name="description" 
                            required 
                            placeholder="Masukkan deskripsi publikasi ..." 
                            class="textarea h-32 w-full text-base leading-relaxed"
                        >{{ old('description') }}</textarea>
                        <x-forms.error name="description" />
                    </fieldset>

                    <fieldset class="fieldset w-full 
                    mb-8">
                        <legend class="fieldset-legend 
                        text-base">Dokumen PDF</legend>
                        <input 
                            type="file" 
                            id="file" 
                            name="file" 
                            class="file-input w-full file-input-secondary" 
                            accept="application/pdf" 
                            required 
                        />
                        <p class="label">Format didukung: PDF (Maks. 5MB).</p>
                        
                        <x-forms.error name="file" />
                        
                        {{-- cover --}}
                        {{-- <div id="cover-preview-container" class="hidden mt-4">
                            <p class="text-sm text-base-content/70 mb-2">Pratinjau Cover Halaman Pertama:</p>
                            <canvas id="pdf-canvas" class="w-32 rounded border shadow-sm"></canvas>
                        </div> --}}
                        <div id="preview-container" class="hidden mt-6">
                            <div class="tabs tabs-border">
                                <input 
                                    type="radio" 
                                    name="pdf_tabs" 
                                    {{-- class="tab font-medium"  --}}
                                    class="tab" 
                                    aria-label="Pratinjau Cover" 
                                    checked="checked" 
                                />
                                <div class="tab-content border-base-300 
                                bg-base-50 p-6 
                                {{-- rounded-b-box rounded-tr-box --}}
                                ">
                                    <p class="text-sm text-base-content/70 mb-4">Pratinjau Cover Halaman Pertama:</p>
                                    <canvas id="pdf-canvas" class="w-48 rounded border shadow-md bg-white"></canvas>
                                </div>

                                <input 
                                    type="radio" 
                                    name="pdf_tabs" 
                                    class="tab font-medium" 
                                    aria-label="Pratinjau Full PDF" 
                                />
                                <div class="tab-content border-base-300 
                                bg-base-50 p-4 
                                {{-- rounded-b-box rounded-tr-box --}}
                                ">
                                    <iframe id="pdf-iframe" class="w-full h-125 border border-base-300 rounded shadow-sm bg-white" src=""></iframe>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-base-200">
                        <a href="{{ route('admin.publication.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-secondary text-white">
                            <x-lucide-file-up class="w-5 h-5 mr-1" /> Simpan & Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- cover --}}
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        document.getElementById('file').addEventListener('change', function(e) {
            let file = e.target.files[0];
            if(!file || file.type !== "application/pdf") return;

            // === FEATURE: Tampilkan Full PDF di Tab 2 ===
            // Membuat URL lokal sementara dari file yang dipilih tanpa mengunggahnya
            let fileUrl = URL.createObjectURL(file);
            document.getElementById('pdf-iframe').src = fileUrl;
            // ===============================================

            // === FEATURE: Ekstrak Cover Halaman 1 ke Tab 1 ===
            let fileReader = new FileReader();  
            fileReader.onload = function() {
                let typedarray = new Uint8Array(this.result);
                
                // Baca PDF
                pdfjsLib.getDocument(typedarray).promise.then(pdf => {
                    // Ambil Halaman 1
                    return pdf.getPage(1);
                }).then(page => {
                    let canvas = document.getElementById('pdf-canvas');
                    let context = canvas.getContext('2d');
                    // Buat gambar berkualitas tinggi (scale 2.0)
                    let viewport = page.getViewport({scale: 2.0});

                    canvas.width = viewport.width;
                    canvas.height = viewport.height;

                    let renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    
                    // Render halaman ke canvas
                    page.render(renderContext).promise.then(() => {
                        // Munculkan seluruh kontainer Tab
                        document.getElementById('preview-container').classList.remove('hidden');
                        
                        // Ubah canvas menjadi teks Base64 JPG dan masukkan ke input rahasia
                        let base64Image = canvas.toDataURL('image/jpeg', 0.8);
                        document.getElementById('cover_base64').value = base64Image;
                    });
                });
            };
            fileReader.readAsArrayBuffer(file);
        });
    </script>
    @endpush
</x-layout-admin>