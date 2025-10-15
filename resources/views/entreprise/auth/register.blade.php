@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-auto flex justify-center">
                <img class="h-12 w-auto" src="{{ asset('images/logo.png') }}" alt="Planify">
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Inscription Entreprise
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ou
                <a href="{{ route('entreprise.login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    connectez-vous à votre compte
                </a>
            </p>
            <p class="mt-2 text-center text-xs text-gray-500">
                Vous travaillez seul ?
                <a href="{{ route('register.indep') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Inscription indépendant
                </a>
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('entreprise.register') }}" method="POST">
            @csrf
            
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations de l'entreprise -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Informations de l'entreprise</h3>
                    
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700">Nom de l'entreprise</label>
                        <input id="company_name" name="company_name" type="text" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="Nom de votre entreprise" value="{{ old('company_name') }}">
                    </div>
                    
                    <div>
                        <label for="company_email" class="block text-sm font-medium text-gray-700">Email de l'entreprise</label>
                        <input id="company_email" name="company_email" type="email" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="contact@entreprise.com" value="{{ old('company_email') }}">
                    </div>
                </div>

                <!-- Informations de l'administrateur -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Vos informations</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Votre nom</label>
                        <input id="name" name="name" type="text" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="Votre nom complet" value="{{ old('name') }}">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Votre email</label>
                        <input id="email" name="email" type="email" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="votre.email@entreprise.com" value="{{ old('email') }}">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input id="password" name="password" type="password" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="Minimum 8 caractères">
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="Confirmer le mot de passe">
                    </div>
                </div>
            </div>

            <!-- Sélection du plan -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Choisissez votre plan d'abonnement</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Plan Starter -->
                    <div class="relative">
                        <input type="radio" id="plan_starter" name="plan" value="starter" 
                               class="peer sr-only" {{ old('plan') === 'starter' ? 'checked' : '' }}>
                        <label for="plan_starter" class="block p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50">
                            <div class="text-center">
                                <h4 class="text-lg font-semibold text-gray-900">Starter</h4>
                                <p class="text-2xl font-bold text-indigo-600">399€<span class="text-sm font-normal text-gray-500">/mois</span></p>
                                <p class="text-sm text-gray-600 mt-2">Jusqu'à 10 utilisateurs</p>
                                <p class="text-xs text-gray-500 mt-1">Parfait pour les petites équipes</p>
                            </div>
                        </label>
                    </div>

                    <!-- Plan Growth -->
                    <div class="relative">
                        <input type="radio" id="plan_growth" name="plan" value="growth" 
                               class="peer sr-only" {{ old('plan') === 'growth' ? 'checked' : '' }}>
                        <label for="plan_growth" class="block p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50">
                            <div class="text-center">
                                <h4 class="text-lg font-semibold text-gray-900">Growth</h4>
                                <p class="text-2xl font-bold text-indigo-600">599€<span class="text-sm font-normal text-gray-500">/mois</span></p>
                                <p class="text-sm text-gray-600 mt-2">Jusqu'à 20 utilisateurs</p>
                                <p class="text-xs text-gray-500 mt-1">Idéal pour les équipes en croissance</p>
                            </div>
                        </label>
                    </div>

                    <!-- Plan Enterprise -->
                    <div class="relative">
                        <input type="radio" id="plan_enterprise" name="plan" value="enterprise" 
                               class="peer sr-only" {{ old('plan') === 'enterprise' ? 'checked' : '' }}>
                        <label for="plan_enterprise" class="block p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50">
                            <div class="text-center">
                                <h4 class="text-lg font-semibold text-gray-900">Enterprise</h4>
                                <p class="text-2xl font-bold text-indigo-600">999€<span class="text-sm font-normal text-gray-500">/mois</span></p>
                                <p class="text-sm text-gray-600 mt-2">Utilisateurs illimités</p>
                                <p class="text-xs text-gray-500 mt-1">Pour les grandes organisations</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            Inscription sécurisée
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Votre paiement sera traité de manière sécurisée via Stripe. Vous pourrez gérer votre abonnement depuis votre dashboard entreprise.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Créer mon entreprise
                </button>
            </div>
        </form>
    </div>
</div>
@endsection