<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return $next($request);
        }

        $user = $request->user();

        // Admin and proprietário always have access
        if (in_array($user->role, ['proprietario', 'admin'])) {
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
    }
}
