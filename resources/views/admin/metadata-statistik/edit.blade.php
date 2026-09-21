<x-layout-admin title="Edit Metadata Statistik">
    <x-hero
        title="Edit Metadata Statistik"
    />

    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
        <div class="card bg-base-100 card-border shadow-lg">
            <div class="card-body">

                <form action="{{ route('admin.metadata-statistik.update', $metadataStatistik->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="cover_base64" id="cover_base64">

                    <fieldset class="fieldset w-full mb-6">
                        <legend class="fieldset-legend text-base">Judul Metadata</legend>
                        <input type="text"
                            name="title"
                            value="{{ old('title', $metadataStatistik->title) }}"
                            required
                            placeholder="Masukkan judul metadata statistik ..."
                            class="input w-full"/>
                        <x-forms.error name="title" />
                    </fieldset>

                    <fieldset class="fieldset w-full mb-8">
                        <legend class="fieldset-legend text-base">Ganti File Metadata (Opsional)</legend>
                        <input
                            type="file"
                            id="file"
                            name="file"
                            class="file-input w-full file-input-secondary"
                            accept="application/pdf,image/png,image/jpeg,image/jpg"
                        />
                        <p class="label">Format didukung: PDF, JPG, PNG (Maks. 5MB). <span class="text-error">Abaikan jika tidak ingin mengganti file.</span></p>

                        <x-forms.error name="file" />

                        <div id="preview-container" class="mt-6">
                            <div class="tabs tabs-border">
                                <input
                                    type="radio"
                                    name="pdf_tabs"
                                    class="tab"
                                    aria-label="Pratinjau Cover"
                                    checked="checked"
                                />
                                <div class="tab-content border-base-300 bg-base-50 p-6">
                                    <p
                                        id="cover-label"
                                        class="text-sm text-base-content/70 mb-4"
                                    >
                                        @if($metadataStatistik->cover_path)
                                            Cover dari PDF saat ini:
                                        @elseif(\Illuminate\Support\Str::endsWith(strtolower($metadataStatistik->file_path), ['.jpg', '.jpeg', '.png']))
                                            File saat ini berupa gambar, akan otomatis menjadi cover.
                                        @else
                                            Cover akan otomatis diekstrak jika file adalah PDF.
                                        @endif
                                    </p>

                                    <canvas id="pdf-canvas" class="w-48 rounded border shadow-md bg-white hidden"></canvas>
                                    <p id="image-cover-text" class="hidden text-sm font-medium text-secondary">Karena file berupa gambar, gambar asli akan langsung digunakan sebagai cover.</p>

                                    @php
                                        $isImage = \Illuminate\Support\Str::endsWith(strtolower($metadataStatistik->file_path), ['.jpg', '.jpeg', '.png']);
                                        $coverSrc = $metadataStatistik->cover_path ? asset('storage/' . $metadataStatistik->cover_path) : ($isImage ? asset('storage/' . $metadataStatistik->file_path) : null);
                                    @endphp

                                    @if($coverSrc)
                                        <img id="old-cover" src="{{ $coverSrc }}" class="w-48 rounded border shadow-md object-cover" loading="lazy" decoding="async">
                                    @endif
                                </div>

                                <input
                                    type="radio"
                                    name="pdf_tabs"
                                    class="tab"
                                    aria-label="Pratinjau File"
                                />
                                <div class="tab-content border-base-300 bg-base-50 p-4">
                                    <iframe
                                        id="pdf-iframe"
                                        class="w-full h-125 border border-base-300 rounded shadow-sm bg-white {{ $isImage ? 'hidden' : '' }}"
                                        src="{{ !$isImage ? asset('storage/' . $metadataStatistik->file_path) : '' }}"></iframe>

                                    <img id="image-preview" class="max-w-full rounded border {{ !$isImage ? 'hidden' : '' }}"
                                        src="{{ $isImage ? asset('storage/' . $metadataStatistik->file_path) : '' }}"
                                        loading="lazy" decoding="async" />
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-base-200">
                        <a href="{{ route('admin.metadata-statistik.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-secondary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
            let oldCover = document.getElementById('old-cover');

            if(oldCover) oldCover.classList.add('hidden');

            if (isPdf) {
                pdfIframe.classList.remove('hidden');
                imagePreview.classList.add('hidden');
                pdfIframe.src = fileUrl;

                coverLabel.innerText = "Pratinjau Cover Baru (Halaman 1):";
                canvas.classList.remove('hidden');
                imageCoverText.classList.add('hidden');
                base64Input.value = "";

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
                pdfIframe.classList.add('hidden');
                imagePreview.classList.remove('hidden');
                imagePreview.src = fileUrl;

                coverLabel.innerText = "Cover Baru:";
                canvas.classList.add('hidden');
                imageCoverText.classList.remove('hidden');
                base64Input.value = "";
            }
        });
    </script>
    @endpush
</x-layout-admin>