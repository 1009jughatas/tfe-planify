@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-project-diagram text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
                    <p class="text-gray-600 mt-1 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Projet personnel
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('projects.edit', $project->id) }}" class="btn-secondary-modern">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('projects.index') }}" class="btn-secondary-modern">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Informations du projet -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Description -->
            @if($project->description)
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-file-text text-blue-500 mr-2"></i>
                            Description
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $project->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Tâches du projet -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-tasks text-orange-500 mr-2"></i>
                            Tâches ({{ $project->tasks->count() }})
                        </h3>
                        <a href="{{ route('tasks.create', ['project' => $project->id]) }}" class="btn-primary-modern">
                            <i class="fas fa-plus mr-2"></i>
                            Nouvelle tâche
                        </a>
                    </div>
                </div>
                <div class="modern-card-body">
                    @php
                        // Séparer les tâches principales des sous-tâches
                        $mainTasks = $project->tasks->whereNull('parent_id');
                        $subtasks = $project->tasks->whereNotNull('parent_id');
                    @endphp
                    
                    @if($project->tasks->count() > 0)
                        <div class="space-y-6">
                            <!-- Tâches principales -->
                            @if($mainTasks->count() > 0)
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-tasks text-blue-500 mr-2"></i>
                                        Tâches Principales ({{ $mainTasks->count() }})
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach($mainTasks as $task)
                                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 hover:shadow-md transition-all duration-200">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-3 mb-2">
                                                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-tasks text-white text-sm"></i>
                                                            </div>
                                                            <h5 class="font-semibold text-gray-900">{{ $task->title }}</h5>
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                                @if($task->status === 'completed') bg-green-100 text-green-800
                                                                @elseif($task->status === 'in-progress') bg-blue-100 text-blue-800
                                                                @elseif($task->status === 'blocked') bg-red-100 text-red-800
                                                                @else bg-gray-100 text-gray-800 @endif">
                                                                @switch($task->status)
                                                                    @case('todo') 📋 À faire @break
                                                                    @case('in-progress') 🚀 En cours @break
                                                                    @case('completed') ✅ Terminé @break
                                                                    @case('blocked') 🚫 Bloqué @break
                                                                    @default 📋 À faire
                                                                @endswitch
                                                            </span>
                                                        </div>
                                                        
                                                        @if($task->description)
                                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $task->description }}</p>
                                                        @endif
                                                        
                                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                            @if($task->due_date)
                                                                <span class="flex items-center">
                                                                    <i class="fas fa-calendar mr-1"></i>
                                                                    {{ $task->due_date->format('d/m/Y') }}
                                                                </span>
                                                            @endif
                                                            <span class="flex items-center">
                                                                <i class="fas fa-flag mr-1"></i>
                                                                @switch($task->priority)
                                                                    @case(0) 🟢 Basse @break
                                                                    @case(1) 🟡 Moyenne @break
                                                                    @case(2) 🟠 Haute @break
                                                                    @case(3) 🔴 Urgente @break
                                                                    @default 🟢 Basse
                                                                @endswitch
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-center space-x-2">
                                                        <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('tasks.edit', $task->id) }}" class="text-gray-600 hover:text-gray-800 text-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                
                                                <!-- Sous-tâches de cette tâche -->
                                                @php
                                                    $taskSubtasks = $subtasks->where('parent_id', $task->id);
                                                @endphp
                                                
                                                @if($taskSubtasks->count() > 0)
                                                    <div class="mt-4 pl-6 border-l-2 border-blue-200">
                                                        <h6 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                            <i class="fas fa-list-ul text-blue-500 mr-1"></i>
                                                            Sous-tâches ({{ $taskSubtasks->count() }})
                                                        </h6>
                                                        <div class="space-y-2">
                                                            @foreach($taskSubtasks as $subtask)
                                                                <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-sm transition-all duration-200">
                                                                    <div class="flex items-center justify-between">
                                                                        <div class="flex items-center space-x-2">
                                                                            <div class="w-6 h-6 bg-orange-500 rounded flex items-center justify-center">
                                                                                <i class="fas fa-list text-white text-xs"></i>
                                                                            </div>
                                                                            <span class="text-sm font-medium text-gray-900">{{ $subtask->title }}</span>
                                                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                                                @if($subtask->status === 'completed') bg-green-100 text-green-800
                                                                                @elseif($subtask->status === 'in-progress') bg-blue-100 text-blue-800
                                                                                @elseif($subtask->status === 'blocked') bg-red-100 text-red-800
                                                                                @else bg-gray-100 text-gray-800 @endif">
                                                                                @switch($subtask->status)
                                                                                    @case('todo') 📋 À faire @break
                                                                                    @case('in-progress') 🚀 En cours @break
                                                                                    @case('completed') ✅ Terminé @break
                                                                                    @case('blocked') 🚫 Bloqué @break
                                                                                    @default 📋 À faire
                                                                                @endswitch
                                                                            </span>
                                                                        </div>
                                                                        <div class="flex items-center space-x-1">
                                                                            <a href="{{ route('tasks.show', $subtask->id) }}" class="text-blue-600 hover:text-blue-800 text-xs">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="{{ route('tasks.edit', $subtask->id) }}" class="text-gray-600 hover:text-gray-800 text-xs">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Sous-tâches orphelines -->
                            @php
                                $orphanSubtasks = $subtasks->filter(function($subtask) use ($mainTasks) {
                                    return !$mainTasks->contains('id', $subtask->parent_id);
                                });
                            @endphp
                            
                            @if($orphanSubtasks->count() > 0)
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                                        Sous-tâches Orphelines ({{ $orphanSubtasks->count() }})
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($orphanSubtasks as $subtask)
                                            <div class="bg-gradient-to-r from-orange-50 to-yellow-50 border border-orange-200 rounded-lg p-3">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-2">
                                                        <div class="w-6 h-6 bg-orange-500 rounded flex items-center justify-center">
                                                            <i class="fas fa-list text-white text-xs"></i>
                                                        </div>
                                                        <span class="text-sm font-medium text-gray-900">{{ $subtask->title }}</span>
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                                            @if($subtask->status === 'completed') bg-green-100 text-green-800
                                                            @elseif($subtask->status === 'in-progress') bg-blue-100 text-blue-800
                                                            @elseif($subtask->status === 'blocked') bg-red-100 text-red-800
                                                            @else bg-gray-100 text-gray-800 @endif">
                                                            @switch($subtask->status)
                                                                @case('todo') 📋 À faire @break
                                                                @case('in-progress') 🚀 En cours @break
                                                                @case('completed') ✅ Terminé @break
                                                                @case('blocked') 🚫 Bloqué @break
                                                                @default 📋 À faire
                                                            @endswitch
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center space-x-1">
                                                        <a href="{{ route('tasks.show', $subtask->id) }}" class="text-blue-600 hover:text-blue-800 text-xs">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('tasks.edit', $subtask->id) }}" class="text-gray-600 hover:text-gray-800 text-xs">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-tasks text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune tâche</h3>
                            <p class="text-gray-600 mb-6">Commencez par créer votre première tâche.</p>
                            <a href="{{ route('tasks.create', ['project' => $project->id]) }}" class="btn-primary-modern">
                                <i class="fas fa-plus mr-2"></i>
                                Créer une tâche
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statut du projet -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Statut du Projet
                    </h3>
                </div>
                <div class="modern-card-body">
                    <form action="{{ route('projects.update', $project->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut actuel</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="planning" {{ $project->status === 'planning' ? 'selected' : '' }}>📋 En planification</option>
                                <option value="active" {{ $project->status === 'active' ? 'selected' : '' }}>🚀 Actif</option>
                                <option value="on-hold" {{ $project->status === 'on-hold' ? 'selected' : '' }}>⏸️ En pause</option>
                                <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>✅ Terminé</option>
                                <option value="cancelled" {{ $project->status === 'cancelled' ? 'selected' : '' }}>❌ Annulé</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full btn-primary-modern">
                            <i class="fas fa-save mr-2"></i>
                            Mettre à jour
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-bar text-green-500 mr-2"></i>
                        Statistiques
                    </h3>
                </div>
                <div class="modern-card-body">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total des tâches</span>
                            <span class="text-lg font-semibold text-gray-900">{{ $project->tasks->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Tâches terminées</span>
                            <span class="text-lg font-semibold text-green-600">{{ $project->tasks->where('status', 'completed')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">En cours</span>
                            <span class="text-lg font-semibold text-blue-600">{{ $project->tasks->where('status', 'in-progress')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Bloquées</span>
                            <span class="text-lg font-semibold text-red-600">{{ $project->tasks->where('status', 'blocked')->count() }}</span>
                        </div>
                        
                        @if($project->tasks->count() > 0)
                            @php
                                $completedTasks = $project->tasks->where('status', 'completed')->count();
                                $totalTasks = $project->tasks->count();
                                $progress = ($completedTasks / $totalTasks) * 100;
                            @endphp
                            <div class="pt-4 border-t border-gray-200">
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>Progression</span>
                                    <span>{{ round($progress) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations du projet -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info text-purple-500 mr-2"></i>
                        Informations
                    </h3>
                </div>
                <div class="modern-card-body">
                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-user w-4 h-4 mr-2 text-gray-400"></i>
                            <span>Créé par {{ $project->author->name }}</span>
                        </div>
                        
                        @if($project->start_date)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar w-4 h-4 mr-2 text-gray-400"></i>
                                <span>Début: {{ $project->start_date->format('d/m/Y') }}</span>
                            </div>
                        @endif

                        @if($project->end_date)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-flag-checkered w-4 h-4 mr-2 text-gray-400"></i>
                                <span>Fin: {{ $project->end_date->format('d/m/Y') }}</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock w-4 h-4 mr-2 text-gray-400"></i>
                            <span>Créé le {{ $project->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection