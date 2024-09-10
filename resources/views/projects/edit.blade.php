<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Project') }}
        </h2>
    </x-slot>

    <div class="container py-12">
        <div class="card">
            <div class="card-header">
                {{ __('Edit Project') }}
            </div>
            <div class="card-body">
                <form action="{{ route('projects.update', $project->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="name" class="form-label">Project Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ $project->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Project Description</label>
                        <textarea name="description" class="form-control" id="description">{{ $project->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="participants" class="form-label">Participants</label>
                        <select name="participants[]" id="participants" class="form-select" multiple>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if(in_array($user->id, json_decode($project->participants, true) ?? [])) selected @endif>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" id="start_date" value="{{ $project->start_date ? $project->start_date->format('Y-m-d') : '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" id="end_date" value="{{ $project->end_date ? $project->end_date->format('Y-m-d') : '' }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Project</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>