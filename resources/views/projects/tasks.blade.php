<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-project-diagram text-white text-lg"></i>
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
        <!-- Informations du projet -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Description -->
            <div class="lg:col-span-2">
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
            </div>

            <!-- Actions rapides -->
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

                <!-- Actions -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-bolt text-primary-600 mr-2"></i>
                            Actions Rapides
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-3">
                            <a href="{{ route('tasks.create', ['project' => $project->id]) }}" class="btn-primary-modern w-full">
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
            </div>
        </div>

        <!-- Métadonnées du projet -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Auteur -->
            <div class="modern-card">
                <div class="modern-card-body text-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <h4 class="text-sm font-medium text-gray-600 mb-1">Auteur</h4>
                    <p class="text-lg font-bold text-gray-900">{{ $project->author->name ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Date de début -->
            <div class="modern-card">
                <div class="modern-card-body text-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar-plus text-white"></i>
                    </div>
                    <h4 class="text-sm font-medium text-gray-600 mb-1">Date de Début</h4>
                    <p class="text-lg font-bold text-gray-900">
                        {{ $project->start_date ? $project->start_date->format('d/m/Y') : 'N/A' }}
                    </p>
                </div>
            </div>

            <!-- Date de fin -->
            <div class="modern-card">
                <div class="modern-card-body text-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar-check text-white"></i>
                    </div>
                    <h4 class="text-sm font-medium text-gray-600 mb-1">Date de Fin</h4>
                    <p class="text-lg font-bold text-gray-900">
                        {{ $project->end_date ? $project->end_date->format('d/m/Y') : 'N/A' }}
                    </p>
                </div>
            </div>

            <!-- Nombre de tâches -->
            <div class="modern-card">
                <div class="modern-card-body text-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-tasks text-white"></i>
                    </div>
                    <h4 class="text-sm font-medium text-gray-600 mb-1">Total Tâches</h4>
                    <p class="text-lg font-bold text-gray-900">{{ $tasks->count() ?? 0 }}</p>
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
                    <div class="flex flex-wrap gap-3">
                        @foreach($project->participants as $participant)
                            <div class="flex items-center space-x-2 bg-gray-50 rounded-lg px-3 py-2">
                                <div class="w-8 h-8 bg-gradient-primary rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium text-sm">{{ substr($participant->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $participant->name }}</p>
                                    <div class="flex items-center space-x-1">
                                        @if($participant->is_admin())
                                            <span class="badge-danger text-xs">Admin</span>
                                        @elseif($participant->is_premium)
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

        <!-- Statistiques des tâches -->
        @if($tasks && $tasks->count() > 0)
            @php
                $todoCount = $tasks->where('status', 'todo')->count();
                $inProgressCount = $tasks->where('status', 'in-progress')->count();
                $doneCount = $tasks->where('status', 'done')->count();
                $blockedCount = $tasks->where('status', 'blocked')->count();
                $totalTasks = $tasks->count();
                $progressPercentage = $totalTasks > 0 ? round(($doneCount / $totalTasks) * 100) : 0;
            @endphp
            
            <div class="modern-card mb-8">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-pie text-primary-600 mr-2"></i>
                        Progression du Projet
                    </h3>
                </div>
                <div class="modern-card-body">
                    <div class="space-y-4">
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
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-400">{{ $todoCount }}</div>
                                <div class="text-xs text-gray-600">📋 À faire</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $inProgressCount }}</div>
                                <div class="text-xs text-gray-600">🔄 En cours</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">{{ $blockedCount }}</div>
                                <div class="text-xs text-gray-600">🚫 Bloquées</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $doneCount }}</div>
                                <div class="text-xs text-gray-600">✅ Terminées</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Liste des tâches -->
        @include('tasks.tasks_list', ['tasks' => $tasks, 'title' => 'Tâches du Projet', 'add' => true, 'project' => $project])
    </div>
</x-app-layout>