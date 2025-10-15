@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">👑 Super Admin Dashboard</h1>
                    <p class="mt-2 text-gray-600">Contrôle total de l'application Planify</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                        <i class="fas fa-crown mr-1"></i>
                        Super Admin
                    </span>
                </div>
            </div>
        </div>

        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Utilisateurs</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-green-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Entreprises</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_companies'] }}</p>
                        <p class="text-xs text-gray-500">{{ $stats['active_companies'] }} actives</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-project-diagram text-purple-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Projets</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_projects'] }}</p>
                        <p class="text-xs text-gray-500">{{ $stats['total_tasks'] }} tâches</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-orange-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tickets Support</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_tickets'] }}</p>
                        <p class="text-xs text-gray-500">{{ $stats['open_tickets'] }} ouverts</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation rapide -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('superadmin.tickets.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-headset text-orange-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tickets Support</h3>
                        <p class="text-sm text-gray-600">Gérer les demandes d'aide</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('superadmin.users.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Utilisateurs</h3>
                        <p class="text-sm text-gray-600">Gérer tous les utilisateurs</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('superadmin.projets.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-project-diagram text-purple-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Projets</h3>
                        <p class="text-sm text-gray-600">Voir tous les projets</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('superadmin.abonnements.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-credit-card text-green-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Abonnements</h3>
                        <p class="text-sm text-gray-600">Gérer les abonnements</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Utilisateurs récents -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Utilisateurs Récents</h2>
                </div>
                <div class="p-6">
                    @if($recent_users->count() > 0)
                        <div class="space-y-4">
                            @foreach($recent_users as $user)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs text-gray-500">{{ $user->created_at->format('d/m/Y') }}</span>
                                        <p class="text-xs text-gray-400">
                                            @if($user->company)
                                                {{ $user->company->name }}
                                            @else
                                                Indépendant
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Aucun utilisateur récent</p>
                    @endif
                </div>
            </div>

            <!-- Tickets récents -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Tickets Récents</h2>
                </div>
                <div class="p-6">
                    @if($recent_tickets->count() > 0)
                        <div class="space-y-4">
                            @foreach($recent_tickets as $ticket)
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $ticket->objet }}</p>
                                        <p class="text-xs text-gray-500">Par {{ $ticket->user->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($ticket->statut === 'ouvert') bg-orange-100 text-orange-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ $ticket->statut }}
                                        </span>
                                        <p class="text-xs text-gray-400 mt-1">{{ $ticket->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Aucun ticket récent</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
