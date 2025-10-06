<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-users-cog text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestion des Utilisateurs</h1>
                    <p class="text-sm text-gray-600 mt-1">Administration et supervision des comptes utilisateurs</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="badge-primary">{{ $totalUsers ?? 0 }} utilisateurs</span>
                <a href="{{ route('admin.users.create') }}" class="btn-primary-modern">
                    <i class="fas fa-user-plus mr-2"></i>
                    Nouvel utilisateur
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Utilisateurs</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalUsers ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Utilisateurs Premium</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $premiumUsers ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-crown text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Administrateurs</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $adminUsers ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shield-alt text-white"></i>
                    </div>
                </div>
            </div>
            
            <div class="stats-card hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Utilisateurs Gratuits</p>
                        <p class="text-2xl font-bold text-gray-900">{{ ($totalUsers ?? 0) - ($premiumUsers ?? 0) - ($adminUsers ?? 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Liste des utilisateurs -->
        <div class="modern-card">
            <div class="modern-card-header">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list text-primary-600 mr-2"></i>
                    Liste des Utilisateurs
                </h3>
            </div>
            <div class="modern-card-body p-0">
                @if($users && $users->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- Avatar -->
                                        <div class="w-12 h-12 bg-gradient-primary rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-lg">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        
                                        <!-- Informations utilisateur -->
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <h4 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h4>
                                                @if ($user->id === Auth::id())
                                                    <span class="badge-primary">Vous</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                            <div class="flex items-center space-x-2 mt-2">
                                                <span class="text-xs text-gray-400">ID: #{{ $user->id }}</span>
                                                <span class="text-xs text-gray-400">•</span>
                                                <span class="text-xs text-gray-400">Inscrit le {{ $user->created_at->format('d/m/Y') }}</span>
                                                <span class="text-xs text-gray-400">•</span>
                                                <span class="text-xs text-gray-400">{{ $user->projects()->count() }} projets</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Badges et actions -->
                                    <div class="flex items-center space-x-3">
                                        <!-- Badges de statut -->
                                        <div class="flex flex-col items-end space-y-2">
                                            <div class="flex space-x-2">
                                                @if ($user->is_admin())
                                                    <span class="badge-danger">Admin</span>
                                                @else
                                                    <span class="badge-secondary">User</span>
                                                @endif
                                                
                                                @if ($user->is_premium)
                                                    <span class="badge-warning">Premium</span>
                                                @else
                                                    <span class="badge-secondary">Gratuit</span>
                                                @endif
                                            </div>
                                            
                                            <!-- Actions -->
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                                   class="btn-secondary-modern text-xs"
                                                   title="Modifier">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Modifier
                                                </a>
                                                
                                                <form action="{{ route('admin.users.toggle-premium', $user->id) }}" 
                                                      method="POST" 
                                                      class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn-{{ $user->is_premium ? 'secondary' : 'accent' }}-modern text-xs"
                                                            title="{{ $user->is_premium ? 'Retirer Premium' : 'Activer Premium' }}">
                                                        <i class="fas fa-crown mr-1"></i>
                                                        {{ $user->is_premium ? 'Retirer' : 'Premium' }}
                                                    </button>
                                                </form>
                                                
                                                @if ($user->id !== Auth::id())
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                                          method="POST" 
                                                          class="inline"
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn-secondary-modern text-xs text-red-600 hover:bg-red-50 hover:text-red-700"
                                                                title="Supprimer">
                                                            <i class="fas fa-trash mr-1"></i>
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun utilisateur trouvé</h3>
                        <p class="text-gray-600 mb-6">Commencez par créer votre premier utilisateur.</p>
                        <a href="{{ route('admin.users.create') }}" class="btn-primary-modern">
                            <i class="fas fa-user-plus mr-2"></i>
                            Créer un utilisateur
                        </a>
                    </div>
                @endif
            </div>
            
            @if($users && $users->hasPages())
                <div class="modern-card-body border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

