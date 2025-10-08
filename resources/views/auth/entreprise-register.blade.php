<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Créer votre entreprise</h2>
        <p class="text-sm text-gray-600 mt-2">Rejoignez des milliers d'équipes qui font confiance à Planify</p>
        <!-- Lien vers inscription indépendant -->
        <div class="mt-4">
            <a href="{{ route('register') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                <i class="fas fa-user mr-1"></i>
                Inscription indépendant
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('entreprise.register') }}" class="space-y-6">
        @csrf

        <!-- Company Name -->
        <div>
            <x-input-label for="company_name" :value="__('Nom de l\'entreprise')" class="form-label-modern" />
            <x-text-input id="company_name" 
                         class="input-modern" 
                         type="text" 
                         name="company_name" 
                         :value="old('company_name')" 
                         required 
                         autofocus
                         placeholder="Ex: Mon Entreprise SARL" />
            <x-input-error :messages="$errors->get('company_name')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Company Email -->
        <div>
            <x-input-label for="company_email" :value="__('Email de l\'entreprise')" class="form-label-modern" />
            <x-text-input id="company_email" 
                         class="input-modern" 
                         type="email" 
                         name="company_email" 
                         :value="old('company_email')" 
                         required 
                         autocomplete="email"
                         placeholder="contact@monentreprise.com" />
            <x-input-error :messages="$errors->get('company_email')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Admin Name -->
        <div>
            <x-input-label for="name" :value="__('Votre nom complet')" class="form-label-modern" />
            <x-text-input id="name" 
                         class="input-modern" 
                         type="text" 
                         name="name" 
                         :value="old('name')" 
                         required 
                         autocomplete="name"
                         placeholder="Jean Dupont" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Admin Email -->
        <div>
            <x-input-label for="email" :value="__('Votre adresse email')" class="form-label-modern" />
            <x-text-input id="email" 
                         class="input-modern" 
                         type="email" 
                         name="email" 
                         :value="old('email')" 
                         required 
                         autocomplete="username"
                         placeholder="jean.dupont@monentreprise.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" class="form-label-modern" />
            <div class="relative">
                <x-text-input id="password" 
                             class="input-modern pr-10" 
                             type="password"
                             name="password"
                             required 
                             autocomplete="new-password"
                             placeholder="••••••••" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="form-label-modern" />
            <div class="relative">
                <x-text-input id="password_confirmation" 
                             class="input-modern pr-10" 
                             type="password"
                             name="password_confirmation" 
                             required 
                             autocomplete="new-password"
                             placeholder="••••••••" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Plan Selection -->
        <div>
            <x-input-label :value="__('Choisissez votre plan d\'abonnement')" class="form-label-modern" />
            <div class="grid grid-cols-1 gap-4 mt-2">
                <!-- Plan Starter -->
                <label class="relative">
                    <input type="radio" name="plan" value="starter" class="sr-only" {{ old('plan') === 'starter' ? 'checked' : '' }}>
                    <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-primary-300 transition-all plan-card">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900">Starter</h4>
                                <p class="text-sm text-gray-600">Parfait pour les petites équipes</p>
                                <ul class="mt-2 space-y-1 text-sm text-gray-600">
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                        Jusqu'à 10 utilisateurs
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                        Projets illimités
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                        Support email
                                    </li>
                                </ul>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900">399€</div>
                                <div class="text-sm text-gray-500">/mois</div>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Plan Professional -->
                <label class="relative">
                    <input type="radio" name="plan" value="professional" class="sr-only" {{ old('plan') === 'professional' ? 'checked' : '' }}>
                    <div class="border-2 border-primary-500 rounded-lg p-4 cursor-pointer plan-card relative">
                        <div class="absolute -top-2 left-4">
                            <span class="bg-primary-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                Populaire
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900">Professional</h4>
                                <p class="text-sm text-gray-600">Idéal pour les équipes moyennes</p>
                                <ul class="mt-2 space-y-1 text-sm text-gray-600">
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                        Jusqu'à 20 utilisateurs
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                        Projets illimités
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                        Support prioritaire
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                        Rapports avancés
                                    </li>
                                </ul>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900">599€</div>
                                <div class="text-sm text-gray-500">/mois</div>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Plan Enterprise -->
                <label class="relative">
                    <input type="radio" name="plan" value="enterprise" class="sr-only" {{ old('plan') === 'enterprise' ? 'checked' : '' }}>
                    <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-primary-300 transition-all plan-card">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900">Enterprise</h4>
                                <p class="text-sm text-gray-600">Pour les grandes organisations</p>
                                <ul class="mt-2 space-y-1 text-sm text-gray-600">
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                        Utilisateurs illimités
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                        Projets illimités
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                        Support 24/7
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                        API personnalisée
                                    </li>
                                </ul>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900">999€</div>
                                <div class="text-sm text-gray-500">/mois</div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            
            <x-input-error :messages="$errors->get('plan')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full btn-primary-modern">
            <i class="fas fa-credit-card mr-2"></i>
            Créer mon équipe et payer
        </button>
    </form>

    <!-- Terms and Login Link -->
    <div class="mt-8 text-center">
        <p class="text-xs text-gray-500 mb-4">
            En créant votre entreprise, vous acceptez nos 
            <a href="#" class="text-primary-600 hover:text-primary-700 underline">conditions d'utilisation</a> 
            et notre 
            <a href="#" class="text-primary-600 hover:text-primary-700 underline">politique de confidentialité</a>
        </p>
        <p class="text-sm text-gray-600">
            Déjà un compte ?
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium transition-colors">
                Se connecter
            </a>
        </p>
    </div>

    <style>
        .plan-card {
            transition: all 0.3s ease;
        }
        
        input[type="radio"]:checked + .plan-card {
            border-color: #0ea5e9;
            background-color: #f0f9ff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.15);
        }
        
        input[type="radio"]:checked + .plan-card h4 {
            color: #0369a1;
        }
    </style>
</x-guest-layout>
