<div x-data="{ 
    sidebarOpen: false,
    isDarkMode: false,
    
    init() {
        console.log('Alpine.js initialisé pour le menu hamburger');
        this.isDarkMode = localStorage.getItem('darkMode') === 'true';
        this.applyDarkMode();
    },
    
    toggleDarkMode() {
        this.isDarkMode = !this.isDarkMode;
        localStorage.setItem('darkMode', this.isDarkMode);
        this.applyDarkMode();
    },
    
    applyDarkMode() {
        if (this.isDarkMode) {
            document.documentElement.classList.add('dark-mode');
        } else {
            document.documentElement.classList.remove('dark-mode');
        }
    }
}" x-init="init()">

<!-- Menu Hamburger Button -->
<button @click="console.log('Bouton hamburger cliqué, sidebarOpen:', sidebarOpen); sidebarOpen = !sidebarOpen" 
        class="fixed top-4 left-4 z-50 p-3 bg-white rounded-lg shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-200">
    <div class="w-6 h-6 flex flex-col justify-center items-center space-y-1">
        <span class="block w-5 h-0.5 bg-gray-600" 
              :class="sidebarOpen ? 'rotate-45 translate-y-1.5' : 'rotate-0 translate-y-0'"></span>
        <span class="block w-5 h-0.5 bg-gray-600"
              :class="sidebarOpen ? 'opacity-0' : 'opacity-100'"></span>
        <span class="block w-5 h-0.5 bg-gray-600"
              :class="sidebarOpen ? '-rotate-45 -translate-y-1.5' : 'rotate-0 translate-y-0'"></span>
    </div>
</button>

<!-- Overlay -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-black/50 z-40"></div>

<!-- Sidebar -->
<div x-show="sidebarOpen"
     @click.away="sidebarOpen = false"
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
            <div class="flex items-center space-x-2">
                <!-- Toggle Mode Sombre -->
                <button @click="toggleDarkMode()" 
                        class="p-2 hover:bg-white/10 rounded-lg transition-all duration-200"
                        title="Basculer le mode sombre">
                    <i class="fas text-white text-lg transition-transform duration-300" 
                       :class="isDarkMode ? 'fa-sun' : 'fa-moon'"></i>
                </button>
                <button @click="sidebarOpen = false" class="p-2 hover:bg-white/10 rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
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

        <!-- 👤 MON COMPTE -->
        @auth
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Mon Compte</h3>
            <div class="space-y-1">
                @if(Auth::user() && Auth::user()->isPartOfCompany())
                    <!-- Routes entreprise -->
                    <a href="{{ route('entreprise.profile.edit') }}" 
                       class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('entreprise.profile.*') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                       @click="sidebarOpen = false">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('entreprise.profile.*') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Mon Profil</span>
                    </a>
                @else
                    <!-- Routes indépendant -->
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                       @click="sidebarOpen = false">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('profile.*') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Mon Profil</span>
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-3 rounded-lg transition-all duration-200 bg-white text-red-600 border border-red-200 hover:bg-red-50" @click="sidebarOpen = false">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-red-100 text-red-600">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Se déconnecter</span>
                    </button>
                </form>
            </div>
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

        <!-- 🛡️ ADMINISTRATION -->
        @if (Auth::user() && Auth::user()->is_admin())
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Administration</h3>
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.*') ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-white text-red-700 border border-red-200 hover:bg-red-50' }}"
               @click="sidebarOpen = false">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('admin.*') ? 'bg-red-200 text-red-700' : 'bg-red-100 text-red-600' }}">
                    <i class="fas fa-shield-alt text-sm"></i>
                </div>
                <span class="text-sm font-medium">Panneau Admin</span>
            </a>
        </div>
        @endif

        <!-- 👥 GESTION UTILISATEURS (Admin Entreprise) -->
        @if (Auth::user() && Auth::user()->isAdminEntreprise())
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Gestion Utilisateurs</h3>
            <div class="space-y-1">
                <a href="{{ route('entreprise.utilisateurs.index') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('entreprise.utilisateurs.*') ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-white text-green-700 border border-green-200 hover:bg-green-50' }}"
                   @click="sidebarOpen = false">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('entreprise.utilisateurs.*') ? 'bg-green-200 text-green-700' : 'bg-green-100 text-green-600' }}">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Liste des Employés</span>
                </a>
                
                <a href="{{ route('entreprise.utilisateurs.inviter') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 bg-white text-green-700 border border-green-200 hover:bg-green-50"
                   @click="sidebarOpen = false">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-green-100 text-green-600">
                        <i class="fas fa-user-plus text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Inviter un Employé</span>
                </a>
            </div>
        </div>
        @endif

        <!-- 💳 GESTION ABONNEMENTS (Admin Entreprise) -->
        @if (Auth::user() && Auth::user()->isAdminEntreprise())
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Abonnements</h3>
            <a href="{{ route('entreprise.abonnement.index') }}" 
               class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('entreprise.abonnement.*') ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' : 'bg-white text-yellow-700 border border-yellow-200 hover:bg-yellow-50' }}"
               @click="sidebarOpen = false">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('entreprise.abonnement.*') ? 'bg-yellow-200 text-yellow-700' : 'bg-yellow-100 text-yellow-600' }}">
                    <i class="fas fa-credit-card text-sm"></i>
                </div>
                <span class="text-sm font-medium">Gestion Abonnement</span>
            </a>
        </div>
        @endif

        <!-- 📄 EXPORT -->
        @if (Auth::user() && Auth::user()->canExportPdf())
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
    </nav>

    <!-- Footer -->
    <div class="p-4 bg-gray-50 border-t border-gray-200 mt-auto">
        <div class="text-center">
            <div class="flex justify-center mb-2">
                <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Planify" class="w-4 h-4 object-contain filter brightness-0 invert">
                </div>
            </div>
            <p class="text-xs text-gray-500">© 2025 Planify</p>
        </div>
    </div>
</div>

</div>