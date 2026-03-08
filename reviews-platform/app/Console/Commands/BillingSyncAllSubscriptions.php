<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\StripeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BillingSyncAllSubscriptions extends Command
{
    protected $signature = 'billing:sync-all-subscriptions';

    protected $description = 'Sincroniza o status de todas as assinaturas com o Stripe (roda automaticamente pelo agendador)';

    public function handle(StripeService $stripeService): int
    {
        if (!config('stripe.secret') || !class_exists(\Stripe\Subscription::class)) {
            Log::debug('billing:sync-all-subscriptions — Stripe não configurado, ignorando.');
            return self::SUCCESS;
        }

        \Stripe\Stripe::setApiKey(config('stripe.secret'));

        $users = User::whereNotNull('stripe_subscription_id')
            ->where('stripe_subscription_id', '!=', '')
            ->get();

        if ($users->isEmpty()) {
            return self::SUCCESS;
        }

        $updated = 0;
        foreach ($users as $user) {
            try {
                $subscription = \Stripe\Subscription::retrieve($user->stripe_subscription_id);
            } catch (\Throwable $e) {
                Log::warning('Stripe sync subscription failed', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ]);
                continue;
            }

            // Status do Stripe: "active" até o fim do período pago; quando o período termina vira "canceled"
            $stripeService->updateUserSubscriptionStatus($user, $subscription->id, $subscription->status);
            $updated++;
        }

        if ($updated > 0) {
            Log::info('billing:sync-all-subscriptions — Sincronizadas ' . $updated . ' assinatura(s).');
        }

        return self::SUCCESS;
    }
}
