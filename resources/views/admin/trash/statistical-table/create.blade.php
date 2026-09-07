<x-layout-admin title="Tambah Tabel Statistik">
    <x-hero
        title="Tambah Tabel Statistik"
    />

    <div class="max-w-4xl mx-auto px-4 lg:px-0 mb-12">
        <div class="card bg-base-100 
        card-border 
        shadow-lg">
            <div class="card-body">
                <h2 class="card-title text-secondary 
                text-xl mb-4 border-b pb-2">Unggah Dokumen Excel</h2>
            
                <form action="{{ route('admin.statistical-table.preview') }}" method="POST" 
                enctype="multipart/form-data">
                    @csrf

                    <fieldset class="fieldset w-full 
                    mb-8
                    min-w-0">
                        <legend class="fieldset-legend 
                        text-base">File Dokumen (Excel / CSV)</legend>
                        <input type="file" 
                               id="excel_file" 
                               name="excel_file" 
                               class="file-input w-full file-input-secondary"
                               accept=".xlsx, .xls, .csv" 
                               required />
                        
                        <p class="label
                        text-wrap break-words">
                            Format didukung: XLSX, XLS, CSV (Maks. 5MB). Pastikan baris pertama (header) adalah nama kolom.
                        </p>
                        
                        <x-forms.error name="excel_file" />
                    </fieldset>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-base-200">
                        <a href="{{ route('admin.statistical-table.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-secondary text-white">
                            <x-lucide-file-search class="w-5 h-5 mr-1" />
                            Lihat Pratinjau
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout-admin>