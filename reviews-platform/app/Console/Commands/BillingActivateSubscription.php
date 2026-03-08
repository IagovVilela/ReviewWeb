<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class BillingActivateSubscription extends Command
{
    protected $signature = 'billing:activate-subscription {email : E-mail do usuário}';

    protected $description = 'Marca a assinatura do usuário como ativa (suporte / quando o webhook não atualizou)';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Usuário com e-mail \"{$email}\" não encontrado.");
            return self::FAILURE;
        }

        $user->update([
            'subscription_status' => 'active',
        ]);

        $this->info("Assinatura ativada para: {$user->name} ({$user->email}). O painel deve liberar o acesso.");
        return self::SUCCESS;
    }
}
