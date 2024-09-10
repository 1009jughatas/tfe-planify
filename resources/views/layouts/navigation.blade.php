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

<nav x-data="{ collapsed: JSON.parse(localStorage.getItem('collapsed')) ?? false }"
    @click.away="localStorage.setItem('collapsed', JSON.stringify(collapsed))"
    class="h-screen bg-white border-r border-gray-200 shadow-md flex flex-col">
    <!-- Toggle Button -->
    <button @click="collapsed = !collapsed; localStorage.setItem('collapsed', JSON.stringify(collapsed));" class="p-4">
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

        @if (!Auth::user()->is_premium)
            <x-nav-link :href="route('premium.show')" :active="request()->routeIs('premium.show')"
                class="flex items-center p-4">
                <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7H7v6h6V7zM21 7h-6v6h6V7zM13 13v6H7v-6h6zM21 13v6h-6v-6z" />
                </svg>
                <span x-show="!collapsed" class="ml-3">{{ __('Premium') }}</span>
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