<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|integer',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'parent_id' => 'nullable|integer|exists:tasks,id',
            'status' => 'nullable|in:todo,in-progress,done,blocked',
        ]);

        $taskData = array_merge($validated, [
            'project_id' => $project->id,
            'author_id' => auth()->id(),
        ]);

        $task = Task::create($taskData);

        return response()->json($task, 201);
    }

    public function show(Project $project, Task $task)
    {
        if ($task->project_id != $project->id) {
            return response()->json(['message' => 'Tâche introuvable dans ce projet.'], 404);
        }

        return response()->json($task);
    }

    public function update(Request $request, Project $project, Task $task)
    {
        if ($task->project_id != $project->id) {
            return response()->json(['message' => 'Tâche introuvable dans ce projet.'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|integer',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'parent_id' => 'nullable|integer|exists:tasks,id',
            'status' => 'nullable|in:todo,in-progress,done,blocked',
        ]);

        $task->update($validated);
        return response()->json($task);
    }

    public function destroy(Project $project, Task $task)
    {
        if ($task->project_id != $project->id) {
            return response()->json(['message' => 'Tâche introuvable dans ce projet.'], 404);
        }

        $task->delete();
        return response()->json(null, 204);
    }
}