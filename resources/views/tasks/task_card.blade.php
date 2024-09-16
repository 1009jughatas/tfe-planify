<div class="card mb-3" style="width:225px; height:300px;">
    <div class="card-body d-flex flex-column justify-content-between">
        <h5 class="card-title" style="text-align: center"><b>{{ $task->title }}</b></h5>
        <div>
            <p class="card-text">
                <strong>Status:</strong>
                <span class="badge 
                {{ 
                    $task->status == 'done' ? 'bg-success' :
    ($task->status == 'in-progress' ? 'bg-warning' :
        ($task->status == 'blocked' ? 'bg-danger' :
            'bg-secondary')) 
                }}">
                    {{ ucfirst($task->status) ?? 'No Status' }}
                </span>
            </p>
            <p class="card-text">
                <strong>Assigned To:</strong>
                {{ $task->assignedUser ? $task->assignedUser->name : 'Unassigned' }}
            </p>
            <p class="card-text">
                <strong>Priority:</strong>
                {{ $task->priority ?? 'No Priority' }}
            </p>
            <p class="card-text">
                <strong>Description:</strong>
                {{ \Illuminate\Support\Str::limit($task->description, 50, $end = '...') }}
            </p>
        </div>
        <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-primary mt-3">View Task</a>
    </div>
</div>