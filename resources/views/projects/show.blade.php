<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-folder-open text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
                    <div class="flex items-center space-x-4 mt-2">
                        <span class="badge-{{ 
                            $project->status == 'completed' ? 'success' :
                            ($project->status == 'active' ? 'primary' :
                            ($project->status == 'on-hold' ? 'warning' :
                            ($project->status == 'cancelled' ? 'danger' : 'secondary')))
                        }}">
                            @switch($project->status)
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
                        @if($project->priority)
                            <span class="badge-{{ 
                                $project->priority == 'high' ? 'danger' :
                                ($project->priority == 'medium' ? 'warning' : 'secondary')
                            }}">
                                @switch($project->priority)
                                    @case('high')
                                        🔴 Priorité élevée
                                        @break
                                    @case('medium')
                                        🟡 Priorité moyenne
                                        @break
                                    @case('low')
                                        🔵 Priorité faible
                                        @break
                                @endswitch
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('projects.tasks', $project->id) }}" class="btn-primary-modern">
                    <i class="fas fa-tasks mr-2"></i>
                    Gérer les tâches
                </a>
                <a href="{{ route('projects.edit', $project->id) }}" class="btn-secondary-modern">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="btn-secondary-modern">
                        <i class="fas fa-ellipsis-v mr-2"></i>
                        Actions
                    </button>
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border border-gray-200">
                        <div class="py-1">
                            <a href="{{ route('tasks.create', ['project' => $project->id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-plus mr-2"></i>Nouvelle tâche
                            </a>
                            {{-- Fonctionnalités à implémenter plus tard --}}
                            {{-- <a href="{{ route('projects.duplicate', $project->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-copy mr-2"></i>Dupliquer le projet
                            </a>
                            <hr class="my-1">
                            <a href="{{ route('projects.export', $project->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-download mr-2"></i>Exporter
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Statistiques rapides -->
        @php
            $totalTasks = $project->tasks ? $project->tasks->count() : 0;
            $completedTasks = $project->tasks ? $project->tasks->where('status', 'done')->count() : 0;
            $inProgressTasks = $project->tasks ? $project->tasks->where('status', 'in-progress')->count() : 0;
            $blockedTasks = $project->tasks ? $project->tasks->where('status', 'blocked')->count() : 0;
            $todoTasks = $project->tasks ? $project->tasks->where('status', 'todo')->count() : 0;
            $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Progression -->
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Progression</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $progressPercentage }}%</p>
                        <p class="text-xs text-gray-500 mt-1">Avancement</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full transition-all duration-500" 
                             style="width: {{ $progressPercentage }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Tâches totales -->
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Tâches ouvertes</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalTasks }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-tasks text-white text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Tâches en cours -->
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">En cours</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $inProgressTasks }}</p>
                        <p class="text-xs text-gray-500 mt-1">Actives</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-primary rounded-xl flex items-center justify-center">
                        <i class="fas fa-play text-white text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Tâches terminées -->
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Terminées</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $completedTasks }}</p>
                        <p class="text-xs text-gray-500 mt-1">Finalisées</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description du projet -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                            Description du Projet
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <p class="text-gray-700 leading-relaxed">
                            {{ $project->description ?? 'Aucune description fournie pour ce projet.' }}
                        </p>
                    </div>
                </div>

                <!-- Statistiques du projet -->
                @php
                    $totalTasks = $project->tasks ? $project->tasks->count() : 0;
                    $completedTasks = $project->tasks ? $project->tasks->where('status', 'done')->count() : 0;
                    $inProgressTasks = $project->tasks ? $project->tasks->where('status', 'in-progress')->count() : 0;
                    $blockedTasks = $project->tasks ? $project->tasks->where('status', 'blocked')->count() : 0;
                    $todoTasks = $project->tasks ? $project->tasks->where('status', 'todo')->count() : 0;
                    $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                @endphp

                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-chart-bar text-primary-600 mr-2"></i>
                            Statistiques du Projet
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-6">
                            <!-- Barre de progression -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Progression globale</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $progressPercentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-3 rounded-full transition-all duration-300" 
                                         style="width: {{ $progressPercentage }}%"></div>
                                </div>
                            </div>

                            <!-- Statistiques détaillées -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-600">{{ $todoTasks }}</div>
                                    <div class="text-xs text-gray-600">📋 À faire</div>
                                </div>
                                <div class="text-center p-3 bg-blue-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ $inProgressTasks }}</div>
                                    <div class="text-xs text-gray-600">🔄 En cours</div>
                                </div>
                                <div class="text-center p-3 bg-red-50 rounded-lg">
                                    <div class="text-2xl font-bold text-red-600">{{ $blockedTasks }}</div>
                                    <div class="text-xs text-gray-600">🚫 Bloquées</div>
                                </div>
                                <div class="text-center p-3 bg-green-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">{{ $completedTasks }}</div>
                                    <div class="text-xs text-gray-600">✅ Terminées</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Statut du projet -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-tasks text-primary-600 mr-2"></i>
                            Statut du Projet
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="flex items-center space-x-3">
                            <span class="badge-{{ 
                                $project->status == 'completed' ? 'success' :
                                ($project->status == 'active' ? 'primary' :
                                ($project->status == 'on-hold' ? 'warning' :
                                ($project->status == 'cancelled' ? 'danger' : 'secondary')))
                            }}">
                                @switch($project->status)
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

                <!-- Informations générales -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                            Informations Générales
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-4">
                            <!-- Auteur -->
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Auteur</p>
                                    <p class="text-sm text-gray-600">{{ $project->author->name ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Date de début -->
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-calendar-plus text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Date de Début</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $project->start_date ? $project->start_date->format('d/m/Y') : 'Non définie' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Date de fin -->
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-calendar-check text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Date de Fin</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $project->end_date ? $project->end_date->format('d/m/Y') : 'Non définie' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Créé le -->
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Créé le</p>
                                    <p class="text-sm text-gray-600">{{ $project->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-bolt text-primary-600 mr-2"></i>
                            Actions
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-3">
                            <a href="{{ route('projects.tasks', $project->id) }}" class="btn-primary-modern w-full">
                                <i class="fas fa-tasks mr-2"></i>
                                Gérer les Tâches
                            </a>
                            <a href="{{ route('tasks.create', ['project' => $project->id]) }}" class="btn-accent-modern w-full">
                                <i class="fas fa-plus mr-2"></i>
                                Nouvelle Tâche
                            </a>
                            <a href="{{ route('projects.edit', $project->id) }}" class="btn-secondary-modern w-full">
                                <i class="fas fa-edit mr-2"></i>
                                Modifier le Projet
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Changer le Statut -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-exchange-alt text-primary-600 mr-2"></i>
                            Changer le Statut
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <form id="statusForm" action="{{ route('projects.updateStatus', $project->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label for="status" class="form-label-modern">Nouveau Statut</label>
                                <select name="status" id="status" class="input-modern" required>
                                    <option value="">Sélectionner un statut</option>
                                    <option value="planning" {{ $project->status == 'planning' ? 'selected' : '' }}>📋 En planification</option>
                                    <option value="active" {{ $project->status == 'active' ? 'selected' : '' }}>🚀 Actif</option>
                                    <option value="on-hold" {{ $project->status == 'on-hold' ? 'selected' : '' }}>⏸️ En pause</option>
                                    <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>✅ Terminé</option>
                                    <option value="cancelled" {{ $project->status == 'cancelled' ? 'selected' : '' }}>❌ Annulé</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn-primary-modern w-full">
                                <i class="fas fa-save mr-2"></i>
                                Mettre à Jour le Statut
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participants -->
        @if($project->participants && $project->participants->isNotEmpty())
            <div class="modern-card mb-8">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-users text-primary-600 mr-2"></i>
                        Participants ({{ $project->participants->count() }})
                    </h3>
                </div>
                <div class="modern-card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($project->participants as $participant)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-12 h-12 bg-gradient-primary rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium">{{ substr($participant->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $participant->name }}</h4>
                                    <div class="flex items-center space-x-2">
                                        @if($participant->is_admin())
                                            <span class="badge-danger text-xs">Admin</span>
                                        @elseif($participant && $participant->is_premium())
                                            <span class="badge-warning text-xs">Premium</span>
                                        @else
                                            <span class="badge-secondary text-xs">Gratuit</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Changement de statut du projet -->
        <div class="modern-card mb-8">
            <div class="modern-card-header">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-exchange-alt text-primary-600 mr-2"></i>
                    Gestion du Statut du Projet
                </h3>
                <p class="text-sm text-gray-600 mt-1">Modifiez le statut de ce projet</p>
            </div>
            <div class="modern-card-body">
                <div class="space-y-4">
                    <div>
                        <label for="project-status" class="form-label-modern">Statut du projet</label>
                        <select id="project-status" class="input-modern" data-project-id="{{ $project->id }}" data-original-status="{{ $project->status }}">
                            <option value="planning" @if($project->status == 'planning') selected @endif>📋 En planification</option>
                            <option value="active" @if($project->status == 'active') selected @endif>🚀 Actif</option>
                            <option value="on-hold" @if($project->status == 'on-hold') selected @endif>⏸️ En pause</option>
                            <option value="completed" @if($project->status == 'completed') selected @endif>✅ Terminé</option>
                            <option value="cancelled" @if($project->status == 'cancelled') selected @endif>❌ Annulé</option>
                        </select>
                    </div>
                    
                    <button id="updateProjectStatusBtn" class="btn-primary-modern w-full">
                        <i class="fas fa-check mr-2"></i>
                        Mettre à Jour le Statut
                    </button>
                    
                    <p class="text-xs text-gray-500 text-center" id="projectStatusHelp">
                        Sélectionnez un nouveau statut et cliquez sur "Mettre à Jour"
                    </p>
                </div>
            </div>
        </div>

        <!-- Liste des tâches -->
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-list text-primary-600 mr-2"></i>
                        Tâches du Projet ({{ $totalTasks }})
                    </h3>
                    <a href="{{ route('tasks.create', ['project' => $project->id]) }}" class="btn-primary-modern">
                        <i class="fas fa-plus mr-2"></i>
                        Nouvelle Tâche
                    </a>
                </div>
            </div>
            <div class="modern-card-body">
                @if($project->tasks && $project->tasks->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($project->tasks as $task)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all duration-200">
                                <div class="flex items-start justify-between mb-3">
                                    <h4 class="font-medium text-gray-900 line-clamp-2">{{ $task->title }}</h4>
                                    <span class="badge-{{ 
                                        $task->status == 'done' ? 'success' :
                                        ($task->status == 'in-progress' ? 'warning' :
                                        ($task->status == 'blocked' ? 'danger' : 'secondary'))
                                    }}">
                                        @switch($task->status)
                                            @case('todo')
                                                📋 À faire
                                                @break
                                            @case('in-progress')
                                                🔄 En cours
                                                @break
                                            @case('done')
                                                ✅ Terminée
                                                @break
                                            @case('blocked')
                                                🚫 Bloquée
                                                @break
                                        @endswitch
                                    </span>
                                </div>
                                
                                @if($task->description)
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $task->description }}</p>
                                @endif
                                
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                    @if($task->due_date)
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $task->due_date->format('d/m/Y') }}
                                        </span>
                                    @endif
                                    @if($task->assignedUser)
                                        <span class="flex items-center">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $task->assignedUser->name }}
                                        </span>
                                    @endif
                                </div>
                                
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn-secondary-modern w-full text-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    Voir la tâche
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-tasks text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune tâche trouvée</h3>
                        <p class="text-gray-600 mb-6">Commencez par créer votre première tâche pour ce projet.</p>
                        <a href="{{ route('tasks.create', ['project' => $project->id]) }}" class="btn-primary-modern">
                            <i class="fas fa-plus mr-2"></i>
                            Créer une tâche
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        $(document).ready(function () {
            console.log('JavaScript chargé pour la page de projet');
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
                    url: `{{ $project->company_id ? '/entreprise/projects/' : '/projects/' }}${projectId}/update-status`,
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
</x-app-layout>