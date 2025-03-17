<?php

namespace App\Http\Middleware;

use App\Traits\SMTPConfig;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetUserSMTPConfig
{
    use SMTPConfig;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->setSettingsSMTPConfig();
        return $next($request);
    }
}
