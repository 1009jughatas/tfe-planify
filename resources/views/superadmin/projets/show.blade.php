@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">📋 Détails du projet</h1>
                    <p class="mt-2 text-gray-600">{{ $project->name }}</p>
                </div>
                <a href="{{ route('superadmin.projets.index') }}" class="btn-secondary-modern">
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
                        <h2 class="text-lg font-semibold text-gray-900">Informations du projet</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom du projet</label>
                                <p class="text-gray-900">{{ $project->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                                <span class="px-3 py-1 text-sm font-medium rounded-full
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
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Créateur</label>
                                <p class="text-gray-900">
                                    @if($project->author)
                                        {{ $project->author->name }} ({{ $project->author->email }})
                                    @else
                                        Utilisateur supprimé
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                <span class="px-3 py-1 text-sm font-medium rounded-full
                                    @if($project->company_id) bg-purple-100 text-purple-800
                                    @else bg-green-100 text-green-800 @endif">
                                    @if($project->company_id)
                                        <i class="fas fa-building mr-1"></i>Entreprise
                                    @else
                                        <i class="fas fa-user mr-1"></i>Indépendant
                                    @endif
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date de création</label>
                                <p class="text-gray-900">{{ $project->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dernière modification</label>
                                <p class="text-gray-900">{{ $project->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($project->description)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <p class="text-gray-900">{{ $project->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($project->company)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Informations entreprise</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'entreprise</label>
                                    <p class="text-gray-900">{{ $project->company->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email entreprise</label>
                                    <p class="text-gray-900">{{ $project->company->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Plan d'abonnement</label>
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($project->company->plan ?? 'starter') }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut entreprise</label>
                                    <span class="px-3 py-1 text-sm font-medium rounded-full
                                        @if($project->company->status === 'active') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($project->company->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tâches du projet -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Tâches ({{ $project->tasks->count() }})</h2>
                    </div>
                    <div class="p-6">
                        @if($project->tasks->count() > 0)
                            <div class="space-y-4">
                                @foreach($project->tasks as $task)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $task->title }}</h3>
                                                <p class="text-sm text-gray-600">{{ Str::limit($task->description, 100) }}</p>
                                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-user mr-1"></i>
                                                        {{ $task->user ? $task->user->name : 'Utilisateur supprimé' }}
                                                    </span>
                                                    @if($task->assignedUser)
                                                        <span class="flex items-center">
                                                            <i class="fas fa-user-check mr-1"></i>
                                                            Assigné à {{ $task->assignedUser ? $task->assignedUser->name : 'Utilisateur supprimé' }}
                                                        </span>
                                                    @endif
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                                        @if($task->status === 'completed') bg-green-100 text-green-800
                                                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        {{ ucfirst($task->status) }}
                                                    </span>
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                                        @if($task->priority === 'high') bg-red-100 text-red-800
                                                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                                        @else bg-green-100 text-green-800 @endif">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-tasks text-gray-400"></i>
                                </div>
                                <p class="text-gray-600">Aucune tâche trouvée</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Statistiques -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Statistiques</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tâches totales</span>
                            <span class="font-semibold text-gray-900">{{ $project->tasks->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tâches terminées</span>
                            <span class="font-semibold text-gray-900">{{ $project->tasks->where('status', 'completed')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tâches en cours</span>
                            <span class="font-semibold text-gray-900">{{ $project->tasks->where('status', 'in_progress')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tâches en attente</span>
                            <span class="font-semibold text-gray-900">{{ $project->tasks->where('status', 'pending')->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Informations créateur -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Créateur</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                            <div>
                                @if($project->author)
                                    <h3 class="text-sm font-semibold text-gray-900">{{ $project->author->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $project->author->email }}</p>
                                @else
                                    <h3 class="text-sm font-semibold text-gray-900">Utilisateur supprimé</h3>
                                    <p class="text-sm text-gray-500">Compte non disponible</p>
                                @endif
                            </div>
                        </div>
                        @if($project->author)
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Rôle:</span>
                                    <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $project->author->role)) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Inscrit le:</span>
                                    <span class="font-medium">{{ $project->author->created_at->format('d/m/Y') }}</span>
                                </div>
                                @if($project->author->is_premium)
                                    <div class="flex justify-between">
                                        <span>Statut:</span>
                                        <span class="font-medium text-yellow-600">
                                            <i class="fas fa-crown mr-1"></i>Premium
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-gray-500">Informations utilisateur non disponibles</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
