<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-plus-circle me-2"></i>{{ __('Nouvelle Tâche') }} : {{ $project->name }}
        </h2>
    </x-slot>

    <div class="container py-6 lg:py-12 px-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Créer une Nouvelle Tâche</h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('tasks.store', $project->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $parent_id ?? '' }}">
                            
                            <!-- Titre -->
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    Titre de la Tâche <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="title" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       value="{{ old('title') }}"
                                       placeholder="Ex: Développer la page d'accueil"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          rows="4"
                                          placeholder="Décrivez la tâche en détail...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date d'échéance -->
                            <div class="mb-3">
                                <label for="due_date" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Date d'Échéance
                                </label>
                                <input type="date" 
                                       name="due_date" 
                                       class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date"
                                       value="{{ old('due_date') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Priorité -->
                            <div class="mb-3">
                                <label for="priority" class="form-label">
                                    <i class="fas fa-flag me-1"></i>Priorité
                                </label>
                                <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror">
                                    <option value="0" {{ old('priority') == 0 ? 'selected' : '' }}>Basse</option>
                                    <option value="1" {{ old('priority') == 1 ? 'selected' : '' }}>Moyenne</option>
                                    <option value="2" {{ old('priority') == 2 ? 'selected' : '' }}>Haute</option>
                                    <option value="3" {{ old('priority') == 3 ? 'selected' : '' }}>Urgente</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Assigner à -->
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">
                                    <i class="fas fa-user me-1"></i>Assigner à
                                    @if (!Auth::user()->is_premium && !Auth::user()->is_admin())
                                        <span class="badge bg-warning text-dark ms-2">
                                            <i class="fas fa-crown me-1"></i>Premium
                                        </span>
                                    @endif
                                </label>
                                <select name="assigned_to" 
                                        id="assigned_to" 
                                        class="form-select @error('assigned_to') is-invalid @enderror"
                                        @if (!Auth::user()->is_premium && !Auth::user()->is_admin()) disabled @endif>
                                    <option value="">-- Non assignée --</option>
                                    @foreach($participants as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if (!Auth::user()->is_premium && !Auth::user()->is_admin())
                                    <small class="text-muted">
                                        L'assignation de tâches est une fonctionnalité premium. 
                                        <a href="{{ route('premium.show') }}">Passez à Premium</a>
                                    </small>
                                @endif
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('projects.tasks', $project->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Créer la Tâche
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>