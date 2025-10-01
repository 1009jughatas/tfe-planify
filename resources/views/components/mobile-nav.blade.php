<div x-data="{ mobileMenuOpen: false }" class="lg:hidden">
    <!-- Mobile menu button -->
    <button @click="mobileMenuOpen = !mobileMenuOpen" 
            class="fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg border border-gray-200">
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
         class="fixed inset-0 bg-black bg-opacity-50 z-40"></div>

    <!-- Mobile menu -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed top-0 left-0 w-80 h-full bg-white shadow-xl z-50">
        
        <!-- Mobile menu header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center mr-3">
                    <span class="text-white font-bold text-sm">P</span>
                </div>
                <span class="font-semibold text-gray-800">Planify</span>
            </div>
            <button @click="mobileMenuOpen = false" class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile menu items -->
        <nav class="mt-4">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-home w-5 h-5 mr-3"></i>
                <span>Dashboard</span>
            </x-nav-link>

            @auth
                <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.index')"
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-project-diagram w-5 h-5 mr-3"></i>
                    <span>Projets</span>
                </x-nav-link>
            @endauth

            @if (!Auth::user()->is_premium && !Auth::user()->is_admin())
                <x-nav-link :href="route('premium.show')" :active="request()->routeIs('premium.show')"
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-crown w-5 h-5 mr-3 text-yellow-500"></i>
                    <span>Premium</span>
                </x-nav-link>
            @endif

            @if (Auth::user()->is_premium || Auth::user()->is_admin())
                <x-nav-link :href="route('preferences.edit')" :active="request()->routeIs('preferences.edit')"
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-cog w-5 h-5 mr-3"></i>
                    <span>Préférences</span>
                </x-nav-link>
            @endif

            @if (Auth::user()->is_admin())
                <div class="border-t border-gray-200 my-2"></div>
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                           class="flex items-center px-4 py-3 bg-red-50 text-red-700 hover:bg-red-100">
                    <i class="fas fa-shield-alt w-5 h-5 mr-3 text-red-600"></i>
                    <span class="font-bold">Administration</span>
                </x-nav-link>
            @endif

            <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')"
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100">
                <i class="fas fa-user w-5 h-5 mr-3"></i>
                <span>Profil</span>
            </x-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </nav>
    </div>
</div>
