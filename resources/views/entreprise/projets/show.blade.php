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
                    <h1 class="text-2xl font-bold text-gray-900">{{ $projet->name }}</h1>
                    <p class="text-gray-600 mt-1 flex items-center">
                        <i class="fas fa-building mr-2 text-blue-500"></i>
                        {{ $company->name }}
                    </p>
                </div>
            </div>
            @if(auth()->user()->isAdminEntreprise())
                <div class="flex items-center space-x-3">
                    <a href="{{ route('entreprise.projets.edit', $projet->id) }}" class="btn-secondary-modern">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier
                    </a>
                    <a href="{{ route('entreprise.projets.index') }}" class="btn-secondary-modern">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Informations du projet -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Description -->
            @if($projet->description)
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-file-text text-blue-500 mr-2"></i>
                            Description
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $projet->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Tâches du projet -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-tasks text-orange-500 mr-2"></i>
                            Tâches ({{ $projet->tasks->count() }})
                        </h3>
                        @if(auth()->user()->isAdminEntreprise())
                            <a href="{{ route('entreprise.tasks.create', ['project' => $projet->id]) }}" class="btn-primary-modern" id="nouvelle-tache-btn" onclick="handleTaskCreation(event, {{ $projet->id }})">
                                <i class="fas fa-plus mr-2"></i>
                                Nouvelle tâche
                            </a>
                        @endif
                    </div>
                </div>
                <div class="modern-card-body">
                    @php
                        // Filtrer les tâches selon les permissions de l'utilisateur
                        $visibleTasks = $projet->tasks->filter(function($task) {
                            return auth()->user()->can('view', $task);
                        });
                    @endphp
                    @if($visibleTasks->count() > 0)
                        <div class="space-y-4">
                            @foreach($visibleTasks as $task)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $task->title }}</h4>
                                        <div class="flex items-center space-x-4 mt-1">
                                            @if($task->assignedUser)
                                                <span class="text-sm text-gray-500">
                                                    <i class="fas fa-user mr-1"></i>
                                                    {{ $task->assignedUser->name }}
                                                </span>
                                            @endif
                                            @if($task->due_date)
                                                <span class="text-sm text-gray-500">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($task->status === 'completed') bg-green-100 text-green-800
                                            @elseif($task->status === 'in-progress') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                        <a href="{{ route('entreprise.tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-tasks text-4xl mb-3"></i>
                            <p>Aucune tâche créée</p>
                            @if(auth()->user()->isAdminEntreprise())
                                <a href="{{ route('entreprise.tasks.create', ['project' => $projet->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Créer la première tâche
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statut et priorité -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Informations</h3>
                </div>
                <div class="modern-card-body space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($projet->status === 'completed') bg-green-100 text-green-800
                            @elseif($projet->status === 'in-progress') bg-blue-100 text-blue-800
                            @elseif($projet->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($projet->status) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($projet->priority === 'high') bg-red-100 text-red-800
                            @elseif($projet->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($projet->priority) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Créé par</label>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                <span class="text-xs text-white font-medium">{{ substr($projet->author->name, 0, 1) }}</span>
                            </div>
                            <span class="text-sm text-gray-900">{{ $projet->author->name }}</span>
                        </div>
                    </div>
                    
                    @if($projet->start_date)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($projet->start_date)->format('d/m/Y') }}</span>
                        </div>
                    @endif
                    
                    @if($projet->end_date)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($projet->end_date)->format('d/m/Y') }}</span>
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Créé le</label>
                        <span class="text-sm text-gray-900">{{ $projet->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Participants -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Participants ({{ $projet->participants->count() }})</h3>
                </div>
                <div class="modern-card-body">
                    @if($projet->participants->count() > 0)
                        <div class="space-y-3">
                            @foreach($projet->participants as $participant)
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                        <span class="text-sm text-white font-medium">{{ substr($participant->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $participant->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $participant->email }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($participant->role === 'admin_entreprise') bg-purple-100 text-purple-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ $participant->role === 'admin_entreprise' ? 'Admin' : 'Employé' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-users text-2xl mb-2"></i>
                            <p class="text-sm">Aucun participant assigné</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Statistiques</h3>
                </div>
                <div class="modern-card-body space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total des tâches</span>
                        <span class="font-semibold text-gray-900">{{ $visibleTasks->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Tâches terminées</span>
                        <span class="font-semibold text-green-600">{{ $visibleTasks->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">En cours</span>
                        <span class="font-semibold text-blue-600">{{ $visibleTasks->where('status', 'in-progress')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">En attente</span>
                        <span class="font-semibold text-gray-600">{{ $visibleTasks->where('status', 'pending')->count() }}</span>
                    </div>
                    
                    @if($visibleTasks->count() > 0)
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">Progression</span>
                                <span class="text-sm font-medium text-gray-900">{{ round(($visibleTasks->where('status', 'completed')->count() / $visibleTasks->count()) * 100) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($visibleTasks->where('status', 'completed')->count() / $visibleTasks->count()) * 100 }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
    
    .btn-secondary-modern {
        @apply inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg shadow-sm border border-gray-300 hover:shadow-md hover:scale-105 transition-all duration-200;
    }
</style>

<script>
function handleTaskCreation(event, projectId) {
    // Empêcher le comportement par défaut
    event.preventDefault();
    
    // Construire l'URL correcte pour l'entreprise
    const correctUrl = `/entreprise/projects/${projectId}/tasks/create`;
    
    console.log('Redirection vers:', correctUrl);
    
    // Rediriger vers la bonne URL
    window.location.href = correctUrl;
}
</script>
@endsection
