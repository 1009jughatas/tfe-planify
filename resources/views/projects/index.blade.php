<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tous les projets') }}
        </h2>
    </x-slot>

    <div class="container py-6 lg:py-12 px-4">
        <div class="row mb-4">
            <div class="col-12 col-md-8">
                <h1 class="h2 mb-3 mb-md-0">Tous les projets</h1>
            </div>
            @if (Auth::user()->is_admin())
                <div class="col-12 col-md-4 text-md-end">
                    <a href="{{ route('projects.create') }}" class="btn btn-primary w-100 w-md-auto">
                        <i class="fas fa-plus me-2"></i>Créer un nouveau projet
                    </a>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Liste des projets</h5>
                    <span class="badge bg-primary">{{ $projects->count() }} projet(s)</span>
                </div>
                <div class="card-body p-0">
                    @foreach ($projects as $project)
                        <div class="border-bottom p-3">
                            <div class="row">
                                <div class="col-12 col-lg-8">
                                    <h5 class="mb-2">
                                        <a href="{{ route('projects.show', $project->id) }}" class="text-decoration-none text-dark">
                                            {{ $project->name }}
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-2">{{ Str::limit($project->description, 100) }}</p>
                                    <div class="row text-sm text-muted">
                                        <div class="col-6 col-md-3">
                                            <strong>Début:</strong><br>
                                            <small>{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') : 'N/A' }}</small>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <strong>Fin:</strong><br>
                                            <small>{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') : 'N/A' }}</small>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <strong>Participants:</strong>
                                            <span class="badge bg-info">{{ $project->participants->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 mt-3 mt-lg-0">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="{{ route('projects.tasks', $project->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-tasks me-1"></i>Tâches
                                        </a>
                                        @if (Auth::user()->is_admin())
                                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit me-1"></i>Modifier
                                            </a>
                                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                                    <i class="fas fa-trash me-1"></i>Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>