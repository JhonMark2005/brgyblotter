<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = session('user');

        if (!$user || ($user['role'] ?? '') !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Access denied. Administrator privileges required.');
        }

        return $next($request);
    }
}
