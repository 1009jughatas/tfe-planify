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

        if ($user->is_admin()) {
            // Admin voit tout
            $activeProjectsCount = Project::where('status', 'active')->count();
            $completedProjectsCount = Project::where('status', 'completed')->count();
            $openTasksCount = Task::whereIn('status', ['pending', 'in-progress'])->count();
            $tasks = Task::all();
        } else {
            // Utilisateur voit ses projets + ceux où il participe
            $ownProjectIds = $user->projects()->pluck('projects.id');
            $participatingProjectIds = $user->participatingProjects()->pluck('projects.id');
            $allProjectIds = $ownProjectIds->merge($participatingProjectIds)->unique();

            $activeProjectsCount = Project::whereIn('id', $allProjectIds)
                ->where('status', 'active')
                ->count();
            $completedProjectsCount = Project::whereIn('id', $allProjectIds)
                ->where('status', 'completed')
                ->count();
            $openTasksCount = Task::whereIn('project_id', $allProjectIds)
                ->whereIn('status', ['pending', 'in-progress'])
                ->count();
            $tasks = Task::whereIn('project_id', $allProjectIds)->get();
        }

        $totalProjectsCount = $activeProjectsCount + $completedProjectsCount;

        return view('dashboard', compact('activeProjectsCount', 'completedProjectsCount', 'totalProjectsCount', 'openTasksCount', 'tasks'));
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
