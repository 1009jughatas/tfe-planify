<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Super admin peut voir tous les projets
        if ($user->is_super_admin()) {
            $projects = Project::with(['author', 'company', 'tasks'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // Utilisateurs d'entreprise voient les projets de leur entreprise
        elseif ($user->isPartOfCompany()) {
            $projects = Project::where('company_id', $user->company_id)
                ->with(['author', 'company', 'tasks'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // Utilisateurs indépendants voient leurs propres projets
        else {
            $projects = Project::where('author_id', $user->id)
                ->with(['author', 'tasks'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'nullable|in:pending,in-progress,completed,on-hold,cancelled',
        ]);

        // Définir l'auteur et l'entreprise selon le type d'utilisateur
        $validated['author_id'] = $user->id;
        
        if ($user->isPartOfCompany()) {
            $validated['company_id'] = $user->company_id;
        }

        $project = Project::create($validated);
        return response()->json($project->load(['author', 'company', 'tasks']), 201);
    }

    public function show(Project $project)
    {
        $user = Auth::user();
        
        // Vérifier les permissions d'accès
        if (!$user->can('view', $project)) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        return response()->json($project->load(['author', 'company', 'tasks.assignedUser', 'tasks.author']));
    }

    public function update(Request $request, Project $project)
    {
        $user = Auth::user();
        
        // Vérifier les permissions de modification
        if (!$user->can('update', $project)) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'nullable|in:pending,in-progress,completed,on-hold,cancelled',
        ]);

        $project->update($validated);
        return response()->json($project->load(['author', 'company', 'tasks']));
    }

    public function destroy(Project $project)
    {
        $user = Auth::user();
        
        // Vérifier les permissions de suppression
        if (!$user->can('delete', $project)) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $project->delete();
        return response()->json(null, 204);
    }

    /**
     * Obtenir les statistiques d'un projet
     */
    public function stats(Project $project)
    {
        $user = Auth::user();
        
        if (!$user->can('view', $project)) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $stats = [
            'total_tasks' => $project->tasks()->count(),
            'completed_tasks' => $project->tasks()->where('status', 'completed')->count(),
            'in_progress_tasks' => $project->tasks()->where('status', 'in-progress')->count(),
            'pending_tasks' => $project->tasks()->where('status', 'pending')->count(),
            'overdue_tasks' => $project->tasks()
                ->where('due_date', '<', now())
                ->where('status', '!=', 'completed')
                ->count(),
            'completion_percentage' => $project->tasks()->count() > 0 
                ? round(($project->tasks()->where('status', 'completed')->count() / $project->tasks()->count()) * 100, 2)
                : 0
        ];

        return response()->json($stats);
    }
}
