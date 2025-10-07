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
                                        <div class="enhanced-project-card hover-lift group" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                            <!-- Barre de couleur en haut -->
                                            <div class="project-color-bar planning"></div>
                                            
                                            <!-- Header avec icône et titre -->
                                            <div class="project-card-header">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="project-icon planning">
                                                            <i class="fas fa-clipboard-list"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="project-title">{{ $project->name }}</h5>
                                                            <div class="project-subtitle">
                                                                <span class="project-status-badge planning">
                                                                    En Planification
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="project-actions-dropdown">
                                                        <button class="action-btn" onclick="event.stopPropagation()">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Description -->
                                            <div class="project-description-section">
                                                <p class="project-description">{{ Str::limit($project->description, 80, '...') ?: 'Aucune description disponible' }}</p>
                                            </div>
                                            
                                            <!-- Progression -->
                                            <div class="project-progress-section">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-xs font-medium text-gray-600">Progression</span>
                                                    <span class="text-xs font-semibold text-gray-900">
                                                        @php
                                                            $totalTasks = $project->tasks->count();
                                                            $completedTasks = $project->tasks->where('status', 'done')->count();
                                                            $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                        @endphp
                                                        {{ $progress }}%
                                                    </span>
                                                </div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill planning" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Métadonnées -->
                                            <div class="project-meta-section">
                                                <div class="meta-grid">
                                                    <div class="meta-item">
                                                        <i class="fas fa-users"></i>
                                                        <span>{{ $project->participants->count() }}</span>
                                                    </div>
                                                    <div class="meta-item">
                                                        <i class="fas fa-tasks"></i>
                                                        <span>{{ $project->tasks->count() }}</span>
                                                    </div>
                                                    @if($project->start_date)
                                                        <div class="meta-item">
                                                            <i class="fas fa-calendar"></i>
                                                            <span>{{ $project->start_date->format('d/m') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Footer avec avatars et actions -->
                                            <div class="project-card-footer">
                                                <div class="flex items-center justify-between">
                                                    <div class="project-team">
                                                        @if($project->author)
                                                            <div class="team-avatar" title="{{ $project->author->name }}">
                                                                {{ substr($project->author->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                        @if($project->participants->count() > 0)
                                                            @foreach($project->participants->take(2) as $participant)
                                                                <div class="team-avatar" title="{{ $participant->name }}">
                                                                    {{ substr($participant->name, 0, 1) }}
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        @if($project->participants->count() > 2)
                                                            <div class="team-avatar-more">
                                                                +{{ $project->participants->count() - 2 }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="project-footer-actions">
                                                        <button class="action-btn-small" onclick="event.stopPropagation()" title="Ajouter aux favoris">
                                                            <i class="fas fa-heart"></i>
                                                        </button>
                                                        <button class="action-btn-small" onclick="event.stopPropagation()" title="Partager">
                                                            <i class="fas fa-share"></i>
                                                        </button>
                                                    </div>
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
                                        <div class="enhanced-project-card hover-lift group" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                            <div class="project-color-bar active"></div>
                                            <div class="project-card-header">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="project-icon active">
                                                            <i class="fas fa-play-circle"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="project-title">{{ $project->name }}</h5>
                                                            <div class="project-subtitle">
                                                                <span class="project-status-badge active">Actif</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="project-actions-dropdown">
                                                        <button class="action-btn" onclick="event.stopPropagation()">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="project-description-section">
                                                <p class="project-description">{{ Str::limit($project->description, 80, '...') ?: 'Aucune description disponible' }}</p>
                                            </div>
                                            <div class="project-progress-section">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-xs font-medium text-gray-600">Progression</span>
                                                    <span class="text-xs font-semibold text-gray-900">
                                                        @php
                                                            $totalTasks = $project->tasks->count();
                                                            $completedTasks = $project->tasks->where('status', 'done')->count();
                                                            $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                        @endphp
                                                        {{ $progress }}%
                                                    </span>
                                                </div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill active" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>
                                            <div class="project-meta-section">
                                                <div class="meta-grid">
                                                    <div class="meta-item">
                                                        <i class="fas fa-users"></i>
                                                        <span>{{ $project->participants->count() }}</span>
                                                    </div>
                                                    <div class="meta-item">
                                                        <i class="fas fa-tasks"></i>
                                                        <span>{{ $project->tasks->count() }}</span>
                                                    </div>
                                                    @if($project->start_date)
                                                        <div class="meta-item">
                                                            <i class="fas fa-calendar"></i>
                                                            <span>{{ $project->start_date->format('d/m') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="project-card-footer">
                                                <div class="flex items-center justify-between">
                                                    <div class="project-team">
                                                        @if($project->author)
                                                            <div class="team-avatar" title="{{ $project->author->name }}">
                                                                {{ substr($project->author->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                        @if($project->participants->count() > 0)
                                                            @foreach($project->participants->take(2) as $participant)
                                                                <div class="team-avatar" title="{{ $participant->name }}">
                                                                    {{ substr($participant->name, 0, 1) }}
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        @if($project->participants->count() > 2)
                                                            <div class="team-avatar-more">
                                                                +{{ $project->participants->count() - 2 }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="project-footer-actions">
                                                        <button class="action-btn-small" onclick="event.stopPropagation()" title="Ajouter aux favoris">
                                                            <i class="fas fa-heart"></i>
                                                        </button>
                                                        <button class="action-btn-small" onclick="event.stopPropagation()" title="Partager">
                                                            <i class="fas fa-share"></i>
                                                        </button>
                                                    </div>
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
                                        <div class="enhanced-project-card hover-lift group" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                            <div class="project-color-bar on-hold"></div>
                                            <div class="project-card-header">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="project-icon on-hold">
                                                            <i class="fas fa-pause-circle"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="project-title">{{ $project->name }}</h5>
                                                            <div class="project-subtitle">
                                                                <span class="project-status-badge on-hold">En Pause</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="project-actions-dropdown">
                                                        <button class="action-btn" onclick="event.stopPropagation()">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="project-description-section">
                                                <p class="project-description">{{ Str::limit($project->description, 80, '...') ?: 'Aucune description disponible' }}</p>
                                            </div>
                                            <div class="project-progress-section">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-xs font-medium text-gray-600">Progression</span>
                                                    <span class="text-xs font-semibold text-gray-900">
                                                        @php
                                                            $totalTasks = $project->tasks->count();
                                                            $completedTasks = $project->tasks->where('status', 'done')->count();
                                                            $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                                        @endphp
                                                        {{ $progress }}%
                                                    </span>
                                                </div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill on-hold" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>
                                            <div class="project-meta-section">
                                                <div class="meta-grid">
                                                    <div class="meta-item">
                                                        <i class="fas fa-users"></i>
                                                        <span>{{ $project->participants->count() }}</span>
                                                    </div>
                                                    <div class="meta-item">
                                                        <i class="fas fa-tasks"></i>
                                                        <span>{{ $project->tasks->count() }}</span>
                                                    </div>
                                                    @if($project->start_date)
                                                        <div class="meta-item">
                                                            <i class="fas fa-calendar"></i>
                                                            <span>{{ $project->start_date->format('d/m') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="project-card-footer">
                                                <div class="flex items-center justify-between">
                                                    <div class="project-team">
                                                        @if($project->author)
                                                            <div class="team-avatar" title="{{ $project->author->name }}">
                                                                {{ substr($project->author->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                        @if($project->participants->count() > 0)
                                                            @foreach($project->participants->take(2) as $participant)
                                                                <div class="team-avatar" title="{{ $participant->name }}">
                                                                    {{ substr($participant->name, 0, 1) }}
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        @if($project->participants->count() > 2)
                                                            <div class="team-avatar-more">
                                                                +{{ $project->participants->count() - 2 }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="project-footer-actions">
                                                        <button class="action-btn-small" onclick="event.stopPropagation()" title="Ajouter aux favoris">
                                                            <i class="fas fa-heart"></i>
                                                        </button>
                                                        <button class="action-btn-small" onclick="event.stopPropagation()" title="Partager">
                                                            <i class="fas fa-share"></i>
                                                        </button>
                                                    </div>
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
                                        <div class="enhanced-project-card hover-lift group" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                            <div class="project-color-bar completed"></div>
                                            <div class="project-card-header">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="project-icon completed">
                                                            <i class="fas fa-check-circle"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="project-title">{{ $project->name }}</h5>
                                                            <div class="project-subtitle">
                                                                <span class="project-status-badge completed">Terminé</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="project-actions-dropdown">
                                                        <button class="action-btn" onclick="event.stopPropagation()">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="project-description-section">
                                                <p class="project-description">{{ Str::limit($project->description, 80, '...') ?: 'Aucune description disponible' }}</p>
                                            </div>
                                            <div class="project-progress-section">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-xs font-medium text-gray-600">Progression</span>
                                                    <span class="text-xs font-semibold text-gray-900">
                                                        @php
                                                            $totalTasks = $project->tasks->count();
                                                            $completedTasks = $project->tasks->where('status', 'done')->count();
                                                            $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 100;
                                                        @endphp
                                                        {{ $progress }}%
                                                    </span>
                                                </div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill completed" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>
                                            <div class="project-meta-section">
                                                <div class="meta-grid">
                                                    <div class="meta-item">
                                                        <i class="fas fa-users"></i>
                                                        <span>{{ $project->participants->count() }}</span>
                                                    </div>
                                                    <div class="meta-item">
                                                        <i class="fas fa-tasks"></i>
                                                        <span>{{ $project->tasks->count() }}</span>
                                                    </div>
                                                    @if($project->end_date)
                                                        <div class="meta-item">
                                                            <i class="fas fa-calendar"></i>
                                                            <span>{{ $project->end_date->format('d/m') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="project-card-footer">
                                                <div class="flex items-center justify-between">
                                                    <div class="project-team">
                                                        @if($project->author)
                                                            <div class="team-avatar" title="{{ $project->author->name }}">
                                                                {{ substr($project->author->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                        @if($project->participants->count() > 0)
                                                            @foreach($project->participants->take(2) as $participant)
                                                                <div class="team-avatar" title="{{ $participant->name }}">
                                                                    {{ substr($participant->name, 0, 1) }}
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        @if($project->participants->count() > 2)
                                                            <div class="team-avatar-more">
                                                                +{{ $project->participants->count() - 2 }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="project-footer-actions">
                                                        <button class="action-btn-small" onclick="event.stopPropagation()" title="Ajouter aux favoris">
                                                            <i class="fas fa-heart"></i>
                                                        </button>
                                                        <button class="action-btn-small" onclick="event.stopPropagation()" title="Partager">
                                                            <i class="fas fa-share"></i>
                                                        </button>
                                                    </div>
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
        
        /* Cartes de projet améliorées */
        .enhanced-project-card {
            @apply bg-white rounded-xl border border-gray-200 shadow-sm cursor-pointer transition-all duration-300;
            position: relative;
            overflow: hidden;
        }
        
        .enhanced-project-card:hover {
            @apply shadow-lg border-primary-300 transform translate-y-2 scale-105;
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
        
        /* Header des cartes */
        .project-card-header {
            @apply p-4 pt-6;
        }
        
        .project-icon {
            @apply w-10 h-10 rounded-lg flex items-center justify-center text-white;
        }
        
        .project-icon.planning {
            @apply bg-gradient-to-br from-gray-500 to-gray-600;
        }
        
        .project-icon.active {
            @apply bg-gradient-to-br from-blue-500 to-blue-600;
        }
        
        .project-icon.on-hold {
            @apply bg-gradient-to-br from-yellow-500 to-yellow-600;
        }
        
        .project-icon.completed {
            @apply bg-gradient-to-br from-green-500 to-green-600;
        }
        
        .project-title {
            @apply text-base font-bold text-gray-900 mb-1 leading-tight;
            margin: 0;
        }
        
        .project-subtitle {
            @apply mb-0;
        }
        
        .project-status-badge {
            @apply text-xs font-medium px-2 py-1 rounded-full;
        }
        
        .project-status-badge.planning {
            @apply bg-gray-100 text-gray-700;
        }
        
        .project-status-badge.active {
            @apply bg-blue-100 text-blue-700;
        }
        
        .project-status-badge.on-hold {
            @apply bg-yellow-100 text-yellow-700;
        }
        
        .project-status-badge.completed {
            @apply bg-green-100 text-green-700;
        }
        
        /* Actions dropdown */
        .project-actions-dropdown {
            @apply flex items-center;
        }
        
        .action-btn {
            @apply w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all;
        }
        
        /* Description */
        .project-description-section {
            @apply px-4 pb-3;
        }
        
        .project-description {
            @apply text-sm text-gray-600 leading-relaxed line-clamp-2;
        }
        
        /* Progression */
        .project-progress-section {
            @apply px-4 pb-3;
        }
        
        .progress-bar {
            @apply w-full h-2 bg-gray-200 rounded-full overflow-hidden;
        }
        
        .progress-fill {
            @apply h-full rounded-full transition-all duration-500;
        }
        
        .progress-fill.planning {
            @apply bg-gradient-to-r from-gray-400 to-gray-500;
        }
        
        .progress-fill.active {
            @apply bg-gradient-to-r from-blue-400 to-blue-500;
        }
        
        .progress-fill.on-hold {
            @apply bg-gradient-to-r from-yellow-400 to-yellow-500;
        }
        
        .progress-fill.completed {
            @apply bg-gradient-to-r from-green-400 to-green-500;
        }
        
        /* Métadonnées */
        .project-meta-section {
            @apply px-4 pb-3;
        }
        
        .meta-grid {
            @apply grid grid-cols-3 gap-2;
        }
        
        .meta-item {
            @apply flex items-center space-x-1 text-xs text-gray-500;
        }
        
        .meta-item i {
            @apply text-gray-400;
        }
        
        /* Footer */
        .project-card-footer {
            @apply px-4 py-3 border-t border-gray-100;
        }
        
        .project-team {
            @apply flex items-center space-x-2;
        }
        
        .team-avatar {
            @apply w-7 h-7 bg-gradient-to-br from-primary-500 to-primary-600 text-white text-xs font-semibold rounded-full flex items-center justify-center border-2 border-white shadow-sm;
        }
        
        .team-avatar-more {
            @apply w-7 h-7 bg-gray-200 text-gray-600 text-xs font-semibold rounded-full flex items-center justify-center border-2 border-white;
        }
        
        .project-footer-actions {
            @apply flex items-center space-x-1;
        }
        
        .action-btn-small {
            @apply w-6 h-6 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all;
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
            
            .enhanced-project-card {
                @apply transform-none hover:transform-none;
            }
            
            .meta-grid {
                @apply grid-cols-2 gap-1;
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