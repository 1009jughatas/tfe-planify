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
        Route::apiResource('users', UserController::class);
        Route::get('/users/profile', [UserController::class, 'profile']);
        Route::put('/users/profile', [UserController::class, 'updateProfile']);
        
        // Routes des projets
        Route::apiResource('projects', ProjectController::class);
        Route::get('/projects/{project}/stats', [ProjectController::class, 'stats']);
        
        // Routes des tâches
        Route::apiResource('tasks', TaskController::class);
        Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus']);
        
        // Routes des entreprises (super admin uniquement)
        Route::middleware('superadmin')->group(function () {
            Route::apiResource('companies', CompanyController::class);
            Route::get('/companies/{company}/users', [CompanyController::class, 'users']);
            Route::get('/companies/{company}/stats', [CompanyController::class, 'stats']);
        });
        
        // Routes des abonnements
        Route::get('/subscriptions', [SubscriptionController::class, 'index']);
        Route::get('/subscriptions/plans', [SubscriptionController::class, 'plans']);
        Route::put('/subscriptions/cancel', [SubscriptionController::class, 'cancel']);
        
        // Routes des tickets support
        Route::apiResource('tickets', TicketController::class);
        Route::post('/tickets/{ticket}/respond', [TicketController::class, 'respond']);
        Route::put('/tickets/{ticket}/close', [TicketController::class, 'close']);
        Route::put('/tickets/{ticket}/reopen', [TicketController::class, 'reopen']);
        Route::get('/tickets/stats', [TicketController::class, 'stats']);
        
        // Routes des exports
        Route::get('/exports/projects', [ExportController::class, 'projects']);
        Route::get('/exports/tasks', [ExportController::class, 'tasks']);
        Route::get('/exports/dashboard', [ExportController::class, 'dashboard']);
        Route::get('/exports/formats', [ExportController::class, 'formats']);
        
        // Routes des exports entreprise (super admin uniquement)
        Route::middleware('superadmin')->group(function () {
            Route::get('/exports/companies/{company}', [ExportController::class, 'company']);
        });
    });
});

// Route pour vérifier les statuts des tâches
Route::post('/tasks/status-check', [TaskStatusController::class, 'statusCheck']);