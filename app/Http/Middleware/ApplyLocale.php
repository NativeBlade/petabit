<?php

namespace App\Http\Middleware;

use App\Native\State\LocaleState;
use Closure;
use Illuminate\Http\Request;

/**
 * Applies the persisted locale to every request before Blade renders, so
 * __() output always matches the language the user picked.
 */
class ApplyLocale
{
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale(LocaleState::current());

        return $next($request);
    }
}
