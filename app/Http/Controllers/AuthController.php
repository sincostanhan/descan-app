<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        // 1. Cek dulu apakah pengguna SUDAH login
        //    Gunakan Facade Auth::check()
        if (Auth::check()) {
            
            // 2. Gunakan Facade Auth::user()
            //    Beri tahu teks editor tipe class-nya agar warning hilang
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // 3. Redirect berdasarkan role
            if ($user->isAdminBps()) {
                return redirect()->route('admin-bps.users.index');
            }

            // return redirect()->route('admin.home.edit');
            // Sisipkan subdomain
            return redirect()->route('admin.home.edit', [
                'subdomain' => $user->village->subdomain
            ]);
        }

        // 4. Jika BELUM login, baru tampilkan halaman form login
        return view('auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Cek role untuk pengalihan halaman
            if ($user->hasRole('bps')) {
                // return redirect()->route('admin-bps.dashboard');
                return redirect()->route('admin-bps.users.index');
            }

            if ($user->hasRole('admin-kelurahan')) {
                // return redirect()->route('admin.home.edit');
                // Sisipkan subdomain
                return redirect()->route('admin.home.edit', [
                    'subdomain' => $user->village->subdomain
                ]);
            }

            // Default jika role tidak ditemukan
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus session yang aktif
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
