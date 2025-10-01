<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-users-cog me-2"></i>{{ __('Gestion des Utilisateurs') }}
        </h2>
    </x-slot>

    <div class="container py-6 lg:py-12 px-4">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12 col-md-8">
                <h1 class="h2 mb-3 mb-md-0">Gestion des Utilisateurs</h1>
                <p class="text-muted">
                    Total : {{ $totalUsers }} utilisateurs 
                    ({{ $premiumUsers }} premium, {{ $adminUsers }} admin)
                </p>
            </div>
            <div class="col-12 col-md-4 text-md-end">
                <a href="{{ route('admin.users.create') }}" class="btn btn-success w-100 w-md-auto">
                    <i class="fas fa-user-plus me-2"></i>Créer un Utilisateur
                </a>
            </div>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Liste des utilisateurs -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des Utilisateurs</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Projets</th>
                                <th>Inscrit le</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td><small class="text-muted">#{{ $user->id }}</small></td>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        @if ($user->id === Auth::id())
                                            <span class="badge bg-info ms-1">Vous</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $user->email }}</small></td>
                                    <td>
                                        @if ($user->is_admin())
                                            <span class="badge bg-danger"><i class="fas fa-shield-alt me-1"></i>Admin</span>
                                        @else
                                            <span class="badge bg-secondary">User</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->is_premium)
                                            <span class="badge bg-warning text-dark"><i class="fas fa-crown me-1"></i>Premium</span>
                                        @else
                                            <span class="badge bg-light text-dark">Gratuit</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $user->projects()->count() }}</span>
                                    </td>
                                    <td><small>{{ $user->created_at->format('d/m/Y') }}</small></td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="btn btn-outline-warning" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('admin.users.toggle-premium', $user->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-outline-{{ $user->is_premium ? 'secondary' : 'warning' }}"
                                                        title="{{ $user->is_premium ? 'Retirer Premium' : 'Activer Premium' }}">
                                                    <i class="fas fa-crown"></i>
                                                </button>
                                            </form>
                                            
                                            @if ($user->id !== Auth::id())
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        Aucun utilisateur trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

