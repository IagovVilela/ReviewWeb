<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\StripeService;
use Illuminate\Console\Command;

class BillingSyncSubscriptionFromStripe extends Command
{
    protected $signature = 'billing:sync-subscription {email : E-mail do usuário}';

    protected $description = 'Sincroniza o status da assinatura do usuário com o Stripe (útil quando o webhook não atualizou, ex.: após cancelamento)';

    public function handle(StripeService $stripeService): int
    {
        $user = User::where('email', $this->argument('email'))->first();
        if (!$user) {
            $this->error('Usuário não encontrado.');
            return self::FAILURE;
        }

        if (!$user->stripe_subscription_id) {
            $this->warn("Usuário {$user->email} não possui stripe_subscription_id. Nada a sincronizar.");
            return self::SUCCESS;
        }

        if (!config('stripe.secret') || !class_exists(\Stripe\Subscription::class)) {
            $this->error('Stripe não configurado (STRIPE_SECRET ou SDK).');
            return self::FAILURE;
        }

        try {
            \Stripe\Stripe::setApiKey(config('stripe.secret'));
            $subscription = \Stripe\Subscription::retrieve($user->stripe_subscription_id);
        } catch (\Throwable $e) {
            $this->error('Erro ao buscar assinatura no Stripe: ' . $e->getMessage());
            return self::FAILURE;
        }

        // Status do Stripe: "active" até o fim do período mesmo com cancel_at_period_end; depois vira "canceled"
        $stripeService->updateUserSubscriptionStatus($user, $subscription->id, $subscription->status);
        $this->info("Status sincronizado para: {$subscription->status} (cancel_at_period_end: " . ($subscription->cancel_at_period_end ? 'sim' : 'não') . ').');
        return self::SUCCESS;
    }
}
