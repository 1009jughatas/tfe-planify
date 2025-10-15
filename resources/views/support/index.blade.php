@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mes Tickets de Support</h1>
                    <p class="mt-2 text-gray-600">Gérez vos demandes d'aide et suivez leur résolution.</p>
                </div>
                <a href="{{ route('support.create') }}" class="btn-primary-modern">
                    <i class="fas fa-plus mr-2"></i>
                    Nouveau ticket
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-blue-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total des tickets</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $tickets->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tickets ouverts</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $tickets->where('statut', 'ouvert')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tickets fermés</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $tickets->where('statut', 'ferme')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des tickets -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Mes tickets</h2>
                <p class="text-sm text-gray-600">Cliquez sur un ticket pour voir les détails</p>
            </div>

            @if($tickets->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($tickets as $ticket)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $ticket->objet }}</h3>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($ticket->statut === 'ouvert') bg-orange-100 text-orange-800
                                            @else bg-green-100 text-green-800 @endif">
                                            @if($ticket->statut === 'ouvert')
                                                <i class="fas fa-clock mr-1"></i>Ouvert
                                            @else
                                                <i class="fas fa-check mr-1"></i>Fermé
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 mb-3 line-clamp-2">{{ $ticket->description }}</p>
                                    
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-1"></i>
                                            Créé le {{ $ticket->created_at->format('d/m/Y à H:i') }}
                                        </span>
                                        @if($ticket->repond_le)
                                            <span class="flex items-center">
                                                <i class="fas fa-reply mr-1"></i>
                                                Répondu le {{ $ticket->repond_le->format('d/m/Y à H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('support.show', $ticket) }}" class="btn-primary-modern text-sm">
                                        <i class="fas fa-eye mr-1"></i>
                                        Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $tickets->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-ticket-alt text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun ticket de support</h3>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore créé de ticket de support.</p>
                    <a href="{{ route('support.create') }}" class="btn-primary-modern">
                        <i class="fas fa-plus mr-2"></i>
                        Créer votre premier ticket
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
