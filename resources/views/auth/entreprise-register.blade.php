<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
        <div class="w-full sm:max-w-4xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <!-- En-tête -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-building text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Créer votre entreprise</h2>
                <p class="text-gray-600 mt-2">Rejoignez des milliers d'équipes qui font confiance à Planify</p>
            </div>

            <form method="POST" action="{{ route('entreprise.register') }}" class="space-y-8">
                @csrf

                <!-- Informations de l'entreprise -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-building text-blue-600 mr-2"></i>
                        Informations de l'entreprise
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom de l'entreprise *
                            </label>
                            <input type="text" 
                                   name="company_name" 
                                   id="company_name" 
                                   value="{{ old('company_name') }}"
                                   class="input-modern @error('company_name') border-red-300 @enderror"
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
                                   class="input-modern @error('company_email') border-red-300 @enderror"
                                   placeholder="contact@monentreprise.com"
                                   required>
                            @error('company_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informations du responsable -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-tie text-green-600 mr-2"></i>
                        Vos informations (Responsable)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Votre nom complet *
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   class="input-modern @error('name') border-red-300 @enderror"
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
                                   class="input-modern @error('email') border-red-300 @enderror"
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
                                   class="input-modern @error('password') border-red-300 @enderror"
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
                                   class="input-modern"
                                   placeholder="Répétez votre mot de passe"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Choix du plan -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-crown text-yellow-600 mr-2"></i>
                        Choisissez votre plan d'abonnement
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Plan Starter -->
                        <label class="relative">
                            <input type="radio" name="plan" value="starter" class="sr-only" {{ old('plan') === 'starter' ? 'checked' : '' }}>
                            <div class="border-2 border-gray-200 rounded-lg p-6 cursor-pointer hover:border-blue-300 transition-colors plan-card">
                                <div class="text-center">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Starter</h4>
                                    <div class="text-3xl font-bold text-gray-900 mb-2">399€<span class="text-sm font-normal text-gray-500">/mois</span></div>
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
                            <div class="border-2 border-blue-500 rounded-lg p-6 cursor-pointer plan-card relative">
                                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                    <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        Populaire
                                    </span>
                                </div>
                                <div class="text-center">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Professional</h4>
                                    <div class="text-3xl font-bold text-gray-900 mb-2">599€<span class="text-sm font-normal text-gray-500">/mois</span></div>
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
                            <div class="border-2 border-gray-200 rounded-lg p-6 cursor-pointer hover:border-purple-300 transition-colors plan-card">
                                <div class="text-center">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Enterprise</h4>
                                    <div class="text-3xl font-bold text-gray-900 mb-2">999€<span class="text-sm font-normal text-gray-500">/mois</span></div>
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
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bouton de soumission -->
                <div class="text-center">
                    <button type="submit" class="btn-primary-modern text-lg px-8 py-4">
                        <i class="fas fa-credit-card mr-2"></i>
                        Créer mon équipe et payer
                    </button>
                    <p class="text-sm text-gray-500 mt-4">
                        En créant votre entreprise, vous acceptez nos 
                        <a href="#" class="text-blue-600 hover:underline">conditions d'utilisation</a> 
                        et notre 
                        <a href="#" class="text-blue-600 hover:underline">politique de confidentialité</a>
                    </p>
                </div>
            </form>

            <!-- Lien vers inscription indépendant -->
            <div class="mt-8 text-center border-t border-gray-200 pt-6">
                <p class="text-gray-600">
                    Vous êtes un utilisateur indépendant ? 
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">
                        Créer un compte gratuit
                    </a>
                </p>
            </div>
        </div>
    </div>

    <style>
        .plan-card {
            transition: all 0.3s ease;
        }
        
        input[type="radio"]:checked + .plan-card {
            border-color: #3B82F6;
            background-color: #EFF6FF;
        }
        
        input[type="radio"]:checked + .plan-card h4 {
            color: #1D4ED8;
        }
    </style>
</x-guest-layout>
