@extends('company-admin.layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
<!-- Statistiques générales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Utilisateurs -->
    <div class="stats-card hover-lift">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Utilisateurs</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
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
                <p class="text-3xl font-bold text-gray-900">{{ $stats['active_projects'] }}</p>
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
                <p class="text-3xl font-bold text-gray-900">{{ $stats['overdue_tasks'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Attention requise</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-white text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Total tâches -->
    <div class="stats-card hover-lift">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total tâches</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_tasks'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Dans tous les projets</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-tasks text-white text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques et utilisateurs récents -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Graphiques -->
    <div class="lg:col-span-2">
        <div class="modern-card">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Répartition des projets</h3>
                    <p class="text-sm text-gray-600">Vue d'ensemble des projets de l'équipe</p>
                </div>
                <div class="flex space-x-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Terminés ({{ $stats['completed_projects'] }})</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Actifs ({{ $stats['active_projects'] }})</span>
                    </div>
                </div>
            </div>
            
            <!-- Graphique simple -->
            <div class="relative h-64">
                <canvas id="projectsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Utilisateurs récents -->
    <div class="modern-card">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Utilisateurs récents</h3>
                <p class="text-sm text-gray-600">Nouveaux membres de l'équipe</p>
            </div>
            @if($recentUsers->count() > 0)
                <a href="{{ route('company-admin.users') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Voir tout
                </a>
            @endif
        </div>
        
        <div class="space-y-4">
            @forelse($recentUsers as $user)
                <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        @if($user->role === 'company_admin')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Admin
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Membre
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-users text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm">Aucun utilisateur récent</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Dernières activités et Actions rapides -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Dernières activités -->
    <div class="modern-card">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Dernières activités</h3>
                <p class="text-sm text-gray-600">Activités récentes de l'équipe</p>
            </div>
        </div>
        <div class="space-y-4">
            @forelse($recentActivities as $activity)
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center">
                            <i class="{{ $activity['icon'] }} text-{{ $activity['color'] }}-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">{{ $activity['message'] }}</p>
                        <p class="text-xs text-gray-500">{{ $activity['time']->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-history text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm">Aucune activité récente</p>
                </div>
            @endforelse
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
            <a href="{{ route('company-admin.users') }}" class="flex items-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all group">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-user-plus text-white"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-900 group-hover:text-blue-700">Inviter un utilisateur</h4>
                    <p class="text-xs text-gray-500">Ajouter un nouveau membre à l'équipe</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600"></i>
            </a>
            
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
            
            <a href="{{ route('company-admin.projects') }}" class="flex items-center p-4 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all group">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-eye text-white"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-900 group-hover:text-purple-700">Voir tous les projets</h4>
                    <p class="text-xs text-gray-500">Gérer les projets de l'équipe</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 group-hover:text-purple-600"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('projectsChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Projets actifs', 'Projets terminés'],
            datasets: [{
                data: [{{ $stats['active_projects'] }}, {{ $stats['completed_projects'] }}],
                backgroundColor: ['#3B82F6', '#10B981'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
