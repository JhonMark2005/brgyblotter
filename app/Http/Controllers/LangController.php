<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LangController extends Controller
{
    private const ALLOWED = ['en', 'war'];

    public function set(Request $request, string $code)
    {
        if (in_array($code, self::ALLOWED)) {
            session(['lang' => $code]);
        }

        return redirect()->back();
    }
}
