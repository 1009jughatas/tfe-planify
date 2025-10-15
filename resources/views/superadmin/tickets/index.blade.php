@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">🎫 Gestion des Tickets Support</h1>
                    <p class="mt-2 text-gray-600">Gérez toutes les demandes d'aide des utilisateurs</p>
                </div>
                <a href="{{ route('superadmin.dashboard') }}" class="btn-secondary-modern">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour au dashboard
                </a>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-blue-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Ouverts</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['ouverts'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Fermés</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['fermes'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-reply text-purple-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Répondus</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['repondus'] }}</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="statut" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="ouvert" {{ request('statut') === 'ouvert' ? 'selected' : '' }}>Ouvert</option>
                            <option value="ferme" {{ request('statut') === 'ferme' ? 'selected' : '' }}>Fermé</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                        <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les rôles</option>
                            <option value="user_independant" {{ request('role') === 'user_independant' ? 'selected' : '' }}>Indépendant</option>
                            <option value="admin_entreprise" {{ request('role') === 'admin_entreprise' ? 'selected' : '' }}>Admin Entreprise</option>
                            <option value="user_entreprise" {{ request('role') === 'user_entreprise' ? 'selected' : '' }}>Utilisateur Entreprise</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Objet, description, utilisateur..."
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

        <!-- Liste des tickets -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Tickets Support</h2>
            </div>

            @if($tickets->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($tickets as $ticket)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">#{{ $ticket->id }} - {{ $ticket->objet }}</h3>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($ticket->statut === 'ouvert') bg-orange-100 text-orange-800
                                            @else bg-green-100 text-green-800 @endif">
                                            @if($ticket->statut === 'ouvert')
                                                <i class="fas fa-clock mr-1"></i>Ouvert
                                            @else
                                                <i class="fas fa-check mr-1"></i>Fermé
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 mb-3 line-clamp-2">{{ $ticket->description }}</p>
                                    
                                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $ticket->user->name }} ({{ $ticket->user->email }})
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-tag mr-1"></i>
                                            @if($ticket->user->is_super_admin())
                                                Super Admin
                                            @elseif($ticket->user->isAdminEntreprise())
                                                Admin Entreprise
                                            @elseif($ticket->user->isUserEntreprise())
                                                Utilisateur Entreprise
                                            @else
                                                Indépendant
                                            @endif
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $ticket->created_at->format('d/m/Y H:i') }}
                                        </span>
                                        @if($ticket->repond_le)
                                            <span class="flex items-center">
                                                <i class="fas fa-reply mr-1"></i>
                                                Répondu le {{ $ticket->repond_le->format('d/m/Y H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('superadmin.tickets.show', $ticket) }}" class="btn-primary-modern text-sm">
                                        <i class="fas fa-eye mr-1"></i>
                                        Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $tickets->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-ticket-alt text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun ticket trouvé</h3>
                    <p class="text-gray-600">Aucun ticket ne correspond aux critères de recherche.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
