<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Stripe integration following Stripe plugin best practices.
 *
 * - Checkout Sessions API for subscriptions (Stripe-hosted checkout).
 * - Billing APIs + Stripe Checkout; no Charges API, Sources API or Tokens.
 * @see https://docs.stripe.com/payments/checkout
 * @see https://docs.stripe.com/billing/subscriptions/overview
 * @see https://docs.stripe.com/get-started/checklist/go-live.md
 *
 * Requires STRIPE_SECRET and STRIPE_SUBSCRIPTION_PRICE_ID in .env.
 */
class StripeService
{
    /** Stripe API version (per plugin stripe-best-practices). */
    private const STRIPE_API_VERSION = '2026-01-28.clover';

    public function __construct()
    {
        if (!class_exists(\Stripe\Stripe::class)) {
            return;
        }
        $secret = config('stripe.secret');
        if ($secret) {
            \Stripe\Stripe::setApiKey($secret);
            // Plugin stripe-best-practices: use API version 2026-01-28.clover when supported by your stripe-php SDK.
            if (method_exists(\Stripe\Stripe::class, 'setApiVersion')) {
                \Stripe\Stripe::setApiVersion(self::STRIPE_API_VERSION);
            }
        }
    }

    /**
     * Get or create Stripe Customer for the user.
     */
    public function getOrCreateCustomer(User $user): ?string
    {
        if ($user->stripe_customer_id) {
            return $user->stripe_customer_id;
        }

        $secret = config('stripe.secret');
        if (!$secret || !class_exists(\Stripe\Customer::class)) {
            return null;
        }

        try {
            $customer = \Stripe\Customer::create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => ['user_id' => (string) $user->id],
            ]);
            $user->update(['stripe_customer_id' => $customer->id]);
            return $customer->id;
        } catch (\Throwable $e) {
            Log::error('Stripe create customer failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);
            return null;
        }
    }

    /**
     * Create a Checkout Session for subscription (R$ 99/month).
     * Returns the session URL to redirect the user to.
     */
    public function createSubscriptionCheckoutSession(User $user, string $successUrl, string $cancelUrl): ?string
    {
        $priceId = config('stripe.subscription_price_id');
        if (!$priceId) {
            Log::warning('STRIPE_SUBSCRIPTION_PRICE_ID not set');
            return null;
        }

        $secret = config('stripe.secret');
        if (str_starts_with((string) $secret, 'rk_')) {
            Log::info('Stripe: usando chave restrita (rk_). Se o checkout falhar, use a chave secreta padrão (sk_test_) no .env.');
        }

        $customerId = $this->getOrCreateCustomer($user);
        if (!$customerId && !class_exists(\Stripe\Checkout\Session::class)) {
            return null;
        }

        try {
            // Checkout Sessions API, mode subscription — per Stripe plugin: prefer Stripe-hosted Checkout.
            $params = [
                'mode' => 'subscription',
                'line_items' => [
                    [
                        'price' => $priceId,
                        'quantity' => 1,
                    ],
                ],
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'metadata' => ['user_id' => (string) $user->id],
                'subscription_data' => [
                    'metadata' => ['user_id' => (string) $user->id],
                ],
            ];

            if ($customerId) {
                $params['customer'] = $customerId;
            } else {
                $params['customer_email'] = $user->email;
            }

            $session = \Stripe\Checkout\Session::create($params);
            return $session->url;
        } catch (\Throwable $e) {
            Log::error('Stripe checkout session failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);
            return null;
        }
    }

    /**
     * Create a Billing Portal session so the customer can manage subscription, payment method, cancel, invoices.
     * Returns the portal URL to redirect the user to, or null if not available.
     * @see https://stripe.com/docs/customer-management/creating-customers#customer-portal
     */
    public function createBillingPortalSession(User $user, string $returnUrl): ?string
    {
        $customerId = $user->stripe_customer_id ?: $this->getOrCreateCustomer($user);
        if (!$customerId) {
            return null;
        }

        if (!class_exists(\Stripe\BillingPortal\Session::class)) {
            Log::warning('Stripe BillingPortal\Session class not found. Update stripe/stripe-php if needed.');
            return null;
        }

        $secret = config('stripe.secret');
        if (!$secret) {
            return null;
        }

        try {
            $session = \Stripe\BillingPortal\Session::create([
                'customer' => $customerId,
                'return_url' => $returnUrl,
            ]);
            return $session->url ?? null;
        } catch (\Throwable $e) {
            Log::error('Stripe billing portal session failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);
            return null;
        }
    }

    /**
     * Update user subscription status from Stripe subscription object.
     */
    public function updateUserSubscriptionStatus(User $user, string $subscriptionId, string $status): void
    {
        $user->update([
            'stripe_subscription_id' => $subscriptionId,
            'subscription_status' => $status,
        ]);
    }

    /**
     * Find user by Stripe customer id or by subscription metadata.
     */
    public function findUserByStripeCustomerId(string $customerId): ?User
    {
        return User::where('stripe_customer_id', $customerId)->first();
    }

    public function findUserByStripeSubscriptionId(string $subscriptionId): ?User
    {
        return User::where('stripe_subscription_id', $subscriptionId)->first();
    }

    /**
     * Sync user subscription status from a Checkout Session (e.g. when user returns to success URL).
     * Use when webhook is not configured so the panel unblocks after payment.
     */
    public function syncSubscriptionFromCheckoutSession(string $sessionId, User $user): bool
    {
        if (!config('stripe.secret') || !class_exists(\Stripe\Checkout\Session::class)) {
            return false;
        }

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId, [
                'expand' => ['subscription'],
            ]);

            if ($session->mode !== 'subscription' || !$session->subscription) {
                return false;
            }

            $subscription = $session->subscription;
            if (is_string($subscription)) {
                $subscription = \Stripe\Subscription::retrieve($subscription);
            }

            $status = $subscription->status ?? null;
            if ($status && in_array($status, ['active', 'trialing'], true)) {
                $this->updateUserSubscriptionStatus($user, $subscription->id, 'active');
                return true;
            }

            if ($status) {
                $this->updateUserSubscriptionStatus($user, $subscription->id, $status);
            }
            return false;
        } catch (\Throwable $e) {
            Log::warning('Stripe sync from checkout session failed', [
                'session_id' => $sessionId,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
