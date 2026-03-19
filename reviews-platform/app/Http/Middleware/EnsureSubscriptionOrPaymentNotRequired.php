<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionOrPaymentNotRequired
{
    /**
     * Routes that are allowed without an active subscription (billing flow).
     */
    protected array $except = [
        'billing.subscribe',
        'billing.checkout',
        'billing.success',
        'billing.cancel',
    ];

    /**
     * If the user has payment_required and no active subscription, redirect to subscribe page.
     * Em caso de erro (ex.: colunas de assinatura inexistentes no banco), deixa passar para não gerar 500.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return $next($request);
        }

        try {
            $user = $request->user();

            // Admin and proprietário always have access
            $role = strtolower((string) ($user->role ?? ''));
            if (in_array($role, ['proprietario', 'admin'], true)) {
                return $next($request);
            }

            // Se o modelo não tiver os métodos/atributos de assinatura (migration antiga), deixa passar
            if (!method_exists($user, 'requiresPayment') || !method_exists($user, 'hasActiveSubscription')) {
                return $next($request);
            }

            if (!$user->requiresPayment()) {
                return $next($request);
            }

            if ($user->hasActiveSubscription()) {
                return $next($request);
            }

            if ($request->routeIs($this->except)) {
                return $next($request);
            }

            return redirect()->route('billing.subscribe');
        } catch (\Throwable $e) {
            Log::warning('EnsureSubscriptionOrPaymentNotRequired: erro ao verificar assinatura, permitindo acesso', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);
            return $next($request);
        }
    }
}
