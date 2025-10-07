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
            <!-- Tableau Kanban des projets -->
            <div class="kanban-board">
                <div class="kanban-grid">
                    @php
                        $statusColumns = [
                            'planning' => [
                                'title' => 'En Planification',
                                'icon' => 'fas fa-clipboard-list',
                                'emoji' => '📋',
                                'color' => 'gray',
                                'bgColor' => 'bg-gray-50',
                                'borderColor' => 'border-gray-200',
                                'headerColor' => 'bg-gray-100'
                            ],
                            'active' => [
                                'title' => 'Actif',
                                'icon' => 'fas fa-play-circle',
                                'emoji' => '🚀',
                                'color' => 'blue',
                                'bgColor' => 'bg-blue-50',
                                'borderColor' => 'border-blue-200',
                                'headerColor' => 'bg-blue-100'
                            ],
                            'on-hold' => [
                                'title' => 'En Pause',
                                'icon' => 'fas fa-pause-circle',
                                'emoji' => '⏸️',
                                'color' => 'yellow',
                                'bgColor' => 'bg-yellow-50',
                                'borderColor' => 'border-yellow-200',
                                'headerColor' => 'bg-yellow-100'
                            ],
                            'completed' => [
                                'title' => 'Terminé',
                                'icon' => 'fas fa-check-circle',
                                'emoji' => '✅',
                                'color' => 'green',
                                'bgColor' => 'bg-green-50',
                                'borderColor' => 'border-green-200',
                                'headerColor' => 'bg-green-100'
                            ],
                            'cancelled' => [
                                'title' => 'Annulé',
                                'icon' => 'fas fa-times-circle',
                                'emoji' => '❌',
                                'color' => 'red',
                                'bgColor' => 'bg-red-50',
                                'borderColor' => 'border-red-200',
                                'headerColor' => 'bg-red-100'
                            ]
                        ];
                    @endphp

                    @foreach($statusColumns as $status => $config)
                        @php
                            $projectsInStatus = $projects->where('status', $status);
                            $projectCount = $projectsInStatus->count();
                        @endphp
                        
                        <div class="kanban-column">
                            <div class="kanban-header {{ $config['headerColor'] }} {{ $config['borderColor'] }}">
                                <div class="kanban-header-content">
                                    <div class="kanban-header-icon">
                                        <i class="{{ $config['icon'] }} text-{{ $config['color'] }}-600"></i>
                                    </div>
                                    <div class="kanban-header-text">
                                        <h3 class="kanban-column-title">{{ $config['emoji'] }} {{ $config['title'] }}</h3>
                                        <span class="kanban-count">{{ $projectCount }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="kanban-content {{ $config['bgColor'] }}">
                                @if($projectCount > 0)
                                    @foreach($projectsInStatus as $project)
                                        <div class="project-card">
                                            <div class="project-card-header">
                                                <h4 class="project-title">
                                                    <a href="{{ route('projects.show', $project->id) }}" class="hover:text-primary-600 transition-colors">
                                                        {{ $project->name }}
                                                    </a>
                                                </h4>
                                                <div class="project-badges">
                                                    @if ($project->author_id === Auth::id())
                                                        <span class="badge-primary text-xs">Votre projet</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="project-card-body">
                                                @if($project->description)
                                                    <p class="project-description">{{ Str::limit($project->description, 80) }}</p>
                                                @endif
                                                
                                                <div class="project-meta">
                                                    <div class="project-meta-item">
                                                        <i class="fas fa-users text-gray-400"></i>
                                                        <span class="text-xs text-gray-600">{{ $project->participants->count() }} participants</span>
                                                    </div>
                                                    <div class="project-meta-item">
                                                        <i class="fas fa-tasks text-gray-400"></i>
                                                        <span class="text-xs text-gray-600">{{ $project->tasks->count() }} tâches</span>
                                                    </div>
                                                </div>
                                                
                                                @if($project->start_date || $project->end_date)
                                                    <div class="project-dates">
                                                        @if($project->start_date)
                                                            <div class="project-date">
                                                                <i class="fas fa-calendar-plus text-gray-400"></i>
                                                                <span class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</span>
                                                            </div>
                                                        @endif
                                                        @if($project->end_date)
                                                            <div class="project-date">
                                                                <i class="fas fa-calendar-check text-gray-400"></i>
                                                                <span class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="project-card-footer">
                                                <div class="project-actions">
                                                    <a href="{{ route('projects.tasks', $project->id) }}" class="project-action-btn project-action-primary">
                                                        <i class="fas fa-tasks"></i>
                                                        Tâches
                                                    </a>
                                                    <a href="{{ route('projects.show', $project->id) }}" class="project-action-btn project-action-secondary">
                                                        <i class="fas fa-eye"></i>
                                                        Voir
                                                    </a>
                                                    @if (Auth::user()->is_admin() || $project->author_id === Auth::id())
                                                        <a href="{{ route('projects.edit', $project->id) }}" class="project-action-btn project-action-accent">
                                                            <i class="fas fa-edit"></i>
                                                            Modifier
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-column">
                                        <div class="empty-column-content">
                                            <i class="{{ $config['icon'] }} text-gray-300 text-2xl mb-2"></i>
                                            <p class="text-sm text-gray-500">Aucun projet</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <style>
        /* Styles pour le tableau Kanban des projets */
        .kanban-board {
            @apply w-full;
        }
        
        .kanban-grid {
            @apply grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6;
        }
        
        .kanban-column {
            @apply flex flex-col min-h-96;
        }
        
        .kanban-header {
            @apply px-4 py-3 border-b rounded-t-xl;
        }
        
        .kanban-header-content {
            @apply flex items-center space-x-3;
        }
        
        .kanban-header-icon {
            @apply w-8 h-8 rounded-full flex items-center justify-center;
        }
        
        .kanban-header-text {
            @apply flex-1;
        }
        
        .kanban-column-title {
            @apply text-sm font-semibold text-gray-900 mb-1;
        }
        
        .kanban-count {
            @apply text-xs text-gray-500 bg-white px-2 py-1 rounded-full;
        }
        
        .kanban-content {
            @apply flex-1 p-4 space-y-4 rounded-b-xl min-h-80;
        }
        
        /* Cartes de projet */
        .project-card {
            @apply bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 p-4;
        }
        
        .project-card-header {
            @apply mb-3;
        }
        
        .project-title {
            @apply text-sm font-semibold text-gray-900 mb-2 line-clamp-2;
        }
        
        .project-badges {
            @apply flex flex-wrap gap-1;
        }
        
        .project-card-body {
            @apply mb-4;
        }
        
        .project-description {
            @apply text-xs text-gray-600 mb-3 line-clamp-2 leading-relaxed;
        }
        
        .project-meta {
            @apply space-y-2 mb-3;
        }
        
        .project-meta-item {
            @apply flex items-center space-x-2;
        }
        
        .project-dates {
            @apply space-y-1;
        }
        
        .project-date {
            @apply flex items-center space-x-2;
        }
        
        .project-card-footer {
            @apply border-t border-gray-100 pt-3;
        }
        
        .project-actions {
            @apply flex flex-wrap gap-2;
        }
        
        .project-action-btn {
            @apply text-xs px-2 py-1 rounded-md font-medium transition-all duration-200 flex items-center space-x-1;
        }
        
        .project-action-primary {
            @apply bg-primary-100 text-primary-700 hover:bg-primary-200;
        }
        
        .project-action-secondary {
            @apply bg-gray-100 text-gray-700 hover:bg-gray-200;
        }
        
        .project-action-accent {
            @apply bg-accent-100 text-accent-700 hover:bg-accent-200;
        }
        
        /* Colonne vide */
        .empty-column {
            @apply flex items-center justify-center h-40;
        }
        
        .empty-column-content {
            @apply text-center;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .kanban-grid {
                @apply grid-cols-1;
            }
            
            .kanban-column {
                @apply min-h-64;
            }
            
            .project-card {
                @apply p-3;
            }
            
            .project-actions {
                @apply flex-col;
            }
            
            .project-action-btn {
                @apply w-full justify-center;
            }
        }
        
        @media (min-width: 769px) and (max-width: 1024px) {
            .kanban-grid {
                @apply grid-cols-2;
            }
        }
        
        @media (min-width: 1025px) and (max-width: 1280px) {
            .kanban-grid {
                @apply grid-cols-3;
            }
        }
        
        @media (min-width: 1281px) {
            .kanban-grid {
                @apply grid-cols-5;
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