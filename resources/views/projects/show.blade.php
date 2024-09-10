<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Project Details') }}
        </h2>
    </x-slot>

    <div class="container py-12">
        <div class="card">
            <div class="card-header">
                {{ $project->name }}
            </div>
            <div class="card-body">
                <h5 class="card-title">Project Description</h5>
                <p class="card-text">{{ $project->description }}</p>
                <p><strong>Start Date:</strong> {{ $project->start_date }}</p>
                <p><strong>End Date:</strong> {{ $project->end_date }}</p>
                <p><strong>Participants:</strong>
                    @foreach($project->participants as $participant)
                        <span class="badge bg-secondary">{{ $participant->name }}</span>
                    @endforeach
                </p>
                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning">Edit Project</a>
                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Project</button>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <h3>Tasks</h3>
            <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-primary mb-3">Add
                Task</a>
            @if ($project->tasks->isEmpty())
                <p>No tasks found for this project.</p>
            @else
                <ul class="list-group">
                    @foreach ($project->tasks as $task)
                        <li class="list-group-item">
                            <a href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>