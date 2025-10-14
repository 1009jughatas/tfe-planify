@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                <i class="fas fa-user-plus text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Inviter un Employé</h1>
                <p class="text-gray-600 mt-1 flex items-center">
                    <i class="fas fa-building mr-2 text-blue-500"></i>
                    {{ $company->name }}
                </p>
            </div>
        </div>
    </div>

    <!-- Statut des utilisateurs -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                <i class="fas fa-info text-white text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-blue-900">Utilisateurs de l'entreprise</h3>
                <p class="text-sm text-blue-700">
                    {{ $currentUsers }} / {{ $maxUsers }} utilisateurs • 
                    {{ ($maxUsers - $currentUsers) > 0 ? ($maxUsers - $currentUsers) . ' places restantes' : 'Limite atteinte' }}
                </p>
            </div>
        </div>
    </div>

    @if($currentUsers >= $maxUsers)
        <!-- Limite atteinte -->
        <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-red-900">Limite d'utilisateurs atteinte</h3>
                    <p class="text-sm text-red-700">
                        Vous avez atteint la limite de {{ $maxUsers }} utilisateurs pour votre abonnement actuel.
                    </p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('entreprise.abonnement.index') }}" class="btn-primary-modern">
                    <i class="fas fa-arrow-up mr-2"></i>
                    Mettre à niveau l'abonnement
                </a>
            </div>
        </div>
    @else
        <!-- Formulaire d'invitation -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Informations de l'invitation</h2>
                <p class="text-sm text-gray-600">Envoyez une invitation par email à un nouvel employé</p>
            </div>
            
            <form method="POST" action="{{ route('entreprise.utilisateurs.inviter.store') }}" class="p-6">
                @csrf
                
                <div class="space-y-6">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                               placeholder="employe@exemple.com"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            L'invitation sera envoyée à cette adresse email
                        </p>
                    </div>

                    <!-- Rôle -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Rôle dans l'entreprise <span class="text-red-500">*</span>
                        </label>
                        <select id="role" 
                                name="role" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror"
                                required>
                            <option value="">Sélectionner un rôle</option>
                            <option value="user_entreprise" {{ old('role') === 'user_entreprise' ? 'selected' : '' }}>Employé</option>
                            <option value="admin_entreprise" {{ old('role') === 'admin_entreprise' ? 'selected' : '' }}>Administrateur</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informations sur les rôles -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Différences entre les rôles :</h4>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-user text-blue-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Employé</p>
                                    <p class="text-xs text-gray-600">Accès aux projets assignés, création de tâches, participation aux discussions</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-crown text-purple-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Administrateur</p>
                                    <p class="text-xs text-gray-600">Tous les droits d'un employé + gestion des projets, invitations, abonnements</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                    <a href="{{ route('entreprise.utilisateurs.index') }}" class="btn-secondary-modern">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn-primary-modern">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Envoyer l'invitation
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Informations sur le processus -->
    <div class="mt-8 bg-green-50 border border-green-200 rounded-xl p-6">
        <div class="flex items-start space-x-3">
            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-lightbulb text-white text-xs"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-green-900 mb-1">💡 Comment fonctionne l'invitation</h3>
                <ol class="text-sm text-green-700 space-y-1 list-decimal list-inside">
                    <li>L'invitation est envoyée par email à l'adresse fournie</li>
                    <li>L'employé reçoit un lien unique pour s'inscrire</li>
                    <li>Il crée son compte et est automatiquement ajouté à votre entreprise</li>
                    <li>L'invitation expire après 7 jours si elle n'est pas acceptée</li>
                    <li>Vous pouvez annuler une invitation en attente à tout moment</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-primary-modern {
        @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200;
    }
    
    .btn-secondary-modern {
        @apply inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg shadow-sm border border-gray-300 hover:shadow-md hover:scale-105 transition-all duration-200;
    }
</style>

<script>
    // Validation en temps réel
    document.getElementById('email').addEventListener('blur', function() {
        const email = this.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            this.setCustomValidity('Veuillez entrer une adresse email valide');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
@endsection
