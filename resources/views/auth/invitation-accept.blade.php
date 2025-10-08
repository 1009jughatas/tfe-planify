<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-user-plus text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Invitation d'Équipe</h1>
                    <p class="text-sm text-gray-600 mt-1">Rejoignez {{ $invitation->company->name }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="modern-card">
            <div class="modern-card-header text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-building text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">{{ $invitation->company->name }}</h3>
                <p class="text-gray-600 mt-2">Vous avez été invité à rejoindre cette équipe</p>
            </div>
            
            <div class="modern-card-body">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-blue-900 mb-1">Détails de l'invitation</p>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li><strong>Email :</strong> {{ $invitation->email }}</li>
                                <li><strong>Rôle :</strong> 
                                    @if($invitation->role === 'company_admin')
                                        <span class="badge-primary">Administrateur</span>
                                    @else
                                        <span class="badge-secondary">Membre</span>
                                    @endif
                                </li>
                                <li><strong>Expire le :</strong> {{ $invitation->expires_at->format('d/m/Y à H:i') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

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

                <form action="{{ route('invitations.accept.store', $invitation->token) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Informations personnelles -->
                    <div>
                        <label for="name" class="form-label-modern">
                            <i class="fas fa-user text-gray-500 mr-1"></i>
                            Nom complet <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                               class="input-modern @error('name') border-red-300 focus:ring-red-500 @enderror"
                               value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="position" class="form-label-modern">
                                <i class="fas fa-briefcase text-gray-500 mr-1"></i>
                                Poste
                            </label>
                            <input type="text" name="position" id="position"
                                   class="input-modern @error('position') border-red-300 focus:ring-red-500 @enderror"
                                   value="{{ old('position') }}" placeholder="ex: Développeur, Manager...">
                            @error('position')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="department" class="form-label-modern">
                                <i class="fas fa-sitemap text-gray-500 mr-1"></i>
                                Département
                            </label>
                            <input type="text" name="department" id="department"
                                   class="input-modern @error('department') border-red-300 focus:ring-red-500 @enderror"
                                   value="{{ old('department') }}" placeholder="ex: IT, Marketing...">
                            @error('department')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Mot de passe -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="form-label-modern">
                                <i class="fas fa-lock text-gray-500 mr-1"></i>
                                Mot de passe <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" id="password"
                                   class="input-modern @error('password') border-red-300 focus:ring-red-500 @enderror"
                                   required>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="form-label-modern">
                                <i class="fas fa-lock text-gray-500 mr-1"></i>
                                Confirmer le mot de passe <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="input-modern @error('password_confirmation') border-red-300 focus:ring-red-500 @enderror"
                                   required>
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex space-x-4 pt-6">
                        <button type="submit" class="btn-primary-modern flex-1">
                            <i class="fas fa-check mr-2"></i>
                            Rejoindre l'équipe
                        </button>
                        
                        <a href="{{ route('invitations.decline', $invitation->token) }}" 
                           class="btn-secondary-modern flex-1 text-center"
                           onclick="return confirm('Êtes-vous sûr de vouloir décliner cette invitation ?')">
                            <i class="fas fa-times mr-2"></i>
                            Decliner
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Informations sur l'entreprise -->
        <div class="modern-card mt-6">
            <div class="modern-card-header">
                <h4 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-building text-primary-600 mr-2"></i>
                    À propos de {{ $invitation->company->name }}
                </h4>
            </div>
            <div class="modern-card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Plan tarifaire</p>
                        <span class="badge-primary">{{ ucfirst($invitation->company->plan) }}</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Membres actuels</p>
                        <p class="font-medium">{{ $invitation->company->users->count() }}/{{ $invitation->company->user_limit == -1 ? '∞' : $invitation->company->user_limit }}</p>
                    </div>
                </div>
                
                @if($invitation->company->website)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-1">Site web</p>
                        <a href="{{ $invitation->company->website }}" target="_blank" 
                           class="text-primary-600 hover:text-primary-700 text-sm">
                            {{ $invitation->company->website }}
                            <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
