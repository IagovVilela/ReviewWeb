<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }

        return null;
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($request->expectsJson()) {
            try {
                $this->authenticate($request, $guards);
            } catch (AuthenticationException $e) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return $next($request);
        }

        return parent::handle($request, $next, ...$guards);
    }
}
