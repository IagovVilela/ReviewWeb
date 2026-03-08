<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        
        // Sincronizar status das assinaturas com o Stripe a cada hora (fallback se o webhook falhar)
        $schedule->command('billing:sync-all-subscriptions')
                 ->hourly()
                 ->withoutOverlapping(10);

        // Enviar relatório semanal de contatos toda segunda-feira às 9:00
        $schedule->command('reviews:send-contacts-export --period=weekly')
                 ->weeklyOn(1, '9:00')
                 ->timezone('America/Sao_Paulo');
        
        // Enviar relatório diário todos os dias às 8:00 (opcional - descomente se necessário)
        // $schedule->command('reviews:send-contacts-export --period=daily')
        //          ->dailyAt('8:00')
        //          ->timezone('America/Sao_Paulo');
        
        // Enviar relatório mensal no primeiro dia do mês às 9:00 (opcional - descomente se necessário)
        // $schedule->command('reviews:send-contacts-export --period=monthly')
        //          ->monthlyOn(1, '9:00')
        //          ->timezone('America/Sao_Paulo');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
