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
                    <h1 class="text-2xl font-bold text-gray-900">{{ $projet->name }}</h1>
                    <p class="text-gray-600 mt-1 flex items-center">
                        <i class="fas fa-building mr-2 text-blue-500"></i>
                        {{ $company->name }}
                    </p>
                </div>
            </div>
            @if(auth()->user()->isAdminEntreprise())
                <div class="flex items-center space-x-3">
                    <a href="{{ route('entreprise.projets.edit', $projet->id) }}" class="btn-secondary-modern">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier
                    </a>
                    <a href="{{ route('entreprise.projets.index') }}" class="btn-secondary-modern">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Informations du projet -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Description -->
            @if($projet->description)
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-file-text text-blue-500 mr-2"></i>
                            Description
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $projet->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Tâches du projet -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-tasks text-orange-500 mr-2"></i>
                            Tâches ({{ $projet->tasks->count() }})
                        </h3>
                        @if(auth()->user()->isAdminEntreprise())
                            <a href="{{ route('entreprise.tasks.create', ['project' => $projet->id]) }}" class="btn-primary-modern" id="nouvelle-tache-btn" onclick="handleTaskCreation(event, {{ $projet->id }})">
                                <i class="fas fa-plus mr-2"></i>
                                Nouvelle tâche
                            </a>
                        @endif
                    </div>
                </div>
                <div class="modern-card-body">
                    @php
                        // Filtrer les tâches selon les permissions de l'utilisateur
                        $visibleTasks = $projet->tasks->filter(function($task) {
                            return auth()->user()->can('view', $task);
                        });
                        
                        // Séparer les tâches principales des sous-tâches
                        $mainTasks = $visibleTasks->whereNull('parent_id');
                        $subtasks = $visibleTasks->whereNotNull('parent_id');
                    @endphp
                    
                    @if($visibleTasks->count() > 0)
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
                                                                    @case('completed') ✅ Terminé @break
                                                                    @case('in-progress') 🚀 En cours @break
                                                                    @case('blocked') 🚫 Bloqué @break
                                                                    @default 📋 En attente
                                                                @endswitch
                                                            </span>
                                                        </div>
                                                        
                                                        @if($task->description)
                                                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ $task->description }}</p>
                                                        @endif
                                                        
                                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                            @if($task->assignedUser)
                                                                <span class="flex items-center">
                                                                    <i class="fas fa-user mr-1"></i>
                                                                    {{ $task->assignedUser->name }}
                                                                </span>
                                                            @endif
                                                            @if($task->due_date)
                                                                <span class="flex items-center">
                                                                    <i class="fas fa-clock mr-1"></i>
                                                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                                                </span>
                                                            @endif
                                                            <span class="flex items-center">
                                                                <i class="fas fa-calendar mr-1"></i>
                                                                Créée {{ $task->created_at->format('d/m/Y') }}
                                                            </span>
                                                        </div>
                                                        
                                                        <!-- Sous-tâches de cette tâche -->
                                                        @php
                                                            $taskSubtasks = $subtasks->where('parent_id', $task->id);
                                                        @endphp
                                                        @if($taskSubtasks->count() > 0)
                                                            <div class="mt-3 pt-3 border-t border-blue-200">
                                                                <div class="flex items-center justify-between mb-2">
                                                                    <span class="text-sm font-medium text-gray-700">
                                                                        <i class="fas fa-list-ul mr-1"></i>
                                                                        Sous-tâches ({{ $taskSubtasks->count() }})
                                                                    </span>
                                                                    <span class="text-xs text-gray-500">
                                                                        {{ $taskSubtasks->where('status', 'completed')->count() }}/{{ $taskSubtasks->count() }} terminées
                                                                    </span>
                                                                </div>
                                                                <div class="space-y-2">
                                                                    @foreach($taskSubtasks as $subtask)
                                                                        <div class="bg-white/60 rounded-lg border border-blue-100 overflow-hidden">
                                                                            <!-- Sous-tâche principale -->
                                                                            <div class="flex items-center justify-between p-2">
                                                                                <div class="flex items-center space-x-2">
                                                                                    <i class="fas fa-arrow-right text-blue-400 text-xs"></i>
                                                                                    <span class="text-sm text-gray-700 font-medium">{{ $subtask->title }}</span>
                                                                                    <span class="px-1.5 py-0.5 text-xs font-medium rounded-full
                                                                                        @if($subtask->status === 'completed') bg-green-100 text-green-700
                                                                                        @elseif($subtask->status === 'in-progress') bg-blue-100 text-blue-700
                                                                                        @else bg-gray-100 text-gray-700 @endif">
                                                                                        @switch($subtask->status)
                                                                                            @case('completed') ✅ @break
                                                                                            @case('in-progress') 🚀 @break
                                                                                            @default 📋
                                                                                        @endswitch
                                                                                    </span>
                                                                                </div>
                                                                                <a href="{{ route('entreprise.tasks.show', $subtask->id) }}" 
                                                                                   class="text-blue-500 hover:text-blue-700 text-xs">
                                                                                    <i class="fas fa-external-link-alt"></i>
                                                                                </a>
                                                                            </div>
                                                                            
                                                                            <!-- Sous-tâches de cette sous-tâche (niveau 3) -->
                                                                            @php
                                                                                $subtaskChildren = $subtasks->where('parent_id', $subtask->id);
                                                                            @endphp
                                                                            @if($subtaskChildren->count() > 0)
                                                                                <div class="bg-blue-50/50 border-t border-blue-100">
                                                                                    <div class="px-3 py-2 border-b border-blue-100">
                                                                                        <div class="flex items-center justify-between">
                                                                                            <span class="text-xs font-medium text-blue-700">
                                                                                                <i class="fas fa-sitemap mr-1"></i>
                                                                                                Sous-sous-tâches ({{ $subtaskChildren->count() }})
                                                                                            </span>
                                                                                            <span class="text-xs text-blue-600">
                                                                                                {{ $subtaskChildren->where('status', 'completed')->count() }}/{{ $subtaskChildren->count() }} terminées
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="p-2 space-y-1">
                                                                                        @foreach($subtaskChildren as $childTask)
                                                                                            <div class="flex items-center justify-between bg-white/80 rounded border border-blue-200 p-2">
                                                                                                <div class="flex items-center space-x-2">
                                                                                                    <i class="fas fa-arrow-turn-down-right text-blue-300 text-xs"></i>
                                                                                                    <span class="text-xs text-gray-600">{{ $childTask->title }}</span>
                                                                                                    <span class="px-1 py-0.5 text-xs font-medium rounded-full
                                                                                                        @if($childTask->status === 'completed') bg-green-100 text-green-600
                                                                                                        @elseif($childTask->status === 'in-progress') bg-blue-100 text-blue-600
                                                                                                        @else bg-gray-100 text-gray-600 @endif">
                                                                                                        @switch($childTask->status)
                                                                                                            @case('completed') ✅ @break
                                                                                                            @case('in-progress') 🚀 @break
                                                                                                            @default 📋
                                                                                                        @endswitch
                                                                                                    </span>
                                                                                                </div>
                                                                                                <a href="{{ route('entreprise.tasks.show', $childTask->id) }}" 
                                                                                                   class="text-blue-400 hover:text-blue-600 text-xs">
                                                                                                    <i class="fas fa-external-link-alt"></i>
                                                                                                </a>
                                                                                            </div>
                                                                                            
                                                                                            <!-- Sous-tâches de niveau 4 (si elles existent) -->
                                                                                            @php
                                                                                                $level4Tasks = $subtasks->where('parent_id', $childTask->id);
                                                                                            @endphp
                                                                                            @if($level4Tasks->count() > 0)
                                                                                                <div class="ml-4 bg-blue-25/50 rounded border border-blue-200 p-2">
                                                                                                    <div class="flex items-center justify-between mb-1">
                                                                                                        <span class="text-xs font-medium text-blue-600">
                                                                                                            <i class="fas fa-layer-group mr-1"></i>
                                                                                                            Niveau 4 ({{ $level4Tasks->count() }})
                                                                                                        </span>
                                                                                                        <span class="text-xs text-blue-500">
                                                                                                            {{ $level4Tasks->where('status', 'completed')->count() }}/{{ $level4Tasks->count() }} terminées
                                                                                                        </span>
                                                                                                    </div>
                                                                                                    <div class="space-y-1">
                                                                                                        @foreach($level4Tasks as $level4Task)
                                                                                                            <div class="flex items-center justify-between bg-white/90 rounded border border-blue-200 p-1.5">
                                                                                                                <div class="flex items-center space-x-1">
                                                                                                                    <i class="fas fa-arrow-turn-down-right text-blue-200 text-xs"></i>
                                                                                                                    <span class="text-xs text-gray-500">{{ $level4Task->title }}</span>
                                                                                                                    <span class="px-1 py-0.5 text-xs font-medium rounded-full
                                                                                                                        @if($level4Task->status === 'completed') bg-green-100 text-green-500
                                                                                                                        @elseif($level4Task->status === 'in-progress') bg-blue-100 text-blue-500
                                                                                                                        @else bg-gray-100 text-gray-500 @endif">
                                                                                                                        @switch($level4Task->status)
                                                                                                                            @case('completed') ✅ @break
                                                                                                                            @case('in-progress') 🚀 @break
                                                                                                                            @default 📋
                                                                                                                        @endswitch
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                                <a href="{{ route('entreprise.tasks.show', $level4Task->id) }}" 
                                                                                                                   class="text-blue-300 hover:text-blue-500 text-xs">
                                                                                                                    <i class="fas fa-external-link-alt"></i>
                                                                                                                </a>
                                                                                                            </div>
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="flex items-center space-x-2 ml-4">
                                                        <a href="{{ route('entreprise.tasks.show', $task->id) }}" 
                                                           class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-xs font-medium rounded-lg hover:bg-blue-600 transition-colors">
                                                            <i class="fas fa-eye mr-1"></i>
                                                            Voir
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
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-tasks text-4xl mb-3"></i>
                            <p>Aucune tâche créée</p>
                            @if(auth()->user()->isAdminEntreprise())
                                <a href="{{ route('entreprise.tasks.create', ['project' => $projet->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Créer la première tâche
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statut et priorité -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Informations</h3>
                </div>
                <div class="modern-card-body space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($projet->status === 'completed') bg-green-100 text-green-800
                            @elseif($projet->status === 'in-progress') bg-blue-100 text-blue-800
                            @elseif($projet->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @switch($projet->status)
                                @case('planning') 📋 En planification @break
                                @case('active') 🚀 Actif @break
                                @case('on-hold') ⏸️ En pause @break
                                @case('completed') ✅ Terminé @break
                                @case('cancelled') ❌ Annulé @break
                                @default 📋 En planification
                            @endswitch
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($projet->priority === 'high') bg-red-100 text-red-800
                            @elseif($projet->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            @switch($projet->priority)
                                @case('high') 🔴 Haute @break
                                @case('medium') 🟡 Moyenne @break
                                @case('low') 🔵 Faible @break
                                @default 🔵 Faible
                            @endswitch
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Créé par</label>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                <span class="text-xs text-white font-medium">{{ substr($projet->author->name, 0, 1) }}</span>
                            </div>
                            <span class="text-sm text-gray-900">{{ $projet->author->name }}</span>
                        </div>
                    </div>
                    
                    @if($projet->start_date)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($projet->start_date)->format('d/m/Y') }}</span>
                        </div>
                    @endif
                    
                    @if($projet->end_date)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($projet->end_date)->format('d/m/Y') }}</span>
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Créé le</label>
                        <span class="text-sm text-gray-900">{{ $projet->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Participants -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Participants ({{ $projet->participants->count() }})</h3>
                </div>
                <div class="modern-card-body">
                    @if($projet->participants->count() > 0)
                        <div class="space-y-3">
                            @foreach($projet->participants as $participant)
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                        <span class="text-sm text-white font-medium">{{ substr($participant->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $participant->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $participant->email }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($participant->role === 'admin_entreprise') bg-purple-100 text-purple-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ $participant->role === 'admin_entreprise' ? 'Admin' : 'Employé' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-users text-2xl mb-2"></i>
                            <p class="text-sm">Aucun participant assigné</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Statistiques</h3>
                </div>
                <div class="modern-card-body space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total des tâches</span>
                        <span class="font-semibold text-gray-900">{{ $visibleTasks->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Tâches terminées</span>
                        <span class="font-semibold text-green-600">{{ $visibleTasks->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">En cours</span>
                        <span class="font-semibold text-blue-600">{{ $visibleTasks->where('status', 'in-progress')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">En attente</span>
                        <span class="font-semibold text-gray-600">{{ $visibleTasks->where('status', 'pending')->count() }}</span>
                    </div>
                    
                    @if($visibleTasks->count() > 0)
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">Progression</span>
                                <span class="text-sm font-medium text-gray-900">{{ round(($visibleTasks->where('status', 'completed')->count() / $visibleTasks->count()) * 100) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($visibleTasks->where('status', 'completed')->count() / $visibleTasks->count()) * 100 }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Changement de statut du projet -->
    @if(auth()->user()->isAdminEntreprise())
    <div class="modern-card mb-8">
        <div class="modern-card-header">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-tasks text-blue-500 mr-2"></i>
                Statut du Projet
            </h3>
        </div>
        <div class="modern-card-body">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-project-diagram text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $projet->name }}</h4>
                            <p class="text-sm text-gray-600">Gérez le statut de ce projet d'entreprise</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-500">Statut actuel</span>
                        <div class="mt-1">
                            <span class="badge-{{ 
                                $projet->status == 'completed' ? 'success' :
                                ($projet->status == 'active' ? 'primary' :
                                ($projet->status == 'on-hold' ? 'warning' :
                                ($projet->status == 'cancelled' ? 'danger' : 'secondary')))
                            }} text-sm font-medium">
                                @switch($projet->status)
                                    @case('planning')
                                        📋 En planification
                                        @break
                                    @case('active')
                                        🚀 Actif
                                        @break
                                    @case('on-hold')
                                        ⏸️ En pause
                                        @break
                                    @case('completed')
                                        ✅ Terminé
                                        @break
                                    @case('cancelled')
                                        ❌ Annulé
                                        @break
                                    @default
                                        📋 En planification
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label for="project-status" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-exchange-alt mr-1"></i>
                            Nouveau statut
                        </label>
                        <select id="project-status" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white shadow-sm" 
                                data-project-id="{{ $projet->id }}" 
                                data-original-status="{{ $projet->status }}">
                            <option value="planning" @if($projet->status == 'planning') selected @endif>📋 En planification</option>
                            <option value="active" @if($projet->status == 'active') selected @endif>🚀 Actif</option>
                            <option value="on-hold" @if($projet->status == 'on-hold') selected @endif>⏸️ En pause</option>
                            <option value="completed" @if($projet->status == 'completed') selected @endif>✅ Terminé</option>
                            <option value="cancelled" @if($projet->status == 'cancelled') selected @endif>❌ Annulé</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <button id="updateProjectStatusBtn" 
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-check mr-2"></i>
                            Mettre à Jour le Statut
                        </button>
                        
                        <div class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Le statut sera mis à jour immédiatement
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .modern-card {
        @apply bg-white rounded-xl shadow-sm border border-gray-200;
    }
    
    .modern-card-header {
        @apply px-6 py-4 border-b border-gray-200;
    }
    
    .modern-card-body {
        @apply p-6;
    }
    
    .btn-primary-modern {
        @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200;
    }
    
    .btn-secondary-modern {
        @apply inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg shadow-sm border border-gray-300 hover:shadow-md hover:scale-105 transition-all duration-200;
    }
</style>

<script>
function handleTaskCreation(event, projectId) {
    // Empêcher le comportement par défaut
    event.preventDefault();
    
    // Construire l'URL correcte pour l'entreprise
    const correctUrl = `/entreprise/projects/${projectId}/tasks/create`;
    
    console.log('Redirection vers:', correctUrl);
    
    // Rediriger vers la bonne URL
    window.location.href = correctUrl;
}

// Gestion du changement de statut du projet
$(document).ready(function () {
    console.log('JavaScript chargé pour la page de projet entreprise');
    let originalProjectStatus = $('#project-status').data('original-status');
    console.log('Statut original du projet:', originalProjectStatus);
    
    // Vérifier si l'élément existe
    if ($('#updateProjectStatusBtn').length === 0) {
        console.error('❌ Élément #updateProjectStatusBtn non trouvé');
        return;
    }
    console.log('✅ Élément #updateProjectStatusBtn trouvé');
    
    // Gérer la validation du statut du projet
    $('#updateProjectStatusBtn').click(function (e) {
        e.preventDefault();
        console.log('🎯 BOUTON PROJET CLIQUÉ !');
        let projectId = $('#project-status').data('project-id');
        let newStatus = $('#project-status').val();
        let selectElement = $('#project-status');
        
        // Vérifier si le statut a vraiment changé
        if (newStatus === originalProjectStatus) {
            showNotification('Aucun changement détecté. Le statut est déjà : ' + newStatus, 'info');
            return;
        }

        // Désactiver les contrôles pendant la requête
        selectElement.prop('disabled', true);
        $('#updateProjectStatusBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Mise à jour...');

        $.ajax({
            url: `/entreprise/projects/${projectId}/update-status`,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            success: function (response) {
                showNotification('Statut du projet mis à jour avec succès !', 'success');
                originalProjectStatus = newStatus;
                
                // Recharger la page après un court délai
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function (xhr, status, error) {
                // Réactiver les contrôles
                selectElement.prop('disabled', false);
                $('#updateProjectStatusBtn').prop('disabled', false).html('<i class="fas fa-check mr-2"></i>Mettre à Jour le Statut');
                
                let errorMessage = 'Erreur lors de la mise à jour du statut du projet.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                showNotification(errorMessage, 'error');
            }
        });
    });

    function showNotification(message, type) {
        let bgColor, textColor, icon;
        
        switch(type) {
            case 'success':
                bgColor = 'bg-green-500';
                textColor = 'text-white';
                icon = 'check';
                break;
            case 'error':
                bgColor = 'bg-red-500';
                textColor = 'text-white';
                icon = 'exclamation';
                break;
            case 'info':
                bgColor = 'bg-blue-500';
                textColor = 'text-white';
                icon = 'info-circle';
                break;
            default:
                bgColor = 'bg-gray-500';
                textColor = 'text-white';
                icon = 'info';
        }
        
        const notification = $(`
            <div class="fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${bgColor} ${textColor}">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-${icon}"></i>
                    <span>${message}</span>
                </div>
            </div>
        `);
        
        $('body').append(notification);
        
        // Supprimer la notification après 3 secondes
        setTimeout(function() {
            notification.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }
});
</script>
@endsection
