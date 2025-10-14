<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        
        // Enregistrer les middlewares personnalisés
        $middleware->alias([
            'entreprise' => \App\Http\Middleware\IsUserEntreprise::class,
            'admin.entreprise' => \App\Http\Middleware\IsAdminEntreprise::class,
            'user.independant' => \App\Http\Middleware\IsUserIndependant::class,
            'isIndependant' => \App\Http\Middleware\IsIndependant::class,
            'isEntreprise' => \App\Http\Middleware\IsEntreprise::class,
            'isAdminEntreprise' => \App\Http\Middleware\IsAdminEntreprise::class,
            'isUserEntreprise' => \App\Http\Middleware\IsUserEntreprise::class,
            'checkEntrepriseAccess' => \App\Http\Middleware\CheckEntrepriseAccess::class,
            'ensureUserIsAdminEntreprise' => \App\Http\Middleware\EnsureUserIsAdminEntreprise::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
