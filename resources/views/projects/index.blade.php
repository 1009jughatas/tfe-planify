<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tous les projets') }}
        </h2>
    </x-slot>

    <div class="container py-6 lg:py-12 px-4">
        <!-- Alertes de limitation -->
        @if (!Auth::user()->is_premium && !Auth::user()->is_admin())
            @php
                $projectCount = Auth::user()->projects()->count();
                $projectLimit = 3;
            @endphp
            @if ($projectCount >= $projectLimit)
                <div class="alert alert-warning mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Vous avez atteint la limite de <strong>{{ $projectLimit }} projets</strong> pour les utilisateurs gratuits.
                    <a href="{{ route('premium.show') }}" class="alert-link">Passez à Premium</a> pour créer des projets illimités.
                </div>
            @else
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    Vous avez <strong>{{ $projectCount }}/{{ $projectLimit }} projets</strong>.
                    <a href="{{ route('premium.show') }}" class="alert-link">Passez à Premium</a> pour des projets illimités et plus de fonctionnalités.
                </div>
            @endif
        @endif

        <div class="row mb-4">
            <div class="col-12 col-md-8">
                <h1 class="h2 mb-3 mb-md-0">
                    Tous les projets
                    @if (Auth::user()->is_premium || Auth::user()->is_admin())
                        <span class="badge bg-warning text-dark"><i class="fas fa-crown me-1"></i>Premium</span>
                    @endif
                </h1>
            </div>
            <div class="col-12 col-md-4 text-md-end">
                @php
                    $canCreate = Auth::user()->is_admin() || 
                                 Auth::user()->is_premium || 
                                 Auth::user()->projects()->count() < 3;
                @endphp
                @if ($canCreate)
                    <a href="{{ route('projects.create') }}" class="btn btn-primary w-100 w-md-auto">
                        <i class="fas fa-plus me-2"></i>Créer un nouveau projet
                    </a>
                @else
                    <button class="btn btn-secondary w-100 w-md-auto" disabled title="Limite atteinte">
                        <i class="fas fa-lock me-2"></i>Limite atteinte ({{ Auth::user()->projects()->count() }}/3)
                    </button>
                @endif
            </div>
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
                                    <!-- Badges -->
                                    <div class="mb-2">
                                        @if ($project->author_id === Auth::id())
                                            <span class="badge bg-primary"><i class="fas fa-user me-1"></i>Votre projet</span>
                                        @endif
                                        @if (Auth::user()->is_admin())
                                            <span class="badge bg-danger"><i class="fas fa-shield-alt me-1"></i>Admin</span>
                                        @endif
                                        @if (Auth::user()->is_premium || Auth::user()->is_admin())
                                            <span class="badge bg-warning text-dark"><i class="fas fa-crown me-1"></i>Premium</span>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="{{ route('projects.tasks', $project->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-tasks me-1"></i>Tâches
                                        </a>
                                        @if (Auth::user()->is_admin() || $project->author_id === Auth::id())
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