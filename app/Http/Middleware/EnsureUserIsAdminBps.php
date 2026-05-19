<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdminBps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User|null $user */
        $user = $request->user();

        // Jika user ada (sudah login) dan rolenya adalah admin BPS
        if ($user && $user->isAdminBps()) {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin BPS.');
    }
}
