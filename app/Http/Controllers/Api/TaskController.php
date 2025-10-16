<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Super admin peut voir toutes les tâches
        if ($user->is_super_admin()) {
            $tasks = Task::with(['project', 'author', 'assignedUser', 'parent'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // Utilisateurs d'entreprise voient les tâches de leur entreprise
        elseif ($user->isPartOfCompany()) {
            $tasks = Task::where('company_id', $user->company_id)
                ->with(['project', 'author', 'assignedUser', 'parent'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // Utilisateurs indépendants voient leurs propres tâches
        else {
            $tasks = Task::where('author_id', $user->id)
                ->orWhere('assigned_to', $user->id)
                ->with(['project', 'author', 'assignedUser', 'parent'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json($tasks);
    }

    public function store(Request $request, Project $project)
    {
        $user = Auth::user();
        
        // Vérifier les permissions sur le projet
        if (!$user->can('view', $project)) {
            return response()->json(['error' => 'Accès non autorisé au projet'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'parent_id' => 'nullable|integer|exists:tasks,id',
            'status' => 'nullable|in:pending,in-progress,completed,on-hold,cancelled',
        ]);

        // Vérifier l'assignation pour les utilisateurs d'entreprise
        if (isset($validated['assigned_to']) && $user->isPartOfCompany()) {
            $assignedUser = \App\Models\User::find($validated['assigned_to']);
            if (!$assignedUser || $assignedUser->company_id !== $user->company_id) {
                return response()->json(['error' => 'L\'utilisateur assigné doit faire partie de votre entreprise'], 422);
            }
        }

        $taskData = array_merge($validated, [
            'project_id' => $project->id,
            'author_id' => $user->id,
            'company_id' => $user->company_id,
        ]);

        $task = Task::create($taskData);
        return response()->json($task->load(['project', 'author', 'assignedUser', 'parent']), 201);
    }

    public function show(Task $task)
    {
        $user = Auth::user();
        
        // Vérifier les permissions d'accès
        if (!$user->can('view', $task)) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        return response()->json($task->load(['project', 'author', 'assignedUser', 'parent', 'comments.user']));
    }

    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Vérifier les permissions de modification
        if (!$user->can('update', $task)) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'parent_id' => 'nullable|integer|exists:tasks,id',
            'status' => 'nullable|in:pending,in-progress,completed,on-hold,cancelled',
        ]);

        // Vérifier l'assignation pour les utilisateurs d'entreprise
        if (isset($validated['assigned_to']) && $user->isPartOfCompany()) {
            $assignedUser = \App\Models\User::find($validated['assigned_to']);
            if (!$assignedUser || $assignedUser->company_id !== $user->company_id) {
                return response()->json(['error' => 'L\'utilisateur assigné doit faire partie de votre entreprise'], 422);
            }
        }

        $task->update($validated);
        return response()->json($task->load(['project', 'author', 'assignedUser', 'parent']));
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();
        
        // Vérifier les permissions de suppression
        if (!$user->can('delete', $task)) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $task->delete();
        return response()->json(null, 204);
    }

    /**
     * Mettre à jour le statut d'une tâche
     */
    public function updateStatus(Request $request, Task $task)
    {
        $user = Auth::user();
        
        if (!$user->can('update', $task)) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in-progress,completed,on-hold,cancelled',
        ]);

        $task->update($validated);
        return response()->json($task->load(['project', 'author', 'assignedUser']));
    }
}