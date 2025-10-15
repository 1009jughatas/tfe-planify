@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">💳 Gestion des Abonnements</h1>
                    <p class="mt-2 text-gray-600">Gérez tous les abonnements des entreprises</p>
                </div>
                <a href="{{ route('superadmin.dashboard') }}" class="btn-secondary-modern">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour au dashboard
                </a>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-blue-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Entreprises</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_companies'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Abonnements Actifs</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active_subscriptions'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-crown text-yellow-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Utilisateurs Premium</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['premium_users'] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-euro-sign text-purple-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Revenus Mensuels</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['monthly_revenue'] }}€</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Filtres</h2>
            </div>
            <div class="p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Plan</label>
                        <select name="plan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les plans</option>
                            <option value="starter" {{ request('plan') === 'starter' ? 'selected' : '' }}>Starter</option>
                            <option value="growth" {{ request('plan') === 'growth' ? 'selected' : '' }}>Growth</option>
                            <option value="enterprise" {{ request('plan') === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nom de l'entreprise..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full btn-primary-modern">
                            <i class="fas fa-search mr-2"></i>
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des abonnements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Abonnements</h2>
            </div>

            @if($companies->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($companies as $company)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-building text-blue-600"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $company->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $company->email }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($company->plan === 'enterprise') bg-purple-100 text-purple-800
                                            @elseif($company->plan === 'growth') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            @if($company->plan === 'enterprise')
                                                <i class="fas fa-crown mr-1"></i>Enterprise
                                            @elseif($company->plan === 'growth')
                                                <i class="fas fa-chart-line mr-1"></i>Growth
                                            @else
                                                <i class="fas fa-play mr-1"></i>Starter
                                            @endif
                                        </span>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($company->status === 'active') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            @if($company->status === 'active')
                                                <i class="fas fa-check mr-1"></i>Actif
                                            @else
                                                <i class="fas fa-times mr-1"></i>Inactif
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-euro-sign mr-1"></i>
                                            {{ $company->monthly_price ?? 0 }}€/mois
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-users mr-1"></i>
                                            @if($company->max_users === -1)
                                                Utilisateurs illimités
                                            @else
                                                {{ $company->max_users ?? 0 }} utilisateurs max
                                            @endif
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Créé le {{ $company->created_at->format('d/m/Y') }}
                                        </span>
                                        @if($company->stripe_customer_id)
                                            <span class="flex items-center text-green-600">
                                                <i class="fas fa-credit-card mr-1"></i>
                                                Stripe connecté
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2 ml-4">
                                    <button onclick="editSubscription({{ $company->id }})" class="btn-primary-modern text-sm">
                                        <i class="fas fa-edit mr-1"></i>
                                        Modifier
                                    </button>
                                    @if($company->status === 'active')
                                        <button onclick="cancelSubscription({{ $company->id }})" class="btn-secondary-modern text-sm">
                                            <i class="fas fa-ban mr-1"></i>
                                            Annuler
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $companies->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-building text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun abonnement trouvé</h3>
                    <p class="text-gray-600">Aucun abonnement ne correspond aux critères de recherche.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de modification d'abonnement -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Modifier l'abonnement</h3>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Plan</label>
                        <select name="plan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="starter">Starter</option>
                            <option value="growth">Growth</option>
                            <option value="enterprise">Enterprise</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix mensuel (€)</label>
                        <input type="number" name="monthly_price" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Limite d'utilisateurs</label>
                        <input type="number" name="max_users" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="-1 pour illimité">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="btn-secondary-modern">
                        Annuler
                    </button>
                    <button type="submit" class="btn-primary-modern">
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editSubscription(companyId) {
    document.getElementById('editForm').action = `/superadmin/abonnements/${companyId}`;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function cancelSubscription(companyId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cet abonnement ?')) {
        fetch(`/superadmin/abonnements/${companyId}/cancel`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de l\'annulation de l\'abonnement');
            }
        });
    }
}
</script>
@endsection
