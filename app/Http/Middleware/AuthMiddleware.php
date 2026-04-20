<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    private const TIMEOUT = 1800; // 30 minutes

    public function handle(Request $request, Closure $next): Response
    {
        if (!session('user')) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        // Session timeout — expire after 30 minutes of inactivity
        $lastActivity = session('last_activity');
        if ($lastActivity && (time() - $lastActivity) > self::TIMEOUT) {
            session()->flush();
            return redirect()->route('login')->with('error', 'Your session expired due to inactivity. Please log in again.');
        }

        session(['last_activity' => time()]);

        return $next($request);
    }
}
