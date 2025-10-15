@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">🎫 Ticket #{{ $ticket->id }}</h1>
                    <p class="mt-2 text-gray-600">{{ $ticket->objet }}</p>
                </div>
                <a href="{{ route('superadmin.tickets.index') }}" class="btn-secondary-modern">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2">
                <!-- Informations du ticket -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Informations du ticket</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Objet</label>
                                <p class="text-gray-900">{{ $ticket->objet }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                                <span class="px-3 py-1 text-sm font-medium rounded-full
                                    @if($ticket->statut === 'ouvert') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                    @if($ticket->statut === 'ouvert')
                                        <i class="fas fa-clock mr-1"></i>Ouvert
                                    @else
                                        <i class="fas fa-check mr-1"></i>Fermé
                                    @endif
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date d'envoi</label>
                                <p class="text-gray-900">{{ $ticket->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dernière modification</label>
                                <p class="text-gray-900">{{ $ticket->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $ticket->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Réponse du support -->
                @if($ticket->reponse)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Réponse du support</h2>
                        </div>
                        <div class="p-6">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $ticket->reponse }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Formulaire de réponse -->
                @if($ticket->statut === 'ouvert')
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Répondre au ticket</h2>
                        </div>
                        <form method="POST" action="{{ route('superadmin.tickets.repondre', $ticket->id) }}" class="p-6">
                            @csrf
                            <div class="mb-4">
                                <label for="reponse" class="block text-sm font-medium text-gray-700 mb-2">Votre réponse</label>
                                <textarea name="reponse" id="reponse" rows="6" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Tapez votre réponse ici..."
                                          required>{{ old('reponse') }}</textarea>
                                @error('reponse')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary-modern">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Envoyer la réponse
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Informations utilisateur -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Utilisateur</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $ticket->user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $ticket->user->email }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Rôle:</span>
                                <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $ticket->user->role)) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Inscrit le:</span>
                                <span class="font-medium">{{ $ticket->user->created_at->format('d/m/Y') }}</span>
                            </div>
                            @if($ticket->user->company)
                                <div class="flex justify-between">
                                    <span>Entreprise:</span>
                                    <span class="font-medium">{{ $ticket->user->company->name }}</span>
                                </div>
                            @endif
                            @if($ticket->user->is_premium)
                                <div class="flex justify-between">
                                    <span>Statut:</span>
                                    <span class="font-medium text-yellow-600">
                                        <i class="fas fa-crown mr-1"></i>Premium
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Actions</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('superadmin.users.show', $ticket->user) }}" class="w-full btn-secondary-modern">
                            <i class="fas fa-user mr-2"></i>
                            Voir le profil utilisateur
                        </a>
                        @if($ticket->user->company)
                            <a href="{{ route('superadmin.abonnements.index') }}?search={{ $ticket->user->company->name }}" class="w-full btn-secondary-modern">
                                <i class="fas fa-building mr-2"></i>
                                Voir l'abonnement entreprise
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
