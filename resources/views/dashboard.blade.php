
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                    <img src="{{ asset('images/logo.png') }}" alt="Planify" class="w-8 h-8 object-contain filter brightness-0 invert">
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Personnel</h1>
                    <p class="text-gray-600 mt-1 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                        Gérez vos projets et tâches personnels
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @if (Auth::user() && Auth::user()->is_premium())
                       <a href="{{ route('export.dashboard') }}" class="btn-premium-modern">
                           <i class="fas fa-file-pdf mr-2"></i>
                           Export Dashboard PDF
                       </a>
                    <span class="badge-premium">
                        <i class="fas fa-crown mr-1"></i>Premium
                    </span>
                @else
                    <a href="{{ route('premium.show') }}" class="btn-primary-modern">
                        <i class="fas fa-crown mr-2"></i>
                        Passer Premium
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Hero Section -->
        <div class="mb-8">
            <div class="modern-card bg-blue-50 border-blue-200">
                <div class="modern-card-body text-center py-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-md">
                        <img src="{{ asset('images/logo.png') }}" alt="Planify" class="w-10 h-10 object-contain filter brightness-0 invert">
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Bienvenue, {{ Auth::user()->name }} !</h2>
                    <p class="text-gray-600 mb-4">Votre espace de travail personnel pour organiser vos projets et tâches</p>
                    <div class="flex justify-center space-x-3">
                        <a href="{{ route('projects.create') }}" class="btn-primary-modern">
                            <i class="fas fa-plus mr-2"></i>
                            Nouveau Projet
                        </a>
                        <a href="{{ route('projects.index') }}" class="btn-secondary-modern">
                            <i class="fas fa-folder-open mr-2"></i>
                            Voir Mes Projets
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Premium -->
        @if (Auth::user() && Auth::user()->is_premium())
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-crown text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Fonctionnalités Premium Actives</h3>
                            <p class="text-sm text-gray-600">Vous bénéficiez de toutes les fonctionnalités Premium</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                       <a href="{{ route('export.dashboard') }}" class="btn-premium-modern">
                           <i class="fas fa-file-pdf mr-2"></i>
                           Export Dashboard PDF
                       </a>
                        <a href="{{ route('preferences.edit') }}" class="btn-premium-modern">
                            <i class="fas fa-palette mr-2"></i>
                            Thème Sombre
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-crown text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Débloquez Premium</h3>
                            <p class="text-sm text-gray-600">Export de données, analyses détaillées, thème sombre et plus</p>
                        </div>
                    </div>
                    <a href="{{ route('premium.show') }}" class="btn-primary-modern">
                        <i class="fas fa-crown mr-2"></i>
                        Passer Premium
                    </a>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Active Projects -->
            <div class="stats-card hover-lift group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Projets Actifs</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $activeProjectsCount }}</p>
                        <p class="text-xs text-gray-500 mt-1 flex items-center">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            En cours
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-md border border-gray-200">
                        <img src="{{ asset('images/logo.png') }}" alt="Planify Logo" class="w-8 h-8 object-contain">
                    </div>
                </div>
            </div>

            <!-- Open Tasks -->
            <div class="stats-card hover-lift group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Tâches Ouvertes</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $openTasksCount }}</p>
                        <p class="text-xs text-gray-500 mt-1 flex items-center">
                            <i class="fas fa-clock text-orange-500 mr-1"></i>
                            À faire
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-tasks text-white text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Completed Projects -->
            <div class="stats-card hover-lift group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Projets Terminés</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $completedProjectsCount }}</p>
                        <p class="text-xs text-gray-500 mt-1 flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>
                            Finalisés
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Premium Feature -->
            @if (Auth::user() && Auth::user()->is_premium())
                <div class="stats-card hover-lift bg-gradient-to-br from-purple-500 to-pink-500 text-white border-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/90 mb-1">Premium</p>
                            <p class="text-lg font-semibold text-white">Export Disponible</p>
                            <a href="{{ route('dashboard.exportReport') }}" class="inline-flex items-center text-white/90 hover:text-white text-xs mt-2 transition-colors">
                                <i class="fas fa-download mr-1"></i>
                                Télécharger
                            </a>
                        </div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <i class="fas fa-crown text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            @else
                <div class="stats-card hover-lift border-2 border-dashed border-purple-200 bg-gradient-to-br from-purple-50 to-pink-50">
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <i class="fas fa-crown text-white text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-700 mb-1">Devenez Premium</p>
                            <p class="text-xs text-gray-500">Accédez aux rapports</p>
                            <a href="{{ route('premium.show') }}" class="inline-block mt-2 text-xs bg-gradient-to-r from-purple-500 to-pink-500 text-white px-3 py-1 rounded-full hover:shadow-md transition-all">
                                En savoir plus
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Calendrier Simple -->
            <div>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                            Calendrier des Échéances
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <!-- Calendrier Simple -->
                        <div class="simple-calendar">
                            <div class="calendar-header">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-semibold text-gray-800" id="current-month">{{ date('F Y') }}</h4>
                                    <div class="flex space-x-2">
                                        <button onclick="changeMonth(-1)" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                                            <i class="fas fa-chevron-left text-gray-600"></i>
                                        </button>
                                        <button onclick="goToToday()" class="px-3 py-2 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors text-sm font-medium">
                                            Aujourd'hui
                                        </button>
                                        <button onclick="changeMonth(1)" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                                            <i class="fas fa-chevron-right text-gray-600"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Légende -->
                            <div class="flex flex-wrap gap-3 mb-4">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                                    <span class="text-sm text-gray-600">Projets</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                    <span class="text-sm text-gray-600">Tâches terminées</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-orange-500 rounded mr-2"></div>
                                    <span class="text-sm text-gray-600">Tâches en cours</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                                    <span class="text-sm text-gray-600">Tâches en retard</span>
                                </div>
                            </div>

                            <!-- Grille du calendrier -->
                            <div class="calendar-grid" id="calendar-grid">
                                <!-- Les jours de la semaine -->
                                <div class="calendar-weekdays">
                                    <div class="calendar-weekday">Lun</div>
                                    <div class="calendar-weekday">Mar</div>
                                    <div class="calendar-weekday">Mer</div>
                                    <div class="calendar-weekday">Jeu</div>
                                    <div class="calendar-weekday">Ven</div>
                                    <div class="calendar-weekday">Sam</div>
                                    <div class="calendar-weekday">Dim</div>
                                </div>
                                
                                <!-- Les jours du mois seront générés par JavaScript -->
                                <div class="calendar-days" id="calendar-days"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des Projets et Tâches -->
            <div>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                                <i class="fas fa-list text-white"></i>
                            </div>
                            Projets et Tâches
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <!-- Projets Actifs -->
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-5 h-5 mr-2 object-contain">
                                Mes Projets
                            </h4>
                            @if($projects->count() > 0)
                                <div class="space-y-3">
                                    @foreach($projects->take(5) as $project)
                                        <div class="project-item p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-all">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <h5 class="font-medium text-gray-900">{{ $project->name }}</h5>
                                                    <div class="flex items-center space-x-4 mt-1">
                                                        <span class="text-sm text-gray-500">
                                                            <i class="fas fa-tasks mr-1"></i>
                                                            {{ $project->tasks->count() }} tâches
                                                        </span>
                                                        @if($project->start_date)
                                                            <span class="text-sm text-gray-500">
                                                                <i class="fas fa-calendar mr-1"></i>
                                                                {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                        @if($project->status === 'completed') bg-green-100 text-green-800
                                                        @elseif($project->status === 'in-progress') bg-blue-100 text-blue-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        @switch($project->status)
                                                            @case('planning') 📋 En planification @break
                                                            @case('active') 🚀 Actif @break
                                                            @case('on-hold') ⏸️ En pause @break
                                                            @case('completed') ✅ Terminé @break
                                                            @case('cancelled') ❌ Annulé @break
                                                            @default 📋 En planification
                                                        @endswitch
                                                    </span>
                                                    <a href="{{ route('projects.show', $project->id) }}" class="text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($projects->count() > 5)
                                    <div class="mt-4 text-center">
                                        <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Voir tous les projets ({{ $projects->count() }})
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 mx-auto mb-3 opacity-50">
                                    <p>Aucun projet créé</p>
                                    <a href="{{ route('projects.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Créer votre premier projet
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Tâches Récentes -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-tasks text-orange-500 mr-2"></i>
                                Tâches Récentes
                            </h4>
                            @if($tasks->count() > 0)
                                <div class="space-y-3">
                                    @foreach($tasks->take(5) as $task)
                                        <div class="task-item p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-all">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <h5 class="font-medium text-gray-900">{{ $task->title }}</h5>
                                                    <div class="flex items-center space-x-4 mt-1">
                                                        <span class="text-sm text-gray-500">
                                                            <i class="fas fa-folder mr-1"></i>
                                                            {{ $task->project->name }}
                                                        </span>
                                                        @if($task->due_date)
                                                            <span class="text-sm text-gray-500">
                                                                <i class="fas fa-clock mr-1"></i>
                                                                {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full task-status-badge
                                            @if($task->status === 'completed') bg-green-100 text-green-800
                                            @elseif($task->status === 'in-progress') bg-orange-100 text-orange-800
                                            @else bg-gray-100 text-gray-800 @endif" 
                                            data-task-id="{{ $task->id }}" id="task-status-{{ $task->id }}">
                                                        @switch($task->status)
                                                            @case('completed') ✅ Terminé @break
                                                            @case('in-progress') 🚀 En cours @break
                                                            @case('blocked') 🚫 Bloqué @break
                                                            @default 📋 En attente
                                                        @endswitch
                                                    </span>
                                                    <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($tasks->count() > 5)
                                    <div class="mt-4 text-center">
                                        <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Voir toutes les tâches ({{ $tasks->count() }})
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-tasks text-4xl mb-3"></i>
                                    <p>Aucune tâche créée</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                        <i class="fas fa-bolt text-white"></i>
                    </div>
                    Actions Rapides
                </h3>
            </div>
            <div class="modern-card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('projects.create') }}" class="group p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-plus text-white"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Nouveau Projet</h4>
                            <p class="text-sm text-gray-600 mt-1">Créer un nouveau projet</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('projects.index') }}" class="group p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-folder-open text-white"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Mes Projets</h4>
                            <p class="text-sm text-gray-600 mt-1">Gérer mes projets</p>
                        </div>
                    </a>
                    
                    @if (Auth::user() && !Auth::user()->is_premium())
                        <a href="{{ route('premium.show') }}" class="group p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-crown text-white"></i>
                                </div>
                                <h4 class="font-semibold text-gray-900">Devenir Premium</h4>
                                <p class="text-sm text-gray-600 mt-1">Débloquer plus de fonctionnalités</p>
                            </div>
                        </a>
                    @else
                        <a href="{{ route('profile.edit') }}" class="group p-6 bg-gradient-to-br from-gray-50 to-slate-50 rounded-2xl border border-gray-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-slate-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <h4 class="font-semibold text-gray-900">Mon Profil</h4>
                                <p class="text-sm text-gray-600 mt-1">Gérer mon compte</p>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Calendrier Simple */
        .simple-calendar {
            font-family: 'Inter', sans-serif;
        }
        
        .calendar-grid {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .calendar-weekday {
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            font-size: 0.875rem;
            color: #6b7280;
            border-right: 1px solid #e5e7eb;
        }
        
        .calendar-weekday:last-child {
            border-right: none;
        }
        
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }
        
        .calendar-day {
            min-height: 80px;
            padding: 8px;
            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            position: relative;
            background: white;
            transition: all 0.2s ease;
        }
        
        .calendar-day:nth-child(7n) {
            border-right: none;
        }
        
        .calendar-day:hover {
            background: #f8fafc;
        }
        
        .calendar-day.other-month {
            background: #f9fafb;
            color: #9ca3af;
        }
        
        .calendar-day.today {
            background: #eff6ff;
            border: 2px solid #3b82f6;
        }
        
        .calendar-day-number {
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 4px;
        }
        
        .calendar-events {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        
        .calendar-event {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .calendar-event:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .calendar-event.project {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .calendar-event.task-completed {
            background: #dcfce7;
            color: #166534;
        }
        
        .calendar-event.task-in-progress {
            background: #fed7aa;
            color: #9a3412;
        }
        
        .calendar-event.task-overdue {
            background: #fecaca;
            color: #991b1b;
        }
        
        .calendar-event.task-pending {
            background: #f3f4f6;
            color: #374151;
        }
        
        /* Projets et Tâches */
        .project-item, .task-item {
            transition: all 0.2s ease;
        }
        
        .project-item:hover, .task-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>


    <script>
        // Données du calendrier
        const calendarData = {
            projects: [
                @foreach($projects as $project)
                    @if($project->start_date)
                        {
                            id: {{ $project->id }},
                            name: '{{ addslashes($project->name) }}',
                            startDate: '{{ $project->start_date }}',
                            endDate: '{{ $project->end_date ?? null }}',
                            url: '{{ route('projects.show', $project->id) }}',
                            type: 'project'
                        },
                    @endif
                @endforeach
            ],
            tasks: [
                @foreach($tasks as $task)
                    @if($task->due_date)
                        {
                            id: {{ $task->id }},
                            title: '{{ addslashes($task->title) }}',
                            dueDate: '{{ $task->due_date }}',
                            status: '{{ $task->status }}',
                            url: '{{ route('tasks.show', $task->id) }}',
                            type: 'task'
                        },
                    @endif
                @endforeach
            ]
        };

        let currentDate = new Date();
        
        // Noms des mois en français
        const monthNames = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];

        // Générer le calendrier
        function generateCalendar(year, month) {
            const calendarDays = document.getElementById('calendar-days');
            const currentMonthElement = document.getElementById('current-month');
            
            // Mettre à jour le titre du mois
            currentMonthElement.textContent = `${monthNames[month]} ${year}`;
            
            // Obtenir le premier jour du mois et le nombre de jours
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            
            // Obtenir le jour de la semaine du premier jour (0 = dimanche, 1 = lundi, etc.)
            let startDay = firstDay.getDay();
            // Convertir pour commencer le lundi (1) au lieu du dimanche (0)
            startDay = startDay === 0 ? 6 : startDay - 1;
            
            // Obtenir le nombre de jours du mois précédent
            const prevMonth = new Date(year, month, 0);
            const daysInPrevMonth = prevMonth.getDate();
            
            calendarDays.innerHTML = '';
            
            // Générer les jours du mois précédent (si nécessaire)
            for (let i = startDay - 1; i >= 0; i--) {
                const dayElement = createDayElement(daysInPrevMonth - i, year, month - 1, true);
                calendarDays.appendChild(dayElement);
            }
            
            // Générer les jours du mois actuel
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = createDayElement(day, year, month, false);
                calendarDays.appendChild(dayElement);
            }
            
            // Générer les jours du mois suivant pour compléter la grille
            const totalCells = calendarDays.children.length;
            const remainingCells = 42 - totalCells; // 6 semaines × 7 jours = 42 cellules
            
            for (let day = 1; day <= remainingCells; day++) {
                const dayElement = createDayElement(day, year, month + 1, true);
                calendarDays.appendChild(dayElement);
            }
        }
        
        // Créer un élément de jour
        function createDayElement(day, year, month, isOtherMonth) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            
            if (isOtherMonth) {
                dayElement.classList.add('other-month');
            }
            
            // Vérifier si c'est aujourd'hui
            const today = new Date();
            const currentDay = new Date(year, month, day);
            if (currentDay.toDateString() === today.toDateString()) {
                dayElement.classList.add('today');
            }
            
            // Numéro du jour
            const dayNumber = document.createElement('div');
            dayNumber.className = 'calendar-day-number';
            dayNumber.textContent = day;
            dayElement.appendChild(dayNumber);
            
            // Événements du jour
            const eventsContainer = document.createElement('div');
            eventsContainer.className = 'calendar-events';
            
            const dayString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            
            // Ajouter les projets
            calendarData.projects.forEach(project => {
                if (project.startDate === dayString || (project.endDate && dayString >= project.startDate && dayString <= project.endDate)) {
                    const eventElement = createEventElement(project.name, 'project', project.url);
                    eventsContainer.appendChild(eventElement);
                }
            });
            
            // Ajouter les tâches
            calendarData.tasks.forEach(task => {
                if (task.dueDate === dayString) {
                    let statusClass = 'task-pending';
                    if (task.status === 'completed') statusClass = 'task-completed';
                    else if (task.status === 'in-progress') statusClass = 'task-in-progress';
                    else if (new Date(task.dueDate) < today) statusClass = 'task-overdue';
                    
                    const eventElement = createEventElement(task.title, statusClass, task.url);
                    eventsContainer.appendChild(eventElement);
                }
            });
            
            dayElement.appendChild(eventsContainer);
            return dayElement;
        }
        
        // Créer un élément d'événement
        function createEventElement(title, className, url) {
            const eventElement = document.createElement('div');
            eventElement.className = `calendar-event ${className}`;
            eventElement.textContent = title;
            eventElement.onclick = () => {
                if (url) {
                    window.location.href = url;
                }
            };
            return eventElement;
        }
        
        // Changer de mois
        function changeMonth(direction) {
            currentDate.setMonth(currentDate.getMonth() + direction);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        }
        
        // Aller à aujourd'hui
        function goToToday() {
            currentDate = new Date();
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        }
        
        // Initialiser le calendrier
        document.addEventListener('DOMContentLoaded', function() {
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        });
    </script>

    <style>
        /* Boutons Premium */
        .btn-premium-modern {
            @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-medium rounded-lg shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200;
        }
        
        .badge-premium {
            @apply inline-flex items-center px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-medium rounded-full shadow-sm;
        }
    </style>
</x-app-layout>