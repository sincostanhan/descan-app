<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdminKelurahan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Pastikan user memiliki role admin kelurahan
        if (!Auth::check() || !Auth::user()->hasRole('admin-kelurahan')) {
            abort(403, 'Akses ditolak. Anda bukan Admin Kelurahan.');
        }

        // 2. Pastikan kelurahan yang diakses COCOK dengan kelurahan milik user
        $currentVillageId = app()->bound('current_village_id') ? app('current_village_id') : null;
        
        if (Auth::user()->village_id !== $currentVillageId) {
            // Jika tidak cocok, lempar kembali ke subdomain aslinya
            $userVillage = \App\Models\Village::find(Auth::user()->village_id);
            if ($userVillage) {
                // $baseUrl = env('APP_URL_BASE', 'descan.scth.tech');
                // $redirectUrl = 'http://' . $userVillage->subdomain . '.' . $baseUrl . '/admin/dashboard';
                // return redirect()->away($redirectUrl)->with('error', 'Anda dialihkan ke kelurahan Anda.');

                // 2. Gunakan route() helper agar otomatis membaca http/https dan path yang benar
                return redirect()->route('admin.home.edit', [
                    'subdomain' => $userVillage->subdomain
                ])->with('error', 'Anda otomatis dialihkan ke kelurahan Anda.');
            }
            abort(403, 'Anda tidak memiliki akses ke kelurahan ini.');
        }

        return $next($request);
    }
}
