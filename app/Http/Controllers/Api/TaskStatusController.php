<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskStatusController extends Controller
{
    /**
     * Vérifier les changements de statut des tâches
     */
    public function statusCheck(Request $request)
    {
        $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'integer|exists:tasks,id',
            'last_check' => 'required|integer'
        ]);

        $taskIds = $request->task_ids;
        $lastCheck = $request->last_check;
        
        // Convertir le timestamp en date
        $lastCheckDate = date('Y-m-d H:i:s', $lastCheck / 1000);
        
        // Vérifier les tâches qui ont été modifiées depuis la dernière vérification
        $updatedTasks = Task::whereIn('id', $taskIds)
            ->where('updated_at', '>', $lastCheckDate)
            ->get()
            ->map(function($task) {
                // Compter les sous-tâches terminées pour cette tâche
                $completedCount = Task::where('parent_id', $task->id)
                    ->whereIn('status', ['completed', 'done'])
                    ->count();
                
                return [
                    'id' => $task->id,
                    'status' => $task->status,
                    'completed_count' => $completedCount,
                    'updated_at' => $task->updated_at
                ];
            });

        return response()->json([
            'updated_tasks' => $updatedTasks,
            'last_check' => time() * 1000 // Retourner en millisecondes
        ]);
    }
}