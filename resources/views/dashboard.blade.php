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
                    <div class="card-body">
                        <h5 class="card-title">Calendrier</h5>
                        <div id="taskCalendar" style="height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>

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
                events: [
                    @foreach($tasks as $task)
                                            {
                            title: '{{ $task->title }}',
                            start: '{{ $task->due_date }}',
                            color: '{{ $task->status === 'completed' ? '#28a745' : '#ffc107' }}'
                        },
                    @endforeach
                ]
            });
            calendar.render();
        });
    </script>
</x-app-layout>