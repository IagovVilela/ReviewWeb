<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Se SESSION_DRIVER=database mas a tabela sessions não existir,
 * usa driver 'file' para esta requisição e evita 500.
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
                config(['session.driver' => 'file']);
            }
        } catch (\Throwable $e) {
            config(['session.driver' => 'file']);
        }

        return $next($request);
    }
}
