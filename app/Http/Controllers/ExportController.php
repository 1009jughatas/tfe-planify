<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;

class ExportController extends Controller
{
    /**
     * Export projects data to JSON
     */
    public function exportProjects(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || !$user->is_premium()) {
            return response()->json([
                'error' => 'Accès refusé. Cette fonctionnalité est réservée aux utilisateurs Premium.'
            ], 403);
        }

        $projects = Project::where('author_id', $user->id)
            ->with(['tasks' => function($query) {
                $query->with('comments');
            }])
            ->get();

        $exportData = [
            'export_date' => Carbon::now()->toISOString(),
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'projects' => $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'description' => $project->description,
                    'status' => $project->status,
                    'priority' => $project->priority,
                    'start_date' => $project->start_date,
                    'end_date' => $project->end_date,
                    'deadline' => $project->deadline,
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at,
                    'tasks' => $project->tasks->map(function($task) {
                        return [
                            'id' => $task->id,
                            'title' => $task->title,
                            'description' => $task->description,
                            'status' => $task->status,
                            'priority' => $task->priority,
                            'due_date' => $task->due_date,
                            'created_at' => $task->created_at,
                            'updated_at' => $task->updated_at,
                            'comments_count' => $task->comments->count(),
                        ];
                    })
                ];
            })
        ];

        $filename = 'projets_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($exportData)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * Export tasks data to JSON
     */
    public function exportTasks(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || !$user->is_premium()) {
            return response()->json([
                'error' => 'Accès refusé. Cette fonctionnalité est réservée aux utilisateurs Premium.'
            ], 403);
        }

        $tasks = Task::whereHas('project', function($query) use ($user) {
                $query->where('author_id', $user->id);
            })
            ->with(['project', 'comments'])
            ->get();

        $exportData = [
            'export_date' => Carbon::now()->toISOString(),
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'tasks' => $tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'due_date' => $task->due_date,
                    'project_name' => $task->project->name,
                    'project_id' => $task->project->id,
                    'created_at' => $task->created_at,
                    'updated_at' => $task->updated_at,
                    'comments' => $task->comments->map(function($comment) {
                        return [
                            'content' => $comment->content,
                            'created_at' => $comment->created_at,
                        ];
                    })
                ];
            })
        ];

        $filename = 'taches_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($exportData)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * Export complete dashboard data
     */
    public function exportDashboard(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || !$user->is_premium()) {
            return response()->json([
                'error' => 'Accès refusé. Cette fonctionnalité est réservée aux utilisateurs Premium.'
            ], 403);
        }

        $projects = Project::where('author_id', $user->id)
            ->with(['tasks' => function($query) {
                $query->with('comments');
            }])
            ->get();

        $stats = [
            'total_projects' => $projects->count(),
            'completed_projects' => $projects->where('status', 'completed')->count(),
            'total_tasks' => $projects->sum(function($project) {
                return $project->tasks->count();
            }),
            'completed_tasks' => $projects->sum(function($project) {
                return $project->tasks->where('status', 'completed')->count();
            }),
        ];

        $exportData = [
            'export_date' => Carbon::now()->toISOString(),
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            'statistics' => $stats,
            'projects' => $projects->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'description' => $project->description,
                    'status' => $project->status,
                    'priority' => $project->priority,
                    'start_date' => $project->start_date,
                    'end_date' => $project->end_date,
                    'deadline' => $project->deadline,
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at,
                    'tasks_count' => $project->tasks->count(),
                    'completed_tasks_count' => $project->tasks->where('status', 'completed')->count(),
                    'tasks' => $project->tasks->map(function($task) {
                        return [
                            'id' => $task->id,
                            'title' => $task->title,
                            'description' => $task->description,
                            'status' => $task->status,
                            'priority' => $task->priority,
                            'due_date' => $task->due_date,
                            'created_at' => $task->created_at,
                            'updated_at' => $task->updated_at,
                            'comments_count' => $task->comments->count(),
                        ];
                    })
                ];
            })
        ];

        $filename = 'dashboard_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($exportData)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }
}