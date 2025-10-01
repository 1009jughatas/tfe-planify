<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-cog me-2"></i>{{ __('Préférences') }}
            <span class="badge bg-warning text-dark ms-2"><i class="fas fa-crown me-1"></i>Premium</span>
        </h2>
    </x-slot>

    <div class="container py-6 lg:py-12 px-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <!-- Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('preferences.update') }}">
                    @csrf
                    @method('PATCH')

                    <!-- Apparence -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-palette me-2"></i>Apparence</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Mode sombre -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Mode d'Affichage</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="dark_mode" 
                                               name="dark_mode" 
                                               value="1"
                                               {{ old('dark_mode', $preferences->dark_mode ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dark_mode">
                                            <i class="fas fa-moon me-1"></i>Mode Sombre
                                        </label>
                                    </div>
                                </div>

                                <!-- Couleur du thème -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="theme_color" class="form-label">Couleur du Thème</label>
                                    <select class="form-select" id="theme_color" name="theme_color">
                                        @foreach($availableColors as $color => $name)
                                            <option value="{{ $color }}" 
                                                    {{ old('theme_color', $preferences->theme_color ?? '#3b82f6') === $color ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Prévisualisation couleur -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Prévisualisation</label>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach($availableColors as $color => $name)
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="theme_color" 
                                                       id="color_{{ $color }}" 
                                                       value="{{ $color }}"
                                                       {{ old('theme_color', $preferences->theme_color ?? '#3b82f6') === $color ? 'checked' : '' }}>
                                                <label class="form-check-label" for="color_{{ $color }}">
                                                    <span class="d-inline-block rounded-circle" 
                                                          style="width: 30px; height: 30px; background-color: {{ $color }}; border: 2px solid #ddd;">
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notifications et Rappels</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Notifications email -->
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="email_notifications" 
                                               name="email_notifications" 
                                               value="1"
                                               {{ old('email_notifications', $preferences->email_notifications ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email_notifications">
                                            <i class="fas fa-envelope me-1"></i>Notifications par Email
                                        </label>
                                    </div>
                                    <small class="text-muted">Recevoir des notifications sur les activités</small>
                                </div>

                                <!-- Rappels de tâches -->
                                <div class="col-12 col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="task_reminders" 
                                               name="task_reminders" 
                                               value="1"
                                               {{ old('task_reminders', $preferences->task_reminders ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="task_reminders">
                                            <i class="fas fa-clock me-1"></i>Rappels de Tâches
                                        </label>
                                    </div>
                                    <small class="text-muted">Rappels automatiques avant les deadlines</small>
                                </div>

                                <!-- Délai des rappels -->
                                <div class="col-12 mb-3">
                                    <label for="reminder_hours_before" class="form-label">
                                        Rappel avant l'échéance
                                    </label>
                                    <select class="form-select" id="reminder_hours_before" name="reminder_hours_before">
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
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-globe me-2"></i>Langue et Format</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Langue -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="language" class="form-label">Langue de l'Interface</label>
                                    <select class="form-select" id="language" name="language">
                                        @foreach($availableLanguages as $code => $name)
                                            <option value="{{ $code }}" 
                                                    {{ old('language', $preferences->language ?? 'fr') === $code ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Format de date -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="date_format" class="form-label">Format de Date</label>
                                    <select class="form-select" id="date_format" name="date_format">
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

                    <!-- Boutons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer les Préférences
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

