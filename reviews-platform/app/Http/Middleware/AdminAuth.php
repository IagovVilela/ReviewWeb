<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Proprietário = cargo mais alto; admin = segundo nível. Apenas esses acessam a área admin.
        $user = Auth::user();
        if (isset($user->role) && !$user->isAtLeastAdmin()) {
            abort(403, 'Acesso negado. Apenas proprietários e administradores podem acessar esta área.');
        }

        return $next($request);
    }
}