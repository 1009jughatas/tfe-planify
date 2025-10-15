<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Préférences</h1>
                <p class="text-sm text-gray-600 mt-1">Personnalisez votre expérience Planify</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(Auth::user() && (Auth::user()->is_premium() || Auth::user()->isPartOfCompany()))
                    @if(Auth::user()->isPartOfCompany())
                        <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-green-400 to-blue-500 text-white text-xs font-medium rounded-full shadow-sm">
                            <i class="fas fa-building mr-1"></i>Entreprise
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-medium rounded-full shadow-sm">
                            <i class="fas fa-crown mr-1"></i>Premium
                        </span>
                    @endif
                @else
                    <a href="{{ route('premium.show') }}" class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-xs font-medium rounded-full shadow-sm hover:shadow-md transition-all duration-200">
                        <i class="fas fa-crown mr-1"></i>Passer Premium
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <div>
                        <h3 class="text-sm font-medium text-green-800">Préférences mises à jour</h3>
                        <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-6">
            <!-- Apparence -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-palette text-blue-600 mr-2"></i>
                        Apparence
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Personnalisez l'apparence de votre interface</p>
                </div>
                <div class="p-6">
                    <!-- Mode Sombre -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center">
                                <i class="fas fa-moon text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">Mode Sombre</h4>
                                <p class="text-sm text-gray-600">Interface sombre pour une meilleure visibilité</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span id="dark-mode-status" class="text-sm text-gray-500">Mode clair</span>
                            <button onclick="toggleDarkModeFromPreferences()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                <i class="fas fa-toggle-off mr-2" id="dark-mode-icon"></i>
                                <span id="dark-mode-text">Activer</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                            <div>
                                <h5 class="text-sm font-medium text-blue-800">Comment utiliser le mode sombre</h5>
                                <p class="text-sm text-blue-700 mt-1">
                                    Vous pouvez également basculer le mode sombre directement depuis le menu latéral en cliquant sur l'icône lune/soleil dans le header du menu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6">
                <a href="{{ Auth::user() && Auth::user()->isPartOfCompany() ? route('entreprise.dashboard') : route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 w-full sm:w-auto">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour au Dashboard
                </a>
            </div>
        </div>
    </div>

    <style>
        /* Styles pour les préférences */
        .modern-card {
            @apply bg-white rounded-xl border border-gray-200 shadow-sm;
        }
        
        .modern-card-header {
            @apply px-6 py-4 border-b border-gray-200 bg-gray-50;
        }
        
        .modern-card-body {
            @apply p-6;
        }
    </style>

    <script>
        // Gestion du mode sombre depuis les préférences
        function updateDarkModeStatus() {
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            const statusElement = document.getElementById('dark-mode-status');
            const iconElement = document.getElementById('dark-mode-icon');
            const textElement = document.getElementById('dark-mode-text');
            
            if (isDarkMode) {
                statusElement.textContent = 'Mode sombre';
                iconElement.className = 'fas fa-toggle-on mr-2';
                textElement.textContent = 'Désactiver';
            } else {
                statusElement.textContent = 'Mode clair';
                iconElement.className = 'fas fa-toggle-off mr-2';
                textElement.textContent = 'Activer';
            }
        }
        
        function toggleDarkModeFromPreferences() {
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            const newMode = !isDarkMode;
            
            localStorage.setItem('darkMode', newMode);
            
            if (newMode) {
                document.documentElement.classList.add('dark-mode');
            } else {
                document.documentElement.classList.remove('dark-mode');
            }
            
            updateDarkModeStatus();
            
            // Afficher un message de succès
            showSuccessMessage('Mode sombre ' + (newMode ? 'activé' : 'désactivé') + ' avec succès !');
        }
        
        function showSuccessMessage(message) {
            // Créer un message de succès temporaire
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
            successDiv.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(successDiv);
            
            // Supprimer le message après 3 secondes
            setTimeout(() => {
                successDiv.style.opacity = '0';
                successDiv.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(successDiv);
                }, 300);
            }, 3000);
        }
        
        // Initialiser le statut au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            updateDarkModeStatus();
        });
    </script>
</x-app-layout>