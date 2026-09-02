{{-- resources\views\components\flash-message.blade.php --}}

{{-- @if(session('success'))
    <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
        <strong>Sukses!</strong> {{ session('success') }}
    </div>
@endif --}}

@if(session('success') || session('error'))
    <dialog id="flash_modal" class="modal">
        <div class="modal-box">
            <div class="flex flex-col items-center text-center mt-4">
                @if(session('success'))
                    <x-lucide-circle-check-big class="w-14 h-14 
                    text-success mb-4"/>
                    <h3 class="font-bold text-xl text-base-content">Sukses!</h3>
                    <p class="py-4 text-base-content/80 text-lg">{{ session('success') }}</p>
                @elseif(session('error'))
                    <x-lucide-circle-x class="w-14 h-14 
                    text-error mb-4"/>
                    {{-- <x-lucide-circle-alert class="w-14 h-14 
                    text-error mb-4"> --}}
                    <h3 class="font-bold text-xl text-base-content">Oops!</h3>
                    <p class="py-4 text-base-content/80 text-lg">{{ session('error') }}</p>
                @endif
            </div>

            <div class="modal-action justify-center">
                <form method="dialog">
                    <button class="btn {{ session('success') ? 'btn-success text-white' : 'btn-error text-white' }} px-8">
                        Tutup
                    </button>
                </form>
            </div>
        </div>
        
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById('flash_modal');
            if (modal) {
                modal.showModal();
            }
        });
    </script>
@endif