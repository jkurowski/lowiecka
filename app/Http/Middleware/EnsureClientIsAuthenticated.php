<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureClientIsAuthenticated
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('client')->check()) {
            return redirect('/client/login');
        }

        return $next($request);
    }
}
