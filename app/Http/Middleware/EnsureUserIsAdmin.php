<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * FR-08/FR-09: hanya user dengan role "admin" yang boleh mengakses
     * halaman manajemen user.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            abort(403, 'Hanya Admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
