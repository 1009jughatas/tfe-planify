@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Inviter un employé</h1>
        <p class="mt-2 text-gray-600">Ajoutez de nouveaux membres à votre équipe</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulaire d'invitation -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Nouvelle invitation</h2>
                </div>
                
                <div class="p-6">
                    @if (session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-md">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('entreprise.admin.users.invite') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                                <input type="email" id="email" name="email" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="employe@entreprise.com" value="{{ old('email') }}">
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                                <select id="role" name="role" required
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="user_entreprise">Employé</option>
                                </select>
                            </div>

                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700">Poste</label>
                                <input type="text" id="position" name="position"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="Développeur, Designer, etc." value="{{ old('position') }}">
                            </div>

                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700">Département</label>
                                <input type="text" id="department" name="department"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="IT, Marketing, etc." value="{{ old('department') }}">
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Envoyer l'invitation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informations sur l'entreprise -->
        <div class="space-y-6">
            <!-- Plan et limites -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Plan actuel</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Plan</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($company->plan) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Utilisateurs</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $company->users->count() }} / {{ $company->max_users == -1 ? '∞' : $company->max_users }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Places restantes</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $company->remaining_user_slots == -1 ? '∞' : $company->remaining_user_slots }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Invitations en cours -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Invitations en cours</h3>
                @if($pendingInvitations->count() > 0)
                    <div class="space-y-3">
                        @foreach($pendingInvitations as $invitation)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $invitation->email }}</p>
                                    <p class="text-xs text-gray-500">Expire le {{ $invitation->expires_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <form action="{{ route('entreprise.admin.users.resend', $invitation->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-500">
                                            Renvoyer
                                        </button>
                                    </form>
                                    <form action="{{ route('entreprise.admin.users.cancel', $invitation->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-500">
                                            Annuler
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Aucune invitation en cours</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
