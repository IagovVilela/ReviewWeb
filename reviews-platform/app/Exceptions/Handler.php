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
    /**
     * Render the exception (para diagnóstico: se APP_EXPOSE_500_MESSAGE=1, mostra o erro na resposta).
     */
    public function render($request, Throwable $e)
    {
        $msg = $e->getMessage();
        $isTableOrColumnError = str_contains($msg, "doesn't exist")
            || str_contains($msg, 'Unknown column')
            || str_contains($msg, 'SQLSTATE[42S02]')
            || str_contains($msg, 'Base table or view not found');

        if ($isTableOrColumnError && !config('app.debug')) {
            return response(
                '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Banco desatualizado</title></head><body style="font-family:sans-serif;max-width:600px;margin:40px auto;padding:20px;"><h1>Banco de dados desatualizado</h1><p>Parece que faltam tabelas ou colunas. No servidor (Railway ou terminal), execute:</p><pre>php artisan migrate --force</pre><p>Se o projeto estiver na pasta <code>reviews-platform</code>, use: <code>cd reviews-platform &amp;&amp; php artisan migrate --force</code></p><p style="color:#666;font-size:14px;">Erro: ' . htmlspecialchars($msg) . '</p></body></html>',
                503,
                ['Content-Type' => 'text/html; charset=utf-8']
            );
        }

        $isDebugPath = str_contains($request->path(), '-debug') || $request->path() === 'api/healthcheck';
        $expose = $isDebugPath
            || config('app.expose_500_message', false)
            || filter_var(env('APP_EXPOSE_500_MESSAGE', false), FILTER_VALIDATE_BOOLEAN);

        if ($expose) {
            $fullMsg = get_class($e) . ': ' . $msg . ' in ' . $e->getFile() . ':' . $e->getLine();
            return response('<pre style="white-space:pre-wrap;font-size:12px;">' . htmlspecialchars($fullMsg . "\n\n" . $e->getTraceAsString()) . '</pre>', 200);
        }
        return parent::render($request, $e);
    }

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
     * Log detalhado para diagnóstico de 401 em rotas /api/companies/{id}/members.
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
