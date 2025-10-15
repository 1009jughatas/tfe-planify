<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-crown text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Premium</h1>
                    <p class="text-sm text-gray-600 mt-1">Débloquez toutes les fonctionnalités</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('dashboard') }}" class="btn-secondary-modern">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Messages de succès/erreur -->
            @if (session('success'))
                <div class="mb-8 bg-green-50 border border-green-200 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full mb-6 shadow-lg">
                    <i class="fas fa-crown text-white text-3xl"></i>
                </div>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Passez à <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-orange-500">Premium</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Accédez à toutes les fonctionnalités avancées et maximisez votre productivité
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <!-- Feature 1 -->
                <div class="modern-card text-center group hover:shadow-xl transition-all duration-300">
                    <div class="modern-card-body">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-infinity text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Projets Illimités</h3>
                        <p class="text-gray-600">Créez autant de projets que vous le souhaitez sans aucune limite</p>
                    </div>
                </div>


                <!-- Feature 3 -->
                <div class="modern-card text-center group hover:shadow-xl transition-all duration-300">
                    <div class="modern-card-body">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-chart-line text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Analyses Détaillées</h3>
                        <p class="text-gray-600">Suivez vos performances avec des rapports et statistiques avancés</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="modern-card text-center group hover:shadow-xl transition-all duration-300">
                    <div class="modern-card-body">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-download text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Export de Données</h3>
                        <p class="text-gray-600">Exportez vos projets et données dans différents formats</p>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="modern-card text-center group hover:shadow-xl transition-all duration-300">
                    <div class="modern-card-body">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-palette text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Personnalisation</h3>
                        <p class="text-gray-600">Personnalisez votre interface selon vos préférences</p>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="modern-card text-center group hover:shadow-xl transition-all duration-300">
                    <div class="modern-card-body">
                        <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-headset text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Support Prioritaire</h3>
                        <p class="text-gray-600">Bénéficiez d'un support client prioritaire et dédié</p>
                    </div>
                </div>
            </div>

            <!-- Pricing Card -->
            <div class="max-w-md mx-auto">
                <div class="modern-card text-center relative overflow-hidden">
                    <!-- Premium Badge -->
                    <div class="absolute top-0 right-0 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-4 py-2 text-sm font-semibold transform rotate-12 translate-x-2 -translate-y-2">
                        POPULAIRE
                    </div>
                    
                    <div class="modern-card-body pt-12">
                        <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <i class="fas fa-crown text-white text-3xl"></i>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Plan Premium</h3>
                        <p class="text-gray-600 mb-6">Accès complet à toutes les fonctionnalités</p>
                        
                        <div class="mb-8">
                            <div class="flex items-center justify-center mb-2">
                                <span class="text-5xl font-bold text-gray-900">99</span>
                                <span class="text-2xl text-gray-500 ml-2">€</span>
                                <span class="text-gray-500 ml-2">/ mois</span>
                            </div>
                            <p class="text-sm text-gray-500">Facturation mensuelle</p>
                        </div>
                        
                        <!-- Feature List -->
                        <div class="space-y-3 mb-8 text-left">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-700">Projets illimités</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-700">Analyses et rapports</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-700">Export de données</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-700">Personnalisation complète</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-700">Support prioritaire</span>
                            </div>
                        </div>
                        
                        <!-- CTA Button -->
                        <form action="{{ route('premium.purchase') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full btn-primary-modern text-lg py-4 relative overflow-hidden group">
                                <span class="relative z-10 flex items-center justify-center">
                                    <i class="fas fa-crown mr-2"></i>
                                    Passer à Premium
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </button>
                        </form>
                        
                        <p class="text-xs text-gray-500 mt-4">
                            Annulation possible à tout moment • Paiement sécurisé par Stripe
                        </p>
                    </div>
                </div>
            </div>

            <!-- Trust Indicators -->
            <div class="text-center mt-12">
                <div class="flex items-center justify-center space-x-8 text-gray-400">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span class="text-sm">Paiement sécurisé</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-lock mr-2"></i>
                        <span class="text-sm">Données protégées</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-headset mr-2"></i>
                        <span class="text-sm">Support 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Premium Page Specific Styles */
        .modern-card {
            @apply bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300;
        }
        
        .modern-card-body {
            @apply p-8;
        }
        
        .btn-primary-modern {
            @apply bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 border-0;
        }
        
        .btn-secondary-modern {
            @apply bg-white text-gray-700 font-medium px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200;
        }
        
        /* Gradient text animation */
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .gradient-text {
            background: linear-gradient(-45deg, #f59e0b, #f97316, #f59e0b, #f97316);
            background-size: 400% 400%;
            animation: gradientShift 3s ease infinite;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</x-app-layout>