<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntrepriseProjetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkEntrepriseAccess');
    }

    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            abort(403, 'Aucune entreprise associée à votre compte.');
        }

        // Récupérer les projets selon le rôle
        if ($user->isAdminEntreprise()) {
            // Admin voit tous les projets de l'entreprise
            $projects = Project::where('company_id', $company->id)
                ->with(['tasks', 'author', 'participants'])
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        } else {
            // Employé voit seulement les projets où il participe
            $projects = Project::where('company_id', $company->id)
                ->whereHas('participants', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orWhere('author_id', $user->id)
                ->with(['tasks', 'author', 'participants'])
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        }

        return view('entreprise.projets.index', compact('projects', 'company'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isAdminEntreprise()) {
            abort(403, 'Seuls les administrateurs peuvent créer des projets.');
        }

        $company = $user->company;
        $employees = User::where('company_id', $company->id)
            ->where('id', '!=', $user->id)
            ->get();

        return view('entreprise.projets.create', compact('employees', 'company'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdminEntreprise()) {
            abort(403, 'Seuls les administrateurs peuvent créer des projets.');
        }

        $company = $user->company;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id'
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'priority' => $request->priority,
            'status' => 'pending',
            'author_id' => $user->id,
            'company_id' => $company->id,
        ]);

        // Assigner les participants
        if ($request->participants) {
            $project->participants()->attach($request->participants);
        }

        return redirect()->route('entreprise.projets.index')
            ->with('success', 'Projet créé avec succès.');
    }

    public function show(Project $projet)
    {
        $user = Auth::user();
        $company = $user->company;

        // Vérifier que le projet appartient à l'entreprise
        if ($projet->company_id !== $company->id) {
            abort(403, 'Accès non autorisé à ce projet.');
        }

        // Vérifier l'accès selon le rôle
        if (!$user->isAdminEntreprise()) {
            $hasAccess = $projet->participants->contains($user->id) || $projet->author_id === $user->id;
            if (!$hasAccess) {
                abort(403, 'Vous n\'avez pas accès à ce projet.');
            }
        }

        $projet->load(['tasks.assignedUser', 'participants', 'author']);

        // Si c'est une requête AJAX pour rafraîchir les statistiques
        if (request()->has('refresh_stats')) {
            return response()->json([
                'total_tasks' => $projet->tasks->count(),
                'completed_tasks' => $projet->tasks->where('status', 'completed')->count(),
                'in_progress_tasks' => $projet->tasks->where('status', 'in-progress')->count(),
                'pending_tasks' => $projet->tasks->where('status', 'pending')->count(),
                'progress_percentage' => $projet->tasks->count() > 0 ? round(($projet->tasks->where('status', 'completed')->count() / $projet->tasks->count()) * 100) : 0
            ]);
        }

        return view('entreprise.projets.show', compact('projet', 'company'));
    }

    public function edit(Project $projet)
    {
        $user = Auth::user();
        
        if (!$user->isAdminEntreprise()) {
            abort(403, 'Seuls les administrateurs peuvent modifier des projets.');
        }

        $company = $user->company;

        // Vérifier que le projet appartient à l'entreprise
        if ($projet->company_id !== $company->id) {
            abort(403, 'Accès non autorisé à ce projet.');
        }

        $employees = User::where('company_id', $company->id)
            ->where('id', '!=', $user->id)
            ->get();

        return view('entreprise.projets.edit', compact('projet', 'employees', 'company'));
    }

    public function update(Request $request, Project $projet)
    {
        $user = Auth::user();
        
        if (!$user->isAdminEntreprise()) {
            abort(403, 'Seuls les administrateurs peuvent modifier des projets.');
        }

        $company = $user->company;

        // Vérifier que le projet appartient à l'entreprise
        if ($projet->company_id !== $company->id) {
            abort(403, 'Accès non autorisé à ce projet.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in-progress,completed,cancelled',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id'
        ]);

        $projet->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'priority' => $request->priority,
            'status' => $request->status,
        ]);

        // Mettre à jour les participants
        if ($request->participants) {
            $projet->participants()->sync($request->participants);
        } else {
            $projet->participants()->detach();
        }

        return redirect()->route('entreprise.projets.index')
            ->with('success', 'Projet mis à jour avec succès.');
    }

    public function destroy(Project $projet)
    {
        $user = Auth::user();
        
        if (!$user->isAdminEntreprise()) {
            abort(403, 'Seuls les administrateurs peuvent supprimer des projets.');
        }

        $company = $user->company;

        // Vérifier que le projet appartient à l'entreprise
        if ($projet->company_id !== $company->id) {
            abort(403, 'Accès non autorisé à ce projet.');
        }

        $projet->delete();

        return redirect()->route('entreprise.projets.index')
            ->with('success', 'Projet supprimé avec succès.');
    }

    public function updateStatus(Request $request, Project $projet)
    {
        \Log::info('Project updateStatus Debug', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'project_id' => $projet->id,
            'project_status' => $projet->status,
            'request_data' => $request->all()
        ]);

        $user = Auth::user();
        
        if (!$user->isAdminEntreprise()) {
            \Log::error('Non-admin trying to update project status', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'project_id' => $projet->id
            ]);
            abort(403, 'Seuls les administrateurs peuvent modifier le statut des projets.');
        }

        $company = $user->company;

        // Vérifier que le projet appartient à l'entreprise
        if ($projet->company_id !== $company->id) {
            \Log::error('Project does not belong to company', [
                'project_company_id' => $projet->company_id,
                'user_company_id' => $company->id,
                'project_id' => $projet->id
            ]);
            abort(403, 'Accès non autorisé à ce projet.');
        }

        try {
            $request->validate([
                'status' => 'required|in:pending,planning,active,on-hold,completed,cancelled'
            ]);

            $projet->update([
                'status' => $request->status,
            ]);

            \Log::info('Project status updated successfully', [
                'project_id' => $projet->id,
                'new_status' => $request->status,
                'user_id' => $user->id
            ]);

            return redirect()->route('entreprise.projets.show', $projet->id)
                ->with('success', 'Statut du projet mis à jour avec succès.');
        } catch (\Exception $e) {
            \Log::error('Error updating project status', [
                'project_id' => $projet->id,
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return redirect()->route('entreprise.projets.show', $projet->id)
                ->with('error', 'Erreur lors de la mise à jour du statut du projet.');
        }
    }

    public function getStats(Project $projet)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company || $projet->company_id !== $company->id) {
            abort(403, 'Accès non autorisé à ce projet.');
        }

        // Filtrer les tâches selon les permissions de l'utilisateur
        $visibleTasks = $projet->tasks->filter(function($task) use ($user) {
            return $user->can('view', $task);
        });

        $totalTasks = $visibleTasks->count();
        $completedTasks = $visibleTasks->where('status', 'completed')->count();
        $inProgressTasks = $visibleTasks->where('status', 'in-progress')->count();
        $pendingTasks = $visibleTasks->where('status', 'pending')->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return response()->json([
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'in_progress_tasks' => $inProgressTasks,
            'pending_tasks' => $pendingTasks,
            'progress_percentage' => $progress
        ]);
    }
}
