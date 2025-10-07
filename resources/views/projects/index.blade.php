<x-app-layout>
    <x-slot name="header">
        <!-- Barre de navigation Kanban style -->
        <div class="kanban-top-bar">
            <div class="kanban-nav-left">
                <div class="kanban-nav-title">
                    <i class="fas fa-arrow-left kanban-back-btn"></i>
                    <h1 class="kanban-main-title">Mes Projets</h1>
                    <i class="fas fa-star kanban-star-btn"></i>
                </div>
                <div class="kanban-nav-center">
                    <i class="fas fa-users"></i>
                    <i class="fas fa-th"></i>
                    <span class="kanban-view-selector">
                        Board <i class="fas fa-chevron-down"></i>
                    </span>
                </div>
            </div>
            <div class="kanban-nav-center-main">
                <span class="kanban-power-up">Calendar Power-Up <i class="fas fa-chevron-down"></i></span>
                <i class="fas fa-paper-plane"></i>
                <i class="fas fa-bolt"></i>
            </div>
            <div class="kanban-nav-right">
                <button class="kanban-filter-btn">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
                <div class="kanban-user-avatar">
                    <span class="avatar-letter">D</span>
                    <span class="avatar-badge">+2</span>
                </div>
            </div>
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
            <!-- Vrai Kanban Board comme sur la photo -->
            <div class="kanban-board-wrapper">
                <div class="kanban-board">
                    <!-- Prerequisites -->
                    <div class="kanban-list">
                        <div class="kanban-list-header">
                            <h3 class="kanban-list-title">Prerequisites</h3>
                            <button class="kanban-list-menu">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                        <div class="kanban-list-content">
                            @php $planningProjects = $projects->where('status', 'planning') @endphp
                            @foreach ($planningProjects as $project)
                                <div class="kanban-card" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                    <div class="card-label yellow-label"></div>
                                    <div class="card-content">
                                        <h4 class="card-title">{{ $project->name }}</h4>
                                        <div class="card-icons">
                                            <i class="fas fa-eye"></i>
                                            @if($project->start_date)
                                                <div class="card-date">
                                                    <i class="fas fa-clock"></i>
                                                    <span>{{ $project->start_date->format('M j') }} - {{ $project->end_date ? $project->end_date->format('M j') : 'TBD' }}</span>
                                                </div>
                                            @endif
                                            <i class="fas fa-grip-lines"></i>
                                        </div>
                                        <div class="card-members">
                                            @if($project->author)
                                                <div class="member-avatar">{{ substr($project->author->name, 0, 1) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="add-card-button">
                            <i class="fas fa-plus"></i>
                            Add a card
                        </button>
                    </div>

                    <!-- Planning and Design -->
                    <div class="kanban-list">
                        <div class="kanban-list-header">
                            <h3 class="kanban-list-title">Planning and Design</h3>
                            <button class="kanban-list-menu">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                        <div class="kanban-list-content">
                            @php $activeProjects = $projects->where('status', 'active') @endphp
                            @foreach ($activeProjects as $project)
                                <div class="kanban-card" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                    <div class="card-label red-label"></div>
                                    <div class="card-content">
                                        <h4 class="card-title">{{ $project->name }}</h4>
                                        <div class="card-icons">
                                            <i class="fas fa-eye"></i>
                                            @if($project->start_date)
                                                <div class="card-date">
                                                    <i class="fas fa-clock"></i>
                                                    <span>{{ $project->start_date->format('M j') }} - {{ $project->end_date ? $project->end_date->format('M j') : 'TBD' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-members">
                                            @if($project->author)
                                                <div class="member-avatar">{{ substr($project->author->name, 0, 1) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="add-card-button">
                            <i class="fas fa-plus"></i>
                            Add a card
                        </button>
                    </div>

                    <!-- Development and Testing -->
                    <div class="kanban-list">
                        <div class="kanban-list-header">
                            <h3 class="kanban-list-title">Development and Testing</h3>
                            <button class="kanban-list-menu">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                        <div class="kanban-list-content">
                            @php $onHoldProjects = $projects->where('status', 'on-hold') @endphp
                            @foreach ($onHoldProjects as $project)
                                <div class="kanban-card" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                    <div class="card-label blue-label"></div>
                                    <div class="card-content">
                                        <h4 class="card-title">{{ $project->name }}</h4>
                                        <div class="card-icons">
                                            <i class="fas fa-eye"></i>
                                            @if($project->start_date)
                                                <div class="card-date">
                                                    <i class="fas fa-clock"></i>
                                                    <span>{{ $project->start_date->format('M j') }} - {{ $project->end_date ? $project->end_date->format('M j') : 'TBD' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-members">
                                            @if($project->author)
                                                <div class="member-avatar">{{ substr($project->author->name, 0, 1) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="add-card-button">
                            <i class="fas fa-plus"></i>
                            Add a card
                        </button>
                    </div>

                    <!-- Release and Close Out -->
                    <div class="kanban-list">
                        <div class="kanban-list-header">
                            <h3 class="kanban-list-title">Release and Close Out</h3>
                            <button class="kanban-list-menu">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                        <div class="kanban-list-content">
                            @php $completedProjects = $projects->where('status', 'completed') @endphp
                            @foreach ($completedProjects as $project)
                                <div class="kanban-card" onclick="window.location.href='{{ route('projects.show', $project->id) }}'">
                                    <div class="card-label red-label"></div>
                                    <div class="card-content">
                                        <h4 class="card-title">{{ $project->name }}</h4>
                                        <div class="card-icons">
                                            <i class="fas fa-eye"></i>
                                            @if($project->end_date)
                                                <div class="card-date">
                                                    <i class="fas fa-clock"></i>
                                                    <span>{{ $project->end_date->format('M j') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-members">
                                            @if($project->author)
                                                <div class="member-avatar">{{ substr($project->author->name, 0, 1) }}</div>
                                            @endif
                                            @if($project->participants->count() > 0)
                                                @foreach($project->participants->take(2) as $participant)
                                                    <div class="member-avatar">{{ substr($participant->name, 0, 1) }}</div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="add-card-button">
                            <i class="fas fa-plus"></i>
                            Add a card
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        /* Barre de navigation supérieure */
        .kanban-top-bar {
            @apply flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
        }
        
        .kanban-nav-left {
            @apply flex items-center space-x-6;
        }
        
        .kanban-nav-title {
            @apply flex items-center space-x-3;
        }
        
        .kanban-back-btn, .kanban-star-btn {
            @apply text-white hover:text-yellow-300 cursor-pointer;
        }
        
        .kanban-main-title {
            @apply text-xl font-bold text-white;
            margin: 0;
        }
        
        .kanban-nav-center {
            @apply flex items-center space-x-4 text-white;
        }
        
        .kanban-view-selector {
            @apply flex items-center space-x-1 cursor-pointer;
        }
        
        .kanban-nav-center-main {
            @apply flex items-center space-x-4 text-white;
        }
        
        .kanban-power-up {
            @apply flex items-center space-x-1 cursor-pointer;
        }
        
        .kanban-nav-right {
            @apply flex items-center space-x-4;
        }
        
        .kanban-filter-btn {
            @apply flex items-center space-x-2 bg-white bg-opacity-20 text-white px-3 py-2 rounded hover:bg-opacity-30 transition-all;
        }
        
        .kanban-user-avatar {
            @apply relative;
        }
        
        .avatar-letter {
            @apply w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-semibold;
        }
        
        .avatar-badge {
            @apply absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center;
        }
        
        /* Container principal */
        .kanban-board-wrapper {
            @apply w-full;
            background: linear-gradient(135deg, #1e1b4b 0%, #581c87 100%);
            min-height: calc(100vh - 80px);
            padding: 24px;
        }
        
        .kanban-board {
            @apply flex space-x-6 overflow-x-auto pb-4;
        }
        
        /* Colonnes (Lists) */
        .kanban-list {
            @apply bg-gray-100 rounded-lg;
            width: 272px;
            flex-shrink: 0;
            min-height: 600px;
        }
        
        .kanban-list-header {
            @apply flex items-center justify-between p-4 border-b border-gray-200;
        }
        
        .kanban-list-title {
            @apply text-lg font-semibold text-gray-800;
            margin: 0;
        }
        
        .kanban-list-menu {
            @apply text-gray-400 hover:text-gray-600 p-1 rounded;
        }
        
        .kanban-list-content {
            @apply p-4 space-y-3;
            min-height: 400px;
        }
        
        /* Cartes */
        .kanban-card {
            @apply bg-white rounded-lg shadow-sm cursor-pointer;
            position: relative;
            padding: 12px;
            border: 1px solid #e5e7eb;
        }
        
        .kanban-card:hover {
            @apply shadow-md;
        }
        
        /* Labels colorés */
        .card-label {
            @apply absolute top-0 left-0 right-0 h-1 rounded-t-lg;
        }
        
        .yellow-label {
            background: #fbbf24;
        }
        
        .red-label {
            background: #ef4444;
        }
        
        .blue-label {
            background: #3b82f6;
        }
        
        .purple-label {
            background: #8b5cf6;
        }
        
        .orange-label {
            background: #f59e0b;
        }
        
        /* Contenu des cartes */
        .card-content {
            @apply pt-2;
        }
        
        .card-title {
            @apply text-sm font-semibold text-gray-900 mb-3 leading-tight;
            margin: 0;
        }
        
        .card-icons {
            @apply flex items-center space-x-3 mb-3;
        }
        
        .card-icons i {
            @apply text-gray-500 text-sm;
        }
        
        .card-date {
            @apply flex items-center space-x-1 text-gray-600 text-xs;
        }
        
        .card-date i {
            @apply text-red-500;
        }
        
        .card-members {
            @apply flex items-center space-x-1;
        }
        
        .member-avatar {
            @apply w-6 h-6 bg-blue-500 text-white text-xs font-semibold rounded-full flex items-center justify-center;
        }
        
        /* Bouton Add a card */
        .add-card-button {
            @apply w-full p-4 text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded-lg transition-all flex items-center space-x-2;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .kanban-board-wrapper {
                @apply p-4;
            }
            
            .kanban-board {
                @apply flex-col space-x-0 space-y-4;
            }
            
            .kanban-list {
                @apply w-full;
            }
        }
        
        /* Scrollbar personnalisée */
        .kanban-board::-webkit-scrollbar {
            height: 8px;
        }
        
        .kanban-board::-webkit-scrollbar-track {
            @apply bg-gray-200 rounded;
        }
        
        .kanban-board::-webkit-scrollbar-thumb {
            @apply bg-gray-400 rounded;
        }
        
        .kanban-board::-webkit-scrollbar-thumb:hover {
            @apply bg-gray-500;
        }
    </style>
</x-app-layout>