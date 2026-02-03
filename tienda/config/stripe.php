<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe Keys
    |--------------------------------------------------------------------------
    |
    | Las claves de API de Stripe. Obtén estas claves desde tu panel de Stripe:
    | https://dashboard.stripe.com/apikeys
    |
    */
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Stripe Webhooks
    |--------------------------------------------------------------------------
    |
    | El secreto del webhook para verificar las solicitudes de Stripe.
    |
    */
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | La moneda predeterminada para los pagos.
    |
    */
    'currency' => env('STRIPE_CURRENCY', 'eur'),
];
