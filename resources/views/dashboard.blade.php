<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container py-6 lg:py-12 px-4">
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title mb-2">Projets actifs</h5>
                        <p class="card-text mb-0 fs-4 fw-bold">{{ $activeProjectsCount }}</p>
                        <small class="opacity-75">Projets en cours</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title mb-2">Tâches ouvertes</h5>
                        <p class="card-text mb-0 fs-4 fw-bold">{{ $openTasksCount }}</p>
                        <small class="opacity-75">Tâches à faire</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <div class="card text-white bg-success h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title mb-2">Projets terminés</h5>
                        <p class="card-text mb-0 fs-4 fw-bold">{{ $completedProjectsCount }}</p>
                        <small class="opacity-75">Projets finis</small>
                    </div>
                </div>
            </div>

            @if (Auth::user()->is_premium)
                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="card text-white bg-secondary h-100">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h5 class="card-title mb-2">Rapport</h5>
                            <a href="{{ route('dashboard.exportReport') }}" class="btn btn-light btn-sm mt-auto">Export PDF</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Calendrier des Projets et Tâches</h5>
                        <div>
                            <span class="badge bg-primary me-1">📁 Projets</span>
                            <span class="badge bg-success me-1">✓ Tâches Terminées</span>
                            <span class="badge bg-warning me-1">✓ Tâches En Cours</span>
                            <span class="badge bg-danger">✓ Tâches En Attente</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="taskCalendar" style="min-height: 600px;"></div>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Astuce :</strong> Cliquez sur un projet (📁) pour voir ses tâches, ou sur une tâche (✓) pour voir ses détails.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .fc-event {
                cursor: pointer;
                border-radius: 4px;
                padding: 2px 4px;
            }
            .fc-event:hover {
                opacity: 0.8;
                transform: scale(1.02);
                transition: all 0.2s ease;
            }
            .project-event {
                font-weight: bold;
            }
            .task-event {
                font-size: 0.9em;
            }
        </style>

        <div class="row">

            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Status des projets</h5>
                        <canvas id="projectStatusChart" style="max-height: 200px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('projectStatusChart').getContext('2d');
        const projectStatusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Actif', 'Completé'],
                datasets: [{
                    data: [{{ $activeProjectsCount }}, {{ $completedProjectsCount }}],
                    backgroundColor: ['#007bff', '#28a745'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
                                color: '{{ $task->status === "completed" || $task->status === "done" ? "#28a745" : ($task->status === "in-progress" ? "#ffc107" : "#dc3545") }}',
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
                }
            });
            calendar.render();
        });
    </script>
</x-app-layout>