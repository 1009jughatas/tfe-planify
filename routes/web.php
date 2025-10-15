<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyInvitationController;
use App\Http\Controllers\CompanyAdminController;
use App\Http\Controllers\AuthIndepController;
use App\Http\Controllers\AuthEntrepriseController;
use App\Http\Controllers\EmployeeInvitationController;
use App\Http\Controllers\Entreprise\EntrepriseDashboardController;
use App\Http\Controllers\Entreprise\EntrepriseProjetController;
use App\Http\Controllers\Entreprise\EntrepriseUserController;
use App\Http\Controllers\Entreprise\EntrepriseSubscriptionController;
use App\Http\Middleware\IsIndependant;
use App\Http\Middleware\IsEntreprise;
use App\Http\Middleware\IsAdminEntreprise;
use App\Http\Middleware\IsUserEntreprise;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// ========================================
// AUTHENTIFICATION INDÉPENDANTS
// ========================================
Route::prefix('')->group(function () {
    // Inscription indépendant
    Route::get('/register', [AuthIndepController::class, 'showRegister'])->name('register.indep');
    Route::post('/register', [AuthIndepController::class, 'register']);
    
    // Connexion indépendant
    Route::get('/login', [AuthIndepController::class, 'showLogin'])->name('login.indep');
    Route::post('/login', [AuthIndepController::class, 'login']);
    
    // Déconnexion indépendant
    Route::post('/logout', [AuthIndepController::class, 'logout'])->name('logout.indep');
});

