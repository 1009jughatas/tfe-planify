<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    /**
     * Export projects data to PDF
     */
    public function exportProjects(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier les permissions d'export PDF
        if (!$user || !$user->canExportPdf()) {
            return response()->json([
                'error' => 'Accès refusé. Vous n\'avez pas l\'autorisation d\'exporter des fichiers PDF.'
            ], 403);
        }

        // Récupérer les projets selon le type d'utilisateur
        if ($user->isUserEntreprise() && $user->company_id) {
            // Pour les employés d'entreprise, récupérer les projets de l'entreprise
            $projects = Project::where('company_id', $user->company_id)
                ->with(['tasks' => function($query) {
                    $query->with('comments');
                }])
                ->get();
        } else {
            // Pour les utilisateurs indépendants, récupérer leurs propres projets
            $projects = Project::where('author_id', $user->id)
                ->with(['tasks' => function($query) {
                    $query->with('comments');
                }])
                ->get();
        }

        $data = [
            'user' => $user,
            'projects' => $projects,
            'export_date' => Carbon::now()->format('d/m/Y à H:i'),
        ];

        $filename = 'projets_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';
        
        $pdf = Pdf::loadView('exports.projects', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download($filename);
    }

    /**
     * Export tasks data to PDF
     */
    public function exportTasks(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier les permissions d'export PDF
        if (!$user || !$user->canExportPdf()) {
            return response()->json([
                'error' => 'Accès refusé. Vous n\'avez pas l\'autorisation d\'exporter des fichiers PDF.'
            ], 403);
        }

        // Récupérer les tâches selon le type d'utilisateur
        if ($user->isUserEntreprise() && $user->company_id) {
            // Pour les employés d'entreprise, récupérer les tâches de l'entreprise
            $tasks = Task::whereHas('project', function($query) use ($user) {
                    $query->where('company_id', $user->company_id);
                })
                ->with(['project', 'comments'])
                ->get();
        } else {
            // Pour les utilisateurs indépendants, récupérer leurs propres tâches
            $tasks = Task::whereHas('project', function($query) use ($user) {
                    $query->where('author_id', $user->id);
                })
                ->with(['project', 'comments'])
                ->get();
        }

        $data = [
            'user' => $user,
            'tasks' => $tasks,
            'export_date' => Carbon::now()->format('d/m/Y à H:i'),
        ];

        $filename = 'taches_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';
        
        $pdf = Pdf::loadView('exports.tasks', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download($filename);
    }

    /**
     * Export complete dashboard data to PDF
     */
    public function exportDashboard(Request $request)
    {
        $user = Auth::user();
        
        // Log pour debug
        \Log::info('Export Dashboard - User Info', [
            'user_id' => $user ? $user->id : 'null',
            'user_name' => $user ? $user->name : 'null',
            'user_role' => $user ? $user->role : 'null',
            'can_export' => $user ? $user->canExportPdf() : false
        ]);
        
        // Vérifier les permissions d'export PDF
        if (!$user || !$user->canExportPdf()) {
            \Log::error('Export Dashboard - Access Denied', [
                'user_id' => $user ? $user->id : 'null',
                'can_export' => $user ? $user->canExportPdf() : false
            ]);
            return response()->json([
                'error' => 'Accès refusé. Vous n\'avez pas l\'autorisation d\'exporter des fichiers PDF.'
            ], 403);
        }

        // Récupérer les projets selon le type d'utilisateur
        if ($user->isUserEntreprise() && $user->company_id) {
            // Pour les employés d'entreprise, récupérer les projets de l'entreprise
            $projects = Project::where('company_id', $user->company_id)
                ->with(['tasks' => function($query) {
                    $query->with('comments');
                }])
                ->get();
        } else {
            // Pour les utilisateurs indépendants, récupérer leurs propres projets
            $projects = Project::where('author_id', $user->id)
                ->with(['tasks' => function($query) {
                    $query->with('comments');
                }])
                ->get();
        }

        $stats = [
            'total_projects' => $projects->count(),
            'completed_projects' => $projects->where('status', 'completed')->count(),
            'total_tasks' => $projects->sum(function($project) {
                return $project->tasks->count();
            }),
            'completed_tasks' => $projects->sum(function($project) {
                return $project->tasks->where('status', 'completed')->count();
            }),
            'active_tasks' => $projects->sum(function($project) {
                return $project->tasks->where('status', 'active')->count();
            }),
            'in_progress_tasks' => $projects->sum(function($project) {
                return $project->tasks->where('status', 'in-progress')->count();
            }),
        ];

        $data = [
            'user' => $user,
            'projects' => $projects,
            'stats' => $stats,
            'export_date' => Carbon::now()->format('d/m/Y à H:i'),
        ];

        $filename = 'dashboard_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';
        
        $pdf = Pdf::loadView('exports.dashboard', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download($filename);
    }
}