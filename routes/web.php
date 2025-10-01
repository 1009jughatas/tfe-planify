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

// Routes for Admin
Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    // Admin Dashboard
    Route::get('/admin', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/logs', [App\Http\Controllers\Admin\DashboardController::class, 'logs'])->name('admin.logs');
    Route::get('/admin/statistics', [App\Http\Controllers\Admin\DashboardController::class, 'statistics'])->name('admin.statistics');

    // User Management
    Route::get('/admin/users', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/admin/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/users/{user}/toggle-premium', [App\Http\Controllers\Admin\UserManagementController::class, 'togglePremium'])->name('admin.users.toggle-premium');
    Route::post('/admin/users/{user}/change-role', [App\Http\Controllers\Admin\UserManagementController::class, 'changeRole'])->name('admin.users.change-role');

    // Legal Content Management
    Route::get('/admin/legal', [App\Http\Controllers\Admin\LegalContentController::class, 'index'])->name('admin.legal.index');
    Route::post('/admin/legal', [App\Http\Controllers\Admin\LegalContentController::class, 'update'])->name('admin.legal.update');
});

// Routes pour tous les users connecte
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/export-report', [DashboardController::class, 'exportReport'])->name('dashboard.exportReport');

    // Premium Routes
    Route::get('/premium', [PaymentController::class, 'show'])->name('premium.show');
    Route::post('/premium/purchase', [PaymentController::class, 'purchase'])->name('premium.purchase');
    Route::get('/premium/success', [PaymentController::class, 'success'])->name('premium.success');

    // Project Routes - Tous les utilisateurs authentifiés
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
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
    
    // Test Responsive Route
    Route::get('/test-responsive', function () {
        return view('test-responsive');
    })->name('test.responsive');

    // User Preferences Routes (Premium uniquement)
    Route::get('/preferences', [App\Http\Controllers\UserPreferenceController::class, 'edit'])->name('preferences.edit');
    Route::patch('/preferences', [App\Http\Controllers\UserPreferenceController::class, 'update'])->name('preferences.update');

    // Attachment Routes (Premium uniquement)
    Route::post('/attachments', [App\Http\Controllers\AttachmentController::class, 'store'])->name('attachments.store');
    Route::get('/attachments/{attachment}/download', [App\Http\Controllers\AttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('/attachments/{attachment}', [App\Http\Controllers\AttachmentController::class, 'destroy'])->name('attachments.destroy');
});

    Route::view('/mentions-legales', 'legal')->name('mentions.legales');


require __DIR__.'/auth.php';