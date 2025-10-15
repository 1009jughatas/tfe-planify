@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-project-diagram text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Projets de l'Entreprise</h1>
                    <p class="text-gray-600 mt-1 flex items-center">
                        <i class="fas fa-building mr-2 text-blue-500"></i>
                        {{ $company->name }}
                    </p>
                </div>
            </div>
            @if(auth()->user()->isAdminEntreprise())
                <a href="{{ route('entreprise.projets.create') }}" class="btn-primary-modern">
                    <i class="fas fa-plus mr-2"></i>
                    Nouveau Projet
                </a>
            @endif
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <input type="text" placeholder="Nom du projet..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="in-progress">En cours</option>
                    <option value="completed">Terminé</option>
                    <option value="cancelled">Annulé</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Toutes les priorités</option>
                    <option value="low">Faible</option>
                    <option value="medium">Moyenne</option>
                    <option value="high">Haute</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full btn-secondary-modern">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrer
                </button>
            </div>
        </div>
    </div>

    <!-- Liste des projets -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($projects as $project)
            <div class="modern-card hover-lift">
                <div class="modern-card-body">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $project->name }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $project->description ?? 'Aucune description' }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($project->status === 'completed') bg-green-100 text-green-800
                                @elseif($project->status === 'in-progress') bg-blue-100 text-blue-800
                                @elseif($project->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($project->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Informations du projet -->
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-user w-4 h-4 mr-2 text-gray-400"></i>
                            <span>Créé par {{ $project->author->name }}</span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-tasks w-4 h-4 mr-2 text-gray-400"></i>
                            <span>{{ $project->tasks->count() }} tâche(s)</span>
                        </div>
                        
                        @if($project->start_date)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar w-4 h-4 mr-2 text-gray-400"></i>
                                <span>Début: {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</span>
                            </div>
                        @endif
                        
                        @if($project->end_date)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-flag-checkered w-4 h-4 mr-2 text-gray-400"></i>
                                <span>Fin: {{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-exclamation-triangle w-4 h-4 mr-2 text-gray-400"></i>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($project->priority === 'high') bg-red-100 text-red-800
                                @elseif($project->priority === 'medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                @switch($project->priority)
                                    @case('high') 🔴 Haute @break
                                    @case('medium') 🟡 Moyenne @break
                                    @case('low') 🔵 Faible @break
                                    @default 🔵 Faible
                                @endswitch
                            </span>
                        </div>
                    </div>

                    <!-- Participants -->
                    @if($project->participants->count() > 0)
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Participants ({{ $project->participants->count() }})</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($project->participants->take(3) as $participant)
                                    <div class="flex items-center space-x-1 bg-gray-100 rounded-full px-2 py-1">
                                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                            <span class="text-xs text-white font-medium">{{ substr($participant->name, 0, 1) }}</span>
                                        </div>
                                        <span class="text-xs text-gray-700">{{ $participant->name }}</span>
                                    </div>
                                @endforeach
                                @if($project->participants->count() > 3)
                                    <div class="bg-gray-100 rounded-full px-2 py-1">
                                        <span class="text-xs text-gray-700">+{{ $project->participants->count() - 3 }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('entreprise.projets.show', $project->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Voir les détails
                        </a>
                        @if(auth()->user()->isAdminEntreprise())
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('entreprise.projets.edit', $project->id) }}" class="text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('entreprise.projets.destroy', $project->id) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-project-diagram text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun projet trouvé</h3>
                    <p class="text-gray-600 mb-6">Commencez par créer votre premier projet pour organiser le travail de votre équipe.</p>
                    @if(auth()->user()->isAdminEntreprise())
                        <a href="{{ route('entreprise.projets.create') }}" class="btn-primary-modern">
                            <i class="fas fa-plus mr-2"></i>
                            Créer un projet
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($projects->hasPages())
        <div class="mt-8">
            {{ $projects->links() }}
        </div>
    @endif
</div>

<style>
    .modern-card {
        @apply bg-white rounded-xl shadow-sm border border-gray-200;
    }
    
    .modern-card-body {
        @apply p-6;
    }
    
    .hover-lift:hover {
        @apply shadow-lg transform -translate-y-1;
    }
    
    .btn-primary-modern {
        @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200;
    }
    
    .btn-secondary-modern {
        @apply inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg shadow-sm border border-gray-300 hover:shadow-md hover:scale-105 transition-all duration-200;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
