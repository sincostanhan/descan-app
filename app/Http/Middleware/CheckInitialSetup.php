<?php

namespace App\Http\Middleware;

use App\Models\About;
use App\Models\Organization;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInitialSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pengecualian agar tidak terjadi infinite redirect pada route setup itu sendiri
        if (!$request->is('admin/setup*')) {
            // Cek Setting, Organization, dan About
            if (Setting::count() === 0 || Organization::count() === 0 || About::count() === 0) {
                return redirect()->route('admin.setup.index');
            }
        }

        return $next($request);
    }
}
