<div x-data="{ mobileMenuOpen: false }" class="lg:hidden">
    <!-- Mobile menu button -->
    <button @click="mobileMenuOpen = !mobileMenuOpen" 
            class="fixed top-4 left-4 z-50 p-3 bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-200 group">
        <div class="w-6 h-6 flex flex-col justify-center items-center space-y-1">
            <span class="block w-5 h-0.5 bg-gray-600 transition-all duration-200 group-hover:bg-gray-800" 
                  :class="{ 'rotate-45 translate-y-1.5': mobileMenuOpen }"></span>
            <span class="block w-5 h-0.5 bg-gray-600 transition-all duration-200 group-hover:bg-gray-800"
                  :class="{ 'opacity-0': mobileMenuOpen }"></span>
            <span class="block w-5 h-0.5 bg-gray-600 transition-all duration-200 group-hover:bg-gray-800"
                  :class="{ '-rotate-45 -translate-y-1.5': mobileMenuOpen }"></span>
        </div>
    </button>

    <!-- Mobile menu overlay -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"></div>

    <!-- Mobile menu -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed top-0 left-0 w-80 h-full bg-white shadow-2xl z-50 overflow-y-auto">
        
        <!-- Mobile menu header -->
        <div class="sticky top-0 bg-gradient-to-r from-primary-500 to-primary-600 text-white p-6 border-b border-primary-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-xl">P</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Planify</h1>
                        <div class="flex items-center space-x-2">
                            @if (Auth::user() && Auth::user()->is_premium())
                                <span class="text-xs font-medium bg-accent-500/20 backdrop-blur-sm px-2 py-1 rounded-full">
                                    <i class="fas fa-crown mr-1"></i>Premium
                                </span>
                            @elseif (Auth::user() && Auth::user()->is_admin())
                                <span class="text-xs font-medium bg-red-500/20 backdrop-blur-sm px-2 py-1 rounded-full">
                                    <i class="fas fa-shield-alt mr-1"></i>Admin
                                </span>
                            @else
                                <span class="text-xs font-medium bg-white/20 backdrop-blur-sm px-2 py-1 rounded-full">
                                    <i class="fas fa-user mr-1"></i>Gratuit
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <button @click="mobileMenuOpen = false" class="p-2 hover:bg-white/10 rounded-lg transition-all duration-200">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- User Info Section -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-lg">{{ Auth::user() ? substr(Auth::user()->name, 0, 1) : 'U' }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ Auth::user()->name ?? 'Utilisateur' }}</h3>
                    <p class="text-sm text-gray-600 truncate">{{ Auth::user()->email ?? 'email@example.com' }}</p>
                    <div class="flex items-center space-x-2 mt-1">
                        @if(Auth::user() && Auth::user()->isAdminEntreprise())
                            <span class="text-xs font-medium text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                                <i class="fas fa-building mr-1"></i>Admin Entreprise
                            </span>
                        @elseif(Auth::user() && Auth::user()->isUserEntreprise())
                            <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                                <i class="fas fa-users mr-1"></i>Équipe
                            </span>
                        @elseif(Auth::user() && Auth::user()->isUserIndependant())
                            <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                <i class="fas fa-user mr-1"></i>Indépendant
                            </span>
                        @else
                            <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded-full">
                                <i class="fas fa-user mr-1"></i>Utilisateur
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu items -->
        <nav class="flex-1 p-6 space-y-6">
            <!-- Principal -->
            <div class="nav-section">
                <h3 class="nav-section-title">Principal</h3>
                <div class="space-y-1">
                    <x-nav-link :href="Auth::user() && Auth::user()->isPartOfCompany() ? route('entreprise.dashboard') : route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('entreprise.dashboard')" 
                               class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'mobile-nav-active' : '' }}">
                        <div class="mobile-nav-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="mobile-nav-content">
                            <span class="mobile-nav-text">Dashboard</span>
                            <span class="mobile-nav-desc">Vue d'ensemble</span>
                        </div>
                        <div class="mobile-nav-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </x-nav-link>
                </div>
            </div>

            <!-- Gestion -->
            @auth
            <div class="nav-section">
                <h3 class="nav-section-title">Gestion</h3>
                <div class="space-y-1">
                    <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')"
                               class="mobile-nav-item {{ request()->routeIs('projects.*') ? 'mobile-nav-active' : '' }}">
                        <div class="mobile-nav-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div class="mobile-nav-content">
                            <span class="mobile-nav-text">Mes Projets</span>
                            <span class="mobile-nav-desc">Gérer vos projets</span>
                        </div>
                        <div class="mobile-nav-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </x-nav-link>
                </div>
            </div>
            @endauth

            <!-- Premium -->
            @if (Auth::user() && !Auth::user()->is_premium() && !Auth::user()->is_admin())
            <div class="nav-section">
                <h3 class="nav-section-title">Premium</h3>
                <div class="space-y-1">
                    <x-nav-link :href="route('premium.show')" :active="request()->routeIs('premium.*')"
                               class="mobile-nav-item mobile-nav-premium {{ request()->routeIs('premium.*') ? 'mobile-nav-active' : '' }}">
                        <div class="mobile-nav-icon mobile-nav-icon-premium">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="mobile-nav-content">
                            <span class="mobile-nav-text">Devenir Premium</span>
                            <span class="mobile-nav-desc">Débloquez toutes les fonctionnalités</span>
                        </div>
                        <div class="mobile-nav-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </x-nav-link>
                </div>
            </div>
            @endif

            <!-- Paramètres -->
            @if (Auth::user() && (Auth::user()->is_premium() || Auth::user()->is_admin()))
            <div class="nav-section">
                <h3 class="nav-section-title">Paramètres</h3>
                <div class="space-y-1">
                    <x-nav-link :href="route('preferences.edit')" :active="request()->routeIs('preferences.*')"
                               class="mobile-nav-item {{ request()->routeIs('preferences.*') ? 'mobile-nav-active' : '' }}">
                        <div class="mobile-nav-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="mobile-nav-content">
                            <span class="mobile-nav-text">Préférences</span>
                            <span class="mobile-nav-desc">Personnaliser l'interface</span>
                        </div>
                        <div class="mobile-nav-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </x-nav-link>
                </div>
            </div>
            @endif

            <!-- Administration -->
            @if (Auth::user() && Auth::user()->is_admin())
            <div class="nav-section">
                <h3 class="nav-section-title">Administration</h3>
                <div class="space-y-1">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                               class="mobile-nav-item mobile-nav-admin {{ request()->routeIs('admin.*') ? 'mobile-nav-active' : '' }}">
                        <div class="mobile-nav-icon mobile-nav-icon-admin">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="mobile-nav-content">
                            <span class="mobile-nav-text">Panneau Admin</span>
                            <span class="mobile-nav-desc">Gérer l'application</span>
                        </div>
                        <div class="mobile-nav-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </x-nav-link>
                </div>
            </div>
            @endif

            <!-- Profil -->
            <div class="nav-section">
                <h3 class="nav-section-title">Compte</h3>
                <div class="space-y-1">
                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')"
                               class="mobile-nav-item {{ request()->routeIs('profile.*') ? 'mobile-nav-active' : '' }}">
                        <div class="mobile-nav-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="mobile-nav-content">
                            <span class="mobile-nav-text">Mon Profil</span>
                            <span class="mobile-nav-desc">Modifier mes informations</span>
                        </div>
                        <div class="mobile-nav-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </x-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full mobile-nav-item mobile-nav-logout">
                            <div class="mobile-nav-icon">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <div class="mobile-nav-content">
                                <span class="mobile-nav-text">Déconnexion</span>
                                <span class="mobile-nav-desc">Se déconnecter</span>
                            </div>
                            <div class="mobile-nav-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Footer -->
        <div class="p-6 bg-gray-50 border-t border-gray-200">
            <div class="text-center">
                <p class="text-xs text-gray-500">© 2025 Planify. Tous droits réservés.</p>
                <div class="flex justify-center space-x-4 mt-2">
                    <a href="#" class="text-xs text-gray-400 hover:text-gray-600">Aide</a>
                    <a href="#" class="text-xs text-gray-400 hover:text-gray-600">Support</a>
                    <a href="#" class="text-xs text-gray-400 hover:text-gray-600">Mentions légales</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Mobile Navigation Styles */
        .nav-section {
            @apply mb-6;
        }
        
        .nav-section-title {
            @apply text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-1;
        }
        
        .mobile-nav-item {
            @apply flex items-center p-4 rounded-xl transition-all duration-200 group;
        }
        
        .mobile-nav-item:hover {
            @apply bg-gray-100 transform translate-x-1;
        }
        
        .mobile-nav-active {
            @apply bg-primary-50 border border-primary-200 text-primary-700;
        }
        
        .mobile-nav-premium {
            @apply bg-gradient-to-r from-accent-50 to-accent-100 text-accent-700 border border-accent-200;
        }
        
        .mobile-nav-premium:hover {
            @apply bg-gradient-to-r from-accent-100 to-accent-200;
        }
        
        .mobile-nav-admin {
            @apply bg-red-50 text-red-700 border border-red-200;
        }
        
        .mobile-nav-admin:hover {
            @apply bg-red-100;
        }
        
        .mobile-nav-logout {
            @apply text-red-600 hover:bg-red-50 hover:text-red-700;
        }
        
        .mobile-nav-icon {
            @apply w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 mr-4;
            @apply bg-gray-100 text-gray-600;
        }
        
        .mobile-nav-icon-premium {
            @apply bg-accent-100 text-accent-600;
        }
        
        .mobile-nav-icon-admin {
            @apply bg-red-100 text-red-600;
        }
        
        .mobile-nav-content {
            @apply flex-1 min-w-0;
        }
        
        .mobile-nav-text {
            @apply block text-base font-medium;
        }
        
        .mobile-nav-desc {
            @apply block text-sm opacity-75 mt-1;
        }
        
        .mobile-nav-arrow {
            @apply text-gray-400 group-hover:text-gray-600 transition-colors;
        }
        
        /* Hamburger animation */
        .hamburger-line {
            @apply block w-5 h-0.5 bg-gray-600 transition-all duration-200;
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .mobile-nav-item {
                @apply p-3;
            }
            
            .mobile-nav-icon {
                @apply w-10 h-10 mr-3;
            }
        }
    </style>
</div>