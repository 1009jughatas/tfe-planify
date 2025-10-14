<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Connexion Indépendant</h2>
        <p class="text-sm text-gray-600 mt-2">Accédez à votre espace personnel</p>
        <!-- Lien vers connexion entreprise -->
        <div class="mt-4">
            <a href="{{ route('entreprise.login') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                <i class="fas fa-building mr-1"></i>
                Connexion entreprise
            </a>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login.indep') }}" class="space-y-6">
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
                         placeholder="votre@email.com" />
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

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center">
                <input id="remember_me" 
                       type="checkbox" 
                       class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2" 
                       name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
            </label>
            
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" 
                   class="text-sm text-primary-600 hover:text-primary-700 transition-colors">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full btn-primary-modern">
            <i class="fas fa-sign-in-alt mr-2"></i>
            {{ __('Se connecter') }}
        </button>
    </form>

    <!-- Register Link -->
    <div class="mt-8 text-center">
        <p class="text-sm text-gray-600">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium transition-colors">
                Créer un compte
            </a>
        </p>
    </div>
</x-guest-layout>
