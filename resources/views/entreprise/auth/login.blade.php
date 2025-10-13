<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Connexion Entreprise</h2>
        <p class="text-sm text-gray-600 mt-2">Accédez à votre espace d'équipe</p>
        <!-- Lien vers connexion indépendant -->
        <div class="mt-4">
            <a href="{{ route('login') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                <i class="fas fa-user mr-1"></i>
                Connexion indépendant
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('entreprise.login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Adresse email')" class="form-label-modern" />
            <x-text-input id="email" 
                         class="input-modern" 
                         type="email" 
                         name="email" 
                         :value="old('email')" 
                         required 
                         autofocus 
                         autocomplete="username"
                         placeholder="admin@monentreprise.com" />
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
                             autocomplete="current-password"
                             placeholder="••••••••" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
            <label for="remember_me" class="ml-2 text-sm text-gray-600">
                Se souvenir de moi
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full btn-primary-modern">
            <i class="fas fa-sign-in-alt mr-2"></i>
            Se connecter à mon équipe
        </button>
    </form>

    <!-- Links -->
    <div class="mt-8 text-center">
        <p class="text-sm text-gray-600">
            Pas encore d'équipe ?
            <a href="{{ route('entreprise.register') }}" class="text-primary-600 hover:text-primary-700 font-medium transition-colors">
                Créer une entreprise
            </a>
        </p>
        <p class="text-sm text-gray-600 mt-2">
            Mot de passe oublié ?
            <a href="{{ route('password.request') }}" class="text-primary-600 hover:text-primary-700 font-medium transition-colors">
                Réinitialiser
            </a>
        </p>
    </div>
</x-guest-layout>
