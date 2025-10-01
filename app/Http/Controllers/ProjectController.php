<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    const PROJECT_LIMIT = 2;

    public function index()
    {
        $user = auth()->user();

        if ($user->is_admin()) {
            // Admins see all projects
            $projects = Project::all();
        } else {
            // Users only see the projects they participate in
            $projects = $user->participatingProjects()->get();
        }

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $user = auth()->user();
        $limit = $this->getLimits();

        if (!$user->is_premium && $user->projects()->count() >= $limit) {
            return redirect()->route('projects.index')->with('error', 'Les utilisateurs non premium ne peuvent créer que ' . $limit . ' projets maximum.');
        }

        $users = User::all();
        return view('projects.create', compact('users'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $limit = $this->getLimits();

        if (!$user->is_premium && $user->projects()->count() == $limit) {
            return redirect()->route('projects.index')->with('error', 'Les utilisateurs non premium ne peuvent créer que ' . $limit . ' projets maximum.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'participants' => 'nullable|array',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'author_id' => $user->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Sync participants via the pivot table
        $project->participants()->sync($request->participants);

        return redirect()->route('projects.index')->with('success', 'Projet créé avec succès.');
    }

    public function show(Project $project)
    {
        $user = auth()->user();

        if (!$user->is_admin() && !$project->participants->contains($user->id)) {
            abort(403, 'Accès non autorisé à ce projet.');
        }

        return view('projects.show', compact('project'));
    }

    public function tasks(Project $project)
    {
        $user = auth()->user();

        // Vérifier l'autorisation
        if (!$user->is_admin() && !$project->participants->contains($user->id)) {
            abort(403, 'Accès non autorisé à ce projet.');
        }

        $tasks = $project->tasks()->whereNull('parent_id')->get();
        $users = User::all();

        return view('projects.tasks', compact('project', 'tasks', 'users'));
    }

    public function edit(Project $project)
    {
        $user = auth()->user();

        // Vérifier l'autorisation (seuls les admins peuvent éditer)
        if (!$user->is_admin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent modifier les projets.');
        }

        $users = User::all();
        return view('projects.edit', compact('project', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        $user = auth()->user();

        // Vérifier l'autorisation (seuls les admins peuvent modifier)
        if (!$user->is_admin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent modifier les projets.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update([
            'name' => htmlspecialchars($request->name, ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars($request->description, ENT_QUOTES, 'UTF-8'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Sync participants via the pivot table
        if ($request->has('participants')) {
            $project->participants()->sync($request->participants);
        }

        return redirect()->route('projects.index')->with('success', 'Projet mis à jour avec succès.');
    }

    public function destroy(Project $project)
    {
        $user = auth()->user();

        // Vérifier l'autorisation (seuls les admins peuvent supprimer)
        if (!$user->is_admin()) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent supprimer les projets.');
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projet supprimé avec succès.');
    }

    public function getLimits()
    {
        return self::PROJECT_LIMIT;
    }
}