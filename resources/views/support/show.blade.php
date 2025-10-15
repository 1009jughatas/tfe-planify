@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Ticket #{{ $ticket->id }}</h1>
                    <p class="mt-2 text-gray-600">{{ $ticket->objet }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 text-sm font-medium rounded-full
                        @if($ticket->statut === 'ouvert') bg-orange-100 text-orange-800
                        @else bg-green-100 text-green-800 @endif">
                        @if($ticket->statut === 'ouvert')
                            <i class="fas fa-clock mr-1"></i>Ouvert
                        @else
                            <i class="fas fa-check mr-1"></i>Fermé
                        @endif
                    </span>
                    <a href="{{ route('support.index') }}" class="btn-secondary-modern">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Détails du ticket -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Description du problème</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                    </div>
                </div>

                <!-- Réponse du support -->
                @if($ticket->reponse)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                            <div class="flex items-center">
                                <i class="fas fa-headset text-green-600 mr-2"></i>
                                <h2 class="text-lg font-semibold text-gray-900">Réponse du support</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->reponse }}</p>
                            
                            @if($ticket->repondPar)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-500">
                                        Répondu par <strong>{{ $ticket->repondPar->name }}</strong> 
                                        le {{ $ticket->repond_le->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-blue-500 mr-3"></i>
                            <div>
                                <h3 class="text-lg font-medium text-blue-900">En attente de réponse</h3>
                                <p class="text-blue-700">Notre équipe de support examine votre demande et vous répondra dans les plus brefs délais.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Informations du ticket -->
            <div class="space-y-6">
                <!-- Informations générales -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informations</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Créé par</label>
                            <p class="text-gray-900">{{ $ticket->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $ticket->user->email }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-600">Rôle</label>
                            <p class="text-gray-900">
                                @if($ticket->user->is_super_admin())
                                    Super Administrateur
                                @elseif($ticket->user->isAdminEntreprise())
                                    Administrateur Entreprise
                                @elseif($ticket->user->isUserEntreprise())
                                    Utilisateur Entreprise
                                @else
                                    Utilisateur Indépendant
                                @endif
                            </p>
                        </div>

                        @if($ticket->user->company)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Entreprise</label>
                                <p class="text-gray-900">{{ $ticket->user->company->name }}</p>
                            </div>
                        @endif

                        <div>
                            <label class="text-sm font-medium text-gray-600">Date de création</label>
                            <p class="text-gray-900">{{ $ticket->created_at->format('d/m/Y à H:i') }}</p>
                        </div>

                        @if($ticket->repond_le)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Dernière réponse</label>
                                <p class="text-gray-900">{{ $ticket->repond_le->format('d/m/Y à H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('support.create') }}" class="w-full btn-primary-modern">
                            <i class="fas fa-plus mr-2"></i>
                            Nouveau ticket
                        </a>
                        
                        <a href="{{ route('support.index') }}" class="w-full btn-secondary-modern">
                            <i class="fas fa-list mr-2"></i>
                            Mes tickets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
