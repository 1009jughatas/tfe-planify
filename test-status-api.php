<?php
/**
 * Test de l'API de vérification des statuts
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Task;

// Configuration Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test de l'API de vérification des statuts ===\n\n";

try {
    // Récupérer quelques tâches avec des sous-tâches
    $tasks = Task::whereNotNull('parent_id')->limit(3)->get();
    
    if ($tasks->count() === 0) {
        echo "❌ Aucune sous-tâche trouvée dans la base de données\n";
        exit;
    }
    
    echo "📋 Sous-tâches trouvées :\n";
    foreach ($tasks as $task) {
        echo "- Tâche ID: {$task->id}, Statut: {$task->status}, Parent ID: {$task->parent_id}\n";
    }
    
    // Simuler une requête à l'API
    $taskIds = $tasks->pluck('id')->toArray();
    $lastCheck = time() * 1000 - 60000; // Il y a 1 minute
    
    echo "\n🔍 Test de l'API avec les IDs: " . implode(', ', $taskIds) . "\n";
    echo "⏰ Dernière vérification: " . date('Y-m-d H:i:s', $lastCheck / 1000) . "\n";
    
    // Vérifier les tâches modifiées
    $lastCheckDate = date('Y-m-d H:i:s', $lastCheck / 1000);
    
    $updatedTasks = Task::whereIn('id', $taskIds)
        ->where('updated_at', '>', $lastCheckDate)
        ->get()
        ->map(function($task) {
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
    
    echo "\n✅ Résultat de l'API :\n";
    echo "Tâches mises à jour: " . $updatedTasks->count() . "\n";
    
    foreach ($updatedTasks as $task) {
        echo "- ID: {$task['id']}, Statut: {$task['status']}, Terminées: {$task['completed_count']}\n";
    }
    
    echo "\n🎉 Test de l'API réussi !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
