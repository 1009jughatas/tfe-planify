@extends('company-admin.layouts.app')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres')

@section('content')
<!-- En-tête -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Paramètres de l'entreprise</h2>
    <p class="text-gray-600 mt-1">Gérez les informations et paramètres de votre entreprise</p>
</div>

<!-- Informations générales -->
<div class="modern-card mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Informations générales</h3>
    
    <form method="POST" action="{{ route('company-admin.settings.update') }}">
        @csrf
        @method('PATCH')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom de l'entreprise</label>
                <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" 
                       class="input-modern @error('name') border-red-300 @enderror" required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email principal</label>
                <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}" 
                       class="input-modern @error('email') border-red-300 @enderror" required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $company->phone) }}" 
                       class="input-modern @error('phone') border-red-300 @enderror">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Site web</label>
                <input type="url" name="website" id="website" value="{{ old('website', $company->website) }}" 
                       class="input-modern @error('website') border-red-300 @enderror" 
                       placeholder="https://www.exemple.com">
                @error('website')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                <textarea name="address" id="address" rows="3" 
                          class="input-modern @error('address') border-red-300 @enderror" 
                          placeholder="Adresse complète de l'entreprise">{{ old('address', $company->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button type="submit" class="btn-primary-modern">
                <i class="fas fa-save mr-2"></i>
                Sauvegarder les modifications
            </button>
        </div>
    </form>
</div>

<!-- Statistiques de l'entreprise -->
<div class="modern-card mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Statistiques de l'entreprise</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <h4 class="text-2xl font-bold text-gray-900">{{ $company->users()->count() }}</h4>
            <p class="text-sm text-gray-600">Utilisateurs actifs</p>
        </div>
        
        <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-folder text-white text-xl"></i>
            </div>
            <h4 class="text-2xl font-bold text-gray-900">{{ $company->projects()->count() }}</h4>
            <p class="text-sm text-gray-600">Projets créés</p>
        </div>
        
        <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-tasks text-white text-xl"></i>
            </div>
            <h4 class="text-2xl font-bold text-gray-900">
                {{ $company->projects()->withCount('tasks')->get()->sum('tasks_count') }}
            </h4>
            <p class="text-sm text-gray-600">Tâches créées</p>
        </div>
        
        <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-calendar-alt text-white text-xl"></i>
            </div>
            <h4 class="text-2xl font-bold text-gray-900">{{ $company->created_at->diffInDays(now()) }}</h4>
            <p class="text-sm text-gray-600">Jours d'activité</p>
        </div>
    </div>
</div>

<!-- Informations de l'abonnement -->
<div class="modern-card mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Informations de l'abonnement</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Plan actuel</h4>
            <p class="text-lg font-semibold text-gray-900">
                @if($company->user_limit === null)
                    Enterprise
                @elseif($company->user_limit == 20)
                    Professional
                @else
                    Starter
                @endif
            </p>
            <p class="text-sm text-gray-600">{{ $company->monthly_price }}€/mois</p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Utilisateurs</h4>
            <p class="text-lg font-semibold text-gray-900">
                {{ $company->users()->count() }} / {{ $company->user_limit ?? '∞' }}
            </p>
            <p class="text-sm text-gray-600">
                {{ $company->user_limit && $company->users()->count() >= $company->user_limit ? 'Limite atteinte' : 'Disponibles' }}
            </p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Prochain paiement</h4>
            <p class="text-lg font-semibold text-gray-900">{{ now()->addMonth()->format('d/m/Y') }}</p>
            <p class="text-sm text-gray-600">{{ $company->monthly_price }}€</p>
        </div>
    </div>
    
    <div class="mt-6">
        <a href="{{ route('company-admin.subscription') }}" class="btn-secondary-modern">
            <i class="fas fa-credit-card mr-2"></i>
            Gérer l'abonnement
        </a>
    </div>
</div>

<!-- Zone critique - Suppression du compte -->
<div class="modern-card border-red-200 bg-red-50">
    <h3 class="text-lg font-semibold text-red-900 mb-6">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        Zone critique
    </h3>
    
    <div class="bg-white rounded-lg p-4 border border-red-200">
        <h4 class="text-md font-semibold text-red-900 mb-2">Supprimer l'entreprise</h4>
        <p class="text-sm text-red-700 mb-4">
            Cette action supprimera définitivement votre entreprise et toutes les données associées :
        </p>
        
        <ul class="text-sm text-red-700 mb-4 list-disc list-inside space-y-1">
            <li>Tous les utilisateurs de l'entreprise</li>
            <li>Tous les projets et leurs tâches</li>
            <li>Toutes les données et fichiers</li>
            <li>L'historique des activités</li>
        </ul>
        
        <p class="text-sm font-semibold text-red-800 mb-4">
            ⚠️ Cette action est irréversible et ne peut pas être annulée.
        </p>
        
        <div class="flex space-x-3">
            <button onclick="confirmCompanyDeletion()" 
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-trash mr-2"></i>
                Supprimer l'entreprise
            </button>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeDeleteModal()"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Confirmer la suppression
                        </h3>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">
                                Êtes-vous absolument certain de vouloir supprimer l'entreprise 
                                <strong>"{{ $company->name }}"</strong> ?
                            </p>
                            <p class="text-sm text-red-600 mt-2">
                                Toutes les données seront définitivement perdues.
                            </p>
                            <div class="mt-4">
                                <input type="text" id="confirmationInput" 
                                       placeholder="Tapez 'SUPPRIMER' pour confirmer"
                                       class="input-modern w-full">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button id="deleteButton" onclick="deleteCompany()" 
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors w-full sm:w-auto sm:ml-3"
                        disabled>
                    Supprimer définitivement
                </button>
                <button onclick="closeDeleteModal()" 
                        class="mt-3 btn-secondary-modern w-full sm:mt-0 sm:w-auto">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmCompanyDeletion() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('confirmationInput').value = '';
    document.getElementById('deleteButton').disabled = true;
}

// Activer le bouton de suppression seulement si "SUPPRIMER" est tapé
document.getElementById('confirmationInput').addEventListener('input', function() {
    const deleteButton = document.getElementById('deleteButton');
    deleteButton.disabled = this.value !== 'SUPPRIMER';
});

function deleteCompany() {
    // TODO: Implémenter la suppression de l'entreprise
    alert('Fonctionnalité de suppression d\'entreprise à implémenter');
    closeDeleteModal();
}
</script>
@endpush
