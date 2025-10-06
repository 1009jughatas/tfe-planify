<div x-data="{ mobileMenuOpen: false }" class="lg:hidden">
    <!-- Mobile menu button -->
    <button @click="mobileMenuOpen = !mobileMenuOpen" 
            class="fixed top-4 left-4 z-50 p-3 bg-white rounded-xl shadow-soft border border-gray-200 hover:shadow-md transition-all duration-200">
        <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
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
         class="fixed top-0 left-0 w-80 h-full bg-white shadow-soft-lg z-50">
        
        <!-- Mobile menu header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-accent-50">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-primary rounded-xl flex items-center justify-center shadow-sm">
                    <span class="text-white font-bold text-lg">P</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Planify</h1>
                    @if (Auth::user()->is_premium)
                        <span class="text-xs font-medium text-accent-600 bg-accent-100 px-2 py-1 rounded-full">
                            Premium
                        </span>
                    @endif
                </div>
            </div>
            <button @click="mobileMenuOpen = false" class="p-2 hover:bg-white/50 rounded-lg transition-colors duration-200">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile menu items -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <!-- Dashboard -->
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                       class="nav-item-modern {{ request()->routeIs('dashboard') ? 'nav-item-active' : '' }}">
                <i class="fas fa-home w-5 h-5"></i>
                <span class="ml-3">Dashboard</span>
            </x-nav-link>

            <!-- Projects -->
            @auth
                <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')"
                           class="nav-item-modern {{ request()->routeIs('projects.*') ? 'nav-item-active' : '' }}">
                    <i class="fas fa-project-diagram w-5 h-5"></i>
                    <span class="ml-3">Projets</span>
                </x-nav-link>
            @endauth

            <!-- Premium -->
            @if (!Auth::user()->is_premium && !Auth::user()->is_admin())
                <x-nav-link :href="route('premium.show')" :active="request()->routeIs('premium.*')"
                           class="nav-item-modern {{ request()->routeIs('premium.*') ? 'nav-item-active' : '' }}">
                    <i class="fas fa-crown w-5 h-5 text-accent-500"></i>
                    <span class="ml-3">Premium</span>
                </x-nav-link>
            @endif

            <!-- Preferences -->
            @if (Auth::user()->is_premium || Auth::user()->is_admin())
                <x-nav-link :href="route('preferences.edit')" :active="request()->routeIs('preferences.*')"
                           class="nav-item-modern {{ request()->routeIs('preferences.*') ? 'nav-item-active' : '' }}">
                    <i class="fas fa-cog w-5 h-5"></i>
                    <span class="ml-3">Préférences</span>
                </x-nav-link>
            @endif

            <!-- Admin -->
            @if (Auth::user()->is_admin())
                <div class="pt-4 border-t border-gray-200">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                               class="nav-item-modern {{ request()->routeIs('admin.*') ? 'nav-item-active' : '' }} bg-red-50 text-red-700 hover:bg-red-100">
                        <i class="fas fa-shield-alt w-5 h-5 text-red-600"></i>
                        <span class="ml-3 font-semibold">Administration</span>
                    </x-nav-link>
                </div>
            @endif

            <!-- Profile -->
            <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')"
                       class="nav-item-modern {{ request()->routeIs('profile.*') ? 'nav-item-active' : '' }}">
                <i class="fas fa-user w-5 h-5"></i>
                <span class="ml-3">Profil</span>
            </x-nav-link>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full nav-item-modern text-red-600 hover:bg-red-50 hover:text-red-700">
                    <i class="fas fa-sign-out-alt w-5 h-5"></i>
                    <span class="ml-3">Déconnexion</span>
                </button>
            </form>
        </nav>
    </div>
</div>
