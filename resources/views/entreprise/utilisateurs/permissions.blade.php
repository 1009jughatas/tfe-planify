@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-shield-alt text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestion des Permissions</h1>
                    <p class="text-gray-600 mt-1 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        {{ $user->name }}
                    </p>
                </div>
            </div>
            <a href="{{ route('entreprise.utilisateurs.index') }}" class="btn-secondary-modern">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Informations utilisateur -->
    <div class="modern-card mb-8">
        <div class="modern-card-header">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                Informations de l'utilisateur
            </h3>
        </div>
        <div class="modern-card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <p class="text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($user->role === 'admin_entreprise') bg-purple-100 text-purple-800
                        @else bg-blue-100 text-blue-800 @endif">
                        {{ $user->role === 'admin_entreprise' ? 'Administrateur' : 'Employé' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de permissions -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-cogs text-green-500 mr-2"></i>
                Permissions de l'utilisateur
            </h3>
            <p class="text-sm text-gray-600 mt-1">
                Cochez les permissions que vous souhaitez accorder à cet utilisateur.
            </p>
        </div>
        <div class="modern-card-body">
            <form method="POST" action="{{ route('entreprise.utilisateurs.update-permissions', $user->id) }}">
                @csrf
                @method('PATCH')
                
                <div class="space-y-6">
                    @foreach($availablePermissions as $permission => $description)
                        <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center h-5">
                                <input 
                                    type="checkbox" 
                                    name="permissions[]" 
                                    value="{{ $permission }}" 
                                    id="permission_{{ $permission }}"
                                    {{ in_array($permission, $userPermissions) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                            </div>
                            <div class="flex-1">
                                <label for="permission_{{ $permission }}" class="text-sm font-medium text-gray-900 cursor-pointer">
                                    {{ $description }}
                                </label>
                                <div class="mt-1">
                                    @switch($permission)
                                        @case('export_pdf')
                                            <p class="text-xs text-gray-600">Permet d'exporter des projets et tâches en format PDF</p>
                                            @break
                                        @case('create_projects')
                                            <p class="text-xs text-gray-600">Permet de créer de nouveaux projets</p>
                                            @break
                                        @case('manage_tasks')
                                            <p class="text-xs text-gray-600">Permet de gérer toutes les tâches des projets</p>
                                            @break
                                        @case('invite_users')
                                            <p class="text-xs text-gray-600">Permet d'inviter de nouveaux utilisateurs dans l'entreprise</p>
                                            @break
                                        @case('view_analytics')
                                            <p class="text-xs text-gray-600">Permet d'accéder aux analyses et statistiques</p>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('entreprise.utilisateurs.index') }}" class="btn-secondary-modern">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn-primary-modern">
                        <i class="fas fa-save mr-2"></i>
                        Sauvegarder les permissions
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Note importante -->
    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-start space-x-3">
            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
            <div>
                <h4 class="text-sm font-medium text-blue-900">Note importante</h4>
                <p class="text-sm text-blue-700 mt-1">
                    Les administrateurs d'entreprise ont automatiquement tous les droits. 
                    Ces permissions s'appliquent uniquement aux employés.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .modern-card {
        @apply bg-white rounded-xl shadow-sm border border-gray-200;
    }
    
    .modern-card-header {
        @apply px-6 py-4 border-b border-gray-200;
    }
    
    .modern-card-body {
        @apply p-6;
    }
    
    .btn-primary-modern {
        @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200;
    }
    
    .btn-secondary-modern {
        @apply inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-200 hover:scale-105 transition-all duration-200;
    }
</style>
@endsection
