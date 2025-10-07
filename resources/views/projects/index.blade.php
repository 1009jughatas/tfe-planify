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
            <!-- Kanban Board Style Moderne -->
            <div class="kanban-board-container">
                <div class="kanban-grid">
                    <!-- En Planification -->
                    <div class="kanban-column">
                        <div class="kanban-column-header">
                            <div class="kanban-header-content">
                                <h3 class="kanban-column-title">En Planification</h3>
                                <div class="kanban-header-actions">
                                    <span class="kanban-count">
                                        @php $planningProjects = $projects->where('status', 'planning') @endphp
                                        {{ $planningProjects->count() }}
                                    </span>
                                    <button class="kanban-menu-btn">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="kanban-column-content">
                            @if ($planningProjects->isEmpty())
                                <div class="empty-state">
                                    <p class="empty-text">Aucun projet en planification</p>
                                </div>
                            @else
                                @foreach ($planningProjects as $project)
                                    <div class="kanban-card" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                        <div class="card-color-bar planning-bar"></div>
                                        <div class="card-content">
                                            <h4 class="card-title">{{ $project->name }}</h4>
                                            <div class="card-meta">
                                                <div class="card-meta-item">
                                                    <i class="fas fa-eye"></i>
                                                </div>
                                                @if($project->start_date)
                                                    <div class="card-meta-item">
                                                        <i class="fas fa-clock"></i>
                                                        <span class="date-range">{{ $project->start_date->format('M j') }}</span>
                                                    </div>
                                                @endif
                                                @if($project->tasks->count() > 0)
                                                    <div class="card-meta-item">
                                                        <i class="fas fa-list-ul"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-footer">
                                                <div class="card-assignees">
                                                    @if($project->author)
                                                        <div class="assignee-avatar">
                                                            {{ substr($project->author->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    @if($project->participants->count() > 0)
                                                        <div class="assignee-avatar">
                                                            {{ substr($project->participants->first()->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <button class="add-card-btn">
                                <i class="fas fa-plus"></i>
                                Ajouter un projet
                            </button>
                        </div>
                    </div>

                    <!-- Actif -->
                    <div class="kanban-column">
                        <div class="kanban-column-header">
                            <div class="kanban-header-content">
                                <h3 class="kanban-column-title">Actif</h3>
                                <div class="kanban-header-actions">
                                    <span class="kanban-count">
                                        @php $activeProjects = $projects->where('status', 'active') @endphp
                                        {{ $activeProjects->count() }}
                                    </span>
                                    <button class="kanban-menu-btn">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="kanban-column-content">
                            @if ($activeProjects->isEmpty())
                                <div class="empty-state">
                                    <p class="empty-text">Aucun projet actif</p>
                                </div>
                            @else
                                @foreach ($activeProjects as $project)
                                    <div class="kanban-card" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                        <div class="card-color-bar active-bar"></div>
                                        <div class="card-content">
                                            <h4 class="card-title">{{ $project->name }}</h4>
                                            <div class="card-meta">
                                                <div class="card-meta-item">
                                                    <i class="fas fa-eye"></i>
                                                </div>
                                                @if($project->start_date)
                                                    <div class="card-meta-item">
                                                        <i class="fas fa-clock"></i>
                                                        <span class="date-range">{{ $project->start_date->format('M j') }} - {{ $project->end_date ? $project->end_date->format('M j') : 'TBD' }}</span>
                                                    </div>
                                                @endif
                                                @if($project->tasks->count() > 0)
                                                    <div class="card-meta-item">
                                                        <i class="fas fa-list-ul"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-footer">
                                                <div class="card-assignees">
                                                    @if($project->author)
                                                        <div class="assignee-avatar">
                                                            {{ substr($project->author->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    @if($project->participants->count() > 0)
                                                        <div class="assignee-avatar">
                                                            {{ substr($project->participants->first()->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <button class="add-card-btn">
                                <i class="fas fa-plus"></i>
                                Ajouter un projet
                            </button>
                        </div>
                    </div>

                    <!-- En Pause -->
                    <div class="kanban-column">
                        <div class="kanban-column-header">
                            <div class="kanban-header-content">
                                <h3 class="kanban-column-title">En Pause</h3>
                                <div class="kanban-header-actions">
                                    <span class="kanban-count">
                                        @php $onHoldProjects = $projects->where('status', 'on-hold') @endphp
                                        {{ $onHoldProjects->count() }}
                                    </span>
                                    <button class="kanban-menu-btn">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="kanban-column-content">
                            @if ($onHoldProjects->isEmpty())
                                <div class="empty-state">
                                    <p class="empty-text">Aucun projet en pause</p>
                                </div>
                            @else
                                @foreach ($onHoldProjects as $project)
                                    <div class="kanban-card" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                        <div class="card-color-bar on-hold-bar"></div>
                                        <div class="card-content">
                                            <h4 class="card-title">{{ $project->name }}</h4>
                                            <div class="card-meta">
                                                <div class="card-meta-item">
                                                    <i class="fas fa-eye"></i>
                                                </div>
                                                @if($project->start_date)
                                                    <div class="card-meta-item">
                                                        <i class="fas fa-clock"></i>
                                                        <span class="date-range">{{ $project->start_date->format('M j') }}</span>
                                                    </div>
                                                @endif
                                                @if($project->tasks->count() > 0)
                                                    <div class="card-meta-item">
                                                        <i class="fas fa-list-ul"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-footer">
                                                <div class="card-assignees">
                                                    @if($project->author)
                                                        <div class="assignee-avatar">
                                                            {{ substr($project->author->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    @if($project->participants->count() > 0)
                                                        <div class="assignee-avatar">
                                                            {{ substr($project->participants->first()->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <button class="add-card-btn">
                                <i class="fas fa-plus"></i>
                                Ajouter un projet
                            </button>
                        </div>
                    </div>

                    <!-- Terminé -->
                    <div class="kanban-column">
                        <div class="kanban-column-header">
                            <div class="kanban-header-content">
                                <h3 class="kanban-column-title">Terminé</h3>
                                <div class="kanban-header-actions">
                                    <span class="kanban-count">
                                        @php $completedProjects = $projects->where('status', 'completed') @endphp
                                        {{ $completedProjects->count() }}
                                    </span>
                                    <button class="kanban-menu-btn">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="kanban-column-content">
                            @if ($completedProjects->isEmpty())
                                <div class="empty-state">
                                    <p class="empty-text">Aucun projet terminé</p>
                                </div>
                            @else
                                @foreach ($completedProjects as $project)
                                    <div class="kanban-card" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                        <div class="card-color-bar completed-bar"></div>
                                        <div class="card-content">
                                            <h4 class="card-title">{{ $project->name }}</h4>
                                            <div class="card-meta">
                                                <div class="card-meta-item">
                                                    <i class="fas fa-eye"></i>
                                                </div>
                                                @if($project->end_date)
                                                    <div class="card-meta-item">
                                                        <i class="fas fa-clock"></i>
                                                        <span class="date-range completed-date">{{ $project->end_date->format('M j') }}</span>
                                                    </div>
                                                @endif
                                                @if($project->tasks->count() > 0)
                                                    <div class="card-meta-item">
                                                        <i class="fas fa-list-ul"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-footer">
                                                <div class="card-assignees">
                                                    @if($project->author)
                                                        <div class="assignee-avatar">
                                                            {{ substr($project->author->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    @if($project->participants->count() > 0)
                                                        @foreach($project->participants->take(2) as $participant)
                                                            <div class="assignee-avatar">
                                                                {{ substr($participant->name, 0, 1) }}
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <button class="add-card-btn">
                                <i class="fas fa-plus"></i>
                                Ajouter un projet
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        /* Kanban Board Style Moderne - Inspiré du design professionnel */
        .kanban-board-container {
            @apply w-full overflow-x-auto;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: calc(100vh - 200px);
            padding: 24px;
            border-radius: 16px;
        }
        
        .kanban-grid {
            @apply grid gap-6;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            min-width: max-content;
        }
        
        /* Colonnes Kanban */
        .kanban-column {
            @apply bg-white rounded-xl shadow-lg;
            min-height: 600px;
            width: 280px;
        }
        
        /* En-têtes de colonnes */
        .kanban-column-header {
            @apply bg-white rounded-t-xl border-b border-gray-200;
            padding: 16px;
        }
        
        .kanban-header-content {
            @apply flex items-center justify-between;
        }
        
        .kanban-column-title {
            @apply text-lg font-semibold text-gray-800;
            margin: 0;
        }
        
        .kanban-header-actions {
            @apply flex items-center space-x-2;
        }
        
        .kanban-count {
            @apply bg-gray-100 text-gray-600 text-sm font-medium px-2 py-1 rounded-full;
        }
        
        .kanban-menu-btn {
            @apply text-gray-400 hover:text-gray-600 p-1 rounded transition-colors;
        }
        
        /* Contenu des colonnes */
        .kanban-column-content {
            @apply p-4 space-y-3;
            min-height: 500px;
        }
        
        /* Cartes Kanban */
        .kanban-card {
            @apply bg-white rounded-lg shadow-sm border border-gray-200 cursor-pointer transition-all duration-200;
            position: relative;
            overflow: hidden;
        }
        
        .kanban-card:hover {
            @apply shadow-md transform scale-105;
        }
        
        /* Barres colorées */
        .card-color-bar {
            @apply absolute top-0 left-0 right-0 h-1;
        }
        
        .planning-bar {
            background: linear-gradient(90deg, #fbbf24, #f59e0b);
        }
        
        .active-bar {
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        }
        
        .on-hold-bar {
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }
        
        .completed-bar {
            background: linear-gradient(90deg, #10b981, #059669);
        }
        
        /* Contenu des cartes */
        .card-content {
            @apply p-4 pt-5;
        }
        
        .card-title {
            @apply text-sm font-semibold text-gray-900 mb-3 leading-tight;
            margin: 0;
        }
        
        .card-meta {
            @apply flex items-center space-x-3 mb-3;
        }
        
        .card-meta-item {
            @apply flex items-center space-x-1 text-gray-500;
        }
        
        .card-meta-item i {
            @apply text-xs;
        }
        
        .date-range {
            @apply text-xs text-gray-600 font-medium;
        }
        
        .completed-date {
            @apply text-green-600;
        }
        
        /* Footer des cartes */
        .card-footer {
            @apply mt-3;
        }
        
        .card-assignees {
            @apply flex items-center space-x-1;
        }
        
        .assignee-avatar {
            @apply w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 text-white text-xs font-semibold rounded-full flex items-center justify-center;
        }
        
        /* Bouton d'ajout */
        .add-card-btn {
            @apply w-full py-3 px-4 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-primary-400 hover:text-primary-600 transition-all duration-200 flex items-center justify-center space-x-2;
        }
        
        .add-card-btn:hover {
            @apply bg-primary-50;
        }
        
        /* États vides */
        .empty-state {
            @apply flex items-center justify-center h-32 text-center;
        }
        
        .empty-text {
            @apply text-gray-500 text-sm;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .kanban-board-container {
                @apply p-4;
            }
            
            .kanban-grid {
                @apply grid-cols-1 gap-4;
            }
            
            .kanban-column {
                @apply w-full;
            }
        }
        
        @media (min-width: 769px) and (max-width: 1024px) {
            .kanban-grid {
                @apply grid-cols-2;
            }
        }
        
        @media (min-width: 1025px) {
            .kanban-grid {
                @apply grid-cols-4;
            }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .kanban-card {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Scrollbar personnalisée */
        .kanban-board-container::-webkit-scrollbar {
            height: 8px;
        }
        
        .kanban-board-container::-webkit-scrollbar-track {
            @apply bg-gray-200 rounded;
        }
        
        .kanban-board-container::-webkit-scrollbar-thumb {
            @apply bg-gray-400 rounded;
        }
        
        .kanban-board-container::-webkit-scrollbar-thumb:hover {
            @apply bg-gray-500;
        }
    </style>
</x-app-layout>