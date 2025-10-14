<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Profile') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Gérez vos informations personnelles et préférences</p>
            </div>
            <div class="flex items-center space-x-3">
                @if (Auth::user() && Auth::user()->is_premium())
                    <span class="badge-premium">
                        <i class="fas fa-crown mr-1"></i>Premium
                    </span>
                @else
                    <a href="{{ route('premium.show') }}" class="btn-primary-modern">
                        <i class="fas fa-crown mr-2"></i>
                        Passer Premium
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Section Premium -->
            @if (Auth::user() && Auth::user()->is_premium())
                <div class="p-4 sm:p-8 bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 shadow sm:rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-crown text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Statut Premium Actif</h3>
                                <p class="text-sm text-gray-600">Vous bénéficiez de toutes les fonctionnalités Premium</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('preferences.edit') }}" class="btn-premium-modern">
                                <i class="fas fa-palette mr-2"></i>
                                Préférences
                            </a>
                            <a href="{{ route('export.dashboard') }}" class="btn-premium-modern">
                                <i class="fas fa-download mr-2"></i>
                                Exporter Données
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 sm:p-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 shadow sm:rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-crown text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Débloquez Premium</h3>
                                <p class="text-sm text-gray-600">Accédez à l'export de données, thème sombre, analyses détaillées</p>
                            </div>
                        </div>
                        <a href="{{ route('premium.show') }}" class="btn-primary-modern">
                            <i class="fas fa-crown mr-2"></i>
                            Passer Premium
                        </a>
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Boutons Premium */
        .btn-premium-modern {
            @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-medium rounded-lg shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200;
        }
        
        .badge-premium {
            @apply inline-flex items-center px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-medium rounded-full shadow-sm;
        }
    </style>
</x-app-layout>
