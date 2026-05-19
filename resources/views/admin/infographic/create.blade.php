<x-layout-admin title="Tambah Infografis Baru">
    <x-hero
        title="Tambah Infografis Baru"
    />

    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Tambah Infografis</h2>
                
                <form action="{{ route('admin.infographic.store') }}" method="POST" 
                enctype="multipart/form-data">
                    @csrf
                    
                    {{-- cover --}}
                    <input 
                        type="hidden" 
                        name="cover_base64" 
                        id="cover_base64"
                    >

                    <fieldset class="fieldset w-full 
                    mb-6">
                        <legend class="fieldset-legend 
                        text-base">Judul Infografis</legend>
                        <input type="text" 
                            id="title" 
                            name="title" 
                            value="{{ old('title') }}" 
                            required 
                            placeholder="Masukkan judul Infografis ..." 
                            class="input w-full"/>
                        <x-forms.error name="title" />
                    </fieldset>

                    <fieldset class="fieldset w-full 
                    mb-6">
                        <legend class="fieldset-legend 
                        text-base">Deskripsi</legend>
                        <textarea 
                            {{-- id="description"  --}}
                            name="description" 
                            required 
                            placeholder="Masukkan deskripsi infografis ..." 
                            class="textarea h-32 w-full text-base leading-relaxed"
                        >{{ old('description') }}</textarea>
                        <x-forms.error name="description" />
                    </fieldset>

                    <fieldset class="fieldset w-full 
                    mb-8">
                        <legend class="fieldset-legend 
                        text-base">File Infografis (PDF / Foto)</legend>
                        <input 
                            type="file" 
                            id="file" 
                            name="file" 
                            class="file-input w-full file-input-secondary" 
                            accept=
                            "
                                application/pdf,
                                image/png,
                                image/jpeg,
                                image/jpg
                            " 
                            required 
                        />
                        <p class="label">Format didukung: PDF, JPG, PNG (Maks. 5MB).</p>
                        
                        <x-forms.error name="file" />
                        
                        {{-- Container Pratinjau Tabs --}}
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
                                    <p 
                                        id="cover-label" 
                                        class="text-sm text-base-content/70 mb-4">Pratinjau Cover:</p>
                                    <canvas id="pdf-canvas" class="w-48 rounded border shadow-md bg-white hidden"></canvas>
                                    <p id="image-cover-text" class="hidden text-sm font-medium text-secondary">Karena file berupa gambar, gambar asli akan langsung digunakan sebagai cover.</p>
                                </div>

                                <input 
                                    type="radio" 
                                    name="pdf_tabs" 
                                    class="tab font-medium" 
                                    aria-label="Lihat File Asli" />
                                <div class="tab-content border-base-300 
                                    bg-base-50 p-4 
                                    {{-- rounded-box --}}
                                ">
                                    <iframe id="pdf-iframe" class="w-full h-125 border 
                                        border-base-300 shadow-sm
                                        rounded bg-white hidden"></iframe>
                                    <img id="image-preview" class="max-w-full rounded border hidden" />
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-base-200">
                        <a href="{{ route('admin.infographic.index') }}" class="btn btn-ghost">Batal</a>
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
            if(!file) return;

            let fileUrl = URL.createObjectURL(file);
            document.getElementById('preview-container').classList.remove('hidden');

            let isPdf = file.type === "application/pdf";
            let isImage = file.type.startsWith("image/");

            let pdfIframe = document.getElementById('pdf-iframe');
            let imagePreview = document.getElementById('image-preview');
            let canvas = document.getElementById('pdf-canvas');
            let coverLabel = document.getElementById('cover-label');
            let imageCoverText = document.getElementById('image-cover-text');
            let base64Input = document.getElementById('cover_base64');

            if (isPdf) {
                // Konfigurasi UI untuk PDF
                pdfIframe.classList.remove('hidden');
                imagePreview.classList.add('hidden');
                pdfIframe.src = fileUrl;

                coverLabel.innerText = "Cover Diekstrak dari Halaman 1:";
                canvas.classList.remove('hidden');
                imageCoverText.classList.add('hidden');
                base64Input.value = ""; 

                // Ekstrak PDF
                let fileReader = new FileReader();  
                fileReader.onload = function() {
                    let typedarray = new Uint8Array(this.result);
                    pdfjsLib.getDocument(typedarray).promise.then(pdf => pdf.getPage(1)).then(page => {
                        let context = canvas.getContext('2d');
                        let viewport = page.getViewport({scale: 2.0});
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;
                        page.render({canvasContext: context, viewport: viewport}).promise.then(() => {
                            base64Input.value = canvas.toDataURL('image/jpeg', 0.8);
                        });
                    });
                };
                fileReader.readAsArrayBuffer(file);

            } else if (isImage) {
                // Konfigurasi UI untuk Gambar
                pdfIframe.classList.add('hidden');
                imagePreview.classList.remove('hidden');
                imagePreview.src = fileUrl;

                coverLabel.innerText = "File berupa Gambar (Tidak perlu ekstrak)";
                canvas.classList.add('hidden');
                imageCoverText.classList.remove('hidden');
                
                // Kosongkan base64 karena backend akan langsung menggunakan file gambar asli
                base64Input.value = ""; 
            }
        });
    </script>
    @endpush
</x-layout-admin>