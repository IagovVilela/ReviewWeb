<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Se SESSION_DRIVER=database mas a tabela sessions não existir ou o BD falhar,
 * usa driver 'cookie' (stateless) para esta requisição e evita 500.
 */
class EnsureSessionDriverSafe
{
    public function handle(Request $request, Closure $next)
    {
        if (config('session.driver') !== 'database') {
            return $next($request);
        }

        try {
            $table = config('session.table', 'sessions');
            if (!Schema::hasTable($table)) {
                @error_log('SESSION_SAFE: tabela "' . $table . '" não encontrada, fallback para cookie');
                config(['session.driver' => 'cookie']);
            }
        } catch (\Throwable $e) {
            @error_log('SESSION_SAFE: erro ao verificar tabela de sessions: ' . $e->getMessage());
            config(['session.driver' => 'cookie']);
        }

        return $next($request);
    }
}
