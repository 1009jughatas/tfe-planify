<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Details') }}
        </h2>
    </x-slot>

    <div class="container py-12">
        <div class="card">
            <div class="card-header" style="display: flex; flex-direction: row; gap: 10px; align-items: center">
                <b>{{ $task->project->name }}</b>
                <span class="badge 
                {{ 
                    $task->status == 'done' ? 'bg-success' :
    ($task->status == 'in-progress' ? 'bg-warning' :
        ($task->status == 'blocked' ? 'bg-danger' :
            'bg-secondary')) 
                }}">
                    {{ ucfirst($task->status) ?? 'No Status' }}
                </span>

                <div style="margin-left: auto;">
                    <label for="status" class="form-label">Update Status:</label>
                    <select name="status" id="status" class="form-select" data-task-id="{{ $task->id }}">
                        <option value="todo" @if($task->status == 'todo') selected @endif>To Do</option>
                        <option value="in-progress" @if($task->status == 'in-progress') selected @endif>In Progress
                        </option>
                        <option value="done" @if($task->status == 'done') selected @endif>Done</option>
                        <option value="blocked" @if($task->status == 'blocked') selected @endif>Blocked</option>
                    </select>
                </div>
            </div>

            <div class="card-body">
                <p class="card-text">{{ $task->description }}</p>
            </div>

            <div class="card-body">
                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning">Edit Task</a>
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Task</button>
                </form>

                <a href="{{ route('tasks.create', ['project' => $task->project->id, 'parent_id' => $task->id]) }}"
                    class="btn btn-primary">Add Subtask</a>
            </div>
        </div>

        <div style="margin-top: 10px;">
            @include('tasks.tasks_list', ['tasks' => $task->subtasks, 'title' => 'Subtasks', 'add' => false])
        </div>

        @if (!$task->comments->isEmpty())
            <div class="card mt-4">
                <div class="card-header">
                    <h3>{{ __('Comments') }}</h3>
                </div>
                <div class="card-body">
                    @foreach ($task->comments as $comment)
                        <div class="d-flex mb-3" style="display: flex; flex-direction: row; align-items: center;">

                            <div class="me-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px; font-size: 1.2em;">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <div class="bg-light p-3 rounded">
                                    <strong>{{ $comment->user->name }}</strong> said:
                                    <p class="mb-1">{{ $comment->content }}</p>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="card mt-4">
            <div class="card-header">
                <h3>{{ __('Add a Comment') }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('comments.store', $task->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Comment</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#status').change(function () {
                let taskId = $(this).data('task-id');
                let newStatus = $(this).val();

                $.ajax({
                    url: `/tasks/${taskId}/update-status`,
                    method: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: newStatus
                    },
                    success: function (response) {
                        alert('Task status updated successfully!');
                        location.reload();
                    },
                    error: function (error) {
                        alert('Failed to update task status.');
                    }
                });
            });
        });
    </script>
</x-app-layout>