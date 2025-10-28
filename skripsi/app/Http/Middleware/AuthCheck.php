<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCheck
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah session user ada
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        return $next($request);
    }
}
