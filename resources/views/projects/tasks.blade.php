<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                    <img src="{{ asset('images/logo.png') }}" alt="Planify" class="w-8 h-8 object-contain filter brightness-0 invert">
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
                    <p class="text-sm text-gray-600 mt-1">Gestion des tâches du projet</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
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
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- En-tête du projet avec informations clés -->
        <div class="modern-card mb-6">
            <div class="modern-card-body">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Informations principales -->
                    <div class="lg:col-span-2">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <span class="badge-{{ 
                                        $project->status == 'completed' ? 'success' :
                                        ($project->status == 'active' ? 'primary' :
                                        ($project->status == 'on-hold' ? 'warning' :
                                        ($project->status == 'cancelled' ? 'danger' : 'secondary')))
                                    }}">
                                        @switch($project->status)
                                            @case('planning') 📋 En planification @break
                                            @case('active') 🚀 Actif @break
                                            @case('on-hold') ⏸️ En pause @break
                                            @case('completed') ✅ Terminé @break
                                            @case('cancelled') ❌ Annulé @break
                                            @default 📋 En planification
                                        @endswitch
                                    </span>
                                    @if($project->priority)
                                        <span class="badge-{{ 
                                            $project->priority == 'high' ? 'danger' :
                                            ($project->priority == 'medium' ? 'warning' : 'secondary')
                                        }}">
                                            @switch($project->priority)
                                                @case('high') 🔴 Priorité élevée @break
                                                @case('medium') 🟡 Priorité moyenne @break
                                                @case('low') 🔵 Priorité faible @break
                                            @endswitch
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-700 leading-relaxed mb-4">
                                    {{ $project->description ?? 'Aucune description fournie pour ce projet.' }}
                                </p>
                                
                                <div class="flex items-center space-x-6 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-2"></i>
                                        <span>{{ $project->author->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <span>{{ $project->start_date ? $project->start_date->format('d/m/Y') : 'N/A' }}</span>
                                    </div>
                                    @if($project->end_date)
                                        <div class="flex items-center">
                                            <i class="fas fa-flag-checkered mr-2"></i>
                                            <span>{{ $project->end_date->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="space-y-3">
                        <a href="{{ route('tasks.create', ['project' => $project->id]) }}" class="btn-primary-modern w-full">
                            <i class="fas fa-plus mr-2"></i>
                            Nouvelle Tâche
                        </a>
                        <a href="{{ route('projects.edit', $project->id) }}" class="btn-secondary-modern w-full">
                            <i class="fas fa-edit mr-2"></i>
                            Modifier le Projet
                        </a>
                        <a href="{{ route('projects.show', $project->id) }}" class="btn-secondary-modern w-full">
                            <i class="fas fa-eye mr-2"></i>
                            Voir le Projet
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques et progression -->
        @if($tasks && $tasks->count() > 0)
            @php
                $todoCount = $tasks->whereIn('status', ['todo', 'pending'])->count();
                $inProgressCount = $tasks->where('status', 'in-progress')->count();
                $doneCount = $tasks->whereIn('status', ['done', 'completed'])->count();
                $blockedCount = $tasks->where('status', 'blocked')->count();
                $totalTasks = $tasks->count();
                $progressPercentage = $totalTasks > 0 ? round(($doneCount / $totalTasks) * 100) : 0;
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Progression globale -->
                <div class="modern-card">
                    <div class="modern-card-body text-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-chart-line text-white text-lg"></i>
                        </div>
                        <h4 class="text-sm font-medium text-gray-600 mb-1">Progression</h4>
                        <p class="text-2xl font-bold text-gray-900">{{ $progressPercentage }}%</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $progressPercentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- À faire -->
                <div class="modern-card">
                    <div class="modern-card-body text-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-circle text-white text-lg"></i>
                        </div>
                        <h4 class="text-sm font-medium text-gray-600 mb-1">À faire</h4>
                        <p class="text-2xl font-bold text-gray-900">{{ $todoCount }}</p>
                    </div>
                </div>

                <!-- En cours -->
                <div class="modern-card">
                    <div class="modern-card-body text-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-play text-white text-lg"></i>
                        </div>
                        <h4 class="text-sm font-medium text-gray-600 mb-1">En cours</h4>
                        <p class="text-2xl font-bold text-gray-900">{{ $inProgressCount }}</p>
                    </div>
                </div>

                <!-- Terminées -->
                <div class="modern-card">
                    <div class="modern-card-body text-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-check-circle text-white text-lg"></i>
                        </div>
                        <h4 class="text-sm font-medium text-gray-600 mb-1">Terminées</h4>
                        <p class="text-2xl font-bold text-gray-900">{{ $doneCount }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modification du statut du projet -->
        <div class="modern-card mb-6">
            <div class="modern-card-header">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-exchange-alt text-blue-600 mr-2"></i>
                    Gestion du Statut
                </h3>
                <p class="text-sm text-gray-600 mt-1">Modifiez le statut de votre projet</p>
            </div>
            <div class="modern-card-body">
                <form id="statusForm" action="{{ route('projects.updateStatus', $project->id) }}" method="POST" class="flex items-center space-x-4">
                    @csrf
                    @method('PATCH')
                    
                    <div class="flex-1">
                        <label for="status" class="form-label-modern">Statut du projet</label>
                        <select name="status" id="status" class="input-modern" required>
                            <option value="planning" {{ $project->status == 'planning' ? 'selected' : '' }}>📋 En planification</option>
                            <option value="active" {{ $project->status == 'active' ? 'selected' : '' }}>🚀 Actif</option>
                            <option value="on-hold" {{ $project->status == 'on-hold' ? 'selected' : '' }}>⏸️ En pause</option>
                            <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>✅ Terminé</option>
                            <option value="cancelled" {{ $project->status == 'cancelled' ? 'selected' : '' }}>❌ Annulé</option>
                        </select>
                    </div>
                    
                    <div class="pt-6">
                        <button type="submit" class="btn-primary-modern">
                            <i class="fas fa-save mr-2"></i>
                            Mettre à Jour
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Participants (si présents) -->
        @if($project->participants && $project->participants->isNotEmpty())
            <div class="modern-card mb-6">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Équipe du Projet
                        <span class="ml-2 badge-secondary">{{ $project->participants->count() }}</span>
                    </h3>
                </div>
                <div class="modern-card-body">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($project->participants as $participant)
                            <div class="flex items-center space-x-3 bg-gray-50 rounded-lg p-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium text-sm">{{ substr($participant->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $participant->name }}</p>
                                    <div class="flex items-center space-x-1 mt-1">
                                        @if($participant->is_admin())
                                            <span class="badge-danger text-xs">Admin</span>
                                        @elseif($participant && $participant->is_premium())
                                            <span class="badge-warning text-xs">Premium</span>
                                        @else
                                            <span class="badge-secondary text-xs">Standard</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Tableau Kanban des tâches -->
        @include('tasks.tasks_list', ['tasks' => $tasks, 'title' => 'Tâches du Projet', 'add' => true, 'project' => $project])
    </div>

    <style>
        /* Styles spécifiques pour la page des tâches */
        .modern-card {
            @apply bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-md hover:-translate-y-1;
        }
        
        .modern-card-header {
            @apply px-6 py-4 border-b border-gray-200 bg-gray-50;
        }
        
        .modern-card-body {
            @apply px-6 py-4;
        }
        
        .stats-card {
            @apply modern-card p-4 relative overflow-hidden;
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #10b981);
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .modern-card-header {
                @apply px-4 py-3;
            }
            
            .modern-card-body {
                @apply px-4 py-3;
            }
        }
    </style>
</x-app-layout>