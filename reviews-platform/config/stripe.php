<?php

return [

    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    'currency' => env('STRIPE_CURRENCY', 'brl'),

    /*
    |--------------------------------------------------------------------------
    | Subscription price (R$ 99/month)
    | Create a Product and recurring Price in Stripe Dashboard, then set the Price ID here.
    |--------------------------------------------------------------------------
    */
    'subscription_price_id' => env('STRIPE_SUBSCRIPTION_PRICE_ID'),
    'trial_days' => (int) env('STRIPE_TRIAL_DAYS', 0),

];
