<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Task: ') . $task->title }}
        </h2>
    </x-slot>

    <div class="container py-12">
        <div class="card">
            <div class="card-header">
                {{ __('Edit Task') }}
            </div>
            <div class="card-body">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="title" class="form-label">Task Title</label>
                        <input type="text" name="title" class="form-control" id="title" value="{{ $task->title }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Task Description</label>
                        <textarea name="description" class="form-control"
                            id="description">{{ $task->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control" id="due_date"
                            value="{{ $task->due_date }}">
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <input type="number" name="priority" class="form-control" id="priority"
                            value="{{ $task->priority }}">
                    </div>
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assign to</label>
                        <select name="assigned_to" id="assigned_to" class="form-select">
                            @foreach($participants as $user)
                                <option value="{{ $user->id }}" @if($task->assigned_to == $user->id) selected @endif>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Update Task</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>