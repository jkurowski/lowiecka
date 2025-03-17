<?php

namespace App\Http\Middleware;

use App\Traits\SMTPConfig;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSMTPConfig
{
    use SMTPConfig;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!$this->isSMTPConfigValid()) {
            return redirect()->route('settings.index');
        }

        return $next($request);
    }
}
