<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-plus text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Nouvelle Tâche</h1>
                    <p class="text-sm text-gray-600 mt-1">Projet : {{ $project->name }}</p>
                </div>
            </div>
            <a href="{{ route('projects.tasks', $project->id) }}" class="btn-secondary-modern">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour aux tâches
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-tasks text-primary-600 mr-2"></i>
                    Créer une Nouvelle Tâche
                </h3>
            </div>
            <div class="modern-card-body">
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-600 mt-0.5 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-red-900 mb-2">Erreurs de validation :</p>
                                <ul class="text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ $project->company_id ? route('entreprise.tasks.store', $project->id) : route('tasks.store', $project->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $parent_id ?? '' }}">
                    
                    <!-- Titre -->
                    <div>
                        <label for="title" class="form-label-modern">
                            Titre de la Tâche <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               class="input-modern @error('title') border-red-300 focus:ring-red-500 @enderror" 
                               id="title" 
                               value="{{ old('title') }}"
                               placeholder="Ex: Développer la page d'accueil"
                               required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="form-label-modern">Description</label>
                        <textarea name="description" 
                                  class="input-modern @error('description') border-red-300 focus:ring-red-500 @enderror" 
                                  id="description" 
                                  rows="4"
                                  placeholder="Décrivez la tâche en détail...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date et Priorité -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date d'échéance -->
                        <div>
                            <label for="due_date" class="form-label-modern">
                                <i class="fas fa-calendar text-gray-500 mr-1"></i>
                                Date d'Échéance
                            </label>
                            <input type="date" 
                                   name="due_date" 
                                   class="input-modern @error('due_date') border-red-300 focus:ring-red-500 @enderror" 
                                   id="due_date"
                                   value="{{ old('due_date') }}">
                            @error('due_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priorité -->
                        <div>
                            <label for="priority" class="form-label-modern">
                                <i class="fas fa-flag text-gray-500 mr-1"></i>
                                Priorité
                            </label>
                            <select name="priority" id="priority" class="input-modern @error('priority') border-red-300 focus:ring-red-500 @enderror">
                                <option value="0" {{ old('priority') == 0 ? 'selected' : '' }}>🟢 Basse</option>
                                <option value="1" {{ old('priority') == 1 ? 'selected' : '' }}>🟡 Moyenne</option>
                                <option value="2" {{ old('priority') == 2 ? 'selected' : '' }}>🟠 Haute</option>
                                <option value="3" {{ old('priority') == 3 ? 'selected' : '' }}>🔴 Urgente</option>
                            </select>
                            @error('priority')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Assigner à (seulement pour les utilisateurs d'entreprise) -->
                    @if (!Auth::user()->isUserIndependant())
                        <div>
                            <label for="assigned_to" class="form-label-modern">
                                <i class="fas fa-user text-gray-500 mr-1"></i>
                                Assigner à
                                @if (Auth::user()->isUserIndependant() && !Auth::user()->is_premium() && !Auth::user()->is_admin())
                                    <span class="badge-warning ml-2">
                                        <i class="fas fa-crown mr-1"></i>Premium
                                    </span>
                                @endif
                            </label>
                            <select name="assigned_to" 
                                    id="assigned_to" 
                                    class="input-modern @error('assigned_to') border-red-300 focus:ring-red-500 @enderror"
                                    @if (Auth::user()->isUserIndependant() && !Auth::user()->is_premium() && !Auth::user()->is_admin()) disabled @endif>
                                <option value="">-- Non assignée --</option>
                                @foreach($participants as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if (Auth::user()->isUserIndependant() && !Auth::user()->is_premium() && !Auth::user()->is_admin())
                                <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-sm text-yellow-700">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        L'assignation de tâches est une fonctionnalité premium. 
                                        <a href="{{ route('premium.show') }}" class="text-yellow-800 hover:text-yellow-900 underline font-medium">
                                            Passez à Premium
                                        </a>
                                    </p>
                                </div>
                            @endif
                            @error('assigned_to')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <!-- Pour les utilisateurs indépendants, assigner automatiquement à eux-mêmes -->
                        <input type="hidden" name="assigned_to" value="{{ Auth::id() }}">
                    @endif

                    <!-- Boutons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('projects.tasks', $project->id) }}" class="btn-secondary-modern flex-1 sm:flex-none">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn-primary-modern flex-1 sm:flex-none">
                            <i class="fas fa-check mr-2"></i>
                            Créer la Tâche
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>