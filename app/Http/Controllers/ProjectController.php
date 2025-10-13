<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    const PROJECT_LIMIT = 3; // Limite pour les utilisateurs gratuits

    public function index()
    {
        $user = auth()->user();

        if ($user->is_admin()) {
            // Admins see all projects
            $projects = Project::all();
        } else {
            // Users see their own projects + projects they participate in
            $ownProjects = $user->projects()->get();
            $participatingProjects = $user->participatingProjects()
                ->select('projects.*') // Spécifier explicitement les colonnes de projects
                ->get();
            $projects = $ownProjects->merge($participatingProjects)->unique('id');
        }

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('create', Project::class)) {
            $limit = $user->is_premium() ? 'illimité' : '3';
            return redirect()->route('projects.index')->with('error', 'Vous avez atteint la limite de projets (' . $limit . '). Les utilisateurs gratuits sont limités à 3 projets. Passez en premium pour créer plus de projets.');
        }

        // Pour les admins, récupérer tous les utilisateurs membres
        // Pour les utilisateurs normaux, pas besoin de la liste des utilisateurs
        if ($user->is_admin()) {
            $users = User::where('role', 'member')->get();
        } else {
            $users = collect(); // Collection vide pour les utilisateurs normaux
        }

        return view('projects.create', compact('users'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('create', Project::class)) {
            return redirect()->route('projects.index')->with('error', 'Vous avez atteint la limite de projets. Les utilisateurs gratuits sont limités à 3 projets. Passez en premium pour créer plus de projets.');
        }

        // Règles de validation selon le rôle
        $validationRules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'deadline' => 'nullable|date|after:today',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:planning,active,on-hold,completed',
        ];

        // Ajouter la validation pour les membres d'équipe si c'est un admin
        if ($user->is_admin()) {
            $validationRules['team_members'] = 'nullable|array';
            $validationRules['team_members.*'] = 'exists:users,id|different:' . $user->id;
        }

        $request->validate($validationRules);

        // Créer le projet
        $projectData = [
            'name' => htmlspecialchars($request->name, ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars($request->description, ENT_QUOTES, 'UTF-8'),
            'author_id' => $user->id,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'status' => $request->status,
            'start_date' => now(), // Date de début par défaut
        ];

        // Ajouter company_id seulement pour les utilisateurs d'entreprise
        if ($user->company_id) {
            $projectData['company_id'] = $user->company_id;
        }

        $project = Project::create($projectData);

        // Gestion de l'assignation d'équipe pour les admins
        if ($user->is_admin() && $request->has('team_members')) {
            $teamMembers = $request->team_members;
            
            // Ajouter le projet à la table pivot pour chaque membre sélectionné
            foreach ($teamMembers as $memberId) {
                $project->participants()->attach($memberId);
            }
            
            $assignedCount = count($teamMembers);
            $message = $assignedCount > 0 
                ? "Projet créé avec succès et assigné à {$assignedCount} membre(s) de l'équipe."
                : "Projet créé avec succès.";
        } else {
            $message = "Projet créé avec succès.";
        }

        return redirect()->route('projects.index')->with('success', $message);
    }

    public function show(Project $project)
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('view', $project)) {
            abort(403, 'Accès non autorisé à ce projet.');
        }

        return view('projects.show', compact('project'));
    }

    public function tasks(Project $project)
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('view', $project)) {
            abort(403, 'Accès non autorisé à ce projet. Vous devez être l\'auteur ou participant.');
        }

        $tasks = $project->tasks()->whereNull('parent_id')->get();
        $users = User::all();

        return view('projects.tasks', compact('project', 'tasks', 'users'));
    }

    public function edit(Project $project)
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('update', $project)) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs et l\'auteur du projet peuvent le modifier.');
        }

        $users = User::all();
        return view('projects.edit', compact('project', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('update', $project)) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs et l\'auteur du projet peuvent le modifier.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string|in:planning,active,on-hold,completed,cancelled',
        ]);

        $project->update([
            'name' => htmlspecialchars($request->name, ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars($request->description, ENT_QUOTES, 'UTF-8'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status ?? $project->status,
        ]);

        // Sync participants via the pivot table (uniquement pour les utilisateurs premium)
        if ($request->has('participants') && $user->can('inviteCollaborators', $project)) {
            $project->participants()->sync($request->participants);
        } elseif ($request->has('participants') && !$user->is_premium()) {
            return redirect()->route('projects.show', $project->id)->with('warning', 'Projet mis à jour. Note : La fonctionnalité d\'invitation de collaborateurs est réservée aux utilisateurs premium.');
        }

        return redirect()->route('projects.index')->with('success', 'Projet mis à jour avec succès.');
    }

    public function destroy(Project $project)
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('delete', $project)) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs et l\'auteur du projet peuvent le supprimer.');
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projet supprimé avec succès.');
    }

    public function updateStatus(Request $request, Project $project)
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('update', $project)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Accès non autorisé. Seuls les administrateurs et l\'auteur du projet peuvent modifier le statut.'], 403);
            }
            abort(403, 'Accès non autorisé. Seuls les administrateurs et l\'auteur du projet peuvent modifier le statut.');
        }

        $request->validate([
            'status' => 'required|in:planning,active,on-hold,completed,cancelled',
        ]);

        $project->update([
            'status' => $request->status,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Le statut du projet a été mis à jour avec succès.',
                'status' => $project->status
            ]);
        }

        return redirect()->back()->with('success', 'Statut du projet mis à jour avec succès.');
    }

    public function getLimits()
    {
        return self::PROJECT_LIMIT;
    }

    /**
     * Déplacer un projet d'une colonne à une autre (drag & drop)
     */
    public function moveProject(Request $request, Project $project)
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('update', $project)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Accès non autorisé. Seuls les administrateurs et l\'auteur du projet peuvent déplacer le projet.'], 403);
            }
            abort(403, 'Accès non autorisé. Seuls les administrateurs et l\'auteur du projet peuvent déplacer le projet.');
        }

        $request->validate([
            'status' => 'required|in:todo,in_progress,blocked,done',
            'position' => 'nullable|integer|min:0',
        ]);

        $project->update([
            'status' => $request->status,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Le projet a été déplacé avec succès.',
                'status' => $project->status,
                'project_id' => $project->id
            ]);
        }

        return redirect()->back()->with('success', 'Projet déplacé avec succès.');
    }
}