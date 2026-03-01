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
        if (!app()->runningInConsole() && request()) {
            $host = request()->getHost();
            $isLocalhostByRequest = in_array($host, ['localhost', '127.0.0.1'], true)
                || str_ends_with($host, '.localhost');
        }

        $isLocalhost = $isLocalhostByConfig || $isLocalhostByRequest;

        if ($isLocalhost) {
            URL::forceScheme('http');
            if ($appUrl && str_starts_with($appUrl, 'https://')) {
                config(['app.url' => str_replace('https://', 'http://', $appUrl)]);
            }
            config(['app.asset_url' => null]);
        } elseif (config('app.env') === 'production') {
            URL::forceScheme('https');
            if ($appUrl && !str_starts_with($appUrl, 'https://')) {
                config(['app.url' => str_replace('http://', 'https://', $appUrl)]);
            }
        }
    }
}
