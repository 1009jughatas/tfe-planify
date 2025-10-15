<!-- Menu Hamburger Simple -->
<div x-data="{ sidebarOpen: false }">
    <!-- Bouton Hamburger -->
    <button @click="sidebarOpen = !sidebarOpen" 
            class="fixed top-4 left-4 z-50 p-3 bg-white rounded-lg shadow-lg border border-gray-200 hover:shadow-xl">
        <div class="w-6 h-6 flex flex-col justify-center items-center space-y-1">
            <span class="block w-5 h-0.5 bg-gray-600"></span>
            <span class="block w-5 h-0.5 bg-gray-600"></span>
            <span class="block w-5 h-0.5 bg-gray-600"></span>
        </div>
    </button>

    <!-- Overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-40"></div>

    <!-- Sidebar -->
    <div x-show="sidebarOpen"
         class="fixed top-0 left-0 w-72 h-full bg-white shadow-2xl z-50 overflow-y-auto">
        
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Planify" class="w-6 h-6 object-contain filter brightness-0 invert">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white">Planify</h1>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="p-2 hover:bg-white/10 rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- User Info -->
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold">{{ Auth::user() ? substr(Auth::user()->name, 0, 1) : 'U' }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name ?? 'Utilisateur' }}</h3>
                    <p class="text-xs text-gray-600 truncate">{{ Auth::user()->email ?? 'email@example.com' }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-4 space-y-4">
            <!-- 🏠 ACCUEIL -->
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Accueil</h3>
                <a href="{{ Auth::user() && Auth::user()->isPartOfCompany() ? route('entreprise.dashboard') : route('dashboard') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') || request()->routeIs('entreprise.dashboard') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                   @click="sidebarOpen = false">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('dashboard') || request()->routeIs('entreprise.dashboard') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-home text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Tableau de bord</span>
                </a>
            </div>

            <!-- 📋 PROJETS -->
            @auth
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Projets</h3>
                @if(Auth::user() && Auth::user()->isPartOfCompany())
                    <!-- Routes entreprise -->
                    <a href="{{ route('entreprise.projets.index') }}" 
                       class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('entreprise.projets.*') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                       @click="sidebarOpen = false">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('entreprise.projets.*') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-project-diagram text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Projets Entreprise</span>
                    </a>
                @else
                    <!-- Routes indépendant -->
                    <a href="{{ route('projects.index') }}" 
                       class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('projects.*') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                       @click="sidebarOpen = false">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('projects.*') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-project-diagram text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Mes Projets</span>
                    </a>
                @endif
            </div>
            @endauth

            <!-- 👑 PREMIUM (Indépendants uniquement) -->
            @if (Auth::user() && !Auth::user()->is_premium() && !Auth::user()->is_admin() && !Auth::user()->isPartOfCompany())
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Premium</h3>
                <a href="{{ route('premium.show') }}"
                   class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('premium.*') ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-white text-purple-700 border border-purple-200 hover:bg-purple-50' }}"
                   @click="sidebarOpen = false">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('premium.*') ? 'bg-purple-200 text-purple-700' : 'bg-purple-100 text-purple-600' }}">
                        <i class="fas fa-crown text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Passer Premium</span>
                </a>
            </div>
            @endif

            <!-- 📄 EXPORT -->
            @if (Auth::user() && (Auth::user()->is_premium() || Auth::user()->isPartOfCompany()))
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Export</h3>
                <div class="space-y-1">
                    <a href="{{ route('export.dashboard') }}"
                       class="flex items-center p-3 rounded-lg transition-all duration-200 bg-white text-purple-700 border border-purple-200 hover:bg-purple-50"
                       @click="sidebarOpen = false">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-purple-100 text-purple-600">
                            <i class="fas fa-file-pdf text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Export Dashboard PDF</span>
                    </a>
                    <a href="{{ route('export.projects') }}"
                       class="flex items-center p-3 rounded-lg transition-all duration-200 bg-white text-purple-700 border border-purple-200 hover:bg-purple-50"
                       @click="sidebarOpen = false">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-purple-100 text-purple-600">
                            <i class="fas fa-file-pdf text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Export Projets PDF</span>
                    </a>
                    <a href="{{ route('export.tasks') }}"
                       class="flex items-center p-3 rounded-lg transition-all duration-200 bg-white text-purple-700 border border-purple-200 hover:bg-purple-50"
                       @click="sidebarOpen = false">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-purple-100 text-purple-600">
                            <i class="fas fa-file-pdf text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Export Tâches PDF</span>
                    </a>
                </div>
            </div>
            @endif

            <!-- ⚙️ PARAMÈTRES -->
            @if (Auth::user() && (Auth::user()->is_premium() || Auth::user()->is_admin() || Auth::user()->isPartOfCompany()))
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Paramètres</h3>
                <a href="{{ Auth::user() && Auth::user()->isPartOfCompany() ? route('entreprise.preferences.edit') : route('preferences.edit') }}"
                   class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('preferences.*') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                   @click="sidebarOpen = false">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('preferences.*') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-cog text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Préférences</span>
                </a>
            </div>
            @endif

            <!-- 👤 MON COMPTE -->
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Mon Compte</h3>
                <a href="{{ Auth::user() && Auth::user()->isPartOfCompany() ? route('entreprise.profile.edit') : route('profile.edit') }}"
                   class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                   @click="sidebarOpen = false">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('profile.*') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Mon Profil</span>
                </a>
            </div>
        </nav>
    </div>
</div>
