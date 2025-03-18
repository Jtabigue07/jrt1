<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isActive()) {
            return $next($request);
        }

        Auth::logout();
        return redirect('/login')->with('error', 'Your account is inactive. Please contact the administrator.');
    }
}