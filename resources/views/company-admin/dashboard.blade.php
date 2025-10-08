@extends('company-admin.layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
<!-- Statistiques générales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Utilisateurs -->
    <div class="modern-card hover-lift">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Utilisateurs</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Membres de l'équipe</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Projets actifs -->
    <div class="modern-card hover-lift">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Projets actifs</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['active_projects'] }}</p>
                <p class="text-sm text-gray-500 mt-1">En cours de réalisation</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-folder-open text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Tâches en retard -->
    <div class="modern-card hover-lift">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Tâches en retard</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['overdue_tasks'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Nécessitent attention</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total tâches -->
    <div class="modern-card hover-lift">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total tâches</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_tasks'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Dans tous les projets</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-tasks text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Graphiques -->
    <div class="lg:col-span-2">
        <div class="modern-card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Répartition des projets</h3>
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
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Utilisateurs récents</h3>
        <div class="space-y-4">
            @forelse($recentUsers as $user)
                <div class="flex items-center space-x-3">
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
                <p class="text-gray-500 text-sm">Aucun utilisateur récent</p>
            @endforelse
        </div>
        
        @if($recentUsers->count() > 0)
            <div class="mt-6">
                <a href="{{ route('company-admin.users') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Voir tous les utilisateurs <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Dernières activités -->
<div class="mt-8">
    <div class="modern-card">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Dernières activités</h3>
        <div class="space-y-4">
            @forelse($recentActivities as $activity)
                <div class="flex items-start space-x-3">
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
                <p class="text-gray-500 text-sm">Aucune activité récente</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="mt-8">
    <div class="modern-card">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Actions rapides</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('company-admin.users') }}" class="btn-secondary-modern text-center">
                <i class="fas fa-user-plus mb-2"></i>
                <span>Inviter un utilisateur</span>
            </a>
            <a href="{{ route('projects.create') }}" class="btn-secondary-modern text-center">
                <i class="fas fa-folder-plus mb-2"></i>
                <span>Créer un projet</span>
            </a>
            <a href="{{ route('company-admin.projects') }}" class="btn-secondary-modern text-center">
                <i class="fas fa-eye mb-2"></i>
                <span>Voir tous les projets</span>
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
