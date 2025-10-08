<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-shield-alt text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Panneau d'Administration</h1>
                    <p class="text-sm text-gray-600 mt-1">Vue d'ensemble complète de la plateforme Planify</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="badge-danger">Administrateur</span>
                <button class="btn-secondary-modern text-sm" onclick="window.print()">
                    <i class="fas fa-print mr-2"></i>
                    Imprimer
                </button>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Utilisateurs -->
            <div class="stats-card hover-lift border-l-4 border-l-blue-500">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Utilisateurs</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</p>
                        <div class="flex items-center space-x-2 mt-2">
                            <span class="badge-warning">{{ $stats['premium_users'] ?? 0 }} Premium</span>
                            <span class="badge-secondary">{{ $stats['free_users'] ?? 0 }} Gratuit</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span class="text-xs text-gray-500">+12% ce mois</span>
                    <i class="fas fa-arrow-up text-green-500 ml-2"></i>
                </div>
            </div>

            <!-- Projets -->
            <div class="stats-card hover-lift border-l-4 border-l-green-500">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Projets</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_projects'] ?? 0 }}</p>
                        <div class="flex items-center space-x-2 mt-2">
                            <span class="badge-primary">{{ $stats['active_projects'] ?? 0 }} Actifs</span>
                            <span class="badge-success">{{ $stats['completed_projects'] ?? 0 }} Terminés</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-project-diagram text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span class="text-xs text-gray-500">+8% ce mois</span>
                    <i class="fas fa-arrow-up text-green-500 ml-2"></i>
                </div>
            </div>

            <!-- Tâches -->
            <div class="stats-card hover-lift border-l-4 border-l-yellow-500">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Tâches</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_tasks'] ?? 0 }}</p>
                        <div class="flex items-center space-x-2 mt-2">
                            <span class="badge-secondary">{{ $stats['pending_tasks'] ?? 0 }} En attente</span>
                            <span class="badge-success">{{ $stats['completed_tasks'] ?? 0 }} Terminées</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-tasks text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span class="text-xs text-gray-500">+15% ce mois</span>
                    <i class="fas fa-arrow-up text-green-500 ml-2"></i>
                </div>
            </div>

            <!-- Conversion Premium -->
            <div class="stats-card hover-lift border-l-4 border-l-purple-500">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Conversion Premium</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $conversionRate ?? 0 }}%</p>
                        <div class="flex items-center space-x-2 mt-2">
                            <span class="badge-warning">{{ $stats['premium_users'] ?? 0 }}/{{ $stats['total_users'] ?? 0 }} Premium</span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span class="text-xs text-gray-500">+3% ce mois</span>
                    <i class="fas fa-arrow-up text-green-500 ml-2"></i>
                </div>
            </div>
        </div>


        <!-- Graphiques et analyses -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Graphique des inscriptions -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-area text-primary-600 mr-2"></i>
                        Inscriptions des 30 derniers jours
                    </h3>
                </div>
                <div class="modern-card-body">
                    <div class="h-64 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-chart-area text-blue-400 text-4xl mb-4"></i>
                            <p class="text-blue-600 font-medium">Graphique des inscriptions</p>
                            <p class="text-sm text-blue-500">Intégration Chart.js recommandée</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Répartition des utilisateurs -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-pie text-primary-600 mr-2"></i>
                        Répartition des utilisateurs
                    </h3>
                </div>
                <div class="modern-card-body">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-500 rounded-full mr-3"></div>
                                <span class="text-sm font-medium text-gray-700">Utilisateurs Gratuits</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $stats['free_users'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3"></div>
                                <span class="text-sm font-medium text-gray-700">Utilisateurs Premium</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $stats['premium_users'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                                <span class="text-sm font-medium text-gray-700">Administrateurs</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $stats['admin_users'] ?? 1 }}</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-yellow-500 h-2 rounded-full" style="width: {{ ($stats['premium_users'] ?? 0) / max(($stats['total_users'] ?? 1), 1) * 100 }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Taux de conversion Premium</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables récentes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Utilisateurs récents -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-user-clock text-primary-600 mr-2"></i>
                            Utilisateurs Récents
                        </h3>
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
                            Voir tout <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="modern-card-body p-0">
                    <div class="divide-y divide-gray-200">
                        @forelse($recentUsers ?? [] as $user)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-primary rounded-full flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if ($user->is_admin())
                                            <span class="badge-danger">Admin</span>
                                        @elseif ($user->is_premium())
                                            <span class="badge-warning">Premium</span>
                                        @else
                                            <span class="badge-secondary">Gratuit</span>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <i class="fas fa-users text-gray-300 text-3xl mb-4"></i>
                                <p class="text-gray-500">Aucun utilisateur récent</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Projets récents -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-folder-open text-primary-600 mr-2"></i>
                            Projets Récents
                        </h3>
                        <a href="{{ route('projects.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
                            Voir tout <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="modern-card-body p-0">
                    <div class="divide-y divide-gray-200">
                        @forelse($recentProjects ?? [] as $project)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-project-diagram text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ Str::limit($project->name, 25) }}</p>
                                            <p class="text-sm text-gray-500">{{ $project->author->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if ($project->status === 'active')
                                            <span class="badge-primary">Actif</span>
                                        @elseif ($project->status === 'completed')
                                            <span class="badge-success">Terminé</span>
                                        @else
                                            <span class="badge-secondary">{{ $project->status }}</span>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">{{ $project->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <i class="fas fa-folder-open text-gray-300 text-3xl mb-4"></i>
                                <p class="text-gray-500">Aucun projet récent</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations système -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-server text-primary-600 mr-2"></i>
                    Informations Système
                </h3>
            </div>
            <div class="modern-card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-database text-blue-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-gray-700">Base de données</p>
                        <p class="text-lg font-bold text-gray-900">MySQL</p>
                        <p class="text-xs text-gray-500">Connexion active</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-server text-green-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-gray-700">Serveur Web</p>
                        <p class="text-lg font-bold text-gray-900">Apache/Nginx</p>
                        <p class="text-xs text-gray-500">Opérationnel</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-clock text-purple-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-gray-700">Uptime</p>
                        <p class="text-lg font-bold text-gray-900">99.9%</p>
                        <p class="text-xs text-gray-500">Ce mois</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

