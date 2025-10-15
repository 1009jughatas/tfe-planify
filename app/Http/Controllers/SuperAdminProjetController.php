<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;

class SuperAdminProjetController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isSuperAdmin']);
    }

    /**
     * Afficher tous les projets
     */
    public function index(Request $request)
    {
        $query = Project::with(['author', 'company', 'tasks']);

        // Filtres
        if ($request->filled('type')) {
            if ($request->type === 'entreprise') {
                $query->whereNotNull('company_id');
            } elseif ($request->type === 'independant') {
                $query->whereNull('company_id');
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user')) {
            $query->where('author_id', $request->user);
        }

        if ($request->filled('company')) {
            $query->where('company_id', $request->company);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $projects = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total' => Project::count(),
            'entreprise' => Project::whereNotNull('company_id')->count(),
            'independant' => Project::whereNull('company_id')->count(),
            'active' => Project::where('status', 'active')->count(),
            'completed' => Project::where('status', 'completed')->count(),
        ];

        // Liste des utilisateurs et entreprises pour les filtres
        $users = User::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();

        return view('superadmin.projets.index', compact('projects', 'stats', 'users', 'companies'));
    }

    /**
     * Afficher les détails d'un projet
     */
    public function show($id)
    {
        $project = Project::with(['author', 'company', 'tasks', 'tasks.assignedUser'])
            ->findOrFail($id);

        // Statistiques du projet
        $projectStats = [
            'total_tasks' => $project->tasks()->count(),
            'completed_tasks' => $project->tasks()->where('status', 'completed')->count(),
            'in_progress_tasks' => $project->tasks()->where('status', 'in-progress')->count(),
            'pending_tasks' => $project->tasks()->where('status', 'pending')->count(),
        ];

        return view('superadmin.projets.show', compact('project', 'projectStats'));
    }
}