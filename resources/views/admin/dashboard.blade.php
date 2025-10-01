<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <i class="fas fa-shield-alt text-red-600 me-2"></i>
            {{ __('Panneau d\'Administration') }}
        </h2>
    </x-slot>

    <div class="container py-6 lg:py-12 px-4">
        <!-- Titre et badge -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2 mb-3">
                    Dashboard Administrateur
                    <span class="badge bg-danger"><i class="fas fa-crown me-1"></i>Admin</span>
                </h1>
                <p class="text-muted">Vue d'ensemble complète de la plateforme Planify</p>
            </div>
        </div>

        <!-- Statistiques principales -->
        <div class="row g-3 mb-4">
            <!-- Utilisateurs -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="card-subtitle text-muted mb-0">Total Utilisateurs</h6>
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                        <h2 class="card-title mb-1">{{ $stats['total_users'] }}</h2>
                        <div class="small text-muted">
                            <span class="badge bg-warning">{{ $stats['premium_users'] }} Premium</span>
                            <span class="badge bg-secondary">{{ $stats['free_users'] }} Gratuit</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projets -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="card-subtitle text-muted mb-0">Total Projets</h6>
                            <i class="fas fa-project-diagram fa-2x text-success"></i>
                        </div>
                        <h2 class="card-title mb-1">{{ $stats['total_projects'] }}</h2>
                        <div class="small text-muted">
                            <span class="badge bg-info">{{ $stats['active_projects'] }} Actifs</span>
                            <span class="badge bg-success">{{ $stats['completed_projects'] }} Terminés</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tâches -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="card-subtitle text-muted mb-0">Total Tâches</h6>
                            <i class="fas fa-tasks fa-2x text-warning"></i>
                        </div>
                        <h2 class="card-title mb-1">{{ $stats['total_tasks'] }}</h2>
                        <div class="small text-muted">
                            <span class="badge bg-secondary">{{ $stats['pending_tasks'] }} En attente</span>
                            <span class="badge bg-success">{{ $stats['completed_tasks'] }} Terminées</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Taux conversion -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-info h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="card-subtitle text-muted mb-0">Conversion Premium</h6>
                            <i class="fas fa-chart-line fa-2x text-info"></i>
                        </div>
                        <h2 class="card-title mb-1">{{ $conversionRate }}%</h2>
                        <div class="small text-muted">
                            <span class="badge bg-warning">{{ $stats['premium_users'] }}/{{ $stats['total_users'] }} Premium</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions Rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2 d-md-flex">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                                <i class="fas fa-users me-2"></i>Gérer les Utilisateurs
                            </a>
                            <a href="{{ route('admin.statistics') }}" class="btn btn-success">
                                <i class="fas fa-chart-bar me-2"></i>Statistiques Globales
                            </a>
                            <a href="{{ route('admin.logs') }}" class="btn btn-warning">
                                <i class="fas fa-file-alt me-2"></i>Consulter les Logs
                            </a>
                            <a href="{{ route('admin.legal.index') }}" class="btn btn-info">
                                <i class="fas fa-gavel me-2"></i>Contenus Légaux
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Utilisateurs récents -->
        <div class="row mb-4">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-clock me-2"></i>Utilisateurs Récents</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Statut</th>
                                        <th>Inscrit le</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td><small>{{ $user->email }}</small></td>
                                            <td>
                                                @if ($user->is_admin())
                                                    <span class="badge bg-danger">Admin</span>
                                                @elseif ($user->is_premium)
                                                    <span class="badge bg-warning text-dark">Premium</span>
                                                @else
                                                    <span class="badge bg-secondary">Gratuit</span>
                                                @endif
                                            </td>
                                            <td><small>{{ $user->created_at->format('d/m/Y') }}</small></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Aucun utilisateur</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                            Voir tous les utilisateurs <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Projets récents -->
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-folder-open me-2"></i>Projets Récents</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom du projet</th>
                                        <th>Auteur</th>
                                        <th>Statut</th>
                                        <th>Créé le</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentProjects as $project)
                                        <tr>
                                            <td>
                                                <a href="{{ route('projects.show', $project->id) }}" class="text-decoration-none">
                                                    {{ Str::limit($project->name, 30) }}
                                                </a>
                                            </td>
                                            <td><small>{{ $project->author->name ?? 'N/A' }}</small></td>
                                            <td>
                                                @if ($project->status === 'active')
                                                    <span class="badge bg-info">Actif</span>
                                                @elseif ($project->status === 'completed')
                                                    <span class="badge bg-success">Terminé</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $project->status }}</span>
                                                @endif
                                            </td>
                                            <td><small>{{ $project->created_at->format('d/m/Y') }}</small></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Aucun projet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-success">
                            Voir tous les projets <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

