@extends('company-admin.layouts.app')

@section('title', 'Abonnement')
@section('page-title', 'Abonnement')

@section('content')
<!-- En-tête -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Gestion de l'abonnement</h2>
    <p class="text-gray-600 mt-1">Gérez votre plan d'abonnement et vos paiements</p>
</div>

<!-- Plan actuel -->
<div class="modern-card mb-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Plan actuel</h3>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
            <i class="fas fa-check-circle mr-2"></i>
            Actif
        </span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-building text-white text-2xl"></i>
            </div>
            <h4 class="text-xl font-bold text-gray-900">{{ $company->name }}</h4>
            <p class="text-gray-600">Votre entreprise</p>
        </div>
        
        <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <h4 class="text-xl font-bold text-gray-900">
                {{ $company->user_limit === null ? 'Illimité' : $company->user_limit }}
            </h4>
            <p class="text-gray-600">Utilisateurs autorisés</p>
        </div>
        
        <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-euro-sign text-white text-2xl"></i>
            </div>
            <h4 class="text-xl font-bold text-gray-900">{{ $company->monthly_price }}€</h4>
            <p class="text-gray-600">Par mois</p>
        </div>
    </div>
    
    <div class="mt-6 pt-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Prochain paiement</p>
                <p class="text-lg font-semibold text-gray-900">{{ now()->addMonth()->format('d/m/Y') }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Utilisateurs actuels</p>
                <p class="text-lg font-semibold text-gray-900">{{ $company->users()->count() }} / {{ $company->user_limit ?? '∞' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Plans disponibles -->
<div class="mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Changer de plan</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Plan Starter -->
        <div class="modern-card {{ $company->user_limit == 10 ? 'ring-2 ring-blue-500' : '' }}">
            <div class="text-center mb-6">
                <h4 class="text-xl font-bold text-gray-900">Starter</h4>
                <p class="text-gray-600">Parfait pour les petites équipes</p>
                <div class="mt-4">
                    <span class="text-4xl font-bold text-gray-900">399€</span>
                    <span class="text-gray-600">/mois</span>
                </div>
            </div>
            
            <ul class="space-y-3 mb-6">
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Jusqu'à 10 utilisateurs</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Projets illimités</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Tâches illimitées</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Support email</span>
                </li>
            </ul>
            
            @if($company->user_limit != 10)
                <button onclick="changePlan(10, 399)" class="btn-primary-modern w-full">
                    Choisir ce plan
                </button>
            @else
                <button disabled class="btn-secondary-modern w-full cursor-not-allowed">
                    Plan actuel
                </button>
            @endif
        </div>
        
        <!-- Plan Professional -->
        <div class="modern-card {{ $company->user_limit == 20 ? 'ring-2 ring-blue-500' : '' }} relative">
            @if($company->user_limit != 20)
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                    <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                        Populaire
                    </span>
                </div>
            @endif
            
            <div class="text-center mb-6">
                <h4 class="text-xl font-bold text-gray-900">Professional</h4>
                <p class="text-gray-600">Idéal pour les équipes moyennes</p>
                <div class="mt-4">
                    <span class="text-4xl font-bold text-gray-900">599€</span>
                    <span class="text-gray-600">/mois</span>
                </div>
            </div>
            
            <ul class="space-y-3 mb-6">
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Jusqu'à 20 utilisateurs</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Projets illimités</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Tâches illimitées</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Support prioritaire</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Rapports avancés</span>
                </li>
            </ul>
            
            @if($company->user_limit != 20)
                <button onclick="changePlan(20, 599)" class="btn-primary-modern w-full">
                    Choisir ce plan
                </button>
            @else
                <button disabled class="btn-secondary-modern w-full cursor-not-allowed">
                    Plan actuel
                </button>
            @endif
        </div>
        
        <!-- Plan Enterprise -->
        <div class="modern-card {{ $company->user_limit === null ? 'ring-2 ring-blue-500' : '' }}">
            <div class="text-center mb-6">
                <h4 class="text-xl font-bold text-gray-900">Enterprise</h4>
                <p class="text-gray-600">Pour les grandes organisations</p>
                <div class="mt-4">
                    <span class="text-4xl font-bold text-gray-900">999€</span>
                    <span class="text-gray-600">/mois</span>
                </div>
            </div>
            
            <ul class="space-y-3 mb-6">
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Utilisateurs illimités</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Projets illimités</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Tâches illimitées</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Support 24/7</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">API personnalisée</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-3"></i>
                    <span class="text-gray-700">Formation dédiée</span>
                </li>
            </ul>
            
            @if($company->user_limit !== null)
                <button onclick="changePlan(null, 999)" class="btn-primary-modern w-full">
                    Choisir ce plan
                </button>
            @else
                <button disabled class="btn-secondary-modern w-full cursor-not-allowed">
                    Plan actuel
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Historique des paiements -->
<div class="modern-card">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Historique des paiements</h3>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facture</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Paiement actuel -->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ now()->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $company->monthly_price }}€
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($company->user_limit === null)
                            Enterprise (Illimité)
                        @elseif($company->user_limit == 20)
                            Professional (20 utilisateurs)
                        @else
                            Starter (10 utilisateurs)
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            Payé
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-download mr-1"></i>
                            Télécharger
                        </button>
                    </td>
                </tr>
                
                <!-- Exemple de paiements précédents -->
                @for($i = 1; $i <= 3; $i++)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ now()->subMonths($i)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $company->monthly_price }}€
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($company->user_limit === null)
                            Enterprise (Illimité)
                        @elseif($company->user_limit == 20)
                            Professional (20 utilisateurs)
                        @else
                            Starter (10 utilisateurs)
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            Payé
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-download mr-1"></i>
                            Télécharger
                        </button>
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

<!-- Informations de facturation -->
<div class="modern-card mt-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Informations de facturation</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-3">Méthode de paiement</h4>
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center">
                    <i class="fas fa-credit-card text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">**** **** **** 4242</p>
                    <p class="text-xs text-gray-500">Expire le 12/25</p>
                </div>
                <button class="text-blue-600 hover:text-blue-900 text-sm">
                    Modifier
                </button>
            </div>
        </div>
        
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-3">Adresse de facturation</h4>
            <div class="text-sm text-gray-900">
                <p>{{ $company->name }}</p>
                @if($company->address)
                    <p>{{ $company->address }}</p>
                @endif
                <button class="text-blue-600 hover:text-blue-900 text-sm mt-1">
                    Modifier
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function changePlan(userLimit, price) {
    const planName = userLimit === null ? 'Enterprise' : 
                    userLimit === 20 ? 'Professional' : 'Starter';
    
    if (confirm(`Êtes-vous sûr de vouloir changer pour le plan ${planName} (${price}€/mois) ?\n\nCette modification prendra effet lors du prochain cycle de facturation.`)) {
        // TODO: Implémenter le changement de plan via Stripe
        alert('Fonctionnalité de changement de plan à implémenter avec Stripe');
    }
}
</script>
@endpush
