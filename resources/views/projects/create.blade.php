<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Project') }}
        </h2>
    </x-slot>

    <div class="container py-12">
        <div class="card">
            <div class="card-header">
                {{ __('Create New Project') }}
            </div>
            <div class="card-body">
                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Project Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Project Description</label>
                        <textarea name="description" class="form-control"
                            id="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="participants" class="form-label">Participants</label>
                        <select name="participants[]" id="participants" class="form-select" multiple>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" id="start_date"
                            value="{{ old('start_date') }}">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" id="end_date"
                            value="{{ old('end_date') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Create Project</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>