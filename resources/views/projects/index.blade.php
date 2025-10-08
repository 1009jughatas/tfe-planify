<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mes Projets</h1>
                <p class="text-sm text-gray-600 mt-1">Vue Kanban - Gérez vos projets par statut</p>
            </div>
            @php
                $canCreate = Auth::user() && (Auth::user()->is_admin() || 
                             Auth::user()->is_premium() || 
                             Auth::user()->projects()->count() < 3);
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

    <!-- Contenu principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Alertes de limitation -->
        @if (Auth::user() && !Auth::user()->is_premium() && !Auth::user()->is_admin())
            @php
                $projectCount = Auth::user()->projects()->count();
                $projectLimit = 3;
            @endphp
            @if ($projectCount >= $projectLimit)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800">Limite de projets atteinte</h3>
                            <p class="text-sm text-yellow-700 mt-1">
                                Vous avez atteint la limite de {{ $projectLimit }} projets pour les utilisateurs gratuits.
                                <a href="{{ route('premium.show') }}" class="font-medium underline hover:text-yellow-600">
                                    Passez en premium
                                </a>
                                pour créer plus de projets.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- En Planification -->
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">En Planification</p>
                        @php $planningProjects = $projects->where('status', 'planning') @endphp
                        <p class="text-3xl font-bold text-gray-900">{{ $planningProjects->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">À démarrer</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-white text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Actifs -->
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Actifs</p>
                        @php $activeProjects = $projects->where('status', 'active') @endphp
                        <p class="text-3xl font-bold text-gray-900">{{ $activeProjects->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">En cours</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-play-circle text-white text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- En Pause -->
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">En Pause</p>
                        @php $onHoldProjects = $projects->where('status', 'on-hold') @endphp
                        <p class="text-3xl font-bold text-gray-900">{{ $onHoldProjects->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Suspendus</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-pause-circle text-white text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Terminés -->
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Terminés</p>
                        @php $completedProjects = $projects->where('status', 'completed') @endphp
                        <p class="text-3xl font-bold text-gray-900">{{ $completedProjects->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Finalisés</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanban Board -->
        @if ($projects->count() > 0)
            <div class="modern-card">
                <div class="modern-card-body">
                    <!-- Kanban Board -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- En Planification -->
                                <div class="kanban-column">
                                    <div class="kanban-header bg-gray-100">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="font-semibold text-gray-700">
                                                    <i class="fas fa-clipboard-list text-gray-500 mr-2"></i>
                                                    En Planification
                                                </h4>
                                                <p class="text-xs text-gray-500 mt-1">Projets à démarrer</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-2xl font-bold text-gray-900">
                                                    @php $planningProjects = $projects->where('status', 'planning') @endphp
                                                    {{ $planningProjects->count() }}
                                                </span>
                                                <div class="w-8 h-8 bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-clipboard-list text-white text-sm"></i>
                                                </div>
                                            </div>
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
                                                <div class="project-block-card hover-lift group">
                                                    <!-- Contenu du bloc -->
                                                    <div class="project-block-content">
                                                        <!-- Header avec titre -->
                                                        <div class="project-block-header">
                                                            <h5 class="project-block-title">{{ $project->name }}</h5>
                                                        </div>
                                                        
                                                        <!-- Progression -->
                                                        <div class="project-progress-block">
                                                            @php
                                                                $totalTasks = $project->tasks->count();
                                                                $completedTasks = $project->tasks->where('status', 'done')->count();
                                                                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                            @endphp
                                                            <div class="progress-block-bar">
                                                                <div class="progress-block-fill planning" style="width: {{ $progress }}%"></div>
                                                            </div>
                                                            <span class="progress-block-text">{{ $progress }}%</span>
                                                        </div>
                                                        
                                                        <!-- Actions -->
                                                        <div class="project-block-actions">
                                                            <button class="action-btn-block" onclick="window.location.href='{{ route('projects.tasks', $project->id) }}'" title="Voir les tâches">
                                                                <i class="fas fa-tasks"></i>
                                                            </button>
                                                            <button class="action-btn-block" onclick="window.location.href='{{ route('projects.edit', $project->id) }}'" title="Modifier le projet">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="action-btn-block action-btn-delete" onclick="confirmDelete({{ $project->id }})" title="Supprimer le projet">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
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
                                            <div>
                                                <h4 class="font-semibold text-blue-700">
                                                    <i class="fas fa-play-circle text-blue-500 mr-2"></i>
                                                    Actif
                                                </h4>
                                                <p class="text-xs text-blue-600 mt-1">Projets en cours</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-2xl font-bold text-blue-900">
                                                    @php $activeProjects = $projects->where('status', 'active') @endphp
                                                    {{ $activeProjects->count() }}
                                                </span>
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-play-circle text-white text-sm"></i>
                                                </div>
                                            </div>
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
                                                <div class="project-block-card hover-lift group">
                                                    <div class="project-block-content">
                                                        <div class="project-block-header">
                                                            <h5 class="project-block-title">{{ $project->name }}</h5>
                                                        </div>
                                                        <div class="project-progress-block">
                                                            @php
                                                                $totalTasks = $project->tasks->count();
                                                                $completedTasks = $project->tasks->where('status', 'done')->count();
                                                                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                            @endphp
                                                            <div class="progress-block-bar">
                                                                <div class="progress-block-fill active" style="width: {{ $progress }}%"></div>
                                                            </div>
                                                            <span class="progress-block-text">{{ $progress }}%</span>
                                                        </div>
                                                        <div class="project-block-actions">
                                                            <button class="action-btn-block" onclick="window.location.href='{{ route('projects.tasks', $project->id) }}'" title="Voir les tâches">
                                                                <i class="fas fa-tasks"></i>
                                                            </button>
                                                            <button class="action-btn-block" onclick="window.location.href='{{ route('projects.edit', $project->id) }}'" title="Modifier le projet">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="action-btn-block action-btn-delete" onclick="confirmDelete({{ $project->id }})" title="Supprimer le projet">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
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
                                            <div>
                                                <h4 class="font-semibold text-yellow-700">
                                                    <i class="fas fa-pause-circle text-yellow-500 mr-2"></i>
                                                    En Pause
                                                </h4>
                                                <p class="text-xs text-yellow-600 mt-1">Projets suspendus</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-2xl font-bold text-yellow-900">
                                                    @php $onHoldProjects = $projects->where('status', 'on-hold') @endphp
                                                    {{ $onHoldProjects->count() }}
                                                </span>
                                                <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-pause-circle text-white text-sm"></i>
                                                </div>
                                            </div>
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
                                                <div class="project-block-card hover-lift group">
                                                    <div class="project-block-content">
                                                        <div class="project-block-header">
                                                            <h5 class="project-block-title">{{ $project->name }}</h5>
                                                        </div>
                                                        <div class="project-progress-block">
                                                            @php
                                                                $totalTasks = $project->tasks->count();
                                                                $completedTasks = $project->tasks->where('status', 'done')->count();
                                                                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                            @endphp
                                                            <div class="progress-block-bar">
                                                                <div class="progress-block-fill on-hold" style="width: {{ $progress }}%"></div>
                                                            </div>
                                                            <span class="progress-block-text">{{ $progress }}%</span>
                                                        </div>
                                                        <div class="project-block-actions">
                                                            <button class="action-btn-block" onclick="window.location.href='{{ route('projects.tasks', $project->id) }}'" title="Voir les tâches">
                                                                <i class="fas fa-tasks"></i>
                                                            </button>
                                                            <button class="action-btn-block" onclick="window.location.href='{{ route('projects.edit', $project->id) }}'" title="Modifier le projet">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="action-btn-block action-btn-delete" onclick="confirmDelete({{ $project->id }})" title="Supprimer le projet">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
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
                                            <div>
                                                <h4 class="font-semibold text-green-700">
                                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                                    Terminé
                                                </h4>
                                                <p class="text-xs text-green-600 mt-1">Projets finalisés</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-2xl font-bold text-green-900">
                                                    @php $completedProjects = $projects->where('status', 'completed') @endphp
                                                    {{ $completedProjects->count() }}
                                                </span>
                                                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-check-circle text-white text-sm"></i>
                                                </div>
                                            </div>
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
                                                <div class="project-block-card hover-lift group">
                                                    <div class="project-block-content">
                                                        <div class="project-block-header">
                                                            <h5 class="project-block-title">{{ $project->name }}</h5>
                                                        </div>
                                                        <div class="project-progress-block">
                                                            @php
                                                                $totalTasks = $project->tasks->count();
                                                                $completedTasks = $project->tasks->where('status', 'done')->count();
                                                                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 100;
                                                            @endphp
                                                            <div class="progress-block-bar">
                                                                <div class="progress-block-fill completed" style="width: {{ $progress }}%"></div>
                                                            </div>
                                                            <span class="progress-block-text">{{ $progress }}%</span>
                                                        </div>
                                                        <div class="project-block-actions">
                                                            <button class="action-btn-block" onclick="window.location.href='{{ route('projects.tasks', $project->id) }}'" title="Voir les tâches">
                                                                <i class="fas fa-tasks"></i>
                                                            </button>
                                                            <button class="action-btn-block" onclick="window.location.href='{{ route('projects.edit', $project->id) }}'" title="Modifier le projet">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="action-btn-block action-btn-delete" onclick="confirmDelete({{ $project->id }})" title="Supprimer le projet">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        
        /* Colonnes Kanban */
        .kanban-column {
            @apply bg-white rounded-xl border border-gray-200 shadow-sm;
        }
        
        .kanban-header {
            @apply p-6 rounded-t-xl border-b border-gray-200 bg-gray-50;
        }
        
        .kanban-content {
            @apply p-4 space-y-4 min-h-96 bg-gray-50/30;
        }
        
        .empty-column {
            @apply flex flex-col items-center justify-center py-8 text-center;
        }
        
        /* Blocs de projet avec actions */
        .project-block-card {
            @apply bg-white rounded-xl border border-gray-200 shadow-sm cursor-pointer transition-all duration-300;
            position: relative;
            overflow: hidden;
            min-height: 140px;
        }
        
        .project-block-card:hover {
            @apply shadow-md border-primary-300 transform translate-y-1;
        }
        
        .project-block-card::before {
            content: '';
            @apply absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-500 to-accent-500;
        }
        
        .project-block-content {
            @apply p-5 h-full flex flex-col;
        }
        
        .project-block-header {
            @apply mb-4;
        }
        
        .project-block-title {
            @apply text-base font-bold text-gray-900 leading-tight;
            margin: 0;
            line-height: 1.3;
        }
        
        .project-progress-block {
            @apply flex items-center justify-between mb-4 flex-1;
        }
        
        .progress-block-bar {
            @apply flex-1 h-3 bg-gray-200 rounded-full overflow-hidden mr-3;
        }
        
        .progress-block-fill {
            @apply h-full rounded-full transition-all duration-500;
        }
        
        .progress-block-fill.planning {
            @apply bg-gradient-to-r from-gray-400 to-gray-500;
        }
        
        .progress-block-fill.active {
            @apply bg-gradient-to-r from-blue-400 to-blue-500;
        }
        
        .progress-block-fill.on-hold {
            @apply bg-gradient-to-r from-yellow-400 to-yellow-500;
        }
        
        .progress-block-fill.completed {
            @apply bg-gradient-to-r from-green-400 to-green-500;
        }
        
        .progress-block-text {
            @apply text-sm font-bold text-gray-800;
        }
        
        /* Boutons d'actions */
        .project-block-actions {
            @apply flex items-center justify-center space-x-2;
        }
        
        .action-btn-block {
            @apply w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border: 1px solid #e2e8f0;
            color: #64748b;
        }
        
        .action-btn-block:hover {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .action-btn-delete:hover {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-color: #ef4444;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        
        .action-btn-block i {
            @apply text-sm;
        }
        
        
        /* Responsive */
        @media (max-width: 768px) {
            .kanban-content {
                @apply min-h-64;
            }
            
            .project-block-card {
                @apply transform-none hover:transform-none;
                min-height: 120px;
            }
            
            .project-block-content {
                @apply p-3;
            }
            
            .action-btn-block {
                @apply w-8 h-8;
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

    <!-- Script pour la confirmation de suppression -->
    <script>
        function confirmDelete(projectId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.')) {
                // Créer un formulaire de suppression
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/projects/${projectId}`;
                
                // Ajouter le token CSRF
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Ajouter la méthode DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                // Soumettre le formulaire
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>