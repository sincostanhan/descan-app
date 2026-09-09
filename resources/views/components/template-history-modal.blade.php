{{-- @props(['template', 'canRestore' => false]) --}}
@props(['template', 'canRestore' => false, 'villageId' => null])

<dialog id="modal_history_{{ $template->id }}" class="modal modal-middle">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            {{-- <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button> --}}
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">
                <x-lucide-x class="w-4 h-4" />
            </button>
        </form>

        <h3 class="text-xl font-bold mb-1">Riwayat Perubahan</h3>
        <p class="text-sm text-base-content/70 mb-6">{{ $template->title }}</p>

        @if($template->logs->isEmpty())
            <p class="text-sm text-base-content/50 italic py-6 text-center">
                Belum ada riwayat perubahan pada template ini.
            </p>
        @else
            <ul class="space-y-3 max-h-96 overflow-y-auto pr-1">
                @foreach($template->logs as $log)
                    {{-- <li class="flex items-start justify-between gap-3 bg-base-200/50 rounded-box p-3"> --}}
                    @php
                        // $log->reads di sini adalah snapshot SEBELUM ditandai terbaca oleh controller
                        // (query sudah dijalankan lebih dulu), jadi tetap akurat mencerminkan status
                        // "belum dibaca" untuk kunjungan halaman SAAT INI, walau baris record read-nya
                        // sudah keburu dibuat di database untuk kunjungan berikutnya.
                        $isUnread = $villageId && $log->reads->where('village_id', $villageId)->isEmpty();
                    @endphp
                    <li class="flex items-start justify-between gap-3 rounded-box p-3 {{ $isUnread ? 'bg-error/10 border border-error/20' : 'bg-base-200/50' }}">
                        <div>
                            {{-- <p class="text-sm">{{ $log->description }}</p> --}}                            
                            <p class="text-sm flex items-center gap-2">
                                @if($isUnread)
                                    <span class="w-2 h-2 rounded-full bg-error shrink-0"></span>
                                @endif
                                <span class="{{ $isUnread ? 'font-semibold' : '' }}">{{ $log->description }}</span>
                            </p>
                            <p class="text-xs text-base-content/50 mt-1">
                                {{ $log->changer->name ?? 'Sistem' }} &middot; {{ $log->created_at->translatedFormat('d M Y, H:i') }}
                            </p>
                        </div>

                        {{-- @if($canRestore && $log->canBeRestored())
                            <form action="{{ route('admin-bps.statistic-templates.logs.restore', [$template, $log]) }}" method="POST"
                                onsubmit="return confirm('Pulihkan kolom/baris ini? Data yang sudah pernah diisi Kelurahan akan langsung muncul kembali.')"
                                class="shrink-0">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-soft btn-success whitespace-nowrap">
                                    Pulihkan
                                </button>
                            </form>
                        @endif --}}
                        @if($canRestore && $log->canBeRestored())
                            <form id="form-restore-log-{{ $log->id }}" action="{{ route('admin-bps.statistic-templates.logs.restore', [$template, $log]) }}" method="POST" class="shrink-0">
                                @csrf
                            </form>
                            <button type="button" onclick="document.getElementById('modal_confirm_restore_{{ $log->id }}').showModal()" class="btn btn-xs btn-soft btn-success whitespace-nowrap">
                                Pulihkan
                            </button>

                            <dialog id="modal_confirm_restore_{{ $log->id }}" class="modal">
                                <div class="modal-box">
                                    <div class="flex flex-col items-center text-center">
                                        <x-lucide-triangle-alert class="w-14 h-14 text-warning mb-4" />
                                        <h3 class="font-bold text-xl text-base-content">Konfirmasi Pulihkan</h3>
                                        <p class="py-4 text-base-content/80">Pulihkan kolom/baris ini? Data yang sudah pernah diisi Kelurahan akan langsung muncul kembali.</p>
                                    </div>
                                    <div class="modal-action justify-center">
                                        <form method="dialog">
                                            <button class="btn btn-ghost">Batal</button>
                                        </form>
                                        <button type="submit" form="form-restore-log-{{ $log->id }}" class="btn btn-success">
                                            Ya, Pulihkan
                                        </button>
                                    </div>
                                </div>
                                <form method="dialog" class="modal-backdrop">
                                    <button>close</button>
                                </form>
                            </dialog>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>