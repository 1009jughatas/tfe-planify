<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Statistiques globales
        $stats = [
            'total_users' => User::count(),
            'premium_users' => User::where('is_premium', true)->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'free_users' => User::where('is_premium', false)->where('role', '!=', 'admin')->count(),
            
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            
            'total_tasks' => Task::count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'in_progress_tasks' => Task::where('status', 'in-progress')->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
        ];

        // Utilisateurs récents
        $recentUsers = User::orderBy('created_at', 'desc')->limit(10)->get();

        // Projets récents
        $recentProjects = Project::with('author')->orderBy('created_at', 'desc')->limit(10)->get();

        // Activité par mois (derniers 6 mois)
        $monthlyActivity = DB::table('users')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // Taux de conversion premium
        $conversionRate = $stats['total_users'] > 0 
            ? round(($stats['premium_users'] / $stats['total_users']) * 100, 2) 
            : 0;

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentProjects', 'monthlyActivity', 'conversionRate'));
    }

    /**
     * Display system logs.
     */
    public function logs()
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logFile)) {
            $content = file_get_contents($logFile);
            $lines = array_reverse(explode("\n", $content));
            $logs = array_slice($lines, 0, 100); // Dernières 100 lignes
        }

        return view('admin.logs', compact('logs'));
    }

    /**
     * Display global statistics.
     */
    public function statistics()
    {
        // Statistiques détaillées
        $stats = [
            'users' => [
                'total' => User::count(),
                'premium' => User::where('is_premium', true)->count(),
                'free' => User::where('is_premium', false)->count(),
                'admin' => User::where('role', 'admin')->count(),
                'verified' => User::whereNotNull('email_verified_at')->count(),
            ],
            'projects' => [
                'total' => Project::count(),
                'active' => Project::where('status', 'active')->count(),
                'completed' => Project::where('status', 'completed')->count(),
                'cancelled' => Project::where('status', 'cancelled')->count(),
                'avg_tasks_per_project' => round(Task::count() / (Project::count() ?: 1), 2),
            ],
            'tasks' => [
                'total' => Task::count(),
                'pending' => Task::where('status', 'pending')->count(),
                'in_progress' => Task::where('status', 'in-progress')->count(),
                'completed' => Task::where('status', 'completed')->count(),
                'blocked' => Task::where('status', 'blocked')->count(),
                'overdue' => Task::where('due_date', '<', now())->where('status', '!=', 'completed')->count(),
            ],
        ];

        return view('admin.statistics', compact('stats'));
    }
}
