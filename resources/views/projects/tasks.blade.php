<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Project Details: ') . $project->name }}
        </h2>
    </x-slot>

    <div class="container py-12">
        <!-- Project Information Section -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Description</b></h5>
                        <p class="card-text">{{ $project->description ?? 'No description available' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Participants</b></h5>
                        <p class="card-text">
                            @if ($project->participants->isNotEmpty())
                                {{ $project->participants->pluck('name')->implode(', ') }}
                            @else
                                No participants assigned
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Auteur</b></h5>
                        <p class="card-text">{{ $project->author->name }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Date de debut</b></h5>
                        <p class="card-text">{{ $project->start_date ? $project->start_date->format('F j, Y') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Date de fin</b></h5>
                        <p class="card-text">{{ $project->end_date ? $project->end_date->format('F j, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @include('tasks.tasks_list', ['tasks' => $tasks, 'title' => 'Tasks', 'add' => true])

    </div>
</x-app-layout>