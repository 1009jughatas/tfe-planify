<nav x-data="{ collapsed: JSON.parse(localStorage.getItem('collapsed')) ?? false }"
     @click.away="localStorage.setItem('collapsed', JSON.stringify(collapsed))"
     class="h-screen bg-white border-r border-gray-200 shadow-lg flex flex-col hidden lg:flex">
    
    <!-- Header with Logo -->
    <div class="p-6 border-b border-gray-200 bg-white">
        <div class="flex items-center justify-between">
            <a href="{{ Auth::user() && Auth::user()->isPartOfCompany() ? route('entreprise.dashboard') : route('dashboard') }}" class="flex items-center space-x-3 group">
                <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-all duration-200 overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Planify" class="w-8 h-8 object-contain filter brightness-0 invert">
                </div>
                <div x-show="!collapsed" class="transition-all duration-200">
                    <h1 class="text-xl font-bold text-gray-900">Planify</h1>
                    <div class="flex items-center space-x-2 mt-1">
                        @if (Auth::user() && Auth::user()->isAdminEntreprise())
                            <span class="text-xs font-medium text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                                <i class="fas fa-building mr-1"></i>Admin Entreprise
                            </span>
                        @elseif (Auth::user() && Auth::user()->isUserEntreprise())
                            <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                                <i class="fas fa-users mr-1"></i>Équipe
                            </span>
                        @elseif (Auth::user() && Auth::user()->isUserIndependant())
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
            </a>
            
            <!-- Toggle Button -->
            <button @click="collapsed = !collapsed; localStorage.setItem('collapsed', JSON.stringify(collapsed));" 
                    class="p-2 hover:bg-white/50 rounded-lg transition-all duration-200 group">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 px-4 py-6 space-y-1">
        <!-- Dashboard -->
        <div class="nav-section">
            <div x-show="!collapsed" class="nav-section-title">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Principal</span>
            </div>
            <x-nav-link :href="Auth::user() && Auth::user()->isPartOfCompany() ? route('entreprise.dashboard') : route('dashboard')" 
                        :active="request()->routeIs('dashboard') || request()->routeIs('entreprise.dashboard')" 
                        class="nav-item-modern {{ request()->routeIs('dashboard') || request()->routeIs('entreprise.dashboard') ? 'nav-item-active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span x-show="!collapsed" class="nav-text">Dashboard</span>
                <div x-show="!collapsed && request()->routeIs('dashboard')" class="nav-indicator"></div>
            </x-nav-link>
        </div>

        <!-- Projects -->
        @auth
        <div class="nav-section">
            <div x-show="!collapsed" class="nav-section-title">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestion</span>
            </div>
            <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')"
                        class="nav-item-modern {{ request()->routeIs('projects.*') ? 'nav-item-active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <span x-show="!collapsed" class="nav-text">Mes Projets</span>
                <div x-show="!collapsed && request()->routeIs('projects.*')" class="nav-indicator"></div>
            </x-nav-link>
        </div>
        @endauth

        <!-- Premium -->
        @if (Auth::user() && !Auth::user()->is_premium() && !Auth::user()->is_admin())
        <div class="nav-section">
            <div x-show="!collapsed" class="nav-section-title">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Premium</span>
            </div>
            <x-nav-link :href="route('premium.show')" :active="request()->routeIs('premium.*')"
                        class="nav-item-modern nav-item-premium {{ request()->routeIs('premium.*') ? 'nav-item-active' : '' }}">
                <div class="nav-icon nav-icon-premium">
                    <i class="fas fa-crown"></i>
                </div>
                <span x-show="!collapsed" class="nav-text">Devenir Premium</span>
                <div x-show="!collapsed && request()->routeIs('premium.*')" class="nav-indicator"></div>
            </x-nav-link>
        </div>
        @endif

        <!-- Preferences -->
        @if (Auth::user() && (Auth::user()->is_premium() || Auth::user()->is_admin()))
        <div class="nav-section">
            <div x-show="!collapsed" class="nav-section-title">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Paramètres</span>
            </div>
            <x-nav-link :href="route('preferences.edit')" :active="request()->routeIs('preferences.*')"
                        class="nav-item-modern {{ request()->routeIs('preferences.*') ? 'nav-item-active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <span x-show="!collapsed" class="nav-text">Préférences</span>
                <div x-show="!collapsed && request()->routeIs('preferences.*')" class="nav-indicator"></div>
            </x-nav-link>
        </div>
        @endif

        <!-- Admin Section -->
        @if (Auth::user() && Auth::user()->is_admin())
        <div class="nav-section">
            <div x-show="!collapsed" class="nav-section-title">
                <span class="text-xs font-semibold text-red-500 uppercase tracking-wider">Administration</span>
            </div>
            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                        class="nav-item-modern nav-item-admin {{ request()->routeIs('admin.*') ? 'nav-item-active' : '' }}">
                <div class="nav-icon nav-icon-admin">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <span x-show="!collapsed" class="nav-text">Panneau Admin</span>
                <div x-show="!collapsed && request()->routeIs('admin.*')" class="nav-indicator nav-indicator-admin"></div>
            </x-nav-link>
        </div>
        @endif
    </div>

    <!-- User Section -->
    <div class="p-4 border-t border-gray-200 bg-gray-50">
        <!-- User Info -->
        <div x-show="!collapsed" class="flex items-center space-x-3 mb-4 p-3 bg-white rounded-lg border border-gray-200">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                <span class="text-white font-semibold text-sm">{{ Auth::user() ? substr(Auth::user()->name, 0, 1) : 'U' }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'email@example.com' }}</p>
            </div>
        </div>

        <!-- Profile & Logout -->
        <div class="space-y-1">
            <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')"
                        class="nav-item-modern nav-item-user {{ request()->routeIs('profile.*') ? 'nav-item-active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-user"></i>
                </div>
                <span x-show="!collapsed" class="nav-text">Mon Profil</span>
            </x-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full nav-item-modern nav-item-logout">
                    <div class="nav-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <span x-show="!collapsed" class="nav-text">Déconnexion</span>
                </button>
            </form>
        </div>
    </div>

    <style>
        /* Navigation Styles */
        .nav-section {
            @apply mb-8;
        }
        
        .nav-section-title {
            @apply px-4 py-3 mb-3;
        }
        
        .nav-item-modern {
            @apply flex items-center px-3 py-3 text-gray-700 rounded-lg transition-all duration-200 group relative mx-1;
        }
        
        .nav-item-modern:hover {
            @apply bg-gray-100 text-gray-900;
        }
        
        .nav-item-active {
            @apply bg-blue-50 text-blue-700 border-r-2 border-blue-500;
        }
        
        .nav-item-premium {
            @apply bg-gradient-to-r from-purple-50 to-pink-50 text-purple-700 border border-purple-200/50;
            background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);
        }
        
        .nav-item-premium:hover {
            @apply bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800;
            background: linear-gradient(135deg, rgba(147, 51, 234, 0.2) 0%, rgba(236, 72, 153, 0.2) 100%);
        }
        
        .nav-item-admin {
            @apply bg-gradient-to-r from-red-50 to-pink-50 text-red-700 border border-red-200/50;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);
        }
        
        .nav-item-admin:hover {
            @apply bg-gradient-to-r from-red-100 to-pink-100 text-red-800;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(236, 72, 153, 0.2) 100%);
        }
        
        .nav-item-user:hover {
            @apply bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
        }
        
        .nav-item-logout {
            @apply text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 hover:text-red-700;
        }
        
        .nav-icon {
            @apply w-6 h-6 flex items-center justify-center flex-shrink-0 rounded-lg transition-all duration-300;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .nav-item-modern:hover .nav-icon {
            background: rgba(255, 255, 255, 0.4);
            transform: scale(1.1);
        }
        
        .nav-icon-premium {
            @apply text-purple-600;
            background: rgba(147, 51, 234, 0.1);
        }
        
        .nav-icon-admin {
            @apply text-red-600;
            background: rgba(239, 68, 68, 0.1);
        }
        
        .nav-text {
            @apply ml-4 font-semibold text-sm;
        }
        
        .nav-indicator {
            @apply absolute right-3 w-3 h-3 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full shadow-sm;
        }
        
        .nav-indicator-admin {
            @apply bg-gradient-to-r from-red-500 to-pink-500;
        }
        
        /* Collapsed state */
        [x-data] .nav-section-title {
            display: none;
        }
        
        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .nav-item-modern {
                @apply px-3 py-3 mx-1;
            }
            
            .nav-text {
                @apply ml-3;
            }
        }
        
        /* Animation for section titles */
        .nav-section-title span {
            @apply relative;
        }
        
        .nav-section-title span::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            transition: width 0.3s ease;
        }
        
        .nav-section:hover .nav-section-title span::after {
            width: 100%;
        }
    </style>
</nav>