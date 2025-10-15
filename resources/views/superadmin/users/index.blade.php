@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">👥 Gestion des Utilisateurs</h1>
                    <p class="mt-2 text-gray-600">Gérez tous les utilisateurs de l'application</p>
                </div>
                <a href="{{ route('superadmin.dashboard') }}" class="btn-secondary-modern">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour au dashboard
                </a>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-green-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Indépendants</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['independants'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-tie text-purple-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Admins Entreprise</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['admin_entreprise'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-friends text-orange-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Employés</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['user_entreprise'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-crown text-red-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Super Admins</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['super_admin'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Filtres</h2>
            </div>
            <div class="p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                        <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les rôles</option>
                            <option value="user_independant" {{ request('role') === 'user_independant' ? 'selected' : '' }}>Indépendant</option>
                            <option value="admin_entreprise" {{ request('role') === 'admin_entreprise' ? 'selected' : '' }}>Admin Entreprise</option>
                            <option value="user_entreprise" {{ request('role') === 'user_entreprise' ? 'selected' : '' }}>Employé Entreprise</option>
                            <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Entreprise</label>
                        <select name="company" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les entreprises</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nom, email..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full btn-primary-modern">
                            <i class="fas fa-search mr-2"></i>
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des utilisateurs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Utilisateurs</h2>
            </div>

            @if($users->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($users as $user)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
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
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($user->is_active) bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            @if($user->is_active)
                                                <i class="fas fa-check mr-1"></i>Actif
                                            @else
                                                <i class="fas fa-times mr-1"></i>Inactif
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Inscrit le {{ $user->created_at->format('d/m/Y') }}
                                        </span>
                                        @if($user->company)
                                            <span class="flex items-center">
                                                <i class="fas fa-building mr-1"></i>
                                                {{ $user->company->name }}
                                            </span>
                                        @endif
                                        @if($user->is_premium)
                                            <span class="flex items-center text-yellow-600">
                                                <i class="fas fa-crown mr-1"></i>
                                                Premium
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('superadmin.users.show', $user) }}" class="btn-primary-modern text-sm">
                                        <i class="fas fa-eye mr-1"></i>
                                        Voir
                                    </a>
                                    <form method="POST" action="{{ route('superadmin.users.toggle-status', $user) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-secondary-modern text-sm">
                                            @if($user->is_active)
                                                <i class="fas fa-ban mr-1"></i>
                                                Désactiver
                                            @else
                                                <i class="fas fa-check mr-1"></i>
                                                Activer
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun utilisateur trouvé</h3>
                    <p class="text-gray-600">Aucun utilisateur ne correspond aux critères de recherche.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