// ========================================
// AUTHENTIFICATION ENTREPRISE
// ========================================
Route::prefix('entreprise')->name('entreprise.')->group(function () {
    // Inscription entreprise
    Route::get('/register', [AuthEntrepriseController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthEntrepriseController::class, 'register']);
    
    // Connexion entreprise
    Route::get('/login', [AuthEntrepriseController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthEntrepriseController::class, 'login']);
    
    // Déconnexion entreprise
    Route::post('/logout', [AuthEntrepriseController::class, 'logout'])->name('logout');
    
    // Paiement Stripe
    Route::get('/payment/success', [AuthEntrepriseController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/failed', [AuthEntrepriseController::class, 'paymentFailed'])->name('payment.failed');
    Route::post('/stripe/webhook', [AuthEntrepriseController::class, 'stripeWebhook'])->name('stripe.webhook');
});

// ========================================
// ROUTES ENTREPRISE (PROTÉGÉES)
// ========================================
Route::prefix('entreprise')->name('entreprise.')->middleware(['auth', 'checkEntrepriseAccess'])->group(function () {
    // Dashboard entreprise
    Route::get('/dashboard', [EntrepriseDashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des projets
    Route::resource('projets', EntrepriseProjetController::class);
    Route::patch('/projets/{projet}/update-status', [EntrepriseProjetController::class, 'updateStatus'])->name('projets.updateStatus');
    
    // Routes de tâches pour les projets d'entreprise
    Route::get('/projects/{project}/tasks', [ProjectController::class, 'tasks'])->name('projects.tasks');
    Route::get('/projects/{project}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::resource('tasks', TaskController::class)->except(['create', 'store']);
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::patch('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    
    // Profile Routes - Entreprise
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Gestion des utilisateurs (Admin seulement)
    Route::middleware('ensureUserIsAdminEntreprise')->group(function () {
        Route::get('/utilisateurs', [EntrepriseUserController::class, 'index'])->name('utilisateurs.index');
        Route::get('/utilisateurs/inviter', [EntrepriseUserController::class, 'createInvitation'])->name('utilisateurs.inviter');
        Route::post('/utilisateurs/inviter', [EntrepriseUserController::class, 'sendInvitation'])->name('utilisateurs.inviter.store');
        Route::delete('/utilisateurs/{user}', [EntrepriseUserController::class, 'destroy'])->name('utilisateurs.destroy');
        Route::patch('/utilisateurs/{user}/role', [EntrepriseUserController::class, 'updateRole'])->name('utilisateurs.update-role');
        Route::get('/utilisateurs/{user}/permissions', [EntrepriseUserController::class, 'showPermissions'])->name('utilisateurs.permissions');
        Route::patch('/utilisateurs/{user}/permissions', [EntrepriseUserController::class, 'updatePermissions'])->name('utilisateurs.update-permissions');
        Route::delete('/invitations/{invitation}/cancel', [EntrepriseUserController::class, 'cancelInvitation'])->name('invitations.cancel');
        
        // Gestion des abonnements
        Route::get('/abonnement', [EntrepriseSubscriptionController::class, 'index'])->name('abonnement.index');
        Route::patch('/abonnement', [EntrepriseSubscriptionController::class, 'update'])->name('abonnement.update');
        Route::delete('/abonnement', [EntrepriseSubscriptionController::class, 'cancel'])->name('abonnement.cancel');
    });
    
    // Routes de préférences pour les utilisateurs d'entreprise
    Route::get('/preferences', [App\Http\Controllers\UserPreferenceController::class, 'edit'])->name('preferences.edit');
    Route::patch('/preferences', [App\Http\Controllers\UserPreferenceController::class, 'update'])->name('preferences.update');
});

// ========================================
// INVITATIONS D'EMPLOYÉS (ACCESSIBLES SANS AUTH)
// ========================================
Route::get('/invitations/{token}', [EmployeeInvitationController::class, 'showAcceptInvitation'])->name('invitations.accept');
Route::post('/invitations/{token}', [EmployeeInvitationController::class, 'acceptInvitation'])->name('invitations.accept.store');
Route::delete('/invitations/{token}/decline', [EmployeeInvitationController::class, 'declineInvitation'])->name('invitations.decline');

// ========================================
// ROUTE DE DEBUG TEMPORAIRE
// ========================================
Route::get('/debug-entreprise', function () {
    $usersWithCompany = \App\Models\User::whereNotNull('company_id')->get();
    $companies = \App\Models\Company::all();
    $adminUsers = \App\Models\User::where('role', 'admin_entreprise')->get();
    
    $debugInfo = [
        'users_with_company' => $usersWithCompany->map(function($user) {
            $company = \App\Models\Company::find($user->company_id);
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'company_id' => $user->company_id,
                'company_exists' => $company ? true : false,
                'company_name' => $company ? $company->name : null
            ];
        }),
        'companies' => $companies->map(function($company) {
            return [
                'id' => $company->id,
                'name' => $company->name,
                'plan' => $company->plan,
                'user_limit' => $company->user_limit,
                'admin_id' => $company->admin_id
            ];
        }),
        'admin_users' => $adminUsers->map(function($user) {
            $company = $user->company;
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'company_id' => $user->company_id,
                'company_relation_works' => $company ? true : false,
                'company_name' => $company ? $company->name : null
            ];
        })
    ];
    
    return response()->json($debugInfo, 200, [], JSON_PRETTY_PRINT);
})->name('debug.entreprise');

Route::get('/create-test-entreprise', function () {
    // Créer un utilisateur admin entreprise de test
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'admin@test-entreprise.com'],
        [
            'name' => 'Admin Test Entreprise',
            'password' => bcrypt('password'),
            'role' => 'admin_entreprise'
        ]
    );
    
    // Créer une company de test
    $company = \App\Models\Company::firstOrCreate(
        ['name' => 'Test Entreprise SARL'],
        [
            'slug' => 'test-entreprise-sarl',
            'email' => 'contact@test-entreprise.com',
            'plan' => 'starter',
            'user_limit' => 5,
            'status' => 'active',
            'admin_id' => $user->id
        ]
    );
    
    // Associer l'utilisateur à la company
    $user->company_id = $company->id;
    $user->save();
    
    return response()->json([
        'message' => 'Test entreprise créée avec succès',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'company_id' => $user->company_id
        ],
        'company' => [
            'id' => $company->id,
            'name' => $company->name,
            'plan' => $company->plan,
            'user_limit' => $company->user_limit
        ]
    ], 200, [], JSON_PRETTY_PRINT);
})->name('create.test.entreprise');

// ========================================
// ROUTES D'EXPORT (ACCESSIBLES À TOUS LES UTILISATEURS AUTHENTIFIÉS)
// ========================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Export routes (Premium only)
    Route::get('/export/projects', [ExportController::class, 'exportProjects'])->name('export.projects');
    Route::get('/export/tasks', [ExportController::class, 'exportTasks'])->name('export.tasks');
    Route::get('/export/dashboard', [ExportController::class, 'exportDashboard'])->name('export.dashboard');
});

