<div class="modern-card">
    <div class="modern-card-header">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-tasks text-primary-600 mr-2"></i>
                {{ $title ? $title : 'Tâches du projet' }}
            </h3>
            @if ($add)
                <a href="{{ route('tasks.create', $project->id) }}" class="btn-primary-modern">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvelle tâche
                </a>
            @endif
        </div>
    </div>
    <div class="modern-card-body">
        @if ($tasks->isEmpty())
            <!-- État vide -->
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tasks text-gray-400 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Aucune tâche</h4>
                <p class="text-gray-600 mb-6">Commencez par créer votre première tâche pour ce projet.</p>
                @if ($add)
                    <a href="{{ route('tasks.create', $project->id) }}" class="btn-primary-modern">
                        <i class="fas fa-plus mr-2"></i>
                        Créer la première tâche
                    </a>
                @endif
            </div>
        @else
            <!-- Kanban Board -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- À Faire -->
                <div class="kanban-column">
                    <div class="kanban-header bg-gray-100">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-gray-700">
                                <i class="fas fa-circle text-gray-400 mr-2"></i>
                                À faire
                            </h4>
                            <span class="badge-secondary">
                                @php $todoTasks = $tasks->whereIn('status', ['todo', 'pending']) @endphp
                                {{ $todoTasks->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="kanban-content">
                        @if ($todoTasks->isEmpty())
                            <div class="empty-column">
                                <i class="fas fa-inbox text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Aucune tâche</p>
                            </div>
                        @endif
                        @foreach ($todoTasks as $task)
                            @include('tasks.task_card', ['task' => $task])
                        @endforeach
                    </div>
                </div>

                <!-- En Cours -->
                <div class="kanban-column">
                    <div class="kanban-header bg-blue-100">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-blue-700">
                                <i class="fas fa-play text-blue-500 mr-2"></i>
                                En cours
                            </h4>
                            <span class="badge-primary">
                                @php $inProgressTasks = $tasks->where('status', 'in-progress') @endphp
                                {{ $inProgressTasks->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="kanban-content">
                        @if ($inProgressTasks->isEmpty())
                            <div class="empty-column">
                                <i class="fas fa-play text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Aucune tâche</p>
                            </div>
                        @endif
                        @foreach ($inProgressTasks as $task)
                            @include('tasks.task_card', ['task' => $task])
                        @endforeach
                    </div>
                </div>

                <!-- Bloqué -->
                <div class="kanban-column">
                    <div class="kanban-header bg-red-100">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-red-700">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                Bloqué
                            </h4>
                            <span class="badge-danger">
                                @php $blockedTasks = $tasks->where('status', 'blocked') @endphp
                                {{ $blockedTasks->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="kanban-content">
                        @if ($blockedTasks->isEmpty())
                            <div class="empty-column">
                                <i class="fas fa-exclamation-triangle text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Aucune tâche</p>
                            </div>
                        @endif
                        @foreach ($blockedTasks as $task)
                            @include('tasks.task_card', ['task' => $task])
                        @endforeach
                    </div>
                </div>

                <!-- Terminé -->
                <div class="kanban-column">
                    <div class="kanban-header bg-green-100">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-green-700">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Terminé
                            </h4>
                            <span class="badge-success">
                                @php $doneTasks = $tasks->whereIn('status', ['done', 'completed']) @endphp
                                {{ $doneTasks->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="kanban-content">
                        @if ($doneTasks->isEmpty())
                            <div class="empty-column">
                                <i class="fas fa-check-circle text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Aucune tâche</p>
                            </div>
                        @endif
                        @foreach ($doneTasks as $task)
                            @include('tasks.task_card', ['task' => $task])
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .kanban-column {
        @apply bg-gray-50 rounded-xl border border-gray-200;
    }
    
    .kanban-header {
        @apply p-4 rounded-t-xl border-b border-gray-200;
    }
    
    .kanban-content {
        @apply p-4 space-y-3 min-h-96;
    }
    
    .empty-column {
        @apply flex flex-col items-center justify-center py-8 text-center;
    }
    
    @media (max-width: 768px) {
        .kanban-content {
            @apply min-h-64;
        }
    }
</style>