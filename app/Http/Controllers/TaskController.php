<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function create(Project $project, Request $request)
    {
        $user = auth()->user();

        // Vérifier l'autorisation selon le type d'utilisateur
        if ($user->isPartOfCompany()) {
            // Pour les utilisateurs d'entreprise, vérifier qu'ils appartiennent à la même entreprise
            if ($project->company_id !== $user->company_id) {
                abort(403, 'Accès non autorisé à ce projet d\'entreprise.');
            }
        } else {
            // Pour les utilisateurs indépendants, vérifier l'ancienne logique
            if (!$user->is_admin() && !$project->participants->contains($user->id) && $project->author_id !== $user->id) {
                abort(403, 'Accès non autorisé à ce projet.');
            }
        }

        // Pour les projets d'entreprise, récupérer tous les utilisateurs de l'entreprise
        if ($project->company_id) {
            $participants = \App\Models\User::where('company_id', $project->company_id)->get();
        } else {
            $participants = $project->participants;
        }

        $parent_id = $request->get('parent_id');

        return view('tasks.create', compact('project', 'participants', 'parent_id'));
    }

    public function store(Request $request, Project $project)
    {
        $user = auth()->user();

        // Vérifier l'autorisation selon le type d'utilisateur
        if ($user->isPartOfCompany()) {
            // Pour les utilisateurs d'entreprise, vérifier qu'ils appartiennent à la même entreprise
            if ($project->company_id !== $user->company_id) {
                abort(403, 'Accès non autorisé à ce projet d\'entreprise.');
            }
        } else {
            // Pour les utilisateurs indépendants, vérifier l'ancienne logique
            if (!$user->is_admin() && !$project->participants->contains($user->id) && $project->author_id !== $user->id) {
                abort(403, 'Accès non autorisé à ce projet.');
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'nullable|integer|min:0|max:3',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'parent_id' => 'nullable|integer|exists:tasks,id',
            'status' => 'nullable|string|in:todo,in-progress,done,blocked',
        ]);

        // Vérifier que l'utilisateur assigné fait partie du projet
        if ($request->assigned_to) {
            if ($user->isPartOfCompany()) {
                // Pour les projets d'entreprise, vérifier que l'utilisateur assigné appartient à la même entreprise
                $assignedUser = \App\Models\User::find($request->assigned_to);
                if (!$assignedUser || $assignedUser->company_id !== $user->company_id) {
                    return back()->withErrors(['assigned_to' => 'L\'utilisateur assigné doit appartenir à votre entreprise.']);
                }
            } else {
                // Pour les projets indépendants, vérifier l'ancienne logique
                if (!$project->participants->contains($request->assigned_to)) {
                    return back()->withErrors(['assigned_to' => 'L\'utilisateur assigné doit faire partie du projet.']);
                }
            }
        }

        $taskData = [
            'title' => htmlspecialchars($request->title, ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars($request->description, ENT_QUOTES, 'UTF-8'),
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? 0,
            'status' => $request->status ?? 'todo', // Utiliser le statut fourni ou 'todo' par défaut
            'project_id' => $project->id,
            'author_id' => auth()->id(),
            'assigned_to' => $request->assigned_to,
            'parent_id' => $request->parent_id,
        ];

        // Ajouter company_id seulement pour les projets d'entreprise
        if ($project->company_id) {
            $taskData['company_id'] = $project->company_id;
        }

        // Pour les utilisateurs indépendants, assigner automatiquement à eux-mêmes
        if ($user->isUserIndependant() && !$request->has('assigned_to')) {
            $taskData['assigned_to'] = $user->id;
        }

        Task::create($taskData);
        return redirect()->route('projects.tasks', $project->id)->with('success', 'Tâche créée avec succès.');
    }

    public function show(Task $task)
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('view', $task)) {
            abort(403, 'Accès non autorisé à cette tâche.');
        }

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('update', $task)) {
            abort(403, 'Accès non autorisé. Seuls l\'auteur ou l\'utilisateur assigné (premium) peuvent modifier cette tâche.');
        }

        $participants = $task->project->participants;
        return view('tasks.edit', compact('task', 'participants'));
    }

    public function update(Request $request, Task $task)
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('update', $task)) {
            abort(403, 'Accès non autorisé. Seuls l\'auteur ou l\'utilisateur assigné (premium) peuvent modifier cette tâche.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|integer|min:0|max:3',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        // Vérifier que l'utilisateur assigné fait partie du projet (seulement pour les utilisateurs d'entreprise)
        if (!$user->isUserIndependant() && $request->assigned_to && !$task->project->participants->contains($request->assigned_to)) {
            return back()->withErrors(['assigned_to' => 'L\'utilisateur assigné doit faire partie du projet.']);
        }

        // Vérifier la permission d'assigner (uniquement premium) - seulement pour les utilisateurs indépendants
        if ($user->isUserIndependant() && $request->has('assigned_to') && $request->assigned_to != $task->assigned_to) {
            if (!$user->can('assign', $task)) {
                return back()->with('warning', 'La fonctionnalité d\'assignation de tâches est réservée aux utilisateurs premium.');
            }
        }

        $task->update([
            'title' => htmlspecialchars($request->title, ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars($request->description, ENT_QUOTES, 'UTF-8'),
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'assigned_to' => ($user->can('assign', $task) && $request->has('assigned_to')) ? $request->assigned_to : $task->assigned_to,
        ]);

        return redirect()->route('projects.tasks', $task->project_id)->with('success', 'Tâche mise à jour avec succès.');
    }

    public function destroy(Task $task)
    {
        $user = auth()->user();

        // Vérifier l'autorisation via Policy
        if (!$user->can('delete', $task)) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs ou l\'auteur peuvent supprimer cette tâche.');
        }

        $task->delete();

        return redirect()->route('projects.tasks', $task->project_id)->with('success', 'Tâche supprimée avec succès.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $user = auth()->user();
        
        // Debug : Log des informations de la tâche et de l'utilisateur
        \Log::info('Task updateStatus Debug', [
            'task_id' => $task->id,
            'task_author_id' => $task->author_id,
            'task_assigned_to' => $task->assigned_to,
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_is_premium' => $user->is_premium(),
            'user_is_admin' => $user->is_admin(),
            'project_participants' => $task->project->participants->pluck('id')->toArray()
        ]);

        // Vérifier l'autorisation via Policy
        if (!$user->can('updateStatus', $task)) {
            \Log::warning('Access denied for task status update', [
                'task_id' => $task->id,
                'user_id' => $user->id
            ]);
            return response()->json(['error' => 'Accès non autorisé. Seuls l\'auteur, l\'utilisateur assigné ou les participants premium peuvent modifier le statut.'], 403);
        }

        $request->validate([
            'status' => 'required|in:todo,in-progress,done,blocked',
        ]);

        $task->update([
            'status' => $request->status,
        ]);

        \Log::info('Task status updated successfully', [
            'task_id' => $task->id,
            'new_status' => $request->status,
            'user_id' => $user->id
        ]);

        return response()->json(['message' => 'Le statut de la tâche a été mis à jour avec succès.']);
    }
}