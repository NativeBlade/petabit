<?php

namespace App\Http\Middleware;

use Closure;
use NativeBlade\Facades\NativeBlade;

class NativeBladeGuest
{
    public function handle($request, Closure $next)
    {
        if (NativeBlade::getState('auth.user')) {
            return NativeBlade::navigate('/home')->toResponse();
        }

        return $next($request);
    }
}
