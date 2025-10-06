<nav x-data="{ collapsed: JSON.parse(localStorage.getItem('collapsed')) ?? false }"
     @click.away="localStorage.setItem('collapsed', JSON.stringify(collapsed))"
     class="h-screen bg-white border-r border-gray-200 shadow-soft flex flex-col hidden lg:flex">
    
    <!-- Header with Logo -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-primary rounded-xl flex items-center justify-center shadow-sm">
                    <span class="text-white font-bold text-lg">P</span>
                </div>
                <div x-show="!collapsed" class="transition-all duration-200">
                    <h1 class="text-xl font-bold text-gray-900">Planify</h1>
                    @if (Auth::user()->is_premium)
                        <span class="text-xs font-medium text-accent-600 bg-accent-50 px-2 py-1 rounded-full">
                            Premium
                        </span>
                    @endif
                </div>
            </a>
            
            <!-- Toggle Button -->
            <button @click="collapsed = !collapsed; localStorage.setItem('collapsed', JSON.stringify(collapsed));" 
                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 px-4 py-6 space-y-2">
        <!-- Dashboard -->
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                    class="nav-item-modern {{ request()->routeIs('dashboard') ? 'nav-item-active' : '' }}">
            <i class="fas fa-home w-5 h-5"></i>
            <span x-show="!collapsed" class="ml-3 transition-all duration-200">Dashboard</span>
        </x-nav-link>

        <!-- Projects -->
        @auth
            <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')"
                        class="nav-item-modern {{ request()->routeIs('projects.*') ? 'nav-item-active' : '' }}">
                <i class="fas fa-project-diagram w-5 h-5"></i>
                <span x-show="!collapsed" class="ml-3 transition-all duration-200">Projets</span>
            </x-nav-link>
        @endauth

        <!-- Premium -->
        @if (!Auth::user()->is_premium && !Auth::user()->is_admin())
            <x-nav-link :href="route('premium.show')" :active="request()->routeIs('premium.*')"
                        class="nav-item-modern {{ request()->routeIs('premium.*') ? 'nav-item-active' : '' }}">
                <i class="fas fa-crown w-5 h-5 text-accent-500"></i>
                <span x-show="!collapsed" class="ml-3 transition-all duration-200">Premium</span>
            </x-nav-link>
        @endif

        <!-- Preferences -->
        @if (Auth::user()->is_premium || Auth::user()->is_admin())
            <x-nav-link :href="route('preferences.edit')" :active="request()->routeIs('preferences.*')"
                        class="nav-item-modern {{ request()->routeIs('preferences.*') ? 'nav-item-active' : '' }}">
                <i class="fas fa-cog w-5 h-5"></i>
                <span x-show="!collapsed" class="ml-3 transition-all duration-200">Préférences</span>
            </x-nav-link>
        @endif

        <!-- Admin -->
        @if (Auth::user()->is_admin())
            <div class="pt-4 border-t border-gray-200">
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                            class="nav-item-modern {{ request()->routeIs('admin.*') ? 'nav-item-active' : '' }} bg-red-50 text-red-700 hover:bg-red-100">
                    <i class="fas fa-shield-alt w-5 h-5 text-red-600"></i>
                    <span x-show="!collapsed" class="ml-3 transition-all duration-200 font-semibold">Administration</span>
                </x-nav-link>
            </div>
        @endif
    </div>

    <!-- User Section -->
    <div class="p-4 border-t border-gray-200 space-y-2">
        <!-- Profile -->
        <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')"
                    class="nav-item-modern {{ request()->routeIs('profile.*') ? 'nav-item-active' : '' }}">
            <i class="fas fa-user w-5 h-5"></i>
            <span x-show="!collapsed" class="ml-3 transition-all duration-200">Profil</span>
        </x-nav-link>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full nav-item-modern text-red-600 hover:bg-red-50 hover:text-red-700">
                <i class="fas fa-sign-out-alt w-5 h-5"></i>
                <span x-show="!collapsed" class="ml-3 transition-all duration-200">Déconnexion</span>
            </button>
        </form>
    </div>
</nav>