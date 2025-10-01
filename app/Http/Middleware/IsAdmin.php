<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Izinkan hanya user dengan role "admin".
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(); // ambil user dari request (sudah login via auth)
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Hanya Admin yang diperbolehkan.');
        }

        return $next($request);
    }
}
