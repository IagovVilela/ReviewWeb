<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $appUrl = config('app.url', '');
        $isLocalhostByConfig = str_contains($appUrl, 'localhost') || str_contains($appUrl, '127.0.0.1');

        // Em contexto web, tambem verificar o host da requisicao (evita problema com proxy/cache)
        $isLocalhostByRequest = false;
        $host = null;
        $root = null;
        if (!app()->runningInConsole() && request()) {
            $host = request()->getHost();
            $root = request()->getSchemeAndHttpHost();
            $isLocalhostByRequest = in_array($host, ['localhost', '127.0.0.1'], true)
                || str_ends_with($host, '.localhost');
        }

        // IMPORTANTE (ngrok): em requests web, o host da requisição manda.
        // Se APP_URL estiver como localhost, mas o usuário acessa via ngrok (https),
        // não podemos forçar http, senão quebra CSRF/sessão por mismatch de scheme.
        $isLocalhost = app()->runningInConsole()
            ? $isLocalhostByConfig
            : $isLocalhostByRequest;

        // Em ambiente local (localhost) ou túnel (ngrok), gerar URLs com base no host atual
        // para evitar troca de domínio/scheme (causa comum de 419/CSRF ao alternar localhost <-> ngrok).
        if (!app()->runningInConsole() && $host && $root) {
            $isNgrokHost = str_contains($host, 'ngrok');
            if ($isLocalhost || $isNgrokHost) {
                URL::forceRootUrl($root);
                config(['app.url' => $root]);
                config(['app.asset_url' => null]);
            }
        }

        if ($isLocalhost) {
            URL::forceScheme('http');
            if ($appUrl && str_starts_with($appUrl, 'https://')) {
                config(['app.url' => str_replace('https://', 'http://', $appUrl)]);
            }
        } elseif (config('app.env') === 'production') {
            URL::forceScheme('https');
            if ($appUrl && !str_starts_with($appUrl, 'https://')) {
                config(['app.url' => str_replace('http://', 'https://', $appUrl)]);
            }
        }
    }
}
