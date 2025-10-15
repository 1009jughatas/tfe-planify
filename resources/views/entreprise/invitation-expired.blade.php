@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-auto flex justify-center">
                <img class="h-12 w-auto" src="{{ asset('images/logo.png') }}" alt="Planify">
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Invitation Expirée
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Cette invitation a expiré et ne peut plus être utilisée
            </p>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Invitation pour {{ $invitation->company->name }}
                </h3>
                
                <p class="text-sm text-gray-500 mb-4">
                    Cette invitation était valide jusqu'au 
                    <strong>{{ $invitation->expires_at->format('d/m/Y à H:i') }}</strong>
                </p>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                    <p class="text-sm text-yellow-800">
                        <strong>Que faire maintenant ?</strong><br>
                        Contactez l'administrateur de l'entreprise pour demander une nouvelle invitation.
                    </p>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('welcome') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Retour à l'accueil
                    </a>
                    
                    <a href="{{ route('entreprise.login') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Se connecter
                    </a>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <p class="text-xs text-gray-500">
                Si vous pensez qu'il s'agit d'une erreur, contactez le support Planify
            </p>
        </div>
    </div>
</div>
@endsection