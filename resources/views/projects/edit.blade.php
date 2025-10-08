<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-edit text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Modifier le Projet</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $project->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('projects.show', $project->id) }}" class="btn-secondary-modern">
                    <i class="fas fa-eye mr-2"></i>
                    Voir le projet
                </a>
                <a href="{{ route('projects.index') }}" class="btn-secondary-modern">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-project-diagram text-primary-600 mr-2"></i>
                    Modifier le Projet
                </h3>
                <p class="text-sm text-gray-600 mt-1">Mettez à jour les informations de votre projet</p>
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

                <form action="{{ route('projects.update', $project->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Nom du projet -->
                    <div>
                        <label for="name" class="form-label-modern">
                            Nom du Projet <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               class="input-modern @error('name') border-red-300 focus:ring-red-500 @enderror" 
                               id="name" 
                               value="{{ old('name', $project->name) }}"
                               placeholder="Ex: Site Web E-commerce"
                               required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="form-label-modern">Description du Projet</label>
                        <textarea name="description" 
                                  class="input-modern @error('description') border-red-300 focus:ring-red-500 @enderror" 
                                  id="description" 
                                  rows="4"
                                  placeholder="Décrivez votre projet en détail...">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dates du projet -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date de début -->
                        <div>
                            <label for="start_date" class="form-label-modern">
                                <i class="fas fa-calendar-plus text-gray-500 mr-1"></i>
                                Date de Début
                            </label>
                            <input type="date" 
                                   name="start_date" 
                                   class="input-modern @error('start_date') border-red-300 focus:ring-red-500 @enderror" 
                                   id="start_date" 
                                   value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
                            @error('start_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de fin -->
                        <div>
                            <label for="end_date" class="form-label-modern">
                                <i class="fas fa-calendar-check text-gray-500 mr-1"></i>
                                Date de Fin
                            </label>
                            <input type="date" 
                                   name="end_date" 
                                   class="input-modern @error('end_date') border-red-300 focus:ring-red-500 @enderror" 
                                   id="end_date" 
                                   value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}">
                            @error('end_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Participants -->
                    <div>
                        <label for="participants" class="form-label-modern">
                            <i class="fas fa-users text-gray-500 mr-1"></i>
                            Participants
                            @if (!Auth::user()->is_premium() && !Auth::user()->is_admin())
                                <span class="badge-warning ml-2">
                                    <i class="fas fa-crown mr-1"></i>Premium
                                </span>
                            @endif
                        </label>
                        <select name="participants[]" 
                                id="participants" 
                                class="input-modern @error('participants') border-red-300 focus:ring-red-500 @enderror"
                                multiple
                                @if (!Auth::user()->is_premium() && !Auth::user()->is_admin()) disabled @endif>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" 
                                        @if(in_array($user->id, json_decode($project->participants, true) ?? [])) selected @endif>
                                    {{ $user->name }}
                                    @if($user->is_admin())
                                        (Admin)
                                    @elseif($user && $user->is_premium())
                                        (Premium)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        
                        @if (!Auth::user()->is_premium() && !Auth::user()->is_admin())
                            <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-700">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    L'ajout de participants est une fonctionnalité premium. 
                                    <a href="{{ route('premium.show') }}" class="text-yellow-800 hover:text-yellow-900 underline font-medium">
                                        Passez à Premium
                                    </a>
                                </p>
                            </div>
                        @else
                            <p class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs participants.
                            </p>
                        @endif
                        
                        @error('participants')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Statut du projet -->
                    <div>
                        <label for="status" class="form-label-modern">
                            <i class="fas fa-tasks text-gray-500 mr-1"></i>
                            Statut du Projet
                        </label>
                        <select name="status" 
                                id="status" 
                                class="input-modern @error('status') border-red-300 focus:ring-red-500 @enderror">
                            <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>📋 En planification</option>
                            <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>🚀 Actif</option>
                            <option value="on-hold" {{ old('status', $project->status) == 'on-hold' ? 'selected' : '' }}>⏸️ En pause</option>
                            <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>✅ Terminé</option>
                            <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>❌ Annulé</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informations supplémentaires -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">
                            <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                            Informations du Projet
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user text-gray-500"></i>
                                <span class="text-gray-600">Créé par :</span>
                                <span class="font-medium text-gray-900">{{ $project->author->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar text-gray-500"></i>
                                <span class="text-gray-600">Créé le :</span>
                                <span class="font-medium text-gray-900">{{ $project->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-tasks text-gray-500"></i>
                                <span class="text-gray-600">Tâches :</span>
                                <span class="font-medium text-gray-900">{{ $project->tasks()->count() }} tâches</span>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn-secondary-modern flex-1 sm:flex-none">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn-primary-modern flex-1 sm:flex-none">
                            <i class="fas fa-save mr-2"></i>
                            Sauvegarder les Modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Amélioration UX pour la sélection multiple
        document.addEventListener('DOMContentLoaded', function() {
            const participantsSelect = document.getElementById('participants');
            
            if (participantsSelect) {
                // Ajouter un indicateur visuel pour la sélection multiple
                participantsSelect.addEventListener('change', function() {
                    const selectedCount = this.selectedOptions.length;
                    const label = document.querySelector('label[for="participants"]');
                    
                    if (selectedCount > 0) {
                        label.innerHTML = label.innerHTML.replace(/\(\d+ sélectionnés?\)/, '') + ` (${selectedCount} sélectionné${selectedCount > 1 ? 's' : ''})`;
                    } else {
                        label.innerHTML = label.innerHTML.replace(/\(\d+ sélectionnés?\)/, '');
                    }
                });
                
                // Déclencher l'événement pour mettre à jour le compteur initial
                participantsSelect.dispatchEvent(new Event('change'));
            }
            
            // Validation des dates
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            
            function validateDates() {
                if (startDate.value && endDate.value) {
                    if (new Date(startDate.value) > new Date(endDate.value)) {
                        endDate.setCustomValidity('La date de fin doit être postérieure à la date de début');
                        endDate.reportValidity();
                    } else {
                        endDate.setCustomValidity('');
                    }
                }
            }
            
            if (startDate && endDate) {
                startDate.addEventListener('change', validateDates);
                endDate.addEventListener('change', validateDates);
            }
        });
    </script>
</x-app-layout>