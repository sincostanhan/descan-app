<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePublicSiteIsPublished
{
    /**
     * Handle an incoming request.
     *
     * Dipasang HANYA di rute publik dalam grup subdomain (bukan di /admin/*), supaya
     * Admin Kelurahan tetap bisa login & isi konten walau situs publiknya belum di-publish.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Setting::first();

        if (!$setting || !$setting->is_published) {
            return response()->view('public.site-not-published', [], 200);
        }

        return $next($request);
    }
}