<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mes Projets</h1>
                <p class="text-sm text-gray-600 mt-1">Vue Kanban - Gérez vos projets par statut</p>
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

    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="w-80 bg-white shadow-lg border-r border-gray-200 overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Filtres & Statistiques</h2>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
                
                <!-- Statistiques -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Vue d'ensemble</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                <span class="text-sm text-gray-600">En Planification</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">
                                @php $planningProjects = $projects->where('status', 'planning') @endphp
                                {{ $planningProjects->count() }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Actifs</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">
                                @php $activeProjects = $projects->where('status', 'active') @endphp
                                {{ $activeProjects->count() }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">En Pause</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">
                                @php $onHoldProjects = $projects->where('status', 'on-hold') @endphp
                                {{ $onHoldProjects->count() }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Terminés</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">
                                @php $completedProjects = $projects->where('status', 'completed') @endphp
                                {{ $completedProjects->count() }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Filtres -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Filtres</h3>
                    <div class="space-y-2">
                        <button class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-user mr-2"></i>Mes projets
                        </button>
                        <button class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-users mr-2"></i>Projets partagés
                        </button>
                        <button class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-star mr-2"></i>Favoris
                        </button>
                        <button class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-clock mr-2"></i>Échéances proches
                        </button>
                    </div>
                </div>
                
                <!-- Actions rapides -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Actions rapides</h3>
                    <div class="space-y-2">
                        @if ($canCreate)
                            <a href="{{ route('projects.create') }}" class="block w-full text-left px-3 py-2 text-sm bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Nouveau projet
                            </a>
                        @endif
                        <button class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-download mr-2"></i>Exporter
                        </button>
                        <button class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="fas fa-search mr-2"></i>Rechercher
                        </button>
                    </div>
                </div>
                
                <!-- Projets récents -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Projets récents</h3>
                    <div class="space-y-2">
                        @foreach($projects->take(3) as $project)
                            <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                                    <span class="text-xs font-semibold text-white">{{ substr($project->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $project->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $project->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="flex-1 overflow-y-auto">
            <div class="p-6">
                <!-- Alertes de limitation -->
                @if (!Auth::user()->is_premium && !Auth::user()->is_admin())
                    @php
                        $projectCount = Auth::user()->projects()->count();
                        $projectLimit = 3;
                    @endphp
                    @if ($projectCount >= $projectLimit)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
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

                @if ($projects->isEmpty())
                    <!-- État vide -->
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-project-diagram text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun projet</h3>
                        <p class="text-gray-600 mb-6">Commencez par créer votre premier projet pour organiser vos tâches.</p>
                        @if ($canCreate)
                            <a href="{{ route('projects.create') }}" class="btn-primary-modern">
                                <i class="fas fa-plus mr-2"></i>
                                Créer mon premier projet
                            </a>
                        @endif
                    </div>
                @else
                    <!-- Kanban Board des Projets - Cohérent avec le design des tâches -->
                    <div class="modern-card">
                        <div class="modern-card-header">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <i class="fas fa-project-diagram text-primary-600 mr-2"></i>
                                    Tableau Kanban des Projets
                                </h3>
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-600">
                                        {{ $projects->count() }} projet(s) au total
                                    </span>
                                    @if ($canCreate)
                                        <a href="{{ route('projects.create') }}" class="btn-primary-modern">
                                            <i class="fas fa-plus mr-2"></i>
                                            Nouveau projet
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modern-card-body">
                            <!-- Kanban Board -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- En Planification -->
                                <div class="kanban-column">
                                    <div class="kanban-header bg-gray-100">
                                        <div class="flex items-center justify-between">
                                            <h4 class="font-semibold text-gray-700">
                                                <i class="fas fa-clipboard-list text-gray-500 mr-2"></i>
                                                En Planification
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
                                                <div class="simple-project-card hover-lift group" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                                    <!-- Barre de couleur en haut -->
                                                    <div class="project-color-bar planning"></div>
                                                    
                                                    <!-- Contenu simplifié -->
                                                    <div class="simple-card-content">
                                                        <h5 class="simple-project-title">{{ $project->name }}</h5>
                                                        
                                                        <!-- Progression -->
                                                        <div class="simple-progress-section">
                                                            @php
                                                                $totalTasks = $project->tasks->count();
                                                                $completedTasks = $project->tasks->where('status', 'done')->count();
                                                                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                            @endphp
                                                            <div class="simple-progress-bar">
                                                                <div class="simple-progress-fill planning" style="width: {{ $progress }}%"></div>
                                                            </div>
                                                            <span class="simple-progress-text">{{ $progress }}%</span>
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
                                                Actif
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
                                                <div class="simple-project-card hover-lift group" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                                    <div class="project-color-bar active"></div>
                                                    <div class="simple-card-content">
                                                        <h5 class="simple-project-title">{{ $project->name }}</h5>
                                                        <div class="simple-progress-section">
                                                            @php
                                                                $totalTasks = $project->tasks->count();
                                                                $completedTasks = $project->tasks->where('status', 'done')->count();
                                                                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                            @endphp
                                                            <div class="simple-progress-bar">
                                                                <div class="simple-progress-fill active" style="width: {{ $progress }}%"></div>
                                                            </div>
                                                            <span class="simple-progress-text">{{ $progress }}%</span>
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
                                                En Pause
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
                                                <div class="simple-project-card hover-lift group" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                                    <div class="project-color-bar on-hold"></div>
                                                    <div class="simple-card-content">
                                                        <h5 class="simple-project-title">{{ $project->name }}</h5>
                                                        <div class="simple-progress-section">
                                                            @php
                                                                $totalTasks = $project->tasks->count();
                                                                $completedTasks = $project->tasks->where('status', 'done')->count();
                                                                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                            @endphp
                                                            <div class="simple-progress-bar">
                                                                <div class="simple-progress-fill on-hold" style="width: {{ $progress }}%"></div>
                                                            </div>
                                                            <span class="simple-progress-text">{{ $progress }}%</span>
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
                                                Terminé
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
                                                <div class="simple-project-card hover-lift group" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                                    <div class="project-color-bar completed"></div>
                                                    <div class="simple-card-content">
                                                        <h5 class="simple-project-title">{{ $project->name }}</h5>
                                                        <div class="simple-progress-section">
                                                            @php
                                                                $totalTasks = $project->tasks->count();
                                                                $completedTasks = $project->tasks->where('status', 'done')->count();
                                                                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 100;
                                                            @endphp
                                                            <div class="simple-progress-bar">
                                                                <div class="simple-progress-fill completed" style="width: {{ $progress }}%"></div>
                                                            </div>
                                                            <span class="simple-progress-text">{{ $progress }}%</span>
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
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Sidebar et Layout Principal */
        .h-screen {
            height: 100vh;
        }
        
        /* Colonnes Kanban */
        .kanban-column {
            @apply bg-gray-50 rounded-xl border border-gray-200;
        }
        
        .kanban-header {
            @apply p-4 rounded-t-xl border-b border-gray-200;
        }
        
        .kanban-content {
            @apply p-4 space-y-4 min-h-96;
        }
        
        .empty-column {
            @apply flex flex-col items-center justify-center py-8 text-center;
        }
        
        /* Cartes de projet simplifiées */
        .simple-project-card {
            @apply bg-white rounded-lg border border-gray-200 shadow-sm cursor-pointer transition-all duration-200;
            position: relative;
            overflow: hidden;
        }
        
        .simple-project-card:hover {
            @apply shadow-md border-primary-300 transform translate-y-1;
        }
        
        .simple-card-content {
            @apply p-4;
        }
        
        .simple-project-title {
            @apply text-sm font-semibold text-gray-900 mb-3 leading-tight;
            margin: 0;
        }
        
        .simple-progress-section {
            @apply flex items-center justify-between;
        }
        
        .simple-progress-bar {
            @apply flex-1 h-2 bg-gray-200 rounded-full overflow-hidden mr-3;
        }
        
        .simple-progress-fill {
            @apply h-full rounded-full transition-all duration-300;
        }
        
        .simple-progress-fill.planning {
            @apply bg-gradient-to-r from-gray-400 to-gray-500;
        }
        
        .simple-progress-fill.active {
            @apply bg-gradient-to-r from-blue-400 to-blue-500;
        }
        
        .simple-progress-fill.on-hold {
            @apply bg-gradient-to-r from-yellow-400 to-yellow-500;
        }
        
        .simple-progress-fill.completed {
            @apply bg-gradient-to-r from-green-400 to-green-500;
        }
        
        .simple-progress-text {
            @apply text-xs font-semibold text-gray-700;
        }
        
        /* Barre de couleur en haut */
        .project-color-bar {
            @apply absolute top-0 left-0 right-0 h-1;
        }
        
        .project-color-bar.planning {
            background: linear-gradient(90deg, #6b7280, #9ca3af);
        }
        
        .project-color-bar.active {
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        }
        
        .project-color-bar.on-hold {
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }
        
        .project-color-bar.completed {
            background: linear-gradient(90deg, #10b981, #059669);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .flex.h-screen {
                @apply flex-col h-auto;
            }
            
            .w-80 {
                @apply w-full;
            }
            
            .kanban-content {
                @apply min-h-64;
            }
            
            .simple-project-card {
                @apply transform-none hover:transform-none;
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