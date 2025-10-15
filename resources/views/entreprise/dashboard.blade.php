@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                    <img src="{{ asset('images/logo.png') }}" alt="Planify" class="w-8 h-8 object-contain filter brightness-0 invert">
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Entreprise</h1>
                    <p class="text-gray-600 mt-1 flex items-center">
                        <i class="fas fa-building mr-2 text-blue-500"></i>
                        {{ $company->name }} • Bienvenue, {{ auth()->user()->name }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @if (auth()->user()->isAdminEntreprise())
                    <span class="badge-premium">
                        <i class="fas fa-crown mr-1"></i>Administrateur
                    </span>
                @else
                    <span class="badge-secondary">
                        <i class="fas fa-user mr-1"></i>Employé
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Projets Actifs -->
        <div class="stats-card hover-lift group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Projets Actifs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $activeProjects }}</p>
                    <p class="text-xs text-gray-500 mt-1 flex items-center">
                        <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                        Sur {{ $totalProjects }} total
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-project-diagram text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Tâches Ouvertes -->
        <div class="stats-card hover-lift group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Tâches Ouvertes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $openTasks }}</p>
                    <p class="text-xs text-gray-500 mt-1 flex items-center">
                        <i class="fas fa-clock text-orange-500 mr-1"></i>
                        Sur {{ $totalTasks }} total
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-tasks text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Équipe -->
        <div class="stats-card hover-lift group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Équipe</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
                    <p class="text-xs text-gray-500 mt-1 flex items-center">
                        <i class="fas fa-users text-blue-500 mr-1"></i>
                        Limite: {{ $maxUsers }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Progression -->
        <div class="stats-card hover-lift group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Progression</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $completedProjects }}</p>
                    <p class="text-xs text-gray-500 mt-1 flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                        Projets terminés
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-chart-line text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides selon le rôle -->
    @if (auth()->user()->isAdminEntreprise())
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-cog text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Administration Entreprise</h3>
                        <p class="text-sm text-gray-600">Gérez votre équipe, projets et abonnements</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('entreprise.projets.create') }}" class="btn-primary-modern">
                        <i class="fas fa-plus mr-2"></i>
                        Nouveau Projet
                    </a>
                    <a href="{{ route('entreprise.utilisateurs.inviter') }}" class="btn-secondary-modern">
                        <i class="fas fa-user-plus mr-2"></i>
                        Inviter Employé
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Projets Récents -->
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                            <i class="fas fa-project-diagram text-white"></i>
                        </div>
                        Projets Récents
                    </h3>
                    <a href="{{ route('entreprise.projets.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Voir tous
                    </a>
                </div>
            </div>
            <div class="modern-card-body">
                @if($recentProjects->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentProjects as $project)
                            <div class="project-item p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h5 class="font-medium text-gray-900">{{ $project->name }}</h5>
                                        <div class="flex items-center space-x-4 mt-1">
                                            <span class="text-sm text-gray-500">
                                                <i class="fas fa-tasks mr-1"></i>
                                                @php
                                                    // Filtrer les tâches selon les permissions de l'utilisateur
                                                    $visibleProjectTasks = $project->tasks->filter(function($task) {
                                                        return auth()->user()->can('view', $task);
                                                    });
                                                @endphp
                                                {{ $visibleProjectTasks->count() }} tâches
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                <i class="fas fa-user mr-1"></i>
                                                {{ $project->author->name }}
                                            </span>
                                            @if($project->start_date)
                                                <span class="text-sm text-gray-500">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            @if($project->status === 'completed') bg-green-100 text-green-800
                                            @elseif($project->status === 'in-progress') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                        <a href="{{ route('entreprise.projets.show', $project->id) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-project-diagram text-4xl mb-3"></i>
                        <p>Aucun projet créé</p>
                        @if(auth()->user()->isAdminEntreprise())
                            <a href="{{ route('entreprise.projets.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Créer votre premier projet
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Tâches Récentes -->
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                            <i class="fas fa-tasks text-white"></i>
                        </div>
                        Tâches Récentes
                    </h3>
                </div>
            </div>
            <div class="modern-card-body">
                @if($recentTasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentTasks as $task)
                            <div class="task-item p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h5 class="font-medium text-gray-900">{{ $task->title }}</h5>
                                        <div class="flex items-center space-x-4 mt-1">
                                            <span class="text-sm text-gray-500">
                                                <i class="fas fa-folder mr-1"></i>
                                                {{ $task->project->name }}
                                            </span>
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
                                            @elseif($task->status === 'in-progress') bg-orange-100 text-orange-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                        <a href="{{ route('entreprise.tasks.show', $task->id) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-tasks text-4xl mb-3"></i>
                        <p>Aucune tâche récente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Échéances et Alertes -->
    @if($upcomingDeadlines->count() > 0 || $overdueTasks->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Échéances Proches -->
            @if($upcomingDeadlines->count() > 0)
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                            Échéances Proches
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-3">
                            @foreach($upcomingDeadlines as $project)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ $project->name }}</h5>
                                        <p class="text-sm text-gray-600">
                                            Échéance: {{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    <span class="text-yellow-600">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tâches en Retard -->
            @if($overdueTasks->count() > 0)
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                                <i class="fas fa-exclamation-circle text-white"></i>
                            </div>
                            Tâches en Retard
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-3">
                            @foreach($overdueTasks as $task)
                                <div class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ $task->title }}</h5>
                                        <p class="text-sm text-gray-600">
                                            {{ $task->project->name }} • Retard: {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}
                                        </p>
                                    </div>
                                    <span class="text-red-600">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Actions Rapides -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                    <i class="fas fa-bolt text-white"></i>
                </div>
                Actions Rapides
            </h3>
        </div>
        <div class="modern-card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @if(auth()->user()->isAdminEntreprise())
                    <a href="{{ route('entreprise.projets.create') }}" class="group p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-plus text-white"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Nouveau Projet</h4>
                            <p class="text-sm text-gray-600 mt-1">Créer un nouveau projet</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('entreprise.utilisateurs.inviter') }}" class="group p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-user-plus text-white"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Inviter Employé</h4>
                            <p class="text-sm text-gray-600 mt-1">Ajouter un membre à l'équipe</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('entreprise.utilisateurs.index') }}" class="group p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Gérer l'Équipe</h4>
                            <p class="text-sm text-gray-600 mt-1">Voir tous les employés</p>
                        </div>
                    </a>
                @else
                    <a href="{{ route('entreprise.projets.index') }}" class="group p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-folder-open text-white"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Mes Projets</h4>
                            <p class="text-sm text-gray-600 mt-1">Voir mes projets assignés</p>
                        </div>
                    </a>
                @endif
                
                <a href="{{ route('profile.edit') }}" class="group p-6 bg-gradient-to-br from-gray-50 to-slate-50 rounded-2xl border border-gray-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-slate-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Mon Profil</h4>
                        <p class="text-sm text-gray-600 mt-1">Gérer mon compte</p>
                    </div>
                </a>
                
                @if(auth()->user()->isAdminEntreprise())
                    <a href="{{ route('entreprise.abonnement.index') }}" class="group p-6 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl border border-yellow-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900">Abonnement</h4>
                            <p class="text-sm text-gray-600 mt-1">Gérer l'abonnement</p>
                        </div>
                    </a>
                @endif
            </div>
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
    
    .btn-secondary-modern {
        @apply inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg shadow-sm border border-gray-300 hover:shadow-md hover:scale-105 transition-all duration-200;
    }
    
    .badge-premium {
        @apply inline-flex items-center px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-medium rounded-full shadow-sm;
    }
    
    .badge-secondary {
        @apply inline-flex items-center px-3 py-1 bg-gray-500 text-white text-xs font-medium rounded-full shadow-sm;
    }
</style>
@endsection