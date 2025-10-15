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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
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
                                            @switch($project->status)
                                                @case('planning') 📋 En planification @break
                                                @case('active') 🚀 Actif @break
                                                @case('on-hold') ⏸️ En pause @break
                                                @case('completed') ✅ Terminé @break
                                                @case('cancelled') ❌ Annulé @break
                                                @default 📋 En planification
                                            @endswitch
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
                                        <span class="px-2 py-1 text-xs font-medium rounded-full task-status-badge
                                            @if($task->status === 'completed') bg-green-100 text-green-800
                                            @elseif($task->status === 'in-progress') bg-orange-100 text-orange-800
                                            @else bg-gray-100 text-gray-800 @endif" 
                                            data-task-id="{{ $task->id }}" id="task-status-{{ $task->id }}">
                                            @switch($task->status)
                                                @case('completed') ✅ Terminé @break
                                                @case('in-progress') 🚀 En cours @break
                                                @case('blocked') 🚫 Bloqué @break
                                                @default 📋 En attente
                                            @endswitch
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

        <!-- Calendrier Professionnel -->
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-md">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        Calendrier des Tâches
                    </h3>
                    <div class="flex items-center space-x-2">
                        <button onclick="changeView('month')" id="btn-month" class="px-4 py-2 text-sm font-medium rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                            <i class="fas fa-calendar-alt mr-2"></i>Mois
                        </button>
                        <button onclick="changeView('week')" id="btn-week" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                            <i class="fas fa-calendar-week mr-2"></i>Semaine
                        </button>
                        <button onclick="changeView('day')" id="btn-day" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                            <i class="fas fa-calendar-day mr-2"></i>Jour
                        </button>
                    </div>
                </div>
            </div>
            <div class="modern-card-body p-0">
                <!-- Header du calendrier -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-4">
                        <button onclick="navigateCalendar(-1)" class="p-3 rounded-lg bg-white hover:bg-gray-100 transition-colors shadow-sm border">
                            <i class="fas fa-chevron-left text-gray-600"></i>
                        </button>
                        <button onclick="goToToday()" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors text-sm font-medium">
                            Aujourd'hui
                        </button>
                        <button onclick="navigateCalendar(1)" class="p-3 rounded-lg bg-white hover:bg-gray-100 transition-colors shadow-sm border">
                            <i class="fas fa-chevron-right text-gray-600"></i>
                        </button>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900" id="calendar-title">{{ date('F Y') }}</h4>
                    <div class="flex items-center space-x-4">
                        <!-- Légende -->
                        <div class="flex items-center space-x-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                                <span class="text-gray-600">Projets</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                <span class="text-gray-600">Terminé</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-orange-500 rounded mr-2"></div>
                                <span class="text-gray-600">En cours</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                                <span class="text-gray-600">En retard</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contenu du calendrier -->
                <div id="calendar-content" class="p-6">
                    <!-- Le contenu sera généré par JavaScript -->
                </div>
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

    /* Styles du calendrier professionnel */
    .calendar-container {
        font-family: 'Inter', sans-serif;
    }
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .calendar-header-row {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-bottom: 2px solid #e5e7eb;
    }
    
    .calendar-header-cell {
        padding: 16px 12px;
        text-align: center;
        font-weight: 700;
        font-size: 0.9rem;
        color: white;
        border-right: 1px solid rgba(255, 255, 255, 0.2);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .calendar-header-cell:last-child {
        border-right: none;
    }
    
    .calendar-day-cell {
        min-height: 120px;
        padding: 12px;
        border-right: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        position: relative;
        background: white;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .calendar-day-cell:nth-child(7n) {
        border-right: none;
    }
    
    .calendar-day-cell:hover {
        background: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .calendar-day-cell.other-month {
        background: #f9fafb;
        color: #9ca3af;
    }
    
    .calendar-day-cell.today {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 700;
    }
    
    .calendar-day-cell.today:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    }
    
    .calendar-day-number {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 8px;
        color: #374151;
    }
    
    .calendar-day-cell.today .calendar-day-number {
        color: white;
    }
    
    .calendar-events {
        display: flex;
        flex-direction: column;
        gap: 4px;
        max-height: 80px;
        overflow-y: auto;
    }
    
    .calendar-event {
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        border-left: 4px solid;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .calendar-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .calendar-event.project {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border-left-color: #3b82f6;
    }
    
    .calendar-event.task-completed {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #166534;
        border-left-color: #22c55e;
    }
    
    .calendar-event.task-in-progress {
        background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
        color: #9a3412;
        border-left-color: #f97316;
    }
    
    .calendar-event.task-overdue {
        background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
        color: #991b1b;
        border-left-color: #ef4444;
    }
    
    .calendar-event.task-pending {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #374151;
        border-left-color: #6b7280;
    }
    
    /* Vue semaine */
    .calendar-week-view {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .calendar-week-day {
        min-height: 200px;
        padding: 16px;
        border-right: 1px solid #e5e7eb;
        position: relative;
        background: white;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .calendar-week-day:last-child {
        border-right: none;
    }
    
    .calendar-week-day:hover {
        background: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .calendar-week-day.today {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .calendar-week-day.today:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    }
    
    /* Vue jour */
    .calendar-day-view {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .calendar-single-day {
        min-height: 400px;
        padding: 24px;
        background: white;
        transition: all 0.3s ease;
    }
    
    .calendar-single-day:hover {
        background: #f8fafc;
    }
    
    .calendar-single-day.today {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    /* Boutons actifs */
    .calendar-view-btn.active {
        background: #3b82f6 !important;
        color: white !important;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }
    
    /* Scrollbar personnalisée */
    .calendar-events::-webkit-scrollbar {
        width: 4px;
    }
    
    .calendar-events::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 2px;
    }
    
    .calendar-events::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 2px;
    }
    
    .calendar-events::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

</style>

<script>
    // Données du calendrier
    const calendarData = {
        projects: [
            @foreach($recentProjects as $project)
                @if($project->start_date)
                    {
                        id: {{ $project->id }},
                        name: '{{ addslashes($project->name) }}',
                        startDate: '{{ $project->start_date }}',
                        endDate: '{{ $project->end_date ?? null }}',
                        url: '{{ route('entreprise.projets.show', $project->id) }}',
                        type: 'project'
                    },
                @endif
            @endforeach
        ],
        tasks: [
            @foreach($calendarTasks as $task)
                {
                    id: {{ $task->id }},
                    name: '{{ addslashes($task->title) }}',
                    date: '{{ $task->due_date }}',
                    status: '{{ $task->status }}',
                    url: '{{ route('entreprise.tasks.show', $task->id) }}',
                    type: 'task'
                },
            @endforeach
        ]
    };

    // Variables globales
    let currentDate = new Date();
    let currentView = 'month';
    
    const monthNames = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];
    
    const dayNames = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    // Fonction principale pour générer le calendrier
    function generateCalendar() {
        const content = document.getElementById('calendar-content');
        const title = document.getElementById('calendar-title');
        
        if (currentView === 'month') {
            title.textContent = monthNames[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
            content.innerHTML = generateMonthView();
        } else if (currentView === 'week') {
            const weekStart = getWeekStart(currentDate);
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekStart.getDate() + 6);
            title.textContent = `${weekStart.getDate()}/${weekStart.getMonth() + 1} - ${weekEnd.getDate()}/${weekEnd.getMonth() + 1} ${weekEnd.getFullYear()}`;
            content.innerHTML = generateWeekView();
        } else if (currentView === 'day') {
            title.textContent = dayNames[currentDate.getDay() === 0 ? 6 : currentDate.getDay() - 1] + ' ' + currentDate.getDate() + ' ' + monthNames[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
            content.innerHTML = generateDayView();
        }
    }

    // Vue mois
    function generateMonthView() {
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = (firstDay.getDay() + 6) % 7;
        
        let html = `
            <div class="calendar-grid">
                <div class="calendar-header-row">
                    ${dayNames.map(day => `<div class="calendar-header-cell">${day}</div>`).join('')}
                </div>
        `;
        
        // Jours du mois précédent
        const prevMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 0);
        const daysInPrevMonth = prevMonth.getDate();
        
        for (let i = startingDayOfWeek - 1; i >= 0; i--) {
            const dayNumber = daysInPrevMonth - i;
            html += createDayCell(dayNumber, true);
        }
        
        // Jours du mois actuel
        for (let day = 1; day <= daysInMonth; day++) {
            html += createDayCell(day, false);
        }
        
        // Jours du mois suivant
        const totalCells = startingDayOfWeek + daysInMonth;
        const remainingCells = 42 - totalCells;
        
        for (let day = 1; day <= remainingCells; day++) {
            html += createDayCell(day, true);
        }
        
        html += '</div>';
        return html;
    }

    // Vue semaine
    function generateWeekView() {
        const weekStart = getWeekStart(currentDate);
        let html = '<div class="calendar-week-view">';
        
        for (let i = 0; i < 7; i++) {
            const dayDate = new Date(weekStart);
            dayDate.setDate(weekStart.getDate() + i);
            html += createWeekDayCell(dayDate);
        }
        
        html += '</div>';
        return html;
    }

    // Vue jour
    function generateDayView() {
        return `<div class="calendar-day-view">${createSingleDayCell(currentDate)}</div>`;
    }

    // Créer une cellule de jour pour la vue mois
    function createDayCell(dayNumber, isOtherMonth) {
        const today = new Date();
        const isToday = !isOtherMonth && 
            dayNumber === today.getDate() && 
            currentDate.getMonth() === today.getMonth() && 
            currentDate.getFullYear() === today.getFullYear();
        
        const events = isOtherMonth ? [] : getEventsForDay(dayNumber);
        const eventsHtml = events.map(event => 
            `<div class="calendar-event ${event.type}-${event.status || 'project'}" onclick="window.location.href='${event.url}'" title="${event.name}">${event.name}</div>`
        ).join('');
        
        return `
            <div class="calendar-day-cell ${isOtherMonth ? 'other-month' : ''} ${isToday ? 'today' : ''}">
                <div class="calendar-day-number">${dayNumber}</div>
                <div class="calendar-events">${eventsHtml}</div>
            </div>
        `;
    }

    // Créer une cellule de jour pour la vue semaine
    function createWeekDayCell(date) {
        const today = new Date();
        const isToday = date.toDateString() === today.toDateString();
        const events = getEventsForDate(date);
        const eventsHtml = events.map(event => 
            `<div class="calendar-event ${event.type}-${event.status || 'project'}" onclick="window.location.href='${event.url}'" title="${event.name}">${event.name}</div>`
        ).join('');
        
        return `
            <div class="calendar-week-day ${isToday ? 'today' : ''}">
                <div class="calendar-day-number">${date.getDate()}</div>
                <div class="calendar-events">${eventsHtml}</div>
            </div>
        `;
    }

    // Créer une cellule de jour pour la vue jour
    function createSingleDayCell(date) {
        const today = new Date();
        const isToday = date.toDateString() === today.toDateString();
        const events = getEventsForDate(date);
        const eventsHtml = events.map(event => 
            `<div class="calendar-event ${event.type}-${event.status || 'project'}" onclick="window.location.href='${event.url}'" title="${event.name}">${event.name}</div>`
        ).join('');
        
        return `
            <div class="calendar-single-day ${isToday ? 'today' : ''}">
                <div class="calendar-day-number">${date.getDate()}</div>
                <div class="calendar-events">${eventsHtml}</div>
            </div>
        `;
    }

    // Obtenir les événements pour un jour spécifique
    function getEventsForDay(day) {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1;
        const dateStr = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
        return getEventsForDateString(dateStr);
    }

    // Obtenir les événements pour une date
    function getEventsForDate(date) {
        const year = date.getFullYear();
        const month = date.getMonth() + 1;
        const day = date.getDate();
        const dateStr = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
        return getEventsForDateString(dateStr);
    }

    // Obtenir les événements pour une date string
    function getEventsForDateString(dateStr) {
        const events = [];
        
        // Projets
        calendarData.projects.forEach(project => {
            if (project.startDate === dateStr || (project.endDate && project.endDate === dateStr)) {
                events.push({...project, type: 'project'});
            }
        });
        
        // Tâches
        calendarData.tasks.forEach(task => {
            const taskDate = task.date.split(' ')[0];
            if (taskDate === dateStr) {
                events.push({...task, type: 'task'});
            }
        });
        
        return events.slice(0, 5); // Limiter à 5 événements
    }

    // Obtenir le début de la semaine (lundi)
    function getWeekStart(date) {
        const day = date.getDay();
        const diff = date.getDate() - day + (day === 0 ? -6 : 1);
        return new Date(date.setDate(diff));
    }

    // Navigation
    function navigateCalendar(direction) {
        if (currentView === 'month') {
            currentDate.setMonth(currentDate.getMonth() + direction);
        } else if (currentView === 'week') {
            currentDate.setDate(currentDate.getDate() + (direction * 7));
        } else if (currentView === 'day') {
            currentDate.setDate(currentDate.getDate() + direction);
        }
        generateCalendar();
    }

    // Aller à aujourd'hui
    function goToToday() {
        currentDate = new Date();
        generateCalendar();
    }

    // Changer de vue
    function changeView(view) {
        currentView = view;
        
        // Mettre à jour les boutons
        document.querySelectorAll('[id^="btn-"]').forEach(btn => {
            btn.classList.remove('active', 'bg-blue-100', 'text-blue-700');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        });
        
        const activeBtn = document.getElementById(`btn-${view}`);
        activeBtn.classList.add('active', 'bg-blue-100', 'text-blue-700');
        activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
        
        generateCalendar();
    }

    // Initialiser
    document.addEventListener('DOMContentLoaded', function() {
        generateCalendar();
    });
</script>

@endsection