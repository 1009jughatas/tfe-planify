<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Header avec logo -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-building text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Planify</span>
                    </div>
                    <div class="hidden sm:block">
                        <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                            Inscription indépendant
                        </a>
                    </div>
                    <!-- Bouton mobile -->
                    <div class="sm:hidden">
                        <a href="{{ route('register') }}" class="text-xs bg-gray-100 text-gray-700 px-3 py-1 rounded-full hover:bg-gray-200 transition-colors">
                            Indépendant
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-5xl">
                <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10">
                    <!-- En-tête -->
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-building text-white text-xl sm:text-2xl"></i>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Créer votre entreprise</h2>
                        <p class="text-sm sm:text-base text-gray-600 mt-2 max-w-2xl mx-auto">
                            Rejoignez des milliers d'équipes qui font confiance à Planify pour gérer leurs projets
                        </p>
                    </div>

                    <form method="POST" action="{{ route('entreprise.register') }}" class="space-y-6 sm:space-y-8">
                        @csrf

                        <!-- Informations de l'entreprise -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 sm:p-6 border border-blue-100">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-building text-white text-sm"></i>
                                </div>
                                Informations de l'entreprise
                            </h3>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nom de l'entreprise *
                                    </label>
                                    <input type="text" 
                                           name="company_name" 
                                           id="company_name" 
                                           value="{{ old('company_name') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('company_name') border-red-300 ring-2 ring-red-200 @enderror"
                                           placeholder="Ex: Mon Entreprise SARL"
                                           required>
                                    @error('company_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="company_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email de l'entreprise *
                                    </label>
                                    <input type="email" 
                                           name="company_email" 
                                           id="company_email" 
                                           value="{{ old('company_email') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('company_email') border-red-300 ring-2 ring-red-200 @enderror"
                                           placeholder="contact@monentreprise.com"
                                           required>
                                    @error('company_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informations du responsable -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 sm:p-6 border border-green-100">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user-tie text-white text-sm"></i>
                                </div>
                                Vos informations (Responsable)
                            </h3>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Votre nom complet *
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('name') border-red-300 ring-2 ring-red-200 @enderror"
                                           placeholder="Jean Dupont"
                                           required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Votre email *
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           value="{{ old('email') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('email') border-red-300 ring-2 ring-red-200 @enderror"
                                           placeholder="jean.dupont@monentreprise.com"
                                           required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mot de passe *
                                    </label>
                                    <input type="password" 
                                           name="password" 
                                           id="password"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('password') border-red-300 ring-2 ring-red-200 @enderror"
                                           placeholder="Minimum 8 caractères"
                                           required>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirmer le mot de passe *
                                    </label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                           placeholder="Répétez votre mot de passe"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!-- Choix du plan -->
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-4 sm:p-6 border border-yellow-100">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-yellow-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-crown text-white text-sm"></i>
                                </div>
                                Choisissez votre plan d'abonnement
                            </h3>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                                <!-- Plan Starter -->
                                <label class="relative">
                                    <input type="radio" name="plan" value="starter" class="sr-only" {{ old('plan') === 'starter' ? 'checked' : '' }}>
                                    <div class="border-2 border-gray-200 rounded-xl p-4 sm:p-6 cursor-pointer hover:border-blue-300 transition-all plan-card bg-white shadow-sm">
                                        <div class="text-center">
                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Starter</h4>
                                            <div class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">399€<span class="text-sm font-normal text-gray-500">/mois</span></div>
                                            <p class="text-sm text-gray-600 mb-4">Parfait pour les petites équipes</p>
                                        </div>
                                        <ul class="space-y-2 text-sm text-gray-600">
                                            <li class="flex items-center">
                                                <i class="fas fa-check text-green-500 mr-2"></i>
                                                Jusqu'à 10 utilisateurs
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-check text-green-500 mr-2"></i>
                                                Projets illimités
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-check text-green-500 mr-2"></i>
                                                Support email
                                            </li>
                                        </ul>
                                    </div>
                                </label>

                                <!-- Plan Professional -->
                                <label class="relative">
                                    <input type="radio" name="plan" value="professional" class="sr-only" {{ old('plan') === 'professional' ? 'checked' : '' }}>
                                    <div class="border-2 border-blue-500 rounded-xl p-4 sm:p-6 cursor-pointer plan-card relative bg-white shadow-lg">
                                        <div class="absolute -top-2 sm:-top-3 left-1/2 transform -translate-x-1/2">
                                            <span class="bg-blue-500 text-white px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">
                                                Populaire
                                            </span>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Professional</h4>
                                            <div class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">599€<span class="text-sm font-normal text-gray-500">/mois</span></div>
                                            <p class="text-sm text-gray-600 mb-4">Idéal pour les équipes moyennes</p>
                                        </div>
                                        <ul class="space-y-2 text-sm text-gray-600">
                                            <li class="flex items-center">
                                                <i class="fas fa-check text-green-500 mr-2"></i>
                                                Jusqu'à 20 utilisateurs
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-check text-green-500 mr-2"></i>
                                                Projets illimités
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-check text-green-500 mr-2"></i>
                                                Support prioritaire
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-check text-green-500 mr-2"></i>
                                                Rapports avancés
                                            </li>
                                        </ul>
                                    </div>
                                </label>

                                <!-- Plan Enterprise -->
                                <label class="relative">
                                    <input type="radio" name="plan" value="enterprise" class="sr-only" {{ old('plan') === 'enterprise' ? 'checked' : '' }}>
                                    <div class="border-2 border-gray-200 rounded-xl p-4 sm:p-6 cursor-pointer hover:border-purple-300 transition-all plan-card bg-white shadow-sm">
                                        <div class="text-center">
                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Enterprise</h4>
                                            <div class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">999€<span class="text-sm font-normal text-gray-500">/mois</span></div>
                                            <p class="text-sm text-gray-600 mb-4">Pour les grandes organisations</p>
                                        </div>
                                        <ul class="space-y-2 text-sm text-gray-600">
                                            <li class="flex items-center">
                                                <i class="fas fa-check text-green-500 mr-2"></i>
                                                Utilisateurs illimités
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-check text-green-500 mr-2"></i>
                                                Projets illimités
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-check text-green-500 mr-2"></i>
                                                Support 24/7
                                            </li>
                                            <li class="flex items-center">
                                                <i class="fas fa-check text-green-500 mr-2"></i>
                                                API personnalisée
                                            </li>
                                        </ul>
                                    </div>
                                </label>
                            </div>
                            
                            @error('plan')
                                <p class="mt-4 text-sm text-red-600 text-center">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="text-center pt-4">
                            <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-4 px-8 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-credit-card mr-2"></i>
                                Créer mon équipe et payer
                            </button>
                            <p class="text-xs sm:text-sm text-gray-500 mt-4 max-w-md mx-auto">
                                En créant votre entreprise, vous acceptez nos 
                                <a href="#" class="text-blue-600 hover:underline">conditions d'utilisation</a> 
                                et notre 
                                <a href="#" class="text-blue-600 hover:underline">politique de confidentialité</a>
                            </p>
                        </div>
                    </form>

                    <!-- Lien vers inscription indépendant -->
                    <div class="mt-6 sm:mt-8 text-center border-t border-gray-200 pt-6">
                        <p class="text-sm text-gray-600">
                            Vous êtes un utilisateur indépendant ? 
                            <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">
                                Créer un compte gratuit
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .plan-card {
            transition: all 0.3s ease;
            min-height: 280px;
        }
        
        input[type="radio"]:checked + .plan-card {
            border-color: #3B82F6;
            background-color: #EFF6FF;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        }
        
        input[type="radio"]:checked + .plan-card h4 {
            color: #1D4ED8;
        }

        /* Amélioration pour mobile */
        @media (max-width: 640px) {
            .plan-card {
                min-height: auto;
            }
            
            .plan-card ul {
                font-size: 0.875rem;
            }
        }

        /* Animation pour les inputs */
        input:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        /* Amélioration des gradients */
        .bg-gradient-to-br {
            background-attachment: fixed;
        }
    </style>
</x-guest-layout>
