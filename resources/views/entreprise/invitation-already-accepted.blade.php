@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-auto flex justify-center">
                <img class="h-12 w-auto" src="{{ asset('images/logo.png') }}" alt="Planify">
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Invitation Déjà Acceptée
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Cette invitation a déjà été utilisée
            </p>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Bienvenue chez {{ $invitation->company->name }} !
                </h3>
                
                <p class="text-sm text-gray-500 mb-4">
                    Vous avez déjà accepté cette invitation et votre compte a été créé avec succès.
                </p>
                
                @if($invitation->acceptedBy)
                <p class="text-sm text-gray-500 mb-4">
                    Compte créé le : <strong>{{ $invitation->accepted_at->format('d/m/Y à H:i') }}</strong>
                </p>
                @endif
                
                <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                    <p class="text-sm text-green-800">
                        <strong>Votre compte est prêt !</strong><br>
                        Vous pouvez maintenant vous connecter avec votre email et mot de passe.
                    </p>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('entreprise.login') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Se connecter maintenant
                    </a>
                    
                    <a href="{{ route('welcome') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <p class="text-xs text-gray-500">
                Si vous avez oublié votre mot de passe, utilisez la fonction "Mot de passe oublié"
            </p>
        </div>
    </div>
</div>
@endsection