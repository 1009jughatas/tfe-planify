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
                    <h1 class="text-2xl font-bold text-gray-900">Mes Projets</h1>
                    <p class="text-gray-600 mt-1 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Projets personnels
                    </p>
                </div>
            </div>
            @php
                $canCreate = Auth::user() && (Auth::user()->is_premium() || Auth::user()->projects()->count() < 3);
            @endphp
            @if ($canCreate)
                <a href="{{ route('projects.create') }}" class="btn-primary-modern">
                    <i class="fas fa-plus mr-2"></i>
                    Nouveau Projet
                </a>
            @else
                <button class="btn-secondary-modern" disabled title="Limite atteinte">
                    <i class="fas fa-lock mr-2"></i>
                    Limite atteinte
                </button>
            @endif
        </div>
    </div>

    <!-- Alertes de limitation -->
    @if (Auth::user() && Auth::user()->isUserIndependant() && !Auth::user()->is_premium())
        @php
            $projectCount = Auth::user() ? Auth::user()->projects()->count() : 0;
            $projectLimit = 3;
        @endphp
        @if ($projectCount >= $projectLimit)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800">Limite de projets atteinte</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            Vous avez atteint la limite de {{ $projectLimit }} projets pour les utilisateurs gratuits.
                            <a href="{{ route('premium.show') }}" class="font-medium underline hover:text-yellow-600">
                                Passez en premium
                            </a>
                            pour créer plus de projets.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endif

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
                                @switch($project->status)
                                    @case('planning') 📋 En planification @break
                                    @case('active') 🚀 Actif @break
                                    @case('on-hold') ⏸️ En pause @break
                                    @case('completed') ✅ Terminé @break
                                    @case('cancelled') ❌ Annulé @break
                                    @default 📋 En planification
                                @endswitch
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
                            <span>{{ $project->tasks->count() }} tâches</span>
                        </div>

                        @if($project->start_date)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar w-4 h-4 mr-2 text-gray-400"></i>
                                <span>Début: {{ $project->start_date->format('d/m/Y') }}</span>
                            </div>
                        @endif

                        @if($project->end_date)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-flag-checkered w-4 h-4 mr-2 text-gray-400"></i>
                                <span>Fin: {{ $project->end_date->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Barre de progression -->
                    @if($project->tasks->count() > 0)
                        @php
                            $completedTasks = $project->tasks->where('status', 'completed')->count();
                            $totalTasks = $project->tasks->count();
                            $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                        @endphp
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Progression</span>
                                <span>{{ round($progress) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>
                                Voir
                            </a>
                            <a href="{{ route('projects.edit', $project) }}" class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                <i class="fas fa-edit mr-1"></i>
                                Modifier
                            </a>
                        </div>
                        <div class="flex items-center space-x-1">
                            <span class="text-xs text-gray-500">
                                {{ $project->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-project-diagram text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun projet trouvé</h3>
                    <p class="text-gray-600 mb-6">Commencez par créer votre premier projet.</p>
                    @if($canCreate)
                        <a href="{{ route('projects.create') }}" class="btn-primary-modern">
                            <i class="fas fa-plus mr-2"></i>
                            Créer un projet
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection