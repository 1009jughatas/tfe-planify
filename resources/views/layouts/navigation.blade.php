<!-- Menu Hamburger Button -->
<button @click="sidebarOpen = !sidebarOpen" 
        class="fixed top-4 left-4 z-50 p-3 bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-200 group lg:hidden">
    <div class="w-6 h-6 flex flex-col justify-center items-center space-y-1">
        <span class="block w-5 h-0.5 bg-gray-600 transition-all duration-200 group-hover:bg-gray-800" 
              :class="{ 'rotate-45 translate-y-1.5': sidebarOpen }"></span>
        <span class="block w-5 h-0.5 bg-gray-600 transition-all duration-200 group-hover:bg-gray-800"
              :class="{ 'opacity-0': sidebarOpen }"></span>
        <span class="block w-5 h-0.5 bg-gray-600 transition-all duration-200 group-hover:bg-gray-800"
              :class="{ '-rotate-45 -translate-y-1.5': sidebarOpen }"></span>
    </div>
</button>

<!-- Overlay pour mobile -->
<div x-show="sidebarOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"></div>

<!-- Sidebar Navigation -->
<nav x-data="{ 
        sidebarOpen: false, 
        collapsed: JSON.parse(localStorage.getItem('collapsed')) ?? false 
    }"
     @click.away="if(window.innerWidth < 1024) { sidebarOpen = false } else { localStorage.setItem('collapsed', JSON.stringify(collapsed)) }"
     :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full lg:translate-x-0': !sidebarOpen }"
     class="fixed lg:relative top-0 left-0 w-80 lg:w-64 xl:w-72 h-full bg-white border-r border-gray-200 shadow-xl lg:shadow-lg flex flex-col z-50 transition-transform duration-300 ease-in-out">
    
    <!-- Header with Logo -->
    <div class="p-6 border-b border-gray-200 bg-white">
        <div class="flex items-center justify-between">
            <a href="{{ Auth::user() && Auth::user()->isPartOfCompany() ? route('entreprise.dashboard') : route('dashboard') }}" class="flex items-center space-x-3 group">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-all duration-200 overflow-hidden">
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
            
            <!-- Toggle Button pour desktop -->
            <button @click="collapsed = !collapsed; localStorage.setItem('collapsed', JSON.stringify(collapsed));" 
                    class="p-2 hover:bg-gray-100 rounded-lg transition-all duration-200 group hidden lg:block">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            
            <!-- Close Button pour mobile -->
            <button @click="sidebarOpen = false" class="p-2 hover:bg-gray-100 rounded-lg transition-all duration-200 group lg:hidden">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- User Info Section -->
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                <span class="text-white font-semibold text-sm">{{ Auth::user() ? substr(Auth::user()->name, 0, 1) : 'U' }}</span>
            </div>
            <div x-show="!collapsed" class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'email@example.com' }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <!-- Dashboard -->
        <div class="nav-section">
            <div x-show="!collapsed" class="nav-section-title">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Principal</span>
            </div>
            <x-nav-link :href="Auth::user() && Auth::user()->isPartOfCompany() ? route('entreprise.dashboard') : route('dashboard')" 
                        :active="request()->routeIs('dashboard') || request()->routeIs('entreprise.dashboard')" 
                        class="nav-item-modern {{ request()->routeIs('dashboard') || request()->routeIs('entreprise.dashboard') ? 'nav-item-active' : '' }}"
                        @click="if(window.innerWidth < 1024) sidebarOpen = false">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span x-show="!collapsed" class="nav-text">Dashboard</span>
                <div x-show="!collapsed && (request()->routeIs('dashboard') || request()->routeIs('entreprise.dashboard'))" class="nav-indicator"></div>
            </x-nav-link>
        </div>

        <!-- Projects -->
        @auth
        <div class="nav-section">
            <div x-show="!collapsed" class="nav-section-title">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestion</span>
            </div>
            <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')"
                        class="nav-item-modern {{ request()->routeIs('projects.*') ? 'nav-item-active' : '' }}"
                        @click="if(window.innerWidth < 1024) sidebarOpen = false">
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
                        class="nav-item-modern nav-item-premium {{ request()->routeIs('premium.*') ? 'nav-item-active' : '' }}"
                        @click="if(window.innerWidth < 1024) sidebarOpen = false">
                <div class="nav-icon nav-icon-premium">
                    <i class="fas fa-crown"></i>
                </div>
                <span x-show="!collapsed" class="nav-text">Devenir Premium</span>
                <div x-show="!collapsed && request()->routeIs('premium.*')" class="nav-indicator nav-indicator-premium"></div>
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
                        class="nav-item-modern {{ request()->routeIs('preferences.*') ? 'nav-item-active' : '' }}"
                        @click="if(window.innerWidth < 1024) sidebarOpen = false">
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
                        class="nav-item-modern nav-item-admin {{ request()->routeIs('admin.*') ? 'nav-item-active' : '' }}"
                        @click="if(window.innerWidth < 1024) sidebarOpen = false">
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
        <!-- Profile & Logout -->
        <div class="space-y-1">
            <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')"
                        class="nav-item-modern nav-item-user {{ request()->routeIs('profile.*') ? 'nav-item-active' : '' }}"
                        @click="if(window.innerWidth < 1024) sidebarOpen = false">
                <div class="nav-icon">
                    <i class="fas fa-user"></i>
                </div>
                <span x-show="!collapsed" class="nav-text">Mon Profil</span>
                <div x-show="!collapsed && request()->routeIs('profile.*')" class="nav-indicator"></div>
            </x-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full nav-item-modern nav-item-logout"
                        @click="if(window.innerWidth < 1024) sidebarOpen = false">
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
            @apply mb-6;
        }
        
        .nav-section-title {
            @apply px-4 py-3 mb-3;
        }
        
        .nav-item-modern {
            @apply flex items-center px-3 py-3 text-gray-700 rounded-lg transition-all duration-200 group relative mx-1 cursor-pointer;
        }
        
        .nav-item-modern:hover {
            @apply bg-gray-100 text-gray-900 transform translate-x-1;
        }
        
        .nav-item-active {
            @apply bg-blue-50 text-blue-700 border-r-2 border-blue-500;
        }
        
        .nav-item-premium {
            @apply bg-gradient-to-r from-purple-50 to-pink-50 text-purple-700 border border-purple-200/50;
        }
        
        .nav-item-premium:hover {
            @apply bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 transform translate-x-1;
        }
        
        .nav-item-admin {
            @apply bg-gradient-to-r from-red-50 to-pink-50 text-red-700 border border-red-200/50;
        }
        
        .nav-item-admin:hover {
            @apply bg-gradient-to-r from-red-100 to-pink-100 text-red-800 transform translate-x-1;
        }
        
        .nav-item-user:hover {
            @apply bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 transform translate-x-1;
        }
        
        .nav-item-logout {
            @apply text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 hover:text-red-700;
        }
        
        .nav-item-logout:hover {
            @apply transform translate-x-1;
        }
        
        .nav-icon {
            @apply w-6 h-6 flex items-center justify-center flex-shrink-0 rounded-lg transition-all duration-200;
            @apply bg-gray-100 text-gray-600;
        }
        
        .nav-item-modern:hover .nav-icon {
            @apply bg-gray-200 text-gray-700 transform scale-110;
        }
        
        .nav-icon-premium {
            @apply bg-purple-100 text-purple-600;
        }
        
        .nav-icon-admin {
            @apply bg-red-100 text-red-600;
        }
        
        .nav-text {
            @apply ml-4 font-medium text-sm;
        }
        
        .nav-indicator {
            @apply absolute right-3 w-2 h-2 bg-blue-500 rounded-full;
        }
        
        .nav-indicator-premium {
            @apply bg-purple-500;
        }
        
        .nav-indicator-admin {
            @apply bg-red-500;
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
        
        /* Smooth transitions */
        .nav-item-modern {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Focus states for accessibility */
        .nav-item-modern:focus {
            @apply outline-none ring-2 ring-blue-500 ring-opacity-50;
        }
        
        /* Mobile sidebar positioning */
        @media (max-width: 1023px) {
            nav {
                transform: translateX(-100%);
            }
        }
    </style>
</nav>