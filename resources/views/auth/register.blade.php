<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Inscription Indépendant</h2>
        <p class="text-sm text-gray-600 mt-2">Créez votre compte personnel et gérez vos projets</p>
        <!-- Lien vers inscription entreprise -->
        <div class="mt-4">
            <a href="{{ route('entreprise.register') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                <i class="fas fa-building mr-1"></i>
                Inscription entreprise
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nom complet')" class="form-label-modern" />
            <x-text-input id="name" 
                         class="input-modern" 
                         type="text" 
                         name="name" 
                         :value="old('name')" 
                         required 
                         autofocus 
                         autocomplete="name"
                         placeholder="Jean Dupont" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Adresse email')" class="form-label-modern" />
            <x-text-input id="email" 
                         class="input-modern" 
                         type="email" 
                         name="email" 
                         :value="old('email')" 
                         required 
                         autocomplete="username"
                         placeholder="jean.dupont@email.com" />
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

        <!-- Submit Button -->
        <button type="submit" class="w-full btn-primary-modern">
            <i class="fas fa-user-plus mr-2"></i>
            {{ __('Créer mon compte') }}
        </button>
    </form>

    <!-- Login Link -->
    <div class="mt-8 text-center">
        <p class="text-sm text-gray-600">
            Déjà un compte ?
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium transition-colors">
                Se connecter
            </a>
        </p>
    </div>
</x-guest-layout>
