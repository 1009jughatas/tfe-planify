<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-building text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Créer votre Entreprise</h1>
                    <p class="text-sm text-gray-600 mt-1">Rejoignez Planify et gérez vos projets d'équipe</p>
                </div>
            </div>
            <a href="{{ route('login') }}" class="btn-secondary-modern">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Se connecter
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Plans tarifaires -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 text-center">Choisissez votre plan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Plan Starter -->
                <div class="modern-card hover-lift">
                    <div class="modern-card-header text-center">
                        <h3 class="text-lg font-semibold text-gray-900">Starter</h3>
                        <div class="mt-2">
                            <span class="text-3xl font-bold text-primary-600">399€</span>
                            <span class="text-gray-500">/mois</span>
                        </div>
                    </div>
                    <div class="modern-card-body">
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                10 utilisateurs
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
                </div>

                <!-- Plan Growth -->
                <div class="modern-card hover-lift border-primary-500 border-2 relative">
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                        <span class="bg-primary-500 text-white px-3 py-1 rounded-full text-xs font-medium">Populaire</span>
                    </div>
                    <div class="modern-card-header text-center">
                        <h3 class="text-lg font-semibold text-gray-900">Growth</h3>
                        <div class="mt-2">
                            <span class="text-3xl font-bold text-primary-600">599€</span>
                            <span class="text-gray-500">/mois</span>
                        </div>
                    </div>
                    <div class="modern-card-body">
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                20 utilisateurs
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Projets illimités
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Support prioritaire
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Plan Enterprise -->
                <div class="modern-card hover-lift">
                    <div class="modern-card-header text-center">
                        <h3 class="text-lg font-semibold text-gray-900">Enterprise</h3>
                        <div class="mt-2">
                            <span class="text-3xl font-bold text-primary-600">999€</span>
                            <span class="text-gray-500">/mois</span>
                        </div>
                    </div>
                    <div class="modern-card-body">
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
                                Support dédié
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire d'inscription -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                    Informations de l'Entreprise
                </h3>
            </div>
            <div class="modern-card-body">
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-600 mt-0.5 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-red-900 mb-2">Erreurs de validation :</p>
                                <ul class="text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('company.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Informations de l'entreprise -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company_name" class="form-label-modern">
                                <i class="fas fa-building text-gray-500 mr-1"></i>
                                Nom de l'entreprise <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="company_name" id="company_name"
                                   class="input-modern @error('company_name') border-red-300 focus:ring-red-500 @enderror"
                                   value="{{ old('company_name') }}" required autofocus>
                            @error('company_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="company_email" class="form-label-modern">
                                <i class="fas fa-envelope text-gray-500 mr-1"></i>
                                Email de l'entreprise <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="company_email" id="company_email"
                                   class="input-modern @error('company_email') border-red-300 focus:ring-red-500 @enderror"
                                   value="{{ old('company_email') }}" required>
                            @error('company_email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="phone" class="form-label-modern">
                                <i class="fas fa-phone text-gray-500 mr-1"></i>
                                Téléphone
                            </label>
                            <input type="tel" name="phone" id="phone"
                                   class="input-modern @error('phone') border-red-300 focus:ring-red-500 @enderror"
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="website" class="form-label-modern">
                                <i class="fas fa-globe text-gray-500 mr-1"></i>
                                Site web
                            </label>
                            <input type="url" name="website" id="website"
                                   class="input-modern @error('website') border-red-300 focus:ring-red-500 @enderror"
                                   value="{{ old('website') }}" placeholder="https://">
                            @error('website')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="plan" class="form-label-modern">
                                <i class="fas fa-credit-card text-gray-500 mr-1"></i>
                                Plan tarifaire <span class="text-red-500">*</span>
                            </label>
                            <select name="plan" id="plan"
                                    class="input-modern @error('plan') border-red-300 focus:ring-red-500 @enderror" required>
                                <option value="starter" {{ old('plan') == 'starter' ? 'selected' : '' }}>Starter - 399€/mois (10 utilisateurs)</option>
                                <option value="growth" {{ old('plan') == 'growth' ? 'selected' : '' }}>Growth - 599€/mois (20 utilisateurs)</option>
                                <option value="enterprise" {{ old('plan') == 'enterprise' ? 'selected' : '' }}>Enterprise - 999€/mois (Illimité)</option>
                            </select>
                            @error('plan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="address" class="form-label-modern">
                            <i class="fas fa-map-marker-alt text-gray-500 mr-1"></i>
                            Adresse
                        </label>
                        <textarea name="address" id="address" rows="3"
                                  class="input-modern @error('address') border-red-300 focus:ring-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informations de l'administrateur -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-user-shield text-primary-600 mr-2"></i>
                            Administrateur de l'Entreprise
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="admin_name" class="form-label-modern">
                                    <i class="fas fa-user text-gray-500 mr-1"></i>
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="admin_name" id="admin_name"
                                       class="input-modern @error('admin_name') border-red-300 focus:ring-red-500 @enderror"
                                       value="{{ old('admin_name') }}" required>
                                @error('admin_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="admin_email" class="form-label-modern">
                                    <i class="fas fa-envelope text-gray-500 mr-1"></i>
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="admin_email" id="admin_email"
                                       class="input-modern @error('admin_email') border-red-300 focus:ring-red-500 @enderror"
                                       value="{{ old('admin_email') }}" required>
                                @error('admin_email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="admin_password" class="form-label-modern">
                                    <i class="fas fa-lock text-gray-500 mr-1"></i>
                                    Mot de passe <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="admin_password" id="admin_password"
                                       class="input-modern @error('admin_password') border-red-300 focus:ring-red-500 @enderror"
                                       required>
                                @error('admin_password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="admin_password_confirmation" class="form-label-modern">
                                    <i class="fas fa-lock text-gray-500 mr-1"></i>
                                    Confirmer le mot de passe <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="admin_password_confirmation" id="admin_password_confirmation"
                                       class="input-modern @error('admin_password_confirmation') border-red-300 focus:ring-red-500 @enderror"
                                       required>
                                @error('admin_password_confirmation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Conditions d'utilisation -->
                    <div class="flex items-start">
                        <input type="checkbox" name="terms" id="terms" value="1" required
                               class="mt-1 h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <label for="terms" class="ml-2 text-sm text-gray-600">
                            J'accepte les <a href="#" class="text-primary-600 hover:text-primary-700">conditions d'utilisation</a> 
                            et la <a href="#" class="text-primary-600 hover:text-primary-700">politique de confidentialité</a>
                            <span class="text-red-500">*</span>
                        </label>
                    </div>
                    @error('terms')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Bouton de soumission -->
                    <div class="flex justify-center pt-6">
                        <button type="submit" class="btn-primary-modern px-8 py-3">
                            <i class="fas fa-building mr-2"></i>
                            Créer mon entreprise
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Avantages -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-white"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Sécurisé</h3>
                <p class="text-sm text-gray-600">Vos données sont protégées et cloisonnées par entreprise</p>
            </div>
            
            <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-white"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Collaboratif</h3>
                <p class="text-sm text-gray-600">Gérez votre équipe et vos projets efficacement</p>
            </div>
            
            <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-rocket text-white"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Productif</h3>
                <p class="text-sm text-gray-600">Outils avancés pour optimiser votre workflow</p>
            </div>
        </div>
    </div>
</x-app-layout>
