@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Nouveau Ticket de Support</h1>
                    <p class="mt-2 text-gray-600">Décrivez votre problème et nous vous aiderons rapidement.</p>
                </div>
                <a href="{{ route('support.index') }}" class="btn-secondary-modern">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour aux tickets
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Créer un ticket de support</h2>
                <p class="text-sm text-gray-600">Remplissez les informations ci-dessous pour nous aider à résoudre votre problème.</p>
            </div>
            
            <form action="{{ route('support.store') }}" method="POST" class="p-6">
                @csrf
                
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Objet du ticket -->
                    <div>
                        <label for="objet" class="block text-sm font-medium text-gray-700 mb-2">
                            Objet du ticket <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="objet" 
                               name="objet" 
                               value="{{ old('objet') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Ex: Problème de connexion, Bug dans l'interface, Demande de fonctionnalité..."
                               required>
                        <p class="mt-1 text-sm text-gray-500">Décrivez brièvement le sujet de votre demande.</p>
                    </div>

                    <!-- Description du problème -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description du problème <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="8"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                  placeholder="Décrivez en détail votre problème, les étapes pour le reproduire, et toute information utile..."
                                  required>{{ old('description') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Plus vous serez précis, plus nous pourrons vous aider rapidement.</p>
                    </div>

                    <!-- Informations utilisateur -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                            </div>
                            <div class="text-sm text-blue-700">
                                <h4 class="font-medium mb-2">Informations automatiques :</h4>
                                <ul class="space-y-1">
                                    <li><strong>Utilisateur :</strong> {{ Auth::user()->name }} ({{ Auth::user()->email }})</li>
                                    <li><strong>Rôle :</strong> 
                                        @if(Auth::user()->is_super_admin())
                                            Super Administrateur
                                        @elseif(Auth::user()->isAdminEntreprise())
                                            Administrateur Entreprise
                                        @elseif(Auth::user()->isUserEntreprise())
                                            Utilisateur Entreprise
                                        @else
                                            Utilisateur Indépendant
                                        @endif
                                    </li>
                                    @if(Auth::user()->company)
                                        <li><strong>Entreprise :</strong> {{ Auth::user()->company->name }}</li>
                                    @endif
                                    <li><strong>Date :</strong> {{ now()->format('d/m/Y H:i') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('support.index') }}" class="btn-secondary-modern">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn-primary-modern">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Envoyer le ticket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
