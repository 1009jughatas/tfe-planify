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
        
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Aucune entreprise associée à votre compte.');
        }

        // Statistiques générales
        $totalProjects = Project::where('company_id', $company->id)->count();
        $activeProjects = Project::where('company_id', $company->id)
            ->where('status', 'in-progress')
            ->count();
        $completedProjects = Project::where('company_id', $company->id)
            ->where('status', 'completed')
            ->count();

        // Statistiques des tâches
        $totalTasks = Task::where('company_id', $company->id)->count();
        $openTasks = Task::where('company_id', $company->id)
            ->whereIn('status', ['pending', 'in-progress'])
            ->count();
        $completedTasks = Task::where('company_id', $company->id)
            ->where('status', 'completed')
            ->count();

        // Utilisateurs de l'entreprise
        $totalUsers = User::where('company_id', $company->id)->count();
        $maxUsers = $company->user_limit ?? 10; // Limite par défaut

        // Projets récents
        $recentProjects = Project::where('company_id', $company->id)
            ->with(['tasks', 'author'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Tâches récentes
        $recentTasks = Task::where('company_id', $company->id)
            ->with(['project', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Projets avec échéances proches
        $upcomingDeadlines = Project::where('company_id', $company->id)
            ->whereNotNull('end_date')
            ->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addDays(7))
            ->orderBy('end_date', 'asc')
            ->take(5)
            ->get();

        // Tâches en retard
        $overdueTasks = Task::where('company_id', $company->id)
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->with(['project'])
            ->orderBy('due_date', 'asc')
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
            'upcomingDeadlines',
            'overdueTasks'
        ));
    }
}
