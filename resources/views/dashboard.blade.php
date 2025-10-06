<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-600 mt-1">Vue d'ensemble de vos projets et tâches</p>
            </div>
            @if (Auth::user()->is_premium)
                <a href="{{ route('dashboard.exportReport') }}" class="btn-primary-modern">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Exporter PDF
                </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Active Projects -->
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Projets actifs</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $activeProjectsCount }}</p>
                        <p class="text-xs text-gray-500 mt-1">En cours</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-primary rounded-xl flex items-center justify-center">
                        <i class="fas fa-project-diagram text-white text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Open Tasks -->
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Tâches ouvertes</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $openTasksCount }}</p>
                        <p class="text-xs text-gray-500 mt-1">À faire</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-tasks text-white text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Completed Projects -->
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Projets terminés</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $completedProjectsCount }}</p>
                        <p class="text-xs text-gray-500 mt-1">Finalisés</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Premium Feature -->
            @if (Auth::user()->is_premium)
                <div class="stats-card hover-lift bg-gradient-accent text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/90 mb-1">Rapport Premium</p>
                            <p class="text-lg font-semibold text-white">Export disponible</p>
                            <a href="{{ route('dashboard.exportReport') }}" class="inline-flex items-center text-white/90 hover:text-white text-xs mt-2">
                                <i class="fas fa-download mr-1"></i>
                                Télécharger
                            </a>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-file-pdf text-white text-lg"></i>
                        </div>
                    </div>
                </div>
            @else
                <div class="stats-card hover-lift border-2 border-dashed border-gray-300">
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-crown text-accent-500 text-lg"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Devenez Premium</p>
                            <p class="text-xs text-gray-500">Accédez aux rapports</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Calendar Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2">
                <div class="modern-card">
                    <div class="modern-card-header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-calendar-alt text-primary-600 mr-2"></i>
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
                        <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-blue-900 mb-1">Astuce</p>
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
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-chart-pie text-primary-600 mr-2"></i>
                            Statut des projets
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="h-64 flex items-center justify-center">
                            <canvas id="projectStatusChart"></canvas>
                        </div>
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-primary-500 rounded-full mr-2"></div>
                                    <span class="text-gray-600">Projets actifs</span>
                                </div>
                                <span class="font-medium text-gray-900">{{ $activeProjectsCount }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-gray-600">Projets terminés</span>
                                </div>
                                <span class="font-medium text-gray-900">{{ $completedProjectsCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fc-event {
            cursor: pointer;
            border-radius: 6px;
            padding: 2px 6px;
            font-size: 0.875rem;
            border: none !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .fc-event:hover {
            opacity: 0.9;
            transform: scale(1.02);
            transition: all 0.2s ease;
        }
        .project-event {
            font-weight: 600;
        }
        .task-event {
            font-size: 0.8rem;
        }
        .fc-button {
            border-radius: 8px !important;
            font-weight: 500 !important;
        }
        .fc-button-primary {
            background-color: #0ea5e9 !important;
            border-color: #0ea5e9 !important;
        }
        .fc-button-primary:hover {
            background-color: #0284c7 !important;
            border-color: #0284c7 !important;
        }
        .fc-today-button {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
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
                    backgroundColor: ['#0ea5e9', '#10b981'],
                    borderWidth: 0,
                    hoverOffset: 4
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
                cutout: '60%'
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
                                color: '#0ea5e9',
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
</x-app-layout>