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
        $participants = $project->participants;
        $parent_id = $request->get('parent_id');

        return view('tasks.create', compact('project', 'participants', 'parent_id'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|integer',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'parent_id' => 'nullable|integer|exists:tasks,id',
        ]);

        $taskData = [
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? 0,
            'project_id' => $project->id,
            'author_id' => auth()->id(),
            'assigned_to' => $request->assigned_to,
            'parent_id' => $request->parent_id,
        ];

        Task::create($taskData);
        return redirect()->route('projects.tasks', $project->id)->with('success', 'Tâche créée avec succès.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $participants = $task->project->participants;
        return view('tasks.edit', compact('task', 'participants'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task->update($request->only(['title', 'description', 'due_date', 'priority', 'assigned_to']));

        return redirect()->route('projects.tasks', $task->project_id)->with('success', 'Tâche mise à jour avec succès.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('projects.tasks', $task->project_id)->with('success', 'Tâche supprimée avec succès.');
    }
}