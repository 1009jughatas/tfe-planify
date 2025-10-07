<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Préférences</h1>
                <p class="text-sm text-gray-600 mt-1">Personnalisez votre expérience Planify</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="badge-warning">
                    <i class="fas fa-crown mr-1"></i>Premium
                </span>
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

        <form method="POST" action="{{ route('preferences.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Apparence -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-palette text-primary-600 mr-2"></i>
                        Apparence
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Personnalisez l'apparence de votre interface</p>
                </div>
                <div class="modern-card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mode sombre -->
                        <div class="space-y-3">
                            <label class="form-label-modern">Mode d'Affichage</label>
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
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           class="sr-only peer" 
                                           id="dark_mode" 
                                           name="dark_mode" 
                                           value="1"
                                           {{ old('dark_mode', $preferences->dark_mode ?? false) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Couleur du thème -->
                        <div class="space-y-3">
                            <label class="form-label-modern">Couleur du Thème</label>
                            <select class="input-modern" id="theme_color" name="theme_color">
                                @foreach($availableColors as $color => $name)
                                    <option value="{{ $color }}" 
                                            {{ old('theme_color', $preferences->theme_color ?? '#3b82f6') === $color ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Prévisualisation couleur -->
                    <div class="mt-6">
                        <label class="form-label-modern">Prévisualisation des Couleurs</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mt-3">
                            @foreach($availableColors as $color => $name)
                                <label class="relative cursor-pointer group">
                                    <input class="sr-only peer" 
                                           type="radio" 
                                           name="theme_color" 
                                           value="{{ $color }}"
                                           {{ old('theme_color', $preferences->theme_color ?? '#3b82f6') === $color ? 'checked' : '' }}>
                                    <div class="w-full aspect-square rounded-xl border-2 border-gray-200 peer-checked:border-primary-500 peer-checked:ring-2 peer-checked:ring-primary-200 transition-all group-hover:scale-105"
                                         style="background: linear-gradient(135deg, {{ $color }} 0%, {{ $color }}dd 100%)">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-check text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                        </div>
                                    </div>
                                    <p class="text-xs text-center text-gray-600 mt-2 font-medium">{{ $name }}</p>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-bell text-green-600 mr-2"></i>
                        Notifications et Rappels
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Configurez vos notifications et rappels</p>
                </div>
                <div class="modern-card-body">
                    <div class="space-y-6">
                        <!-- Notifications email -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Notifications par Email</h4>
                                    <p class="text-sm text-gray-600">Recevoir des notifications sur les activités importantes</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       class="sr-only peer" 
                                       id="email_notifications" 
                                       name="email_notifications" 
                                       value="1"
                                       {{ old('email_notifications', $preferences->email_notifications ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>

                        <!-- Rappels de tâches -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Rappels de Tâches</h4>
                                    <p class="text-sm text-gray-600">Rappels automatiques avant les deadlines</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       class="sr-only peer" 
                                       id="task_reminders" 
                                       name="task_reminders" 
                                       value="1"
                                       {{ old('task_reminders', $preferences->task_reminders ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>

                        <!-- Délai des rappels -->
                        <div class="space-y-3">
                            <label class="form-label-modern">Rappel avant l'échéance</label>
                            <select class="input-modern" id="reminder_hours_before" name="reminder_hours_before">
                                <option value="1" {{ old('reminder_hours_before', $preferences->reminder_hours_before ?? 24) == 1 ? 'selected' : '' }}>1 heure avant</option>
                                <option value="6" {{ old('reminder_hours_before', $preferences->reminder_hours_before ?? 24) == 6 ? 'selected' : '' }}>6 heures avant</option>
                                <option value="24" {{ old('reminder_hours_before', $preferences->reminder_hours_before ?? 24) == 24 ? 'selected' : '' }}>1 jour avant</option>
                                <option value="48" {{ old('reminder_hours_before', $preferences->reminder_hours_before ?? 24) == 48 ? 'selected' : '' }}>2 jours avant</option>
                                <option value="72" {{ old('reminder_hours_before', $preferences->reminder_hours_before ?? 24) == 72 ? 'selected' : '' }}>3 jours avant</option>
                                <option value="168" {{ old('reminder_hours_before', $preferences->reminder_hours_before ?? 24) == 168 ? 'selected' : '' }}>1 semaine avant</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Langue et Format -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-globe text-blue-600 mr-2"></i>
                        Langue et Format
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Personnalisez la langue et les formats d'affichage</p>
                </div>
                <div class="modern-card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Langue -->
                        <div class="space-y-3">
                            <label class="form-label-modern">Langue de l'Interface</label>
                            <select class="input-modern" id="language" name="language">
                                @foreach($availableLanguages as $code => $name)
                                    <option value="{{ $code }}" 
                                            {{ old('language', $preferences->language ?? 'fr') === $code ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Format de date -->
                        <div class="space-y-3">
                            <label class="form-label-modern">Format de Date</label>
                            <select class="input-modern" id="date_format" name="date_format">
                                <option value="d/m/Y" {{ old('date_format', $preferences->date_format ?? 'd/m/Y') === 'd/m/Y' ? 'selected' : '' }}>
                                    DD/MM/YYYY (31/12/2025)
                                </option>
                                <option value="m/d/Y" {{ old('date_format', $preferences->date_format ?? 'd/m/Y') === 'm/d/Y' ? 'selected' : '' }}>
                                    MM/DD/YYYY (12/31/2025)
                                </option>
                                <option value="Y-m-d" {{ old('date_format', $preferences->date_format ?? 'd/m/Y') === 'Y-m-d' ? 'selected' : '' }}>
                                    YYYY-MM-DD (2025-12-31)
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paramètres avancés -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-cogs text-gray-600 mr-2"></i>
                        Paramètres Avancés
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Options avancées pour les utilisateurs expérimentés</p>
                </div>
                <div class="modern-card-body">
                    <div class="space-y-6">
                        <!-- Auto-sauvegarde -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-save text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Sauvegarde Automatique</h4>
                                    <p class="text-sm text-gray-600">Sauvegarde automatique des modifications toutes les 5 minutes</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       class="sr-only peer" 
                                       id="auto_save" 
                                       name="auto_save" 
                                       value="1"
                                       {{ old('auto_save', $preferences->auto_save ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>

                        <!-- Mode compact -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-compress text-purple-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Mode Compact</h4>
                                    <p class="text-sm text-gray-600">Interface plus compacte pour afficher plus d'informations</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       class="sr-only peer" 
                                       id="compact_mode" 
                                       name="compact_mode" 
                                       value="1"
                                       {{ old('compact_mode', $preferences->compact_mode ?? false) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6">
                <a href="{{ route('dashboard') }}" class="btn-secondary-modern w-full sm:w-auto">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour au Dashboard
                </a>
                
                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <button type="button" class="btn-secondary-modern w-full sm:w-auto" onclick="resetToDefaults()">
                        <i class="fas fa-undo mr-2"></i>
                        Réinitialiser
                    </button>
                    <button type="submit" class="btn-primary-modern w-full sm:w-auto">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les Préférences
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        /* Styles pour les préférences */
        .modern-card {
            @apply bg-white rounded-xl border border-gray-200 shadow-sm;
        }
        
        .modern-card-header {
            @apply p-6 border-b border-gray-200;
        }
        
        .modern-card-body {
            @apply p-6;
        }
        
        .form-label-modern {
            @apply block text-sm font-medium text-gray-700 mb-2;
        }
        
        .input-modern {
            @apply w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors;
        }
        
        .btn-primary-modern {
            @apply inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200;
        }
        
        .btn-secondary-modern {
            @apply inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200;
        }
        
        .badge-warning {
            @apply inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full;
        }
        
        /* Animations pour les switches */
        .peer:checked ~ div {
            background-color: rgb(59 130 246);
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .modern-card-header,
            .modern-card-body {
                @apply p-4;
            }
        }
    </style>

    <script>
        function resetToDefaults() {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser toutes vos préférences aux valeurs par défaut ?')) {
                // Réinitialiser tous les champs aux valeurs par défaut
                document.getElementById('dark_mode').checked = false;
                document.getElementById('email_notifications').checked = true;
                document.getElementById('task_reminders').checked = true;
                document.getElementById('reminder_hours_before').value = '24';
                document.getElementById('language').value = 'fr';
                document.getElementById('date_format').value = 'd/m/Y';
                document.getElementById('auto_save').checked = true;
                document.getElementById('compact_mode').checked = false;
                
                // Réinitialiser la couleur du thème
                document.querySelector('input[name="theme_color"][value="#3b82f6"]').checked = true;
            }
        }

        // Mise à jour en temps réel de la prévisualisation
        document.addEventListener('DOMContentLoaded', function() {
            const colorInputs = document.querySelectorAll('input[name="theme_color"]');
            const selectInput = document.getElementById('theme_color');
            
            colorInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.checked) {
                        selectInput.value = this.value;
                    }
                });
            });
            
            selectInput.addEventListener('change', function() {
                const correspondingRadio = document.querySelector(`input[name="theme_color"][value="${this.value}"]`);
                if (correspondingRadio) {
                    correspondingRadio.checked = true;
                }
            });
        });
    </script>
</x-app-layout>