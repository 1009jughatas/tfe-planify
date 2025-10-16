<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\TaskStatusController;

Route::prefix('v1')->group(function () {
    
    // Routes d'authentification
    Route::post('/auth/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/auth/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/auth/user', [App\Http\Controllers\Api\AuthController::class, 'user'])->middleware('auth:sanctum');
    
    // Routes protégées par authentification
    Route::middleware('auth:sanctum')->group(function () {
        
        // Routes des utilisateurs
        Route::get('/users/profile', [UserController::class, 'profile'])->name('api.users.profile');
        Route::put('/users/profile', [UserController::class, 'updateProfile'])->name('api.users.updateProfile');
        Route::apiResource('users', UserController::class)->names([
            'index' => 'api.users.index',
            'store' => 'api.users.store',
            'show' => 'api.users.show',
            'update' => 'api.users.update',
            'destroy' => 'api.users.destroy'
        ]);
        
        // Routes des projets
        Route::apiResource('projects', ProjectController::class)->names([
            'index' => 'api.projects.index',
            'store' => 'api.projects.store',
            'show' => 'api.projects.show',
            'update' => 'api.projects.update',
            'destroy' => 'api.projects.destroy'
        ]);
        Route::get('/projects/{project}/stats', [ProjectController::class, 'stats'])->name('api.projects.stats');
        
        // Routes des tâches
        Route::apiResource('tasks', TaskController::class)->names([
            'index' => 'api.tasks.index',
            'store' => 'api.tasks.store',
            'show' => 'api.tasks.show',
            'update' => 'api.tasks.update',
            'destroy' => 'api.tasks.destroy'
        ]);
        Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('api.tasks.updateStatus');
        
        // Routes des entreprises (super admin uniquement)
        Route::middleware('superadmin')->group(function () {
            Route::apiResource('companies', CompanyController::class)->names([
                'index' => 'api.companies.index',
                'store' => 'api.companies.store',
                'show' => 'api.companies.show',
                'update' => 'api.companies.update',
                'destroy' => 'api.companies.destroy'
            ]);
            Route::get('/companies/{company}/users', [CompanyController::class, 'users'])->name('api.companies.users');
            Route::get('/companies/{company}/stats', [CompanyController::class, 'stats'])->name('api.companies.stats');
        });
        
        // Routes des abonnements
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('api.subscriptions.index');
        Route::get('/subscriptions/plans', [SubscriptionController::class, 'plans'])->name('api.subscriptions.plans');
        Route::put('/subscriptions/cancel', [SubscriptionController::class, 'cancel'])->name('api.subscriptions.cancel');
        
        // Routes des tickets support
        Route::apiResource('tickets', TicketController::class)->names([
            'index' => 'api.tickets.index',
            'store' => 'api.tickets.store',
            'show' => 'api.tickets.show',
            'update' => 'api.tickets.update',
            'destroy' => 'api.tickets.destroy'
        ]);
        Route::post('/tickets/{ticket}/respond', [TicketController::class, 'respond'])->name('api.tickets.respond');
        Route::put('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('api.tickets.close');
        Route::put('/tickets/{ticket}/reopen', [TicketController::class, 'reopen'])->name('api.tickets.reopen');
        Route::get('/tickets/stats', [TicketController::class, 'stats'])->name('api.tickets.stats');
        
        // Routes des exports
        Route::get('/exports/projects', [ExportController::class, 'projects'])->name('api.exports.projects');
        Route::get('/exports/tasks', [ExportController::class, 'tasks'])->name('api.exports.tasks');
        Route::get('/exports/dashboard', [ExportController::class, 'dashboard'])->name('api.exports.dashboard');
        Route::get('/exports/formats', [ExportController::class, 'formats'])->name('api.exports.formats');
        
        // Routes des exports entreprise (super admin uniquement)
        Route::middleware('superadmin')->group(function () {
            Route::get('/exports/companies/{company}', [ExportController::class, 'company'])->name('api.exports.companies');
        });
    });
});

// Route pour vérifier les statuts des tâches
Route::post('/tasks/status-check', [TaskStatusController::class, 'statusCheck']);