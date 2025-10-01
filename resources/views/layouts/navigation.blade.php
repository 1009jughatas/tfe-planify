<style>
    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }

    .blink {
        animation: blink 1.5s infinite;
    }

    .styled-brand {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .styled-title {
        font-size: 0.9rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .circle {
        padding: 10px 14px;
        border-radius: 50%;
        background: #ebe7df;
        font-size: 0.9rem;
        font-weight: bold;
    }
</style>

<nav x-data="{ collapsed: JSON.parse(localStorage.getItem('collapsed')) ?? false, mobileMenuOpen: false }"
    @click.away="localStorage.setItem('collapsed', JSON.stringify(collapsed))"
    class="h-screen bg-white border-r border-gray-200 shadow-md flex flex-col lg:block hidden lg:block"
    :class="{ 'hidden': !mobileMenuOpen && window.innerWidth < 1024 }">
    <!-- Mobile Menu Button -->
    <div class="lg:hidden flex justify-between items-center p-4 bg-gray-50">
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div class="circle">
            <span class="text-2xl font-bold">P</span>
        </div>
    </div>

    <!-- Desktop Toggle Button -->
    <button @click="collapsed = !collapsed; localStorage.setItem('collapsed', JSON.stringify(collapsed));" 
            class="p-4 hidden lg:block">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Logo Section -->
    <div class="py-4 px-6 bg-gray-50 styled-brand">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center">
            <!-- Collapsed logo -->
            <div class="circle" x-show="collapsed">
                <span class="text-2xl font-bold">P</span>
            </div>
            <!-- Full logo -->
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" x-show="!collapsed" />
        </a>

        <!-- Premium Badge -->
        @if (Auth::user()->is_premium)
            <span class="text-yellow-500 font-bold blink styled-title" x-show="!collapsed">
                Premium
            </span>
        @endif
    </div>

    <!-- Nav Links -->
    <div class="flex flex-col flex-1">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center p-4">
            <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7m-9 9v-6a2 2 0 012-2h2a2 2 0 012 2v6m4 0h-4m-4 0H7" />
            </svg>
            <span x-show="!collapsed" class="ml-3">{{ __('Dashboard') }}</span>
        </x-nav-link>

        @auth
            <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.index')"
                class="flex items-center p-4">
                <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 3v4M3 5h4m2 2v14a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-8a2 2 0 00-2 2v14m6-14V3m-6 14H9m0-10H9" />
                </svg>
                <span x-show="!collapsed" class="ml-3">{{ __('Projects') }}</span>
            </x-nav-link>
        @endauth

        @if (!Auth::user()->is_premium && !Auth::user()->is_admin())
            <x-nav-link :href="route('premium.show')" :active="request()->routeIs('premium.show')"
                class="flex items-center p-4">
                <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 3v4M3 5h4m10-2l-.867 12.142A2 2 0 0116.138 17H7.862a2 2 0 01-1.995-1.858L5 3m5 4v10m4-10v10" />
                </svg>
                <span x-show="!collapsed" class="ml-3">{{ __('Premium') }}</span>
            </x-nav-link>
        @endif

        @if (Auth::user()->is_premium || Auth::user()->is_admin())
            <x-nav-link :href="route('preferences.edit')" :active="request()->routeIs('preferences.edit')"
                class="flex items-center p-4">
                <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span x-show="!collapsed" class="ml-3">{{ __('Préférences') }}</span>
            </x-nav-link>
        @endif

        @if (Auth::user()->is_admin())
            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                class="flex items-center p-4 bg-red-50 border-l-4 border-red-500">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span x-show="!collapsed" class="ml-3 font-bold text-red-600">{{ __('Admin') }}</span>
            </x-nav-link>
        @endif

        <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')"
            class="flex items-center p-4">
            <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A7 7 0 0117.87 5.121m4.244 4.244a7 7 0 01-9.9 9.9M12 4v.01M4 12h.01M12 20h.01M20 12h.01" />
            </svg>
            <span x-show="!collapsed" class="ml-3">{{ __('Profile') }}</span>
        </x-nav-link>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-nav-link :href="route('logout')" onclick="event.preventDefault();
                                    this.closest('form').submit();" class="flex items-center p-4">
                <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H3" />
                </svg>
                <span x-show="!collapsed" class="ml-3">{{ __('Log Out') }}</span>
            </x-nav-link>
        </form>
    </div>
</nav>