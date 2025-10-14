@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                <i class="fas fa-plus text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Créer un Nouveau Projet</h1>
                <p class="text-gray-600 mt-1 flex items-center">
                    <i class="fas fa-building mr-2 text-blue-500"></i>
                    {{ $company->name }}
                </p>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Informations du projet</h2>
            <p class="text-sm text-gray-600">Remplissez les informations de base pour créer un nouveau projet</p>
        </div>
        
        <form method="POST" action="{{ route('entreprise.projets.store') }}" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Nom du projet -->
                <div class="lg:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom du projet <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Nom du projet..."
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="lg:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Description du projet...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de début -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de début
                    </label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ old('start_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date de fin -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de fin
                    </label>
                    <input type="date" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ old('end_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priorité -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                        Priorité <span class="text-red-500">*</span>
                    </label>
                    <select id="priority" 
                            name="priority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('priority') border-red-500 @enderror"
                            required>
                        <option value="">Sélectionner une priorité</option>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Faible</option>
                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Moyenne</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Haute</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Participants -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Participants
                    </label>
                    <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-4">
                        @forelse($employees as $employee)
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       id="participant_{{ $employee->id }}" 
                                       name="participants[]" 
                                       value="{{ $employee->id }}"
                                       {{ in_array($employee->id, old('participants', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="participant_{{ $employee->id }}" class="flex items-center space-x-2 cursor-pointer">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                        <span class="text-xs text-white font-medium">{{ substr($employee->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $employee->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $employee->email }}</p>
                                    </div>
                                </label>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-users text-2xl mb-2"></i>
                                <p>Aucun employé disponible</p>
                                <p class="text-xs">Invitez des employés pour les assigner aux projets</p>
                            </div>
                        @endforelse
                    </div>
                    @error('participants')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('entreprise.projets.index') }}" class="btn-secondary-modern">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Annuler
                </a>
                <button type="submit" class="btn-primary-modern">
                    <i class="fas fa-save mr-2"></i>
                    Créer le projet
                </button>
            </div>
        </form>
    </div>

    <!-- Aide -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start space-x-3">
            <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-lightbulb text-white text-xs"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-blue-900 mb-1">💡 Conseils pour créer un projet</h3>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Choisissez un nom clair et descriptif pour votre projet</li>
                    <li>• Définissez des dates réalistes pour le début et la fin</li>
                    <li>• Assignez les bonnes personnes selon leurs compétences</li>
                    <li>• Utilisez les priorités pour organiser le travail</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-primary-modern {
        @apply inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200;
    }
    
    .btn-secondary-modern {
        @apply inline-flex items-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg shadow-sm border border-gray-300 hover:shadow-md hover:scale-105 transition-all duration-200;
    }
</style>

<script>
    // Validation des dates
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const endDateInput = document.getElementById('end_date');
        
        if (startDate && endDateInput.value) {
            const endDate = new Date(endDateInput.value);
            if (endDate < startDate) {
                endDateInput.setCustomValidity('La date de fin doit être après la date de début');
            } else {
                endDateInput.setCustomValidity('');
            }
        }
    });
    
    document.getElementById('end_date').addEventListener('change', function() {
        const endDate = new Date(this.value);
        const startDateInput = document.getElementById('start_date');
        
        if (endDate && startDateInput.value) {
            const startDate = new Date(startDateInput.value);
            if (endDate < startDate) {
                this.setCustomValidity('La date de fin doit être après la date de début');
            } else {
                this.setCustomValidity('');
            }
        }
    });
</script>
@endsection
