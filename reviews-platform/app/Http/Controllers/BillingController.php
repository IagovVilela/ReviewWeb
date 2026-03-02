<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function __construct(
        private StripeService $stripeService
    ) {
    }

    /**
     * Show the "Complete your payment" / subscribe page.
     */
    public function subscribe()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        if (!$user->requiresPayment()) {
            return redirect()->route('dashboard');
        }
        if ($user->hasActiveSubscription()) {
            return redirect()->route('dashboard');
        }

        return view('billing.subscribe');
    }

    /**
     * Create Stripe Checkout Session and redirect to Stripe.
     */
    public function redirectToCheckout(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        if (!$user->requiresPayment() || $user->hasActiveSubscription()) {
            return redirect()->route('dashboard');
        }

        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('billing.subscribe')
                ->with('error', __('billing.invalid_email'));
        }

        $successUrl = route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = route('billing.subscribe');

        $url = $this->stripeService->createSubscriptionCheckoutSession($user, $successUrl, $cancelUrl);
        if (!$url) {
            return redirect()->route('billing.subscribe')
                ->with('error', __('billing.checkout_error'));
        }

        return redirect()->away($url);
    }

    /**
     * Success URL after Stripe Checkout (user returns here).
     * Syncs subscription status from session_id so the panel unblocks even if webhook is not set.
     */
    public function success(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $sessionId = $request->query('session_id');
        if ($sessionId) {
            $this->stripeService->syncSubscriptionFromCheckoutSession($sessionId, $user);
        }

        return redirect()->route('dashboard')
            ->with('success', __('billing.subscription_success'));
    }

    /**
     * Cancel URL when user abandons Stripe Checkout.
     */
    public function cancel()
    {
        return redirect()->route('billing.subscribe')
            ->with('info', __('billing.checkout_canceled'));
    }
}
