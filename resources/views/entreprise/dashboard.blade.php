<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Entreprise
        </h2>
    </x-slot>
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Bienvenue dans votre espace entreprise</h1>
        <p class="text-gray-600 mt-2">Gérez votre équipe et vos projets efficacement</p>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Utilisateurs -->
        <div class="stats-card hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Utilisateurs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ Auth::user()->company->users()->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Membres de l'équipe</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Projets actifs -->
        <div class="stats-card hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Projets actifs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ Auth::user()->company->projects()->where('status', '!=', 'done')->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">En cours</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-folder-open text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Tâches en retard -->
        <div class="stats-card hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Tâches en retard</p>
                    <p class="text-3xl font-bold text-gray-900">{{ Auth::user()->company->projects()->with('tasks')->get()->flatMap->tasks->where('due_date', '<', now())->where('status', '!=', 'done')->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Attention requise</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Plan actuel -->
        <div class="stats-card hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Plan actuel</p>
                    <p class="text-3xl font-bold text-gray-900">{{ Auth::user()->company->plan }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ Auth::user()->company->monthly_price }}€/mois</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-crown text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Projets récents -->
        <div class="lg:col-span-2">
            <div class="modern-card">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Projets récents</h3>
                        <p class="text-sm text-gray-600">Derniers projets de votre équipe</p>
                    </div>
                    <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Voir tout
                    </a>
                </div>
                
                <div class="space-y-4">
                    @forelse(Auth::user()->company->projects()->latest()->limit(5)->get() as $project)
                        <div class="flex items-center space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-folder text-white"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 truncate">{{ $project->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $project->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-folder text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm">Aucun projet pour le moment</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="modern-card">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Actions rapides</h3>
                    <p class="text-sm text-gray-600">Accès direct aux fonctionnalités</p>
                </div>
            </div>
            <div class="space-y-3">
                <a href="{{ route('projects.create') }}" class="flex items-center p-4 rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-all group">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-folder-plus text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900 group-hover:text-green-700">Créer un projet</h4>
                        <p class="text-xs text-gray-500">Lancer un nouveau projet d'équipe</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-green-600"></i>
                </a>
                
                <a href="{{ route('projects.index') }}" class="flex items-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all group">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-eye text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900 group-hover:text-blue-700">Voir tous les projets</h4>
                        <p class="text-xs text-gray-500">Gérer les projets de l'équipe</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600"></i>
                </a>
                
                <a href="#" class="flex items-center p-4 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all group">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900 group-hover:text-purple-700">Gérer l'équipe</h4>
                        <p class="text-xs text-gray-500">Inviter et gérer les membres</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-purple-600"></i>
                </a>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
