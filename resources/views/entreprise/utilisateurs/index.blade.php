@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestion de l'Équipe</h1>
                    <p class="text-gray-600 mt-1 flex items-center">
                        <i class="fas fa-building mr-2 text-blue-500"></i>
                        {{ $company->name }}
                    </p>
                </div>
            </div>
            <a href="{{ route('entreprise.utilisateurs.inviter') }}" class="btn-primary-modern">
                <i class="fas fa-user-plus mr-2"></i>
                Inviter un employé
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stats-card hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Utilisateurs Actifs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="stats-card hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Limite d'Utilisateurs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $maxUsers }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-user-lock text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="stats-card hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Places Restantes</p>
                    <p class="text-3xl font-bold {{ ($maxUsers - $totalUsers) > 0 ? 'text-green-600' : 'text-red-600' }}">{{ max(0, $maxUsers - $totalUsers) }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-plus-circle text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Invitations en attente -->
    @if($pendingInvitations->count() > 0)
        <div class="modern-card mb-8">
            <div class="modern-card-header">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-clock text-yellow-500 mr-2"></i>
                    Invitations en Attente ({{ $pendingInvitations->count() }})
                </h3>
            </div>
            <div class="modern-card-body">
                <div class="space-y-4">
                    @foreach($pendingInvitations as $invitation)
                        <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $invitation->email }}</h4>
                                    <p class="text-sm text-gray-600">
                                        Invité par {{ $invitation->invitedBy->name }} • 
                                        {{ \Carbon\Carbon::parse($invitation->created_at)->format('d/m/Y à H:i') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Rôle: {{ $invitation->role === 'admin_entreprise' ? 'Administrateur' : 'Employé' }} • 
                                        Expire: {{ \Carbon\Carbon::parse($invitation->expires_at)->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    En attente
                                </span>
                                <form method="POST" action="{{ route('entreprise.invitations.cancel', $invitation->id) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette invitation ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Liste des utilisateurs -->
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Membres de l'Équipe</h3>
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Rechercher un utilisateur..." class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les rôles</option>
                        <option value="admin_entreprise">Administrateurs</option>
                        <option value="user_entreprise">Employés</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modern-card-body">
            @if($users->count() > 0)
                <div class="space-y-4">
                    @foreach($users as $user)
                        <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-all">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-medium">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h4 class="font-medium text-gray-900">{{ $user->name }}</h4>
                                        @if($user->id === auth()->id())
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                Vous
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                    <div class="flex items-center space-x-4 mt-1">
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Rejoint le {{ $user->created_at->format('d/m/Y') }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-project-diagram mr-1"></i>
                                            {{ $user->projects->count() }} projet(s)
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <!-- Rôle -->
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($user->role === 'admin_entreprise') bg-purple-100 text-purple-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ $user->role === 'admin_entreprise' ? 'Administrateur' : 'Employé' }}
                                    </span>
                                    
                                    <!-- Changement de rôle (seulement pour les autres utilisateurs) -->
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('entreprise.utilisateurs.update-role', $user->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" onchange="this.form.submit()" class="text-xs border border-gray-300 rounded px-2 py-1">
                                                <option value="user_entreprise" {{ $user->role === 'user_entreprise' ? 'selected' : '' }}>Employé</option>
                                                <option value="admin_entreprise" {{ $user->role === 'admin_entreprise' ? 'selected' : '' }}>Administrateur</option>
                                            </select>
                                        </form>
                                    @endif
                                </div>

                                <!-- Actions -->
                                @if($user->id !== auth()->id())
                                    <div class="flex items-center space-x-2">
                                        <!-- Bouton Permissions -->
                                        <a href="{{ route('entreprise.utilisateurs.permissions', $user->id) }}" 
                                           class="text-purple-600 hover:text-purple-800 p-2 hover:bg-purple-50 rounded-lg transition-colors"
                                           title="Gérer les permissions">
                                            <i class="fas fa-shield-alt"></i>
                                        </a>
                                        
                                        <!-- Bouton Supprimer -->
                                        <form method="POST" action="{{ route('entreprise.utilisateurs.destroy', $user->id) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Supprimer l'utilisateur">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun utilisateur trouvé</h3>
                    <p class="text-gray-600 mb-6">Commencez par inviter des employés à rejoindre votre entreprise.</p>
                    <a href="{{ route('entreprise.utilisateurs.inviter') }}" class="btn-primary-modern">
                        <i class="fas fa-user-plus mr-2"></i>
                        Inviter un employé
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .stats-card {
        @apply bg-white rounded-xl shadow-sm border border-gray-200 p-6 transition-all duration-200;
    }
    
    .hover-lift:hover {
        @apply shadow-lg transform -translate-y-1;
    }
    
    .modern-card {
        @apply bg-white rounded-xl shadow-sm border border-gray-200;
    }
    
    .modern-card-header {
        @apply px-6 py-4 border-b border-gray-200;
    }
    
    .modern-card-body {
        @apply p-6;
    }
    
    .btn-primary-modern {
        @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200;
    }
</style>
@endsection
