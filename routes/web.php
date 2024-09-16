<?php
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes for Admin (only admins can create, edit, and delete projects)
Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::resource('projects', ProjectController::class)->except(['index', 'show']);
});

// Routes pour tous les users connecte
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/export-report', [DashboardController::class, 'exportReport'])->name('dashboard.exportReport');

    // Premium Routes
    Route::get('/premium', [PaymentController::class, 'show'])->name('premium.show');
    Route::post('/premium/purchase', [PaymentController::class, 'purchase'])->name('premium.purchase');
    Route::get('/premium/success', [PaymentController::class, 'success'])->name('premium.success');

    // Project Routes 
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/tasks', [ProjectController::class, 'tasks'])->name('projects.tasks');

    // Task Routes
    Route::get('/projects/{project}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::resource('tasks', TaskController::class)->except(['create', 'store']);
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';