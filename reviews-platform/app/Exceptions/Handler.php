<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Log para stderr para aparecer nos Logs do Railway (stdout/stderr são visíveis no painel)
            $msg = 'LARAVEL_500 ' . get_class($e) . ': ' . $e->getMessage()
                . ' in ' . $e->getFile() . ':' . $e->getLine();
            @error_log($msg);
            @error_log('Trace: ' . $e->getTraceAsString());
        });
    }

    /**
     * Log detalhado para diagnóstico de 401 em /api/companies/*/members.
     * Envolvido em try/catch para nunca causar 500.
     */
    protected function logAuthenticationFailure(Request $request): void
    {
        try {
            $sessionId = $request->hasSession() ? $request->session()->getId() : null;
            $sessionCookieName = config('session.cookie');

            \Illuminate\Support\Facades\Log::channel('single')->warning('401 Unauthenticated on members API', [
                'path' => $request->fullUrl(),
                'method' => $request->method(),
                'session_id' => $sessionId,
                'session_id_short' => $sessionId ? substr($sessionId, 0, 12) . '...' : null,
                'has_session' => $request->hasSession(),
                'has_session_cookie' => $request->hasCookie($sessionCookieName),
                'session_driver' => config('session.driver'),
                'cookie_names' => array_keys($request->cookies->all()),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::channel('single')->warning('401 Unauthenticated on members API (log failed)', [
                'path' => $request->fullUrl(),
                'log_error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Convertir AuthenticationException em resposta (redirect ou JSON).
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->is('api/*')) {
            $this->logAuthenticationFailure($request);
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
