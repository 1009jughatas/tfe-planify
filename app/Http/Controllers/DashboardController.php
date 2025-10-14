<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\Task;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Rediriger selon le rôle
        if ($user->company_id) {
            // L'utilisateur appartient à une entreprise
            return $this->entrepriseDashboard($user);
        } else {
            // L'utilisateur est indépendant
            return $this->independantDashboard($user);
        }
    }

    /**
     * Dashboard pour les utilisateurs indépendants
     */
    public function independantDashboard($user = null)
    {
        $user = $user ?? auth()->user();
        
        // Récupérer les projets de l'utilisateur indépendant
        $projects = $user->projects()->get();
        
        // Statistiques
        $activeProjectsCount = $projects->where('status', '!=', 'completed')->count();
        $completedProjectsCount = $projects->where('status', 'completed')->count();
        $totalProjectsCount = $projects->count();

        // Tâches
        $projectIds = $projects->pluck('id');
        $tasks = Task::whereIn('project_id', $projectIds)->get();
        $pendingTasks = $tasks->whereIn('status', ['pending', 'in_progress'])->count();
        $completedTasks = $tasks->where('status', 'completed')->count();
        
        // Progression
        $progressPercentage = $tasks->count() > 0 ? round(($completedTasks / $tasks->count()) * 100) : 0;

        // Projets récents (5 derniers)
        $recentProjects = $projects->sortByDesc('created_at')->take(5);

        // Tâches urgentes (avec deadline proche)
        $urgentTasks = $tasks->where('deadline', '<=', now()->addDays(3))->where('status', '!=', 'completed')->take(5);
        
        // Variables supplémentaires pour le nouveau dashboard
        $openTasksCount = $tasks->whereIn('status', ['todo', 'pending', 'in-progress'])->count();

        return view('dashboard', compact(
            'projects', 'activeProjectsCount', 'completedProjectsCount', 'totalProjectsCount',
            'pendingTasks', 'completedTasks', 'progressPercentage', 'recentProjects', 'urgentTasks',
            'tasks', 'openTasksCount'
        ));
    }

    /**
     * Dashboard pour les utilisateurs d'entreprise
     */
    public function entrepriseDashboard()
    {
        $user = auth()->user();
        
        // Récupérer les projets de l'entreprise
        $companyProjects = $user->company->projects()->get();
        
        // Statistiques
        $activeProjectsCount = $companyProjects->where('status', '!=', 'completed')->count();
        $completedProjectsCount = $companyProjects->where('status', 'completed')->count();
        $totalProjectsCount = $companyProjects->count();

        // Tâches assignées à l'utilisateur
        $assignedTasks = Task::where('assigned_to', $user->id)->get();
        $pendingTasks = $assignedTasks->whereIn('status', ['pending', 'in_progress'])->count();
        $completedTasks = $assignedTasks->where('status', 'completed')->count();
        
        // Progression
        $progressPercentage = $assignedTasks->count() > 0 ? round(($completedTasks / $assignedTasks->count()) * 100) : 0;

        // Projets récents de l'entreprise (5 derniers)
        $recentProjects = $companyProjects->sortByDesc('created_at')->take(5);

        return view('entreprise.dashboard', compact(
            'companyProjects', 'activeProjectsCount', 'completedProjectsCount', 'totalProjectsCount',
            'assignedTasks', 'pendingTasks', 'completedTasks', 'progressPercentage', 'recentProjects'
        ));
    }

    public function exportReport()
    {
        $activeProjects = Project::where('status', '!=', 'finished')->orWhereNull('status')->get();
        $completedProjects = Project::where('status', 'finished')->get();
        $openTasksCount = Task::where('status', '!=', 'done')->count();
        $tasks = Task::all();

        $data = [
            'activeProjects' => $activeProjects,
            'completedProjects' => $completedProjects,
            'openTasksCount' => $openTasksCount,
            'tasks' => $tasks,
        ];

        $pdf = PDF::loadView('projects.report', $data);
        return $pdf->download('projects_report.pdf');
    }
}
