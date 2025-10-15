@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-credit-card text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestion de l'Abonnement</h1>
                    <p class="text-gray-600 mt-1 flex items-center">
                        <i class="fas fa-building mr-2 text-blue-500"></i>
                        {{ $company->name }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Plan actuel -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Plan Actuel</h2>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-crown text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $currentPlanData['name'] }}</h3>
                        <p class="text-gray-600">{{ $currentPlanData['price'] }}€/mois</p>
                        <!-- Debug: Afficher les données pour vérification -->
                        <div class="text-xs text-gray-400 mt-1">
                            Debug: Plan={{ $company->plan }}, Prix={{ $company->monthly_price }}, MaxUsers={{ $company->max_users }}<br>
                            CurrentPlanData: {{ json_encode($currentPlanData) }}
                        </div>
                        <p class="text-sm text-gray-500">
                            @if($currentPlanData['max_users'] === -1)
                                Utilisateurs illimités
                            @else
                                Jusqu'à {{ $currentPlanData['max_users'] }} utilisateurs
                            @endif
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-gray-900">{{ $currentUsers }} / {{ $maxUsers }}</div>
                    <div class="text-sm text-gray-500">Utilisateurs actifs</div>
                    @if($usersRemaining > 0)
                        <div class="text-sm text-green-600">{{ $usersRemaining }} places restantes</div>
                    @else
                        <div class="text-sm text-red-600">Limite atteinte</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Fonctionnalités du plan actuel -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Fonctionnalités de votre plan</h2>
            <p class="text-sm text-gray-600">Voici ce qui est inclus dans votre abonnement {{ $currentPlanData['name'] }}</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($currentPlanData['features'] as $feature)
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm text-gray-700">{{ $feature }}</span>
                    </div>
                @endforeach
            </div>
            
            <!-- Informations détaillées de l'abonnement -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h5 class="text-sm font-semibold text-gray-700 mb-3">Détails de votre abonnement :</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <p><strong>Plan:</strong> {{ $currentPlanData['name'] }}</p>
                        <p><strong>Prix:</strong> {{ $currentPlanData['price'] }}€/mois</p>
                        <p><strong>Utilisateurs:</strong> 
                            @if($currentPlanData['max_users'] === -1)
                                Illimités
                            @else
                                Jusqu'à {{ $currentPlanData['max_users'] }} utilisateurs
                            @endif
                        </p>
                    </div>
                    <div>
                        @if($company->created_at)
                            <p><strong>Date d'inscription:</strong> {{ $company->created_at->format('d/m/Y') }}</p>
                        @endif
                        @if($company->stripe_subscription_id)
                            <p><strong>Statut:</strong> <span class="text-green-600">Actif</span></p>
                            <p><strong>Prochaine facturation:</strong> {{ now()->addMonth()->format('d/m/Y') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques d'utilisation -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="stats-card hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Utilisateurs Actifs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $currentUsers }}</p>
                    <p class="text-xs text-gray-500 mt-1">Sur {{ $maxUsers }} autorisés</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="stats-card hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Projets Créés</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalProjects }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activeProjects }} actifs</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-project-diagram text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="stats-card hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Tâches Créées</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalTasks }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $completedTasks }} terminées</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-tasks text-white text-lg"></i>
                </div>
            </div>
        </div>

        <div class="stats-card hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Taux d'Utilisation</p>
                    <p class="text-3xl font-bold text-gray-900">{{ round(($currentUsers / $maxUsers) * 100) }}%</p>
                    <p class="text-xs text-gray-500 mt-1">Utilisateurs</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-chart-pie text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Plans disponibles -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Plans Disponibles</h2>
            <p class="text-sm text-gray-600">Choisissez le plan qui correspond le mieux aux besoins de votre entreprise</p>
            <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                <div class="flex items-start space-x-2">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    <div class="text-sm text-blue-700">
                        <strong>💳 Paiement sécurisé :</strong> 
                        @if($company->stripe_customer_id)
                            Vous serez redirigé vers le portail client Stripe pour gérer votre abonnement. 
                            Les changements prendront effet à la prochaine facturation.
                        @else
                            Vous serez redirigé vers Stripe pour effectuer le paiement de manière sécurisée. 
                            Votre abonnement sera activé automatiquement après validation du paiement.
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($plans as $planKey => $plan)
                    <div class="relative border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-200 {{ $planKey === $currentPlan ? 'border-purple-500 bg-purple-50' : '' }}">
                        @if($planKey === $currentPlan)
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-500 text-white">
                                    Plan actuel
                                </span>
                            </div>
                        @endif
                        
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-bold text-gray-900">{{ $plan['name'] }}</h3>
                            <div class="mt-2">
                                <span class="text-4xl font-bold text-gray-900">{{ $plan['price'] }}€</span>
                                <span class="text-gray-500">/mois</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                @if($plan['max_users'] === -1)
                                    Utilisateurs illimités
                                @else
                                    Jusqu'à {{ $plan['max_users'] }} utilisateurs
                                @endif
                            </p>
                        </div>

                        <div class="space-y-3 mb-6">
                            @foreach($plan['features'] as $feature)
                                <div class="flex items-center space-x-3">
                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>

                        @if($planKey !== $currentPlan)
                            @if($planKey === 'starter' && !$canDowngrade)
                                <button disabled class="w-full btn-secondary-modern opacity-50 cursor-not-allowed">
                                    <i class="fas fa-lock mr-2"></i>
                                    Trop d'utilisateurs
                                </button>
                            @elseif($planKey !== 'starter' && $plan['max_users'] !== -1 && $currentUsers > $plan['max_users'])
                                <button disabled class="w-full btn-secondary-modern opacity-50 cursor-not-allowed">
                                    <i class="fas fa-lock mr-2"></i>
                                    Trop d'utilisateurs
                                </button>
                            @else
                                <form method="POST" action="{{ route('entreprise.abonnement.update') }}" class="w-full">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="plan" value="{{ $planKey }}">
                                    <button type="submit" class="w-full btn-primary-modern">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        {{ $planKey === 'starter' ? 'Passer à' : 'Mettre à niveau vers' }} {{ $plan['name'] }}
                                        <span class="ml-2 text-sm">({{ $plan['price'] }}€/mois)</span>
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="w-full btn-secondary-modern cursor-default">
                                <i class="fas fa-check mr-2"></i>
                                Plan actuel
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Alertes et recommandations -->
    @if($needsUpgrade)
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-8">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-yellow-900 mb-1">⚠️ Limite d'utilisateurs atteinte</h3>
                    <p class="text-sm text-yellow-700 mb-3">
                        Vous avez atteint la limite de {{ $maxUsers }} utilisateurs pour votre plan {{ ucfirst($currentPlan) }}. 
                        Vous ne pouvez plus inviter de nouveaux employés.
                    </p>
                    <div class="flex items-center space-x-3">
                        <a href="#plans" class="btn-primary-modern">
                            <i class="fas fa-arrow-up mr-2"></i>
                            Mettre à niveau
                        </a>
                        <a href="{{ route('entreprise.utilisateurs.index') }}" class="btn-secondary-modern">
                            <i class="fas fa-users mr-2"></i>
                            Gérer l'équipe
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Actions de gestion -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Actions de Gestion</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations de facturation -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-receipt text-blue-500 mr-2"></i>
                        Informations de facturation
                    </h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>Plan:</strong> {{ ucfirst($currentPlanData['name']) }}</p>
                        <p><strong>Prix:</strong> {{ $currentPlanData['price'] }}€/mois</p>
                        <p><strong>Utilisateurs:</strong> 
                            @if($currentPlanData['max_users'] === -1)
                                Illimités
                            @else
                                Jusqu'à {{ $currentPlanData['max_users'] }} utilisateurs
                            @endif
                        </p>
                        @if($company->stripe_subscription_id)
                            <p><strong>Statut:</strong> <span class="text-green-600">Actif</span></p>
                            <p><strong>Prochaine facturation:</strong> {{ now()->addMonth()->format('d/m/Y') }}</p>
                            <p><strong>ID Abonnement:</strong> <code class="text-xs bg-gray-100 px-1 rounded">{{ $company->stripe_subscription_id }}</code></p>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-cog text-gray-500 mr-2"></i>
                        Actions
                    </h3>
                    <div class="space-y-3">
                        @if($company->stripe_customer_id)
                            <a href="{{ route('entreprise.abonnement.portal') }}" class="w-full btn-primary-modern">
                                <i class="fas fa-credit-card mr-2"></i>
                                Gérer mon abonnement
                            </a>
                        @endif
                        
                        @if($currentPlan !== 'starter')
                            <form method="POST" action="{{ route('entreprise.abonnement.cancel') }}" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler l\'abonnement ? Vous passerez au plan gratuit avec des limitations.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full btn-danger-modern">
                                    <i class="fas fa-times mr-2"></i>
                                    Annuler l'abonnement
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('entreprise.utilisateurs.index') }}" class="w-full btn-secondary-modern">
                            <i class="fas fa-users mr-2"></i>
                            Gérer l'équipe
                        </a>
                        
                        <a href="{{ route('entreprise.dashboard') }}" class="w-full btn-secondary-modern">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour au dashboard
                        </a>
                    </div>
                </div>
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
    
    .btn-primary-modern {
        @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200;
    }
    
    .btn-secondary-modern {
        @apply inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg shadow-sm border border-gray-300 hover:shadow-md hover:scale-105 transition-all duration-200;
    }
    
    .btn-danger-modern {
        @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 text-white font-medium rounded-lg shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200;
    }
</style>
@endsection
