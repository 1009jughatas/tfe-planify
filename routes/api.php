<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TaskStatusController;

Route::prefix('v1')->group(function () {
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('users', UserController::class);
});

// Route pour vérifier les statuts des tâches
Route::post('/tasks/status-check', [TaskStatusController::class, 'statusCheck']);