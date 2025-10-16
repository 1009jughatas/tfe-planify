<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Exporter les projets en PDF
     */
    public function projects(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier les permissions d'export
        if (!$user->canExportPdf()) {
            return response()->json(['error' => 'Accès non autorisé. Vous n\'avez pas l\'autorisation d\'exporter des fichiers PDF.'], 403);
        }

        // Récupérer les projets selon le type d'utilisateur
        if ($user->isUserEntreprise() && $user->company_id) {
            $projects = Project::where('company_id', $user->company_id)
                ->with(['author', 'tasks'])
                ->get();
        } else {
            $projects = Project::where('author_id', $user->id)
                ->with(['author', 'tasks'])
                ->get();
        }

        $pdf = Pdf::loadView('exports.projects', [
            'projects' => $projects,
            'user' => $user,
            'export_date' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download('projets_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exporter les tâches en PDF
     */
    public function tasks(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->canExportPdf()) {
            return response()->json(['error' => 'Accès non autorisé. Vous n\'avez pas l\'autorisation d\'exporter des fichiers PDF.'], 403);
        }

        // Récupérer les tâches selon le type d'utilisateur
        if ($user->isUserEntreprise() && $user->company_id) {
            $tasks = Task::where('company_id', $user->company_id)
                ->with(['project', 'author', 'assignedUser'])
                ->get();
        } else {
            $tasks = Task::where('author_id', $user->id)
                ->orWhere('assigned_to', $user->id)
                ->with(['project', 'author', 'assignedUser'])
                ->get();
        }

        $pdf = Pdf::loadView('exports.tasks', [
            'tasks' => $tasks,
            'user' => $user,
            'export_date' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download('taches_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exporter le dashboard en PDF
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->canExportPdf()) {
            return response()->json(['error' => 'Accès non autorisé. Vous n\'avez pas l\'autorisation d\'exporter des fichiers PDF.'], 403);
        }

        // Récupérer les données du dashboard
        $data = $this->getDashboardData($user);

        $pdf = Pdf::loadView('exports.dashboard', [
            'data' => $data,
            'user' => $user,
            'export_date' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download('dashboard_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exporter les données d'entreprise (super admin uniquement)
     */
    public function company(Company $company)
    {
        $user = Auth::user();
        
        if (!$user->is_super_admin()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $companyData = [
            'company' => $company,
            'users' => $company->users,
            'projects' => $company->projects()->with(['author', 'tasks'])->get(),
            'tasks' => $company->tasks()->with(['project', 'author', 'assignedUser'])->get(),
        ];

        $pdf = Pdf::loadView('exports.company', [
            'data' => $companyData,
            'export_date' => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download('entreprise_' . $company->name . '_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Obtenir les données du dashboard pour l'export
     */
    private function getDashboardData($user)
    {
        if ($user->isPartOfCompany()) {
            return [
                'projects' => Project::where('company_id', $user->company_id)
                    ->with(['author', 'tasks'])
                    ->get(),
                'tasks' => Task::where('company_id', $user->company_id)
                    ->with(['project', 'author', 'assignedUser'])
                    ->get(),
                'company' => $user->company,
            ];
        } else {
            return [
                'projects' => Project::where('author_id', $user->id)
                    ->with(['author', 'tasks'])
                    ->get(),
                'tasks' => Task::where('author_id', $user->id)
                    ->orWhere('assigned_to', $user->id)
                    ->with(['project', 'author', 'assignedUser'])
                    ->get(),
                'company' => null,
            ];
        }
    }

    /**
     * Obtenir les formats d'export disponibles
     */
    public function formats()
    {
        $user = Auth::user();
        
        $formats = [
            'pdf' => [
                'name' => 'PDF',
                'description' => 'Document portable',
                'available' => $user->canExportPdf(),
            ],
        ];

        return response()->json($formats);
    }
}