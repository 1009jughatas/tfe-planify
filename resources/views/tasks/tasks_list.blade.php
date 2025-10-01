<div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0">{{ __($title ? $title : 'Tasks') }}</h3>
        <!-- Add Task Button -->
        @if ($add)
            <a href="{{ route('tasks.create', $project->id) }}" class="btn btn-primary">Add Task</a>
        @endif
    </div>
    <div class="card-body">
        @if ($tasks->isEmpty())
            <!-- Message si aucune tâche -->
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Aucune tâche pour ce projet.</strong>
                @if ($add)
                    <br>
                    <a href="{{ route('tasks.create', $project->id) }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-2"></i>Créer la première tâche
                    </a>
                @endif
            </div>
        @else
            <!-- Jira-style Task Columns -->
            <div class="row">
                <!-- To Do Column -->
                <div class="col-md-3 styled-column with-border">
                    <h4 class="text-center" style="margin-bottom: 10px;"><b>À Faire</b></h4>
                    @php    $todoTasks = $tasks->whereIn('status', ['todo', 'pending']) @endphp
                    @if ($todoTasks->isEmpty())
                        <p class="text-center text-muted">Aucune tâche</p>
                    @endif
                    @foreach ($todoTasks as $task)
                        @include('tasks.task_card', ['task' => $task])
                    @endforeach
                </div>

                <!-- In Progress Column -->
                <div class="col-md-3 styled-column with-border">
                    <h4 class="text-center" style="margin-bottom: 10px;"><b>En Cours</b></h4>
                    @php    $inProgressTasks = $tasks->where('status', 'in-progress'); @endphp
                    @if ($inProgressTasks->isEmpty())
                        <p class="text-center text-muted">Aucune tâche</p>
                    @endif
                    @foreach ($inProgressTasks as $task)
                        @include('tasks.task_card', ['task' => $task])
                    @endforeach
                </div>

                <!-- Blocked Column -->
                <div class="col-md-3 styled-column with-border">
                    <h4 class="text-center" style="margin-bottom: 10px;"><b>Bloqué</b></h4>
                    @php    $blockedTasks = $tasks->where('status', 'blocked'); @endphp
                    @if ($blockedTasks->isEmpty())
                        <p class="text-center text-muted">Aucune tâche</p>
                    @endif
                    @foreach ($blockedTasks as $task)
                        @include('tasks.task_card', ['task' => $task])
                    @endforeach
                </div>

                <!-- Done Column -->
                <div class="col-md-3 styled-column">
                    <h4 class="text-center styled-column" style="margin-bottom: 10px;"><b>Terminé</b></h4>
                    @php    $doneTasks = $tasks->whereIn('status', ['done', 'completed']); @endphp
                    @if ($doneTasks->isEmpty())
                        <p class="text-center text-muted">Aucune tâche</p>
                    @endif
                    @foreach ($doneTasks as $task)
                        @include('tasks.task_card', ['task' => $task])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .styled-column {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .with-border {
        border-right: 1px solid #ddd;
    }

    /* Remove the right border from the last column */
    .column-with-border:last-child {
        border-right: none;
    }
</style>