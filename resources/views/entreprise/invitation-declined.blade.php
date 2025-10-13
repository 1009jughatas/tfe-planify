@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-auto flex justify-center">
                <img class="h-12 w-auto" src="{{ asset('images/logo.png') }}" alt="Planify">
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Invitation refusée
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Vous avez refusé l'invitation
            </p>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h3 class="mt-4 text-lg font-medium text-gray-900">Invitation refusée</h3>
            <p class="mt-2 text-sm text-gray-500">
                Vous avez refusé l'invitation pour rejoindre <strong>{{ $invitation->company->name }}</strong>.
            </p>
            <p class="mt-2 text-sm text-gray-500">
                Si vous changez d'avis, contactez votre administrateur pour recevoir une nouvelle invitation.
            </p>

            <div class="mt-6 space-x-4">
                <a href="{{ route('login.entreprise') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Se connecter
                </a>
                <a href="{{ route('register.entreprise') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Créer une entreprise
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
