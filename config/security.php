<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | Configuration des paramètres de sécurité de l'application
    |
    */

    // Limitation du nombre de projets pour les utilisateurs non-premium
    'project_limit_non_premium' => env('PROJECT_LIMIT_NON_PREMIUM', 2),

    // Limitation du nombre de tâches par projet
    'task_limit_per_project' => env('TASK_LIMIT_PER_PROJECT', 100),

    // Durée de vie des sessions en minutes
    'session_lifetime' => env('SESSION_LIFETIME', 120),

    // Nombre maximum de tentatives de connexion
    'login_max_attempts' => env('LOGIN_MAX_ATTEMPTS', 5),

    // Durée du blocage après dépassement (en minutes)
    'login_decay_minutes' => env('LOGIN_DECAY_MINUTES', 1),

    // Validation des mots de passe
    'password' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => false,
    ],

    // Protection XSS
    'xss_protection' => [
        'enabled' => true,
        'escape_html' => true,
    ],

    // En-têtes de sécurité
    'headers' => [
        'x_frame_options' => 'SAMEORIGIN',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'strict_transport_security' => 'max-age=31536000; includeSubDomains',
        'referrer_policy' => 'strict-origin-when-cross-origin',
    ],

    // Content Security Policy
    'csp' => [
        'default_src' => "'self'",
        'script_src' => "'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://fonts.bunny.net https://cdnjs.cloudflare.com",
        'style_src' => "'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.bunny.net https://cdnjs.cloudflare.com",
        'img_src' => "'self' data: https:",
        'font_src' => "'self' https://fonts.bunny.net https://cdnjs.cloudflare.com",
        'connect_src' => "'self'",
        'frame_ancestors' => "'self'",
    ],

    // Permissions de l'API
    'api' => [
        'rate_limit' => env('API_RATE_LIMIT', 60), // Requêtes par minute
        'throttle' => true,
    ],

    // Journalisation des événements de sécurité
    'logging' => [
        'log_failed_logins' => true,
        'log_unauthorized_access' => true,
        'log_suspicious_activity' => true,
    ],

    // IP whitelisting (optionnel)
    'ip_whitelist' => [
        'enabled' => env('IP_WHITELIST_ENABLED', false),
        'ips' => explode(',', env('IP_WHITELIST', '')),
    ],

    // Protection contre les attaques par énumération
    'anti_enumeration' => [
        'generic_error_messages' => env('APP_ENV') === 'production',
    ],

];
