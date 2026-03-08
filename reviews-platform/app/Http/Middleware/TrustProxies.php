<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Trust all proxies (required for Railway, Heroku, and similar PaaS).
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    /**
     * Em localhost/127.0.0.1 nao confiar em X-Forwarded-Proto para evitar
     * que URLs sejam geradas como https e causem "Unsupported SSL request".
     */
    public function handle(Request $request, \Closure $next)
    {
        $host = $request->getHost();
        if ($host === 'localhost' || $host === '127.0.0.1' || str_ends_with($host, '.localhost')) {
            $this->proxies = [];
        }

        return parent::handle($request, $next);
    }
}
