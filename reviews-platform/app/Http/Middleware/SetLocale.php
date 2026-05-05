<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * App is English-only; session and ?lang= are ignored.
     */
    public function handle(Request $request, Closure $next)
    {
        App::setLocale('en_US');

        return $next($request);
    }
}
