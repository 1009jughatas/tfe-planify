@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-auto flex justify-center">
                <img class="h-12 w-auto" src="{{ asset('images/logo.png') }}" alt="Planify">
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Rejoindre {{ $invitation->company->name }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Vous avez été invité à rejoindre l'équipe de cette entreprise
            </p>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-center mb-6">
                <h3 class="text-lg font-medium text-gray-900">{{ $invitation->company->name }}</h3>
                <p class="text-sm text-gray-500">Poste: {{ $invitation->position ?? 'Employé' }}</p>
                <p class="text-sm text-gray-500">Département: {{ $invitation->department ?? 'Général' }}</p>
                <p class="text-sm text-gray-500">Email: {{ $invitation->email }}</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('invitations.accept.store', $invitation->token) }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Votre nom complet</label>
                        <input id="name" name="name" type="text" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                               placeholder="Votre nom complet" value="{{ old('name') }}">
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

                <div class="mt-6">
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Rejoindre l'équipe
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <form action="{{ route('invitations.decline', $invitation->token) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="text-sm text-gray-500 hover:text-gray-700">
                        Refuser l'invitation
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center">
            <p class="text-xs text-gray-500">
                Cette invitation expire le {{ $invitation->expires_at->format('d/m/Y à H:i') }}
            </p>
        </div>
    </div>
</div>
@endsection
