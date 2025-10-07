<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mes Projets</h1>
                <p class="text-sm text-gray-600 mt-1">Gérez tous vos projets et tâches</p>
            </div>
            @php
                $canCreate = Auth::user()->is_admin() || 
                             Auth::user()->is_premium || 
                             Auth::user()->projects()->count() < 3;
            @endphp
            @if ($canCreate)
                <a href="{{ route('projects.create') }}" class="btn-primary-modern">
                    <i class="fas fa-plus mr-2"></i>
                    Nouveau projet
                </a>
            @else
                <button class="btn-secondary-modern" disabled title="Limite atteinte">
                    <i class="fas fa-lock mr-2"></i>
                    Limite atteinte
                </button>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Alertes de limitation -->
        @if (!Auth::user()->is_premium && !Auth::user()->is_admin())
            @php
                $projectCount = Auth::user()->projects()->count();
                $projectLimit = 3;
            @endphp
            @if ($projectCount >= $projectLimit)
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-yellow-900 mb-1">Limite atteinte</p>
                            <p class="text-sm text-yellow-700">
                                Vous avez atteint la limite de <strong>{{ $projectLimit }} projets</strong> pour les utilisateurs gratuits.
                                <a href="{{ route('premium.show') }}" class="text-yellow-800 hover:text-yellow-900 underline font-medium">Passez à Premium</a> pour créer des projets illimités.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-blue-900 mb-1">Projets disponibles</p>
                            <p class="text-sm text-blue-700">
                                Vous avez <strong>{{ $projectCount }}/{{ $projectLimit }} projets</strong>.
                                <a href="{{ route('premium.show') }}" class="text-blue-800 hover:text-blue-900 underline font-medium">Passez à Premium</a> pour des projets illimités et plus de fonctionnalités.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Messages de session -->
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($projects->isEmpty())
            <!-- État vide -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-project-diagram text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun projet trouvé</h3>
                <p class="text-gray-600 mb-6">Commencez par créer votre premier projet pour organiser vos tâches.</p>
                @if ($canCreate)
                    <a href="{{ route('projects.create') }}" class="btn-primary-modern">
                        <i class="fas fa-plus mr-2"></i>
                        Créer mon premier projet
                    </a>
                @endif
            </div>
        @else
            <!-- Kanban Board des Projets -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <!-- En Planification -->
                <div class="kanban-column">
                    <div class="kanban-header bg-gray-100">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-gray-700">
                                <i class="fas fa-clipboard-list text-gray-500 mr-2"></i>
                                📋 En Planification
                            </h4>
                            <span class="badge-secondary">
                                @php $planningProjects = $projects->where('status', 'planning') @endphp
                                {{ $planningProjects->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="kanban-content">
                        @if ($planningProjects->isEmpty())
                            <div class="empty-column">
                                <i class="fas fa-clipboard-list text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Aucun projet</p>
                            </div>
                        @else
                            @foreach ($planningProjects as $project)
                                <div class="project-card hover-lift group">
                                    <div class="project-card-header">
                                        <h5 class="project-title">{{ $project->name }}</h5>
                                        <div class="project-status-badge">
                                            <span class="project-status project-status-planning">
                                                <i class="fas fa-clipboard-list mr-1"></i>
                                                📋 En Planification
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="project-card-body">
                                        <p class="project-description">{{ Str::limit($project->description, 80, '...') ?: 'Aucune description' }}</p>
                                        
                                        <div class="project-meta">
                                            <div class="project-meta-item">
                                                <i class="fas fa-users text-gray-400"></i>
                                                <span class="text-xs text-gray-600">{{ $project->participants->count() }} participants</span>
                                            </div>
                                            
                                            <div class="project-meta-item">
                                                <i class="fas fa-tasks text-gray-400"></i>
                                                <span class="text-xs text-gray-600">{{ $project->tasks->count() }} tâches</span>
                                            </div>
                                            
                                            @if($project->start_date)
                                                <div class="project-meta-item">
                                                    <i class="fas fa-calendar text-gray-400"></i>
                                                    <span class="text-xs text-gray-600">{{ $project->start_date->format('d/m/Y') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="project-card-footer">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                @if ($project->author_id === Auth::id())
                                                    <span class="text-xs text-gray-500 bg-primary-100 text-primary-700 px-2 py-1 rounded-full">
                                                        Votre projet
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <a href="{{ route('projects.show', $project->id) }}" class="project-view-btn group-hover:bg-primary-50">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Actif -->
                <div class="kanban-column">
                    <div class="kanban-header bg-blue-100">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-blue-700">
                                <i class="fas fa-play-circle text-blue-500 mr-2"></i>
                                🚀 Actif
                            </h4>
                            <span class="badge-primary">
                                @php $activeProjects = $projects->where('status', 'active') @endphp
                                {{ $activeProjects->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="kanban-content">
                        @if ($activeProjects->isEmpty())
                            <div class="empty-column">
                                <i class="fas fa-play-circle text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Aucun projet</p>
                            </div>
                        @else
                            @foreach ($activeProjects as $project)
                                <div class="project-card hover-lift group">
                                    <div class="project-card-header">
                                        <h5 class="project-title">{{ $project->name }}</h5>
                                        <div class="project-status-badge">
                                            <span class="project-status project-status-active">
                                                <i class="fas fa-play-circle mr-1"></i>
                                                🚀 Actif
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="project-card-body">
                                        <p class="project-description">{{ Str::limit($project->description, 80, '...') ?: 'Aucune description' }}</p>
                                        
                                        <div class="project-meta">
                                            <div class="project-meta-item">
                                                <i class="fas fa-users text-gray-400"></i>
                                                <span class="text-xs text-gray-600">{{ $project->participants->count() }} participants</span>
                                            </div>
                                            
                                            <div class="project-meta-item">
                                                <i class="fas fa-tasks text-gray-400"></i>
                                                <span class="text-xs text-gray-600">{{ $project->tasks->count() }} tâches</span>
                                            </div>
                                            
                                            @if($project->start_date)
                                                <div class="project-meta-item">
                                                    <i class="fas fa-calendar text-gray-400"></i>
                                                    <span class="text-xs text-gray-600">{{ $project->start_date->format('d/m/Y') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="project-card-footer">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                @if ($project->author_id === Auth::id())
                                                    <span class="text-xs text-gray-500 bg-primary-100 text-primary-700 px-2 py-1 rounded-full">
                                                        Votre projet
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <a href="{{ route('projects.show', $project->id) }}" class="project-view-btn group-hover:bg-primary-50">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- En Pause -->
                <div class="kanban-column">
                    <div class="kanban-header bg-yellow-100">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-yellow-700">
                                <i class="fas fa-pause-circle text-yellow-500 mr-2"></i>
                                ⏸️ En Pause
                            </h4>
                            <span class="badge-warning">
                                @php $onHoldProjects = $projects->where('status', 'on-hold') @endphp
                                {{ $onHoldProjects->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="kanban-content">
                        @if ($onHoldProjects->isEmpty())
                            <div class="empty-column">
                                <i class="fas fa-pause-circle text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Aucun projet</p>
                            </div>
                        @else
                            @foreach ($onHoldProjects as $project)
                                <div class="project-card hover-lift group">
                                    <div class="project-card-header">
                                        <h5 class="project-title">{{ $project->name }}</h5>
                                        <div class="project-status-badge">
                                            <span class="project-status project-status-on-hold">
                                                <i class="fas fa-pause-circle mr-1"></i>
                                                ⏸️ En Pause
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="project-card-body">
                                        <p class="project-description">{{ Str::limit($project->description, 80, '...') ?: 'Aucune description' }}</p>
                                        
                                        <div class="project-meta">
                                            <div class="project-meta-item">
                                                <i class="fas fa-users text-gray-400"></i>
                                                <span class="text-xs text-gray-600">{{ $project->participants->count() }} participants</span>
                                            </div>
                                            
                                            <div class="project-meta-item">
                                                <i class="fas fa-tasks text-gray-400"></i>
                                                <span class="text-xs text-gray-600">{{ $project->tasks->count() }} tâches</span>
                                            </div>
                                            
                                            @if($project->start_date)
                                                <div class="project-meta-item">
                                                    <i class="fas fa-calendar text-gray-400"></i>
                                                    <span class="text-xs text-gray-600">{{ $project->start_date->format('d/m/Y') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="project-card-footer">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                @if ($project->author_id === Auth::id())
                                                    <span class="text-xs text-gray-500 bg-primary-100 text-primary-700 px-2 py-1 rounded-full">
                                                        Votre projet
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <a href="{{ route('projects.show', $project->id) }}" class="project-view-btn group-hover:bg-primary-50">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Terminé -->
                <div class="kanban-column">
                    <div class="kanban-header bg-green-100">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-green-700">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                ✅ Terminé
                            </h4>
                            <span class="badge-success">
                                @php $completedProjects = $projects->where('status', 'completed') @endphp
                                {{ $completedProjects->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="kanban-content">
                        @if ($completedProjects->isEmpty())
                            <div class="empty-column">
                                <i class="fas fa-check-circle text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Aucun projet</p>
                            </div>
                        @else
                            @foreach ($completedProjects as $project)
                                <div class="project-card hover-lift group">
                                    <div class="project-card-header">
                                        <h5 class="project-title">{{ $project->name }}</h5>
                                        <div class="project-status-badge">
                                            <span class="project-status project-status-completed">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                ✅ Terminé
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="project-card-body">
                                        <p class="project-description">{{ Str::limit($project->description, 80, '...') ?: 'Aucune description' }}</p>
                                        
                                        <div class="project-meta">
                                            <div class="project-meta-item">
                                                <i class="fas fa-users text-gray-400"></i>
                                                <span class="text-xs text-gray-600">{{ $project->participants->count() }} participants</span>
                                            </div>
                                            
                                            <div class="project-meta-item">
                                                <i class="fas fa-tasks text-gray-400"></i>
                                                <span class="text-xs text-gray-600">{{ $project->tasks->count() }} tâches</span>
                                            </div>
                                            
                                            @if($project->end_date)
                                                <div class="project-meta-item">
                                                    <i class="fas fa-calendar text-gray-400"></i>
                                                    <span class="text-xs text-gray-600">{{ $project->end_date->format('d/m/Y') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="project-card-footer">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                @if ($project->author_id === Auth::id())
                                                    <span class="text-xs text-gray-500 bg-primary-100 text-primary-700 px-2 py-1 rounded-full">
                                                        Votre projet
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <a href="{{ route('projects.show', $project->id) }}" class="project-view-btn group-hover:bg-primary-50">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Annulé -->
                <div class="kanban-column">
                    <div class="kanban-header bg-red-100">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-red-700">
                                <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                ❌ Annulé
                            </h4>
                            <span class="badge-danger">
                                @php $cancelledProjects = $projects->where('status', 'cancelled') @endphp
                                {{ $cancelledProjects->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="kanban-content">
                        @if ($cancelledProjects->isEmpty())
                            <div class="empty-column">
                                <i class="fas fa-times-circle text-gray-300 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Aucun projet</p>
                            </div>
                        @else
                            @foreach ($cancelledProjects as $project)
                                <div class="project-card hover-lift group">
                                    <div class="project-card-header">
                                        <h5 class="project-title">{{ $project->name }}</h5>
                                        <div class="project-status-badge">
                                            <span class="project-status project-status-cancelled">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                ❌ Annulé
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="project-card-body">
                                        <p class="project-description">{{ Str::limit($project->description, 80, '...') ?: 'Aucune description' }}</p>
                                        
                                        <div class="project-meta">
                                            <div class="project-meta-item">
                                                <i class="fas fa-users text-gray-400"></i>
                                                <span class="text-xs text-gray-600">{{ $project->participants->count() }} participants</span>
                                            </div>
                                            
                                            <div class="project-meta-item">
                                                <i class="fas fa-tasks text-gray-400"></i>
                                                <span class="text-xs text-gray-600">{{ $project->tasks->count() }} tâches</span>
                                            </div>
                                            
                                            @if($project->start_date)
                                                <div class="project-meta-item">
                                                    <i class="fas fa-calendar text-gray-400"></i>
                                                    <span class="text-xs text-gray-600">{{ $project->start_date->format('d/m/Y') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="project-card-footer">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                @if ($project->author_id === Auth::id())
                                                    <span class="text-xs text-gray-500 bg-primary-100 text-primary-700 px-2 py-1 rounded-full">
                                                        Votre projet
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <a href="{{ route('projects.show', $project->id) }}" class="project-view-btn group-hover:bg-primary-50">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        /* Tableau Kanban des projets - Design cohérent avec l'application */
        .kanban-column {
            @apply bg-white rounded-xl border border-gray-200 shadow-sm;
        }
        
        .kanban-header {
            @apply px-4 py-3 border-b border-gray-200 rounded-t-xl;
        }
        
        .kanban-content {
            @apply p-4 space-y-4 min-h-80;
        }
        
        /* Cartes de projet - Style moderne et cohérent */
        .project-card {
            @apply bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-primary-200 transition-all duration-200;
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
        }
        
        .project-card-header {
            @apply p-4 border-b border-gray-100;
        }
        
        .project-title {
            @apply text-sm font-semibold text-gray-900 mb-2 line-clamp-2 leading-tight;
        }
        
        .project-status-badge {
            @apply flex justify-end;
        }
        
        .project-status {
            @apply text-xs font-medium px-2 py-1 rounded-full flex items-center;
        }
        
        .project-status-planning {
            @apply bg-gray-100 text-gray-700;
        }
        
        .project-status-active {
            @apply bg-blue-100 text-blue-700;
        }
        
        .project-status-on-hold {
            @apply bg-yellow-100 text-yellow-700;
        }
        
        .project-status-completed {
            @apply bg-green-100 text-green-700;
        }
        
        .project-status-cancelled {
            @apply bg-red-100 text-red-700;
        }
        
        .project-card-body {
            @apply p-4 flex-1;
        }
        
        .project-description {
            @apply text-xs text-gray-600 mb-3 line-clamp-2 leading-relaxed;
        }
        
        .project-meta {
            @apply space-y-2;
        }
        
        .project-meta-item {
            @apply flex items-center space-x-2;
        }
        
        .project-card-footer {
            @apply p-4 border-t border-gray-100;
        }
        
        .project-view-btn {
            @apply text-xs text-primary-600 hover:text-primary-700 font-medium px-2 py-1 rounded-md transition-all duration-200;
        }
        
        /* Colonne vide */
        .empty-column {
            @apply flex items-center justify-center h-40 text-center;
        }
        
        /* Responsive design */
        @media (max-width: 640px) {
            .project-card {
                @apply p-3;
            }
            
            .project-card-header {
                @apply p-3;
            }
            
            .project-card-body {
                @apply p-3;
            }
            
            .project-card-footer {
                @apply p-3;
            }
            
            .project-title {
                @apply text-xs;
            }
            
            .project-status {
                @apply text-xs px-1.5 py-0.5;
            }
        }
        
        /* Utilitaires */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>