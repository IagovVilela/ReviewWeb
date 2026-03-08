<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function __construct(
        private StripeService $stripeService
    ) {
    }

    /**
     * Handle Stripe webhook events.
     */
    public function handle(Request $request)
    {
        if (class_exists(\Stripe\Stripe::class)) {
            \Stripe\Stripe::setApiKey(config('stripe.secret'));
        }

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('stripe.webhook_secret');

        if (!$webhookSecret) {
            Log::warning('STRIPE_WEBHOOK_SECRET not set');
            return response()->json(['error' => 'Webhook not configured'], 500);
        }

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook invalid payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature verification failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;
            case 'customer.subscription.updated':
            case 'customer.subscription.deleted':
                $this->handleSubscriptionUpdated($event->data->object);
                break;
            case 'invoice.paid':
                $this->handleInvoicePaid($event->data->object);
                break;
            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;
            default:
                Log::info('Stripe webhook unhandled event', ['type' => $event->type]);
        }

        return response()->json(['received' => true]);
    }

    private function handleCheckoutSessionCompleted(object $session): void
    {
        $userId = $session->metadata->user_id ?? null;
        $subscriptionId = $session->subscription ?? null;

        if ($userId && $subscriptionId) {
            $user = User::find($userId);
            if ($user) {
                $user->update([
                    'stripe_customer_id' => $session->customer ?? $user->stripe_customer_id,
                    'stripe_subscription_id' => $subscriptionId,
                ]);
                $this->syncSubscriptionStatus($user, $subscriptionId);
            }
        }
    }

    private function handleSubscriptionUpdated(object $subscription): void
    {
        $user = $this->stripeService->findUserByStripeSubscriptionId($subscription->id);
        if (!$user) {
            return;
        }
        // Usamos o status do Stripe como está: enquanto cancel_at_period_end, o status segue "active"
        // e o cliente mantém acesso até o fim do período pago. Quando o período termina, o Stripe
        // envia status "canceled" e aí barramos o acesso.
        $this->stripeService->updateUserSubscriptionStatus($user, $subscription->id, $subscription->status);
    }

    private function handleInvoicePaid(object $invoice): void
    {
        $subscriptionId = $invoice->subscription ?? null;
        if (!$subscriptionId) {
            return;
        }
        $user = $this->stripeService->findUserByStripeSubscriptionId($subscriptionId);
        if ($user) {
            $user->update(['subscription_status' => 'active']);
        }
    }

    private function handleInvoicePaymentFailed(object $invoice): void
    {
        $subscriptionId = $invoice->subscription ?? null;
        if (!$subscriptionId) {
            return;
        }
        $user = $this->stripeService->findUserByStripeSubscriptionId($subscriptionId);
        if ($user) {
            $user->update(['subscription_status' => 'past_due']);
        }
    }

    private function syncSubscriptionStatus(User $user, string $subscriptionId): void
    {
        try {
            $subscription = \Stripe\Subscription::retrieve($subscriptionId);
            $this->stripeService->updateUserSubscriptionStatus($user, $subscription->id, $subscription->status);
        } catch (\Throwable $e) {
            Log::error('Stripe sync subscription status failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }
    }
}
