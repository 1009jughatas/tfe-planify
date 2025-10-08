<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Admin') - {{ Auth::user()->company->name ?? 'Planify' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0"
         x-show="true">
        
        <!-- Logo et nom de l'entreprise -->
        <div class="flex items-center justify-between h-16 px-6 bg-gradient-to-r from-blue-600 to-purple-600">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-white font-bold text-sm">{{ Auth::user()->company->name ?? 'Planify' }}</h1>
                    <p class="text-blue-100 text-xs">Administration</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-blue-200">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="mt-6 px-4">
            <div class="space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('company-admin.dashboard') }}" 
                   class="nav-item {{ request()->routeIs('company-admin.dashboard') ? 'nav-item-active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Tableau de bord</span>
                </a>

                <!-- Gestion des utilisateurs -->
                <a href="{{ route('company-admin.users') }}" 
                   class="nav-item {{ request()->routeIs('company-admin.users*') ? 'nav-item-active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Gestion des utilisateurs</span>
                </a>

                <!-- Projets -->
                <a href="{{ route('company-admin.projects') }}" 
                   class="nav-item {{ request()->routeIs('company-admin.projects*') ? 'nav-item-active' : '' }}">
                    <i class="fas fa-folder"></i>
                    <span>Projets</span>
                </a>

                <!-- Tâches -->
                <a href="{{ route('company-admin.tasks') }}" 
                   class="nav-item {{ request()->routeIs('company-admin.tasks*') ? 'nav-item-active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>Tâches</span>
                </a>

                <!-- Abonnement -->
                <a href="{{ route('company-admin.subscription') }}" 
                   class="nav-item {{ request()->routeIs('company-admin.subscription*') ? 'nav-item-active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Abonnement</span>
                </a>

                <!-- Paramètres -->
                <a href="{{ route('company-admin.settings') }}" 
                   class="nav-item {{ request()->routeIs('company-admin.settings*') ? 'nav-item-active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
            </div>

            <!-- Séparateur -->
            <div class="border-t border-gray-200 my-6"></div>

            <!-- Déconnexion -->
            <form method="POST" action="{{ route('logout') }}" class="px-4">
                @csrf
                <button type="submit" class="nav-item text-red-600 hover:text-red-700 hover:bg-red-50 w-full">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </nav>

        <!-- Info entreprise en bas -->
        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">{{ substr(Auth::user()->company->name ?? 'P', 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">Administrateur d'équipe</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay mobile -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
         @click="sidebarOpen = false"></div>

    <!-- Contenu principal -->
    <div class="lg:pl-64">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-900 ml-2">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                        <i class="fas fa-bell"></i>
                    </button>
                    
                    <!-- Profil -->
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Contenu de la page -->
        <main class="p-4 sm:p-6 lg:p-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('warning') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>

<style>
.nav-item {
    @apply flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200;
}

.nav-item-active {
    @apply bg-blue-50 text-blue-700 border-r-2 border-blue-700;
}

.nav-item i {
    @apply w-5 h-5;
}
</style>
