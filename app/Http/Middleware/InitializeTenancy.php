<?php

namespace App\Http\Middleware;

use App\Models\Village;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil nama subdomain dari route parameter '{subdomain}'
        $subdomain = $request->route('subdomain');

        // Jika rute ini memiliki parameter subdomain (berarti sedang akses rute kelurahan)
        if ($subdomain) {
            $village = Village::where('subdomain', $subdomain)->first();

            if ($village) {
                // Simpan ID kelurahan ke container aplikasi
                app()->instance('current_village_id', $village->id);
                
                // Bagikan data village ke semua view agar mudah diakses di layout
                View::share('currentVillage', $village);

                // Set default parameter url untuk seluruh aplikasi
                URL::defaults(['subdomain' => $subdomain]);

                // Trik agar parameter $subdomain tidak dilempar ke Controller (menjaga Controller tetap bersih)
                $request->route()->forgetParameter('subdomain');
            } else {
                abort(404, 'Kelurahan tidak ditemukan.');
            }
        }

        return $next($request);
    }
}
