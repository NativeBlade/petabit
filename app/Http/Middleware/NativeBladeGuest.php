<?php

namespace App\Http\Middleware;

use App\Native\State\LaunchState;
use Closure;
use NativeBlade\Facades\NativeBlade;

class NativeBladeGuest
{
    public function handle($request, Closure $next)
    {
        // Signed in: open where the launch decided (evolution / rebirth), else home.
        if (NativeBlade::getState('auth.user')) {
            return NativeBlade::navigate(LaunchState::route() ?? '/home')->toResponse();
        }

        return $next($request);
    }
}
