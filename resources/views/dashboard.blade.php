<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container py-12">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Projets actifs</h5>
                        <p class="card-text">{{ $activeProjectsCount }} Projets actifs</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">
                            Projets terminés</h5>
                        <p class="card-text">{{ $completedProjectsCount }}
                            Projets terminés</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">
                            Tâches ouvertes</h5>
                        <p class="card-text">{{ $openTasksCount }}
                            Tâches ouvertes</p>
                    </div>
                </div>
            </div>

            @if (Auth::user()->is_premium)
                <div class="col-md-3 mb-4">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h5 class="card-title">Rapport</h5>
                            <a href="{{ route('dashboard.exportReport') }}">Export PDF</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="row">

            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Task Calendar</h5>
                        <div id="taskCalendar" style="height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Project Status</h5>
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
                labels: ['Active', 'Completed'],
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