// ROUTES INDÉPENDANTS (PROTÉGÉES)
// ========================================
Route::middleware(['auth', 'verified', IsIndependant::class])->group(function () {
    // Dashboard indépendant
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/export-report', [DashboardController::class, 'exportReport'])->name('dashboard.exportReport');

    // Premium Routes pour indépendants
    Route::get('/premium', [PaymentController::class, 'show'])->name('premium.show');
    Route::post('/premium/purchase', [PaymentController::class, 'purchase'])->name('premium.purchase');
    Route::get('/premium/success', [PaymentController::class, 'success'])->name('premium.success');
    
    // Theme routes (Premium only)
    Route::post('/theme/toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');
    Route::get('/theme', [ThemeController::class, 'get'])->name('theme.get');
    Route::post('/theme/set', [ThemeController::class, 'set'])->name('theme.set');
    

    // Project Routes - Indépendants uniquement
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/projects/{project}/tasks', [ProjectController::class, 'tasks'])->name('projects.tasks');
    Route::patch('/projects/{project}/update-status', [ProjectController::class, 'updateStatus'])->name('projects.updateStatus');
    Route::patch('/projects/{project}/move', [ProjectController::class, 'moveProject'])->name('projects.move');

    // Task Routes - Indépendants uniquement
    Route::get('/projects/{project}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::resource('tasks', TaskController::class)->except(['create', 'store']);
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::patch('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

    // Profile Routes - Indépendants uniquement
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Preferences Routes (Premium uniquement)
    Route::get('/preferences', [App\Http\Controllers\UserPreferenceController::class, 'edit'])->name('preferences.edit');
    Route::patch('/preferences', [App\Http\Controllers\UserPreferenceController::class, 'update'])->name('preferences.update');

    // Attachment Routes (Premium uniquement)
    Route::post('/attachments', [App\Http\Controllers\AttachmentController::class, 'store'])->name('attachments.store');
    Route::get('/attachments/{attachment}/download', [App\Http\Controllers\AttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('/attachments/{attachment}', [App\Http\Controllers\AttachmentController::class, 'destroy'])->name('attachments.destroy');
});

// ========================================
// ROUTES ENTREPRISE (ANCIENNES - SUPPRIMÉES)
// ========================================
// Les routes entreprise ont été déplacées vers les nouveaux contrôleurs
// Voir plus bas dans le fichier pour les nouvelles routes

// ========================================
// ROUTES ADMIN ENTREPRISE (PROTÉGÉES)
// ========================================
Route::middleware(['auth', 'verified', IsAdminEntreprise::class])->prefix('entreprise/admin')->name('entreprise.admin.')->group(function () {
    // Dashboard Admin d'entreprise
    Route::get('/dashboard', [CompanyAdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestion des utilisateurs
    Route::get('/users', [CompanyAdminController::class, 'users'])->name('users');
    Route::get('/users/invite', [EmployeeInvitationController::class, 'showInviteForm'])->name('users.invite');
    Route::post('/users/invite', [EmployeeInvitationController::class, 'sendInvitation'])->name('users.invite');
    Route::post('/users/invitations/{invitation}/resend', [EmployeeInvitationController::class, 'resendInvitation'])->name('users.resend');
    Route::delete('/users/invitations/{invitation}/cancel', [EmployeeInvitationController::class, 'cancelInvitation'])->name('users.cancel');
    Route::patch('/users/{user}/role', [CompanyAdminController::class, 'updateUserRole'])->name('users.role');
    Route::delete('/users/{user}/delete', [CompanyAdminController::class, 'deleteUser'])->name('users.delete');
    
    // Gestion des projets
    Route::get('/projects', [CompanyAdminController::class, 'projects'])->name('projects');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::patch('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::patch('/projects/{project}/update-status', [ProjectController::class, 'updateStatus'])->name('projects.updateStatus');
    Route::patch('/projects/{project}/move', [ProjectController::class, 'moveProject'])->name('projects.move');
    
    // Gestion des tâches
    Route::get('/tasks', [CompanyAdminController::class, 'tasks'])->name('tasks');
    
    // Abonnement
    Route::get('/subscription', [CompanyAdminController::class, 'subscription'])->name('subscription');
    
    // Paramètres
    Route::get('/settings', [CompanyAdminController::class, 'settings'])->name('settings');
    Route::patch('/settings', [CompanyAdminController::class, 'updateSettings'])->name('settings.update');
});

// ========================================
// ROUTES SUPER ADMIN (PROTÉGÉES)
// ========================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::group(['middleware' => function ($request, $next) {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }
        return $next($request);
    }], function () {
        // Gestion des entreprises
        Route::resource('admin/companies', CompanyController::class)->except(['show']);
        Route::get('/admin/companies/{company}', [CompanyController::class, 'show'])->name('admin.companies.show');

        // Legal Content Management
        Route::get('/admin/legal', [App\Http\Controllers\Admin\LegalContentController::class, 'index'])->name('admin.legal.index');
        Route::post('/admin/legal', [App\Http\Controllers\Admin\LegalContentController::class, 'update'])->name('admin.legal.update');
    });
});

// ========================================
// ROUTES POUR LA GESTION D'INVITATIONS (ADMIN ENTREPRISE)
// ========================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::group(['middleware' => function ($request, $next) {
        $user = auth()->user();
        if (!$user->isCompanyAdmin() && !$user->isSuperAdmin()) {
            abort(403);
        }
        return $next($request);
    }], function () {
        Route::post('/companies/{company}/invite', [CompanyController::class, 'inviteUser'])->name('companies.invite');
        Route::post('/invitations/{invitation}/resend', [CompanyInvitationController::class, 'resend'])->name('invitations.resend');
        Route::delete('/invitations/{invitation}', [CompanyInvitationController::class, 'cancel'])->name('invitations.cancel');
    });
});

// ========================================
// ROUTES PUBLIQUES
// ========================================
Route::view('/mentions-legales', 'legal')->name('mentions.legales');

// Test Responsive Route (temporaire)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/test-responsive', function () {
        return view('test-responsive');
    })->name('test.responsive');
});

require __DIR__.'/auth.php';
