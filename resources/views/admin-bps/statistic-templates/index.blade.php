<x-layout-admin-bps title="Panel Admin BPS | Template Tabel Statistik">
    <x-hero
        title="Template Tabel Statistik"
        subtitle="Kelola struktur kolom & baris tabel yang akan diisi oleh Admin Kelurahan"
    />

    <div class="max-w-6xl mx-auto px-4 lg:px-0 mb-12">
        <x-flash-message />
        @error('template')
            <div class="alert alert-error shadow-sm mb-4">
                <x-lucide-triangle-alert class="w-5 h-5" />
                <span>{{ $message }}</span>
            </div>
        @enderror

        <div class="flex flex-col gap-4 mb-6 pl-0 md:pl-6">

            <div class="w-full">
                <form action="{{ url()->current() }}" method="GET" class="relative w-full sm:max-w-md">
                    {{-- Pertahankan query string yang sedang aktif (seperti per_page) --}}
                    @foreach(request()->except(['search', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan judul template..."
                        class="input input-sm md:input-md input-bordered w-full pr-10"
                    />

                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-base-content/50 hover:text-primary">
                        <x-lucide-search class="w-4 h-4 md:w-5 md:h-5" />
                    </button>
                </form>
            </div>

            <div class="flex flex-col-reverse items-end gap-3 md:flex-row md:justify-between md:items-center">
                <x-pagination-dropdown :perPage="$perPage" />

                <a class="btn btn-sm md:btn-md btn-secondary shrink-0"
                href="{{ route('admin-bps.statistic-templates.create') }}">
                    <x-lucide-plus class="w-5 h-5" /> Tambah Template Baru
                </a>
            </div>
        </div>

        <x-section-card title="Daftar Template" title-size="text-xl">

                @if($templates->isEmpty())
                    @if(request('search'))
                        <x-empty-alert message="Template dengan judul '{{ request('search') }}' tidak ditemukan." />
                    @else
                        <x-empty-alert message="Belum ada template tabel statistik. Tambah template pertama untuk memulai." />
                    @endif
                @else
                    <div class="overflow-x-auto rounded-box border-base-200 border">
                        <table class="table table-zebra w-full">
                            <thead class="bg-base-200/50 text-base-content text-sm">
                                <tr>
                                    {{-- BARU: kolom checkbox pilih-semua — disembunyikan sampai admin pilih mode aksi lewat FAB --}}
                                    {{-- <th class="w-4 checkbox-col hidden">
                                        <input type="checkbox" id="selectAllTemplates" class="checkbox checkbox-sm" />
                                    </th> --}}
                                    <th class="w-4 checkbox-col hidden">
                                        <label class="cursor-pointer inline-flex">
                                            <input type="checkbox" id="selectAllTemplates" class="peer sr-only" />
                                            <x-lucide-square class="w-5 h-5 text-base-content/40 peer-checked:hidden" />
                                            <x-lucide-square-check class="w-5 h-5 text-primary hidden peer-checked:block" />
                                        </label>
                                    </th>
                                    <th>Judul Template</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Dashboard Peta</th>
                                    <th class="text-center">Dipakai Kelurahan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $template)
                                    <tr>
                                        {{-- BARU: checkbox per baris — disembunyikan sampai admin pilih mode aksi --}}
                                        {{-- <td class="checkbox-col hidden">
                                            <input type="checkbox" class="checkbox checkbox-sm template-checkbox" value="{{ $template->id }}" />
                                        </td> --}}
                                        <td class="checkbox-col hidden">
                                            <label class="cursor-pointer inline-flex">
                                                <input type="checkbox" class="peer sr-only template-checkbox" value="{{ $template->id }}" />
                                                <x-lucide-square class="w-5 h-5 text-base-content/40 peer-checked:hidden" />
                                                <x-lucide-square-check class="w-5 h-5 text-primary hidden peer-checked:block" />
                                            </label>
                                        </td>
                                        <td class="font-medium max-w-50 md:max-w-xs text-wrap wrap-break-words">
                                            {{ $template->title }}
                                            @if($template->description)
                                                <p class="text-xs text-base-content/60 font-normal mt-1 line-clamp-1">
                                                    {{ $template->description }}
                                                </p>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($template->is_active)
                                                <div class="badge badge-soft badge-success whitespace-nowrap">Aktif</div>
                                            @else
                                                <div class="badge badge-soft badge-neutral whitespace-nowrap">Nonaktif</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($template->is_mapped)
                                                <div class="badge badge-soft badge-info whitespace-nowrap">Tampil di Peta</div>
                                            @else
                                                <div class="badge badge-outline whitespace-nowrap">Tidak Dipetakan</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="badge badge-outline whitespace-nowrap">
                                                {{ $template->entries_count }} Kelurahan
                                            </div>
                                        </td>
                                        <td class="flex justify-center gap-2">
                                            <button type="button" onclick="document.getElementById('modal_history_{{ $template->id }}').showModal()" class="btn btn-soft btn-info btn-sm">
                                                Riwayat
                                            </button>
                                            <a href="{{ route('admin-bps.statistic-templates.edit', $template) }}" class="btn btn-soft btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <form id="form-delete-template-{{ $template->id }}" action="{{ route('admin-bps.statistic-templates.destroy', $template) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" onclick="document.getElementById('modal_confirm_delete_template_{{ $template->id }}').showModal()" class="btn btn-soft btn-error btn-sm">
                                                Hapus
                                            </button>

                                            <dialog id="modal_confirm_delete_template_{{ $template->id }}" class="modal">
                                                <div class="modal-box">
                                                    <div class="flex flex-col items-center text-center">
                                                        <x-lucide-triangle-alert class="w-14 h-14 text-error mb-4" />
                                                        <h3 class="font-bold text-xl text-base-content">Konfirmasi Hapus</h3>
                                                        <p class="py-4 text-base-content/80">Apakah Anda yakin ingin menghapus template ini? Aksi ini tidak bisa dibatalkan.</p>
                                                    </div>
                                                    <div class="modal-action justify-center">
                                                        <form method="dialog">
                                                            <button class="btn btn-ghost">Batal</button>
                                                        </form>
                                                        <button type="submit" form="form-delete-template-{{ $template->id }}" class="btn btn-error">
                                                            Ya, Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                                <form method="dialog" class="modal-backdrop">
                                                    <button>close</button>
                                                </form>
                                            </dialog>
                                        </td>
                                        <x-template-history-modal :template="$template" :can-restore="true" />
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($templates, 'links'))
                        <div class="mt-6">
                            {{ $templates->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
        </x-section-card>
    </div>

    {{-- BARU: FAB speed-dial untuk bulk action — fixed di pojok kanan bawah --}}
    <div id="bulkFabGroup" class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3">

        {{-- badge jumlah terpilih — cuma tampil setelah 1 dari 3 aksi dipilih --}}
        <div id="bulkSelectedBadge" class="hidden badge badge-neutral shadow-md">
            <span id="bulkSelectedCount">0</span>&nbsp;dipilih
        </div>

        {{-- 3 gelembung aksi — hidden sampai FAB utama diklik --}}
        <div id="fabBubbleDelete" class="hidden tooltip tooltip-left" data-tip="Hapus Terpilih">
            <button type="button" id="bulkDeleteBtn" class="btn btn-circle btn-error shadow-lg">
                <x-lucide-trash-2 class="w-5 h-5" />
            </button>
        </div>
        <div id="fabBubbleDisable" class="hidden tooltip tooltip-left" data-tip="Nonaktifkan dari Peta">
            <button type="button" id="bulkDisableMapBtn" class="btn btn-circle btn-neutral shadow-lg">
                <x-lucide-map-pin-off class="w-5 h-5" />
            </button>
        </div>
        <div id="fabBubbleEnable" class="hidden tooltip tooltip-left" data-tip="Aktifkan di Peta">
            <button type="button" id="bulkEnableMapBtn" class="btn btn-circle btn-info shadow-lg">
                <x-lucide-map-pin class="w-5 h-5" />
            </button>
        </div>

        {{-- gelembung utama: trigger buka menu / tombol X untuk batal --}}
        <button type="button" id="bulkFabMain" class="btn btn-circle btn-secondary btn-lg shadow-xl">
            <x-lucide-list-checks id="bulkFabIconDefault" class="w-6 h-6" />
            <x-lucide-x id="bulkFabIconClose" class="w-6 h-6 hidden" />
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.template-checkbox');
            if (checkboxes.length === 0) return; // halaman kosong (empty-state), FAB tidak perlu tampil

            const selectAll = document.getElementById('selectAllTemplates');
            const checkboxCols = document.querySelectorAll('.checkbox-col');

            const fabMain = document.getElementById('bulkFabMain');
            const iconDefault = document.getElementById('bulkFabIconDefault');
            const iconClose = document.getElementById('bulkFabIconClose');

            const bubbleDelete = document.getElementById('fabBubbleDelete');
            const bubbleDisable = document.getElementById('fabBubbleDisable');
            const bubbleEnable = document.getElementById('fabBubbleEnable');

            const btnDelete = document.getElementById('bulkDeleteBtn');
            const btnDisable = document.getElementById('bulkDisableMapBtn');
            const btnEnable = document.getElementById('bulkEnableMapBtn');

            const badge = document.getElementById('bulkSelectedBadge');
            const countEl = document.getElementById('bulkSelectedCount');

            // null: idle | 'menu': 3 gelembung terbuka, belum pilih aksi | 'delete'/'enable-map'/'disable-map': mode aksi aktif
            let mode = null;

            function selectedIds() {
                return Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
            }

            function showBubbles() {
                bubbleDelete.classList.remove('hidden');
                bubbleDisable.classList.remove('hidden');
                bubbleEnable.classList.remove('hidden');
            }

            function hideBubbles() {
                bubbleDelete.classList.add('hidden');
                bubbleDisable.classList.add('hidden');
                bubbleEnable.classList.add('hidden');
            }

            function showCheckboxColumn(show) {
                checkboxCols.forEach(el => el.classList.toggle('hidden', !show));
            }

            function setActiveBubble(activeMode) {
                const map = { 'delete': btnDelete, 'disable-map': btnDisable, 'enable-map': btnEnable };
                Object.entries(map).forEach(([key, btn]) => {
                    if (key === activeMode) {
                        btn.disabled = false;
                        btn.classList.add('ring', 'ring-2', 'ring-offset-2', 'ring-current');
                    } else {
                        btn.disabled = true;
                        btn.classList.add('opacity-40');
                        btn.classList.remove('ring', 'ring-2', 'ring-offset-2', 'ring-current');
                    }
                });
            }

            function clearBubbleStates() {
                [btnDelete, btnDisable, btnEnable].forEach(btn => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-40', 'ring', 'ring-2', 'ring-offset-2', 'ring-current');
                });
            }

            function updateBadge() {
                const count = selectedIds().length;
                countEl.textContent = count;
                badge.classList.toggle('hidden', mode === null || mode === 'menu');
            }

            function resetAll() {
                mode = null;
                iconDefault.classList.remove('hidden');
                iconClose.classList.add('hidden');
                hideBubbles();
                showCheckboxColumn(false);
                clearBubbleStates();
                checkboxes.forEach(cb => cb.checked = false);
                if (selectAll) selectAll.checked = false;
                updateBadge();
            }

            function enterMenuMode() {
                mode = 'menu';
                iconDefault.classList.add('hidden');
                iconClose.classList.remove('hidden');
                showBubbles();
            }

            function chooseAction(actionMode) {
                mode = actionMode;
                setActiveBubble(actionMode);
                showCheckboxColumn(true);
                updateBadge();
            }

            function submitBulk(url, method, extra = {}) {
                const ids = selectedIds();
                if (ids.length === 0) {
                    alert('Pilih minimal 1 template dulu (centang di kolom paling kiri tabel).');
                    return;
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = method;
                form.appendChild(methodField);

                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                Object.entries(extra).forEach(([key, value]) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }

            // Gelembung utama: buka menu (idle -> menu), atau batal (menu/aksi -> idle)
            fabMain.addEventListener('click', function () {
                if (mode === null) {
                    enterMenuMode();
                } else {
                    resetAll();
                }
            });

            // Tiap gelembung aksi: klik pertama = pilih mode, klik kedua (saat sudah jadi mode aktif) = eksekusi
            btnDelete.addEventListener('click', function () {
                if (this.disabled) return;
                if (mode === 'menu') { chooseAction('delete'); return; }
                if (mode === 'delete') {
                    const count = selectedIds().length;
                    if (count === 0) { alert('Pilih minimal 1 template dulu (centang di kolom paling kiri tabel).'); return; }
                    if (!confirm(`Hapus ${count} template terpilih? Template yang masih dipakai Kelurahan akan otomatis dilewati (tidak ikut terhapus).`)) return;
                    submitBulk('{{ route('admin-bps.statistic-templates.bulk-destroy') }}', 'DELETE');
                }
            });

            btnEnable.addEventListener('click', function () {
                if (this.disabled) return;
                if (mode === 'menu') { chooseAction('enable-map'); return; }
                if (mode === 'enable-map') {
                    submitBulk('{{ route('admin-bps.statistic-templates.bulk-set-mapped') }}', 'PATCH', { is_mapped: 1 });
                }
            });

            btnDisable.addEventListener('click', function () {
                if (this.disabled) return;
                if (mode === 'menu') { chooseAction('disable-map'); return; }
                if (mode === 'disable-map') {
                    submitBulk('{{ route('admin-bps.statistic-templates.bulk-set-mapped') }}', 'PATCH', { is_mapped: 0 });
                }
            });

            checkboxes.forEach(cb => cb.addEventListener('change', function () {
                if (!cb.checked && selectAll) selectAll.checked = false;
                updateBadge();
            }));

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                    updateBadge();
                });
            }
        });
    </script>
</x-layout-admin-bps>