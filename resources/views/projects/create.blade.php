<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Créer un projet</h1>
                <p class="text-sm text-gray-600 mt-1">
                    @if (Auth::user() && Auth::user()->is_admin())
                        Créez un nouveau projet et assignez-le à votre équipe
                    @else
                        Créez votre nouveau projet personnel
                    @endif
                </p>
            </div>
            <a href="{{ route('projects.index') }}" class="btn-secondary-modern">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour aux projets
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-primary rounded-xl flex items-center justify-center">
                        <i class="fas fa-plus text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Nouveau Projet</h2>
                        <p class="text-sm text-gray-600">
                            @if (Auth::user() && Auth::user()->is_admin())
                                Configurez les détails du projet et assignez-le à votre équipe
                            @else
                                Configurez les détails de votre projet personnel
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="modern-card-body">
                <form action="{{ route('projects.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- Informations de base -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                            Informations de base
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Titre du projet -->
                            <div class="md:col-span-2">
                                <label for="name" class="form-label-modern">
                                    <i class="fas fa-tag text-gray-500 mr-2"></i>
                                    Titre du projet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name') }}"
                                       class="input-modern @error('name') border-red-300 focus:border-red-500 @enderror"
                                       placeholder="Nom de votre projet"
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="form-label-modern">
                                    <i class="fas fa-align-left text-gray-500 mr-2"></i>
                                    Description
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="4"
                                          class="input-modern @error('description') border-red-300 focus:border-red-500 @enderror"
                                          placeholder="Décrivez brièvement votre projet...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Configuration du projet -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-cog text-primary-600 mr-2"></i>
                            Configuration
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Deadline -->
                            <div>
                                <label for="deadline" class="form-label-modern">
                                    <i class="fas fa-calendar-alt text-gray-500 mr-2"></i>
                                    Deadline
                                </label>
                                <input type="date" 
                                       name="deadline" 
                                       id="deadline" 
                                       value="{{ old('deadline') }}"
                                       class="input-modern @error('deadline') border-red-300 focus:border-red-500 @enderror">
                                @error('deadline')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priorité -->
                            <div>
                                <label for="priority" class="form-label-modern">
                                    <i class="fas fa-flag text-gray-500 mr-2"></i>
                                    Priorité
                                </label>
                                <select name="priority" 
                                        id="priority" 
                                        class="input-modern @error('priority') border-red-300 focus:border-red-500 @enderror">
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>
                                        🔵 Faible
                                    </option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>
                                        🟡 Moyenne
                                    </option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                        🔴 Élevée
                                    </option>
                                </select>
                                @error('priority')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Statut initial -->
                            <div>
                                <label for="status" class="form-label-modern">
                                    <i class="fas fa-play-circle text-gray-500 mr-2"></i>
                                    Statut initial
                                </label>
                                <select name="status" 
                                        id="status" 
                                        class="input-modern @error('status') border-red-300 focus:border-red-500 @enderror">
                                    <option value="planning" {{ old('status') == 'planning' ? 'selected' : '' }}>
                                        📋 En Planification
                                    </option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                        ▶️ Actif
                                    </option>
                                    <option value="on-hold" {{ old('status') == 'on-hold' ? 'selected' : '' }}>
                                        ⏸️ En Pause
                                    </option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                        ✅ Terminé
                                    </option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Assignation d'équipe (Admin uniquement) -->
                    @if (Auth::user() && Auth::user()->is_admin())
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-users text-primary-600 mr-2"></i>
                                Assignation d'équipe
                            </h3>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-blue-900 mb-1">Assignation d'équipe</h4>
                                        <p class="text-sm text-blue-700">Sélectionnez les membres de votre équipe qui participeront à ce projet. Vous resterez le propriétaire du projet.</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="team_members" class="form-label-modern">
                                    <i class="fas fa-user-friends text-gray-500 mr-2"></i>
                                    Membres de l'équipe
                                </label>
                                <div class="space-y-3 max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    @foreach ($users as $user)
                                        @if ($user->id !== Auth::id() && $user->role === 'member')
                                            <div class="flex items-center space-x-3">
                                                <input type="checkbox" 
                                                       name="team_members[]" 
                                                       value="{{ $user->id }}" 
                                                       id="user_{{ $user->id }}"
                                                       class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2"
                                                       {{ in_array($user->id, old('team_members', [])) ? 'checked' : '' }}>
                                                <label for="user_{{ $user->id }}" class="flex items-center space-x-3 cursor-pointer">
                                                    <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                                                        <span class="text-white text-sm font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                                    </div>
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                @error('team_members')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Boutons d'action -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('projects.index') }}" class="btn-secondary-modern">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </a>
                        
                        <button type="submit" class="btn-primary-modern">
                            <i class="fas fa-plus mr-2"></i>
                            @if (Auth::user() && Auth::user()->is_admin())
                                Créer et assigner le projet
                            @else
                                Créer mon projet
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Styles pour les formulaires modernes */
        .form-label-modern {
            @apply block text-sm font-medium text-gray-700 mb-2;
        }
        
        .input-modern {
            @apply w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 bg-white;
        }
        
        .input-modern:focus {
            @apply outline-none;
        }
        
        .input-modern::placeholder {
            @apply text-gray-400;
        }
        
        /* Styles pour les cartes modernes */
        .modern-card {
            @apply bg-white rounded-xl border border-gray-200 shadow-sm;
        }
        
        .modern-card-header {
            @apply p-6 border-b border-gray-200 bg-gray-50 rounded-t-xl;
        }
        
        .modern-card-body {
            @apply p-6;
        }
        
        /* Styles pour les boutons */
        .btn-primary-modern {
            @apply inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 focus:ring-4 focus:ring-primary-200 transition-all duration-200 shadow-sm hover:shadow-md;
        }
        
        .btn-secondary-modern {
            @apply inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 transition-all duration-200 shadow-sm hover:shadow-md;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .modern-card-header {
                @apply p-4;
            }
            
            .modern-card-body {
                @apply p-4;
            }
            
            .grid {
                @apply gap-4;
            }
        }
    </style>
</x-app-layout>