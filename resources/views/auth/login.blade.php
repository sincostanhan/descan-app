<x-layout-auth>
    {{-- <div class="card bg-base-100 shadow-xl border border-base-200"> --}}
    <div class="card bg-base-100 card-border shadow-xl">
        <div class="card-body">
            <h2 class="card-title text-2xl font-bold mb-4 justify-center">Login Sistem</h2>
            
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
                        class="input w-full"
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
                        class="input w-full"
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
</x-layout-auth>