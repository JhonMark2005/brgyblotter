<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    private const ALLOWED = ['en', 'war'];

    public function handle(Request $request, Closure $next)
    {
        $lang = session('lang', 'en');
        if (in_array($lang, self::ALLOWED)) {
            App::setLocale($lang);
        }
        return $next($request);
    }
}
