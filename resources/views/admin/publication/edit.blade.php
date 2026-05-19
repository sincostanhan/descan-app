<x-layout-admin title="Edit Publikasi">
    <x-hero
        title="Edit Publikasi"
    />

    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                {{-- <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Edit Publikasi</h2> --}}
            
                <form action="{{ route('admin.publication.update', $publication->id) }}" method="POST" 
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    {{-- cover --}}
                    <input type="hidden" name="cover_base64" id="cover_base64">

                    <fieldset class="fieldset w-full 
                    mb-6">
                        <legend class="fieldset-legend 
                        text-base">Judul Publikasi</legend>
                        <input type="text" 
                            {{-- id="title"  --}}
                            name="title" 
                            value="{{ old('title', $publication->title) }}"
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
                            {{-- id="description"  --}}
                            name="description" 
                            required 
                            placeholder="Masukkan deskripsi publikasi ..." 
                            class="textarea h-32 w-full text-base leading-relaxed"
                        >{{ old('description', $publication->description) }}
                        </textarea>
                        <x-forms.error name="description" />
                    </fieldset>

                    <fieldset class="fieldset w-full 
                    mb-8">
                        <legend class="fieldset-legend 
                        text-base">Ganti File PDF (Opsional)</legend>
                        <input 
                            type="file" 
                            id="file" 
                            name="file" 
                            class="file-input w-full file-input-secondary" 
                            accept="application/pdf" 
                            {{-- required  --}}
                        />
                        <p class="label">Format didukung: PDF (Maks. 5MB). <span class="text-error">Abaikan jika tidak ingin mengganti file.</span></p>
                        
                        <x-forms.error name="file" />
                        
                        {{-- Container Pratinjau --}}
                        {{-- <div id="preview-container" class="hidden mt-6"> --}}
                        <div id="preview-container" class="mt-6">
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
                                        class="text-sm text-base-content/70 mb-4"
                                    >
                                        {{ $publication->cover_path ? 'Cover saat ini:' : 'Cover akan otomatis diekstrak saat file dipilih.' }}
                                    </p>

                                    {{-- Canvas untuk ekstrak PDF baru --}}
                                    <canvas id="pdf-canvas" class="w-48 rounded border shadow-md bg-white {{ $publication->cover_path ? 'hidden' : '' }}"></canvas>
                                    
                                    {{-- Gambar cover lama (ditampilkan jika ada) --}}
                                    @if($publication->cover_path)
                                        <img id="old-cover" src="{{ asset('storage/' . $publication->cover_path) }}" class="w-48 rounded border shadow-md">
                                    @endif
                                </div>

                                <input 
                                    type="radio" 
                                    name="pdf_tabs" 
                                    {{-- class="tab font-medium"  --}}
                                    class="tab" 
                                    aria-label="Pratinjau Full PDF" 
                                />
                                <div class="tab-content border-base-300 
                                bg-base-50 p-4 
                                {{-- rounded-b-box rounded-tr-box --}}
                                ">
                                    <iframe 
                                        id="pdf-iframe" 
                                        class="w-full h-125 border border-base-300 rounded shadow-sm bg-white" 
                                        src="{{ asset('storage/' . $publication->file_path) }}"
                                    ></iframe>
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

            // Update Iframe Preview
            let fileUrl = URL.createObjectURL(file);
            document.getElementById('pdf-iframe').src = fileUrl;

            // Update Cover Label & Toggle Visibility
            document.getElementById('cover-label').innerText = "Pratinjau Cover Baru (Halaman 1):";
            let oldCover = document.getElementById('old-cover');
            if(oldCover) oldCover.classList.add('hidden');
            document.getElementById('pdf-canvas').classList.remove('hidden');

            // Ekstrak Cover Baru
            let fileReader = new FileReader();  
            fileReader.onload = function() {
                let typedarray = new Uint8Array(this.result);
                pdfjsLib.getDocument(typedarray).promise.then(pdf => {
                    return pdf.getPage(1);
                }).then(page => {
                    let canvas = document.getElementById('pdf-canvas');
                    let context = canvas.getContext('2d');
                    let viewport = page.getViewport({scale: 2.0});
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;

                    page.render({canvasContext: context, viewport: viewport}).promise.then(() => {
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