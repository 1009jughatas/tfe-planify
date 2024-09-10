<?php

namespace App\Http\Controllers;
use App\Models\Project;
use App\Models\Task;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalProjectsCount = Project::count();
        $activeProjectsCount = Project::where('status', '!=', 'finished')
            ->orWhereNull('status')
            ->count();
        $completedProjectsCount = Project::where('status', 'finished')->count();
        $openTasksCount = Task::where('status', '!=', 'completed')->count();
        $tasks = Task::all();

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
