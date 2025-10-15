<?php
/**
 * Test de la mise à jour des compteurs de sous-tâches
 */

require_once 'vendor/autoload.php';

// Configuration Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test de la mise à jour des compteurs ===\n\n";

try {
    // Récupérer un projet avec des tâches
    $project = \App\Models\Project::whereNotNull('company_id')->with('tasks')->first();
    
    if (!$project) {
        echo "❌ Aucun projet d'entreprise trouvé\n";
        exit;
    }
    
    echo "📋 Projet trouvé: {$project->name} (ID: {$project->id})\n";
    
    // Récupérer les tâches principales et leurs sous-tâches
    $mainTasks = $project->tasks->whereNull('parent_id');
    $subtasks = $project->tasks->whereNotNull('parent_id');
    
    echo "\n📊 Statistiques:\n";
    echo "- Tâches principales: " . $mainTasks->count() . "\n";
    echo "- Sous-tâches: " . $subtasks->count() . "\n";
    
    foreach ($mainTasks as $task) {
        $taskSubtasks = $subtasks->where('parent_id', $task->id);
        $completedCount = $taskSubtasks->whereIn('status', ['completed', 'done'])->count();
        
        echo "\n🔹 Tâche: {$task->title} (ID: {$task->id})\n";
        echo "   Sous-tâches: {$taskSubtasks->count()}\n";
        echo "   Terminées: {$completedCount}\n";
        echo "   Compteur: {$completedCount}/{$taskSubtasks->count()} terminées\n";
        
        if ($taskSubtasks->count() > 0) {
            echo "   Détail des sous-tâches:\n";
            foreach ($taskSubtasks as $subtask) {
                echo "     - {$subtask->title} (ID: {$subtask->id}) - Statut: {$subtask->status}\n";
            }
        }
    }
    
    echo "\n🎯 Test de simulation de changement de statut:\n";
    
    // Simuler un changement de statut d'une sous-tâche
    $firstSubtask = $subtasks->first();
    if ($firstSubtask) {
        echo "Changement du statut de la sous-tâche ID {$firstSubtask->id} de '{$firstSubtask->status}' vers 'completed'\n";
        
        // Mettre à jour le statut
        $firstSubtask->status = 'completed';
        $firstSubtask->save();
        
        echo "✅ Statut mis à jour\n";
        
        // Recalculer les compteurs
        $parentTask = $mainTasks->where('id', $firstSubtask->parent_id)->first();
        if ($parentTask) {
            $newSubtasks = $subtasks->where('parent_id', $parentTask->id);
            $newCompletedCount = $newSubtasks->whereIn('status', ['completed', 'done'])->count();
            
            echo "Nouveau compteur pour la tâche parent {$parentTask->id}: {$newCompletedCount}/{$newSubtasks->count()} terminées\n";
        }
    }
    
    echo "\n🎉 Test terminé avec succès !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
