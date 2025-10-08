<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'intégration Stripe
    |
    */

    'public_key' => env('STRIPE_PUBLIC_KEY'),
    'secret_key' => env('STRIPE_SECRET_KEY'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    
    'currency' => 'eur',
    
    'plans' => [
        'starter' => [
            'name' => 'Starter',
            'price' => 399,
            'description' => 'Parfait pour les petites équipes',
        ],
        'professional' => [
            'name' => 'Professional', 
            'price' => 599,
            'description' => 'Idéal pour les équipes moyennes',
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'price' => 999,
            'description' => 'Pour les grandes organisations',
        ],
    ],
];
