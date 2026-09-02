{{-- resources\views\auth\login.blade.php --}}

<x-layout-auth>
    {{-- <div class="card bg-base-100 shadow-xl border border-base-200"> --}}
    <div class="card bg-base-100 card-border shadow-xl">
        <div class="card-body">
            <div class="text-center mb-2">
                {{-- <span class="text gradient font-bold tracking-wide block">Gen-Descan 695</span> --}}
                {{-- <span class="text gradient font-bold tracking-wide block">Gen-Descan 695</span> --}}
                <span class="text gradient font-bold tracking-wide block">Gen-Descan 7472</span>
            </div>

            {{-- <h2 class="card-title text-2xl font-bold mb-4 justify-center">Login Sistem</h2> --}}
            
            <form action="{{ route('login.authenticate') }}" method="POST" class="space-y-4">
                @csrf

                <fieldset class="fieldset w-full">
                    {{-- <legend class="fieldset-legend">Username</legend> --}}
                    <legend class="fieldset-legend text-base">Username</legend>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="{{ old('username') }}"
                        class="input w-full
                        @error('username') input-error @enderror"
                        placeholder="Masukkan username ..." 
                        required autofocus />
                    <x-forms.error name="username" />
                </fieldset>

                <fieldset class="fieldset w-full">
                    <legend class="fieldset-legend text-base">Password</legend>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="input w-full
                        @error('password') input-error @enderror"
                        placeholder="••••••••" 
                        required />
                    <x-forms.error name="password" />
                </fieldset>

                <div class="card-actions justify-end mt-6">
                    <button type="submit" class="btn btn-primary w-full text-white">Login</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .text {
            font-size: 28px; /* Sedikit diperbesar dari 24px agar nama aplikasi terlihat jelas */
            font-weight: 700;
        }

        /* Kode asli Efek Nomor 3 (Gradient Flow) */
        .gradient {
            background: linear-gradient(90deg, #38bdf8, #818cf8, #f472b6, #38bdf8);
            background-size: 300%;
            -webkit-background-clip: text;
            color: transparent;
            animation: gradientFlow 6s linear infinite;
        }
        
        @keyframes gradientFlow {
            0% {
                background-position: 0%
            }
            100% {
                background-position: 300%
            }
        }
    </style>
</x-layout-auth>