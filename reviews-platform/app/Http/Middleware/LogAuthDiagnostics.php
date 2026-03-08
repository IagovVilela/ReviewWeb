<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Loga detalhes da requisição e da sessão para diagnóstico de 401 em rotas /api/companies/{id}/members.
 */
class LogAuthDiagnostics
{
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        if (!preg_match('#^api/companies/\d+/members#', $path)) {
            return $next($request);
        }

        $sessionId = session()->getId();
        $hasSessionCookie = $request->hasCookie(config('session.cookie'));
        $authCheck = auth()->check();
        $userId = auth()->id();

        Log::channel('single')->info('Auth diagnostics for members API', [
            'path' => $request->fullUrl(),
            'method' => $request->method(),
            'session_id' => $sessionId,
            'session_id_short' => $sessionId ? substr($sessionId, 0, 8) . '...' : null,
            'has_session_cookie' => $hasSessionCookie,
            'auth_check' => $authCheck,
            'user_id' => $userId,
            'cookie_names' => array_keys($request->cookies->all()),
            'session_driver' => config('session.driver'),
        ]);

        return $next($request);
    }
}
