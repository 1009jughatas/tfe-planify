<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tous les projets') }}
        </h2>
    </x-slot>

    <div class="container py-12">
        <div class="row mb-4">
            <div class="col">
                <h1 class="display-4">Tous les projets</h1>
            </div>
            @if (Auth::user()->is_admin())
                <div class="col text-end">
                    <a href="{{ route('projects.create') }}" class="btn btn-primary">
                        Créer un nouveau projet</a>
                </div>
            @endif
        </div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($projects->isEmpty())
            <div class="alert alert-warning" role="alert">
                Aucun projet trouvé.
            </div>
        @else
            <div class="card">
                <div class="card-header">
                    Liste des projets
                </div>
                <ul class="list-group list-group-flush">
                    @foreach ($projects as $project)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">
                                        <a href="{{ route('projects.show', $project->id) }}" class="text-decoration-none">
                                            {{ $project->name }}
                                        </a>
                                    </h5>
                                    <p class="mb-1">{{ $project->description }}</p>
                                    <small>
                                        <strong>Date de début:</strong> {{ $project->start_date ?? 'N/A' }} |
                                        <strong>Date de fin:</strong> {{ $project->end_date ?? 'N/A' }}
                                    </small>
                                    <br>
                                    <small>
                                        <strong>Participants:</strong>
                                        {{ $project->participants->count() }}
                                    </small>
                                </div>
                                <div class="btn-group" role="group" aria-label="Project Actions">
                                    <a href="{{ route('projects.tasks', $project->id) }}" class="btn btn-sm btn-success">Voir
                                        les tâches</a>
                                    @if (Auth::user()->is_admin())
                                        <a href="{{ route('projects.edit', $project->id) }}"
                                            class="btn btn-sm btn-warning">Modifier</a>
                                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-app-layout>