@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">📋 Gestion des Projets</h1>
                    <p class="mt-2 text-gray-600">Consultez tous les projets de l'application</p>
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
                        <i class="fas fa-folder text-blue-600 text-lg"></i>
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
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Terminés</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['completed'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">En cours</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['in_progress'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-pause text-gray-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">En attente</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les types</option>
                            <option value="entreprise" {{ request('type') === 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                            <option value="independant" {{ request('type') === 'independant' ? 'selected' : '' }}>Indépendant</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En cours</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminé</option>
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
                               placeholder="Nom du projet..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="md:col-span-4 flex justify-end">
                        <button type="submit" class="btn-primary-modern">
                            <i class="fas fa-search mr-2"></i>
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des projets -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Projets</h2>
            </div>

            @if($projects->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($projects as $project)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-folder text-blue-600"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $project->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ Str::limit($project->description, 100) }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($project->status === 'completed') bg-green-100 text-green-800
                                            @elseif($project->status === 'in_progress') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($project->status === 'completed')
                                                <i class="fas fa-check mr-1"></i>Terminé
                                            @elseif($project->status === 'in_progress')
                                                <i class="fas fa-clock mr-1"></i>En cours
                                            @else
                                                <i class="fas fa-pause mr-1"></i>En attente
                                            @endif
                                        </span>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($project->company_id) bg-purple-100 text-purple-800
                                            @else bg-green-100 text-green-800 @endif">
                                            @if($project->company_id)
                                                <i class="fas fa-building mr-1"></i>Entreprise
                                            @else
                                                <i class="fas fa-user mr-1"></i>Indépendant
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $project->author->name }}
                                        </span>
                                        @if($project->company)
                                            <span class="flex items-center">
                                                <i class="fas fa-building mr-1"></i>
                                                {{ $project->company->name }}
                                            </span>
                                        @endif
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Créé le {{ $project->created_at->format('d/m/Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-tasks mr-1"></i>
                                            {{ $project->tasks->count() }} tâches
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('superadmin.projets.show', $project) }}" class="btn-primary-modern text-sm">
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
                    {{ $projects->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-folder-open text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun projet trouvé</h3>
                    <p class="text-gray-600">Aucun projet ne correspond aux critères de recherche.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
