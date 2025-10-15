@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">👤 Détails de l'utilisateur</h1>
                    <p class="mt-2 text-gray-600">{{ $user->name }} - {{ $user->email }}</p>
                </div>
                <a href="{{ route('superadmin.users.index') }}" class="btn-secondary-modern">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Informations personnelles</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                                <p class="text-gray-900">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <p class="text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                                <span class="px-3 py-1 text-sm font-medium rounded-full
                                    @if($user->role === 'super_admin') bg-red-100 text-red-800
                                    @elseif($user->role === 'admin_entreprise') bg-purple-100 text-purple-800
                                    @elseif($user->role === 'user_entreprise') bg-orange-100 text-orange-800
                                    @else bg-green-100 text-green-800 @endif">
                                    @if($user->role === 'super_admin')
                                        <i class="fas fa-crown mr-1"></i>Super Admin
                                    @elseif($user->role === 'admin_entreprise')
                                        <i class="fas fa-user-tie mr-1"></i>Admin Entreprise
                                    @elseif($user->role === 'user_entreprise')
                                        <i class="fas fa-user-friends mr-1"></i>Employé
                                    @else
                                        <i class="fas fa-user mr-1"></i>Indépendant
                                    @endif
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                                <span class="px-3 py-1 text-sm font-medium rounded-full
                                    @if($user->is_active) bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    @if($user->is_active)
                                        <i class="fas fa-check mr-1"></i>Actif
                                    @else
                                        <i class="fas fa-times mr-1"></i>Inactif
                                    @endif
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date d'inscription</label>
                                <p class="text-gray-900">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dernière connexion</label>
                                <p class="text-gray-900">
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->format('d/m/Y à H:i') }}
                                    @else
                                        Jamais connecté
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($user->company)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Informations entreprise</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'entreprise</label>
                                    <p class="text-gray-900">{{ $user->company ? $user->company->name : 'Entreprise non disponible' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email entreprise</label>
                                    <p class="text-gray-900">{{ $user->company ? $user->company->email : 'Email non disponible' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Plan d'abonnement</label>
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                                        {{ $user->company ? ucfirst($user->company->plan ?? 'starter') : 'Non défini' }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Limite d'utilisateurs</label>
                                    <p class="text-gray-900">
                                        @if($user->company)
                                            @if($user->company->max_users === -1)
                                                Illimité
                                            @else
                                                {{ $user->company->max_users ?? 'Non défini' }}
                                            @endif
                                        @else
                                            Non défini
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Projets de l'utilisateur -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Projets ({{ $user->projects->count() }})</h2>
                    </div>
                    <div class="p-6">
                        @if($user->projects->count() > 0)
                            <div class="space-y-4">
                                @foreach($user->projects as $project)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $project->name }}</h3>
                                                <p class="text-sm text-gray-600">{{ Str::limit($project->description, 100) }}</p>
                                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ $project->created_at->format('d/m/Y') }}
                                                    </span>
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                                        @if($project->status === 'completed') bg-green-100 text-green-800
                                                        @elseif($project->status === 'in_progress') bg-blue-100 text-blue-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        {{ ucfirst($project->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <a href="{{ route('superadmin.projets.show', $project) }}" class="btn-primary-modern text-sm">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-folder-open text-gray-400"></i>
                                </div>
                                <p class="text-gray-600">Aucun projet trouvé</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Actions</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <form method="POST" action="{{ route('superadmin.users.toggle-status', $user) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full btn-secondary-modern">
                                @if($user->is_active)
                                    <i class="fas fa-ban mr-2"></i>
                                    Désactiver le compte
                                @else
                                    <i class="fas fa-check mr-2"></i>
                                    Activer le compte
                                @endif
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Statistiques</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Projets créés</span>
                            <span class="font-semibold text-gray-900">{{ $user->projects->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tâches créées</span>
                            <span class="font-semibold text-gray-900">{{ $user->tasks->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tickets de support</span>
                            <span class="font-semibold text-gray-900">{{ $user->ticketSupports->count() }}</span>
                        </div>
                        @if($user->is_premium)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Statut Premium</span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-crown mr-1"></i>Premium
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
