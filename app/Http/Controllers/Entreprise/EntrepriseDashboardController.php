<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;

class EntrepriseDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Utilisateur non authentifié.');
        }
        
        // Debug: Log des informations utilisateur
        \Log::info('Dashboard Entreprise - User Info', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'company_id' => $user->company_id
        ]);
        
        $company = $user->company;
        
        // Debug: Log des informations company
        if ($company) {
            \Log::info('Dashboard Entreprise - Company Info', [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'company_plan' => $company->plan,
                'user_limit' => $company->user_limit
            ]);
        } else {
            \Log::error('Dashboard Entreprise - Company not found', [
                'user_id' => $user->id,
                'user_company_id' => $user->company_id
            ]);
        }
        
        if (!$company) {
            abort(403, 'Aucune entreprise associée à votre compte. User ID: ' . $user->id . ', Company ID: ' . $user->company_id);
        }

        // Statistiques générales
        $totalProjects = Project::where('company_id', $company->id)->count();
        $activeProjects = Project::where('company_id', $company->id)
            ->where('status', 'in-progress')
            ->count();
        $completedProjects = Project::where('company_id', $company->id)
            ->where('status', 'completed')
            ->count();

        // Statistiques des tâches - filtrer selon les permissions
        $tasksQuery = Task::where('company_id', $company->id);
        
        // Pour les employés, ne compter que leurs propres tâches
        if ($user->isUserEntreprise()) {
            $tasksQuery->where(function($query) use ($user) {
                $query->where('author_id', $user->id)
                      ->orWhere('assigned_to', $user->id);
            });
        }
        
        $totalTasks = $tasksQuery->count();
        $openTasks = (clone $tasksQuery)->whereIn('status', ['pending', 'in-progress'])->count();
        $completedTasks = (clone $tasksQuery)->where('status', 'completed')->count();

        // Utilisateurs de l'entreprise
        $totalUsers = User::where('company_id', $company->id)->count();
        $maxUsers = $company->user_limit ?? 10; // Limite par défaut

        // Projets récents
        $recentProjects = Project::where('company_id', $company->id)
            ->with(['tasks', 'author'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Tâches récentes - filtrer selon les permissions
        $recentTasksQuery = Task::where('company_id', $company->id)
            ->with(['project', 'assignedUser']);
        
        // Pour les employés, ne montrer que leurs propres tâches
        if ($user->isUserEntreprise()) {
            $recentTasksQuery->where(function($query) use ($user) {
                $query->where('author_id', $user->id)
                      ->orWhere('assigned_to', $user->id);
            });
        }
        
        $recentTasks = $recentTasksQuery->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Tâches pour le calendrier - toutes les tâches avec dates d'échéance
        $calendarTasksQuery = Task::where('company_id', $company->id)
            ->whereNotNull('due_date')
            ->with(['project', 'assignedUser']);
        
        // Pour les employés, ne montrer que leurs propres tâches
        if ($user->isUserEntreprise()) {
            $calendarTasksQuery->where(function($query) use ($user) {
                $query->where('author_id', $user->id)
                      ->orWhere('assigned_to', $user->id);
            });
        }
        
        $calendarTasks = $calendarTasksQuery->orderBy('due_date', 'asc')->get();

        // Projets avec échéances proches
        $upcomingDeadlines = Project::where('company_id', $company->id)
            ->whereNotNull('end_date')
            ->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addDays(7))
            ->orderBy('end_date', 'asc')
            ->take(5)
            ->get();

        // Tâches en retard - filtrer selon les permissions
        $overdueTasksQuery = Task::where('company_id', $company->id)
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->with(['project']);
        
        // Pour les employés, ne montrer que leurs propres tâches
        if ($user->isUserEntreprise()) {
            $overdueTasksQuery->where(function($query) use ($user) {
                $query->where('author_id', $user->id)
                      ->orWhere('assigned_to', $user->id);
            });
        }
        
        $overdueTasks = $overdueTasksQuery->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        return view('entreprise.dashboard', compact(
            'company',
            'totalProjects',
            'activeProjects',
            'completedProjects',
            'totalTasks',
            'openTasks',
            'completedTasks',
            'totalUsers',
            'maxUsers',
            'recentProjects',
            'recentTasks',
            'calendarTasks',
            'upcomingDeadlines',
            'overdueTasks'
        ));
    }
}
