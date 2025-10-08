<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\CompanyInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
class CompanyAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vérifier que l'utilisateur est un admin d'entreprise
     */
    private function checkCompanyAdmin()
    {
        if (!Auth::user()->isCompanyAdmin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs d\'entreprise peuvent accéder à cette section.');
        }
    }

    /**
     * Dashboard principal du chef d'équipe
     */
    public function dashboard()
    {
        $this->checkCompanyAdmin();
        $company = Auth::user()->company;
        
        // Statistiques générales
        $stats = [
            'total_users' => $company->users()->count(),
            'active_projects' => $company->projects()->whereIn('status', ['active', 'planning'])->count(),
            'completed_projects' => $company->projects()->where('status', 'completed')->count(),
            'overdue_tasks' => Task::whereHas('project', function($query) use ($company) {
                $query->where('company_id', $company->id);
            })->where('due_date', '<', now())->where('status', '!=', 'done')->count(),
            'total_tasks' => Task::whereHas('project', function($query) use ($company) {
                $query->where('company_id', $company->id);
            })->count(),
        ];

        // Dernières activités
        $recentActivities = collect();
        
        // Derniers projets créés
        $recentProjects = $company->projects()->with('author')->latest()->limit(5)->get();
        foreach ($recentProjects as $project) {
            $authorName = $project->author ? $project->author->name : 'Utilisateur inconnu';
            $recentActivities->push([
                'type' => 'project_created',
                'message' => "Projet \"{$project->name}\" créé par {$authorName}",
                'time' => $project->created_at,
                'icon' => 'fas fa-folder-plus',
                'color' => 'blue'
            ]);
        }

        // Dernières tâches complétées
        $recentTasks = Task::whereHas('project', function($query) use ($company) {
            $query->where('company_id', $company->id);
        })->where('status', 'done')->with(['author', 'project'])->latest()->limit(5)->get();
        
        foreach ($recentTasks as $task) {
            $projectName = $task->project ? $task->project->name : 'Projet inconnu';
            $recentActivities->push([
                'type' => 'task_completed',
                'message' => "Tâche \"{$task->title}\" terminée dans le projet \"{$projectName}\"",
                'time' => $task->updated_at,
                'icon' => 'fas fa-check-circle',
                'color' => 'green'
            ]);
        }

        // Trier par date et prendre les 10 dernières
        $recentActivities = $recentActivities->sortByDesc('time')->take(10);

        // Utilisateurs récents
        $recentUsers = $company->users()->latest()->limit(5)->get();

        return view('company-admin.dashboard', compact('stats', 'recentActivities', 'recentUsers', 'company'));
    }

    /**
     * Gestion des utilisateurs de l'équipe
     */
    public function users()
    {
        $this->checkCompanyAdmin();
        $company = Auth::user()->company;
        $users = $company->users()->with('projects')->paginate(15);
        $invitations = $company->invitations()->where('expires_at', '>', now())->get();

        return view('company-admin.users.index', compact('users', 'invitations', 'company'));
    }

    /**
     * Inviter un nouvel utilisateur
     */
    public function inviteUser(Request $request)
    {
        $this->checkCompanyAdmin();
        $company = Auth::user()->company;
        
        $request->validate([
            'email' => 'required|email|unique:users,email|unique:company_invitations,email',
            'role' => 'required|in:member,company_admin',
        ]);

        // Vérifier la limite d'utilisateurs
        if ($company->user_limit !== null && $company->users()->count() >= $company->user_limit) {
            return back()->with('error', 'Vous avez atteint la limite d\'utilisateurs pour votre plan actuel.');
        }

        $token = Str::random(40);
        $invitation = CompanyInvitation::create([
            'company_id' => $company->id,
            'email' => $request->email,
            'token' => $token,
            'role' => $request->role,
            'expires_at' => now()->addDays(7),
        ]);

        // TODO: Envoyer l'email d'invitation
        // Mail::to($request->email)->send(new CompanyInvitationMail($invitation));

        return back()->with('success', "Invitation envoyée à {$request->email}");
    }

    /**
     * Modifier le rôle d'un utilisateur
     */
    public function updateUserRole(Request $request, User $user)
    {
        $this->checkCompanyAdmin();
        $company = Auth::user()->company;
        
        // Vérifier que l'utilisateur appartient à la même entreprise
        if ($user->company_id !== $company->id) {
            abort(403, 'Utilisateur non autorisé');
        }

        // Ne pas permettre de modifier son propre rôle
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $request->validate([
            'role' => 'required|in:member,company_admin',
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', "Rôle de {$user->name} mis à jour avec succès");
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteUser(User $user)
    {
        $this->checkCompanyAdmin();
        $company = Auth::user()->company;
        
        // Vérifier que l'utilisateur appartient à la même entreprise
        if ($user->company_id !== $company->id) {
            abort(403, 'Utilisateur non autorisé');
        }

        // Ne pas permettre de se supprimer soi-même
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return back()->with('success', "Utilisateur {$user->name} supprimé avec succès");
    }

    /**
     * Gestion des projets de l'équipe
     */
    public function projects()
    {
        $this->checkCompanyAdmin();
        $company = Auth::user()->company;
        $projects = $company->projects()->with(['author', 'participants'])->paginate(15);
        $users = $company->users()->get();

        return view('company-admin.projects.index', compact('projects', 'users', 'company'));
    }

    /**
     * Gestion des tâches de l'équipe
     */
    public function tasks()
    {
        $this->checkCompanyAdmin();
        $company = Auth::user()->company;
        
        $tasks = Task::whereHas('project', function($query) use ($company) {
            $query->where('company_id', $company->id);
        })->with(['project', 'author', 'assignedTo'])->paginate(20);

        $projects = $company->projects()->get();
        $users = $company->users()->get();

        return view('company-admin.tasks.index', compact('tasks', 'projects', 'users', 'company'));
    }

    /**
     * Page d'abonnement
     */
    public function subscription()
    {
        $this->checkCompanyAdmin();
        $company = Auth::user()->company;
        
        return view('company-admin.subscription.index', compact('company'));
    }

    /**
     * Page des paramètres de l'entreprise
     */
    public function settings()
    {
        $this->checkCompanyAdmin();
        $company = Auth::user()->company;
        
        return view('company-admin.settings.index', compact('company'));
    }

    /**
     * Mettre à jour les paramètres de l'entreprise
     */
    public function updateSettings(Request $request)
    {
        $this->checkCompanyAdmin();
        $company = Auth::user()->company;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
        ]);

        $company->update($request->only(['name', 'email', 'phone', 'address', 'website']));

        return back()->with('success', 'Paramètres de l\'entreprise mis à jour avec succès');
    }
}
