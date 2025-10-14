<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>

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
                        <i class="fas fa-download mr-2"></i>
                        Exporter Dashboard
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
                            <i class="fas fa-download mr-2"></i>
                            Exporter Dashboard
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
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-project-diagram text-white text-lg"></i>
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Calendar Section -->
            <div class="lg:col-span-2">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                                Calendrier des Projets et Tâches
                            </h3>
                            <div class="flex space-x-2">
                                <span class="badge-primary">📁 Projets</span>
                                <span class="badge-success">✓ Terminées</span>
                                <span class="badge-warning">✓ En cours</span>
                                <span class="badge-danger">✓ En attente</span>
                            </div>
                        </div>
                    </div>
                    <div class="modern-card-body">
                        <div id="taskCalendar" class="min-h-96"></div>
                        <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-lightbulb text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-blue-900 mb-1">💡 Astuce</p>
                                    <p class="text-sm text-blue-700">
                                        Cliquez sur un projet (📁) pour voir ses tâches, ou sur une tâche (✓) pour voir ses détails.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                                <i class="fas fa-chart-pie text-white"></i>
                            </div>
                            Statut des Projets
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="h-64 flex items-center justify-center">
                            <canvas id="projectStatusChart"></canvas>
                        </div>
                        <div class="mt-6 space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Projets actifs</span>
                                </div>
                                <span class="font-bold text-gray-900 text-lg">{{ $activeProjectsCount }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-gradient-to-r from-green-500 to-green-600 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Projets terminés</span>
                                </div>
                                <span class="font-bold text-gray-900 text-lg">{{ $completedProjectsCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
                    
                    <a href="{{ route('profile.edit') }}" class="group p-6 bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl border border-orange-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-cog text-white"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Paramètres</h4>
                            <p class="text-sm text-gray-600 mt-1">Configurer l'interface</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fc-event {
            cursor: pointer;
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 0.875rem;
            border: none !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        .fc-event:hover {
            opacity: 0.9;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .project-event {
            font-weight: 600;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
        }
        .task-event {
            font-size: 0.8rem;
        }
        .fc-button {
            border-radius: 12px !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }
        .fc-button-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
            border: none !important;
        }
        .fc-button-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }
        .fc-today-button {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0) !important;
            border: 1px solid #cbd5e1 !important;
            color: #475569 !important;
        }
    </style>

    <script>
        // Modern Chart Configuration
        const ctx = document.getElementById('projectStatusChart').getContext('2d');
        const projectStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Projets actifs', 'Projets terminés'],
                datasets: [{
                    data: [{{ $activeProjectsCount }}, {{ $completedProjectsCount }}],
                    backgroundColor: [
                        'linear-gradient(135deg, #3b82f6, #1d4ed8)',
                        'linear-gradient(135deg, #10b981, #059669)'
                    ],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '65%',
                animation: {
                    animateRotate: true,
                    duration: 2000
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('taskCalendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listWeek'
                },
                locale: 'fr',
                buttonText: {
                    today: 'Aujourd\'hui',
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour',
                    list: 'Liste'
                },
                events: [
                    // Afficher les projets (date de début et fin)
                    @foreach($projects as $project)
                        @if($project->start_date)
                            {
                                title: '📁 {{ addslashes($project->name) }}',
                                start: '{{ $project->start_date }}',
                                @if($project->end_date)
                                end: '{{ $project->end_date }}',
                                @endif
                                color: '#3b82f6',
                                url: '{{ route('projects.tasks', $project->id) }}',
                                classNames: ['project-event']
                            },
                        @endif
                    @endforeach
                    
                    // Afficher les tâches
                    @foreach($tasks as $task)
                        @if($task->due_date)
                            {
                                title: '✓ {{ addslashes($task->title) }}',
                                start: '{{ $task->due_date }}',
                                color: '{{ $task->status === "completed" || $task->status === "done" ? "#10b981" : ($task->status === "in-progress" ? "#f59e0b" : "#ef4444") }}',
                                url: '{{ route('tasks.show', $task->id) }}',
                                classNames: ['task-event']
                            },
                        @endif
                    @endforeach
                ],
                eventClick: function(info) {
                    // Rediriger vers le projet ou la tâche
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                },
                dayMaxEvents: 3,
                moreLinkClick: 'popover'
            });
            calendar.render();
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