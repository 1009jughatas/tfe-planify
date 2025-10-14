<div x-data="{ 
    sidebarOpen: false,
    isDarkMode: false,
    
    init() {
        // Vérifier le mode sombre depuis le localStorage
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
<button @click="sidebarOpen = !sidebarOpen" 
        class="fixed top-4 left-4 z-50 p-3 bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-200 group">
    <div class="w-6 h-6 flex flex-col justify-center items-center space-y-1">
        <span class="block w-5 h-0.5 bg-gray-600 transition-all duration-300 ease-in-out" 
              :class="sidebarOpen ? 'rotate-45 translate-y-1.5' : 'rotate-0 translate-y-0'"></span>
        <span class="block w-5 h-0.5 bg-gray-600 transition-all duration-300 ease-in-out"
              :class="sidebarOpen ? 'opacity-0' : 'opacity-100'"></span>
        <span class="block w-5 h-0.5 bg-gray-600 transition-all duration-300 ease-in-out"
              :class="sidebarOpen ? '-rotate-45 -translate-y-1.5' : 'rotate-0 translate-y-0'"></span>
    </div>
</button>

<!-- Overlay -->
<div x-show="sidebarOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40"></div>

<!-- Sidebar -->
<div x-show="sidebarOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     @click.away="sidebarOpen = false"
     class="fixed top-0 left-0 w-80 h-full bg-white shadow-2xl z-50 overflow-y-auto">
    
    <!-- Header -->
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Planify" class="w-8 h-8 object-contain filter brightness-0 invert">
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">Planify</h1>
                    <div class="flex items-center space-x-2">
                        @if (Auth::user() && Auth::user()->isAdminEntreprise())
                            <span class="text-xs font-medium bg-purple-500/20 backdrop-blur-sm px-2 py-1 rounded-full">
                                <i class="fas fa-building mr-1"></i>Admin Entreprise
                            </span>
                        @elseif (Auth::user() && Auth::user()->isUserEntreprise())
                            <span class="text-xs font-medium bg-blue-500/20 backdrop-blur-sm px-2 py-1 rounded-full">
                                <i class="fas fa-users mr-1"></i>Équipe
                            </span>
                        @elseif (Auth::user() && Auth::user()->isUserIndependant())
                            <span class="text-xs font-medium bg-green-500/20 backdrop-blur-sm px-2 py-1 rounded-full">
                                <i class="fas fa-user mr-1"></i>Indépendant
                            </span>
                        @else
                            <span class="text-xs font-medium bg-white/20 backdrop-blur-sm px-2 py-1 rounded-full">
                                <i class="fas fa-user mr-1"></i>Utilisateur
                            </span>
                        @endif
                    </div>
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
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="p-6 bg-gray-50 border-b border-gray-200">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                <span class="text-white font-bold text-lg">{{ Auth::user() ? substr(Auth::user()->name, 0, 1) : 'U' }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ Auth::user()->name ?? 'Utilisateur' }}</h3>
                <p class="text-sm text-gray-600 truncate">{{ Auth::user()->email ?? 'email@example.com' }}</p>
                <div class="flex items-center space-x-2 mt-1">
                    @if(Auth::user() && Auth::user()->isAdminEntreprise())
                        <span class="text-xs font-medium text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                            <i class="fas fa-building mr-1"></i>Admin Entreprise
                        </span>
                    @elseif(Auth::user() && Auth::user()->isUserEntreprise())
                        <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                            <i class="fas fa-users mr-1"></i>Équipe
                        </span>
                    @elseif(Auth::user() && Auth::user()->isUserIndependant())
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
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 p-6 space-y-6">
        <!-- Accueil -->
        <div class="nav-section">
            <h3 class="nav-section-title">Accueil</h3>
            <a href="{{ Auth::user() && Auth::user()->isPartOfCompany() ? route('entreprise.dashboard') : route('dashboard') }}" 
               class="nav-item {{ request()->routeIs('dashboard') || request()->routeIs('entreprise.dashboard') ? 'nav-item-active' : '' }}"
               @click="sidebarOpen = false">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span class="nav-text">Tableau de bord</span>
            </a>
        </div>

        <!-- Projets -->
        @auth
        <div class="nav-section">
            <h3 class="nav-section-title">Projets</h3>
            <a href="{{ route('projects.index') }}" 
               class="nav-item {{ request()->routeIs('projects.*') ? 'nav-item-active' : '' }}"
               @click="sidebarOpen = false">
                <div class="nav-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <span class="nav-text">Mes Projets</span>
            </a>
        </div>
        @endauth

        <!-- Premium -->
        @if (Auth::user() && !Auth::user()->is_premium() && !Auth::user()->is_admin())
        <div class="nav-section">
            <h3 class="nav-section-title">Premium</h3>
            <a href="{{ route('premium.show') }}" 
               class="nav-item nav-item-premium {{ request()->routeIs('premium.*') ? 'nav-item-active' : '' }}"
               @click="sidebarOpen = false">
                <div class="nav-icon nav-icon-premium">
                    <i class="fas fa-crown"></i>
                </div>
                <span class="nav-text">Passer Premium</span>
            </a>
        </div>
        @endif

        <!-- Paramètres -->
        @if (Auth::user() && (Auth::user()->is_premium() || Auth::user()->is_admin()))
        <div class="nav-section">
            <h3 class="nav-section-title">Paramètres</h3>
            <a href="{{ route('preferences.edit') }}" 
               class="nav-item {{ request()->routeIs('preferences.*') ? 'nav-item-active' : '' }}"
               @click="sidebarOpen = false">
                <div class="nav-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <span class="nav-text">Préférences</span>
            </a>
        </div>
        @endif

        <!-- Administration -->
        @if (Auth::user() && Auth::user()->is_admin())
        <div class="nav-section">
            <h3 class="nav-section-title">Administration</h3>
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-item nav-item-admin {{ request()->routeIs('admin.*') ? 'nav-item-active' : '' }}"
               @click="sidebarOpen = false">
                <div class="nav-icon nav-icon-admin">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <span class="nav-text">Panneau Admin</span>
            </a>
        </div>
        @endif

        <!-- Export (Premium) -->
        @if (Auth::user() && Auth::user()->is_premium())
        <div class="nav-section">
            <h3 class="nav-section-title">Export Premium</h3>
            <a href="{{ route('export.dashboard') }}" 
               class="nav-item nav-item-premium"
               @click="sidebarOpen = false">
                <div class="nav-icon nav-icon-premium">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <span class="nav-text">Export Dashboard PDF</span>
            </a>
            
            <a href="{{ route('export.projects') }}" 
               class="nav-item nav-item-premium"
               @click="sidebarOpen = false">
                <div class="nav-icon nav-icon-premium">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <span class="nav-text">Export Projets PDF</span>
            </a>
            
            <a href="{{ route('export.tasks') }}" 
               class="nav-item nav-item-premium"
               @click="sidebarOpen = false">
                <div class="nav-icon nav-icon-premium">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <span class="nav-text">Export Tâches PDF</span>
            </a>
        </div>
        @endif

        <!-- Mon Compte -->
        <div class="nav-section">
            <h3 class="nav-section-title">Mon Compte</h3>
            <a href="{{ route('profile.edit') }}" 
               class="nav-item {{ request()->routeIs('profile.*') ? 'nav-item-active' : '' }}"
               @click="sidebarOpen = false">
                <div class="nav-icon">
                    <i class="fas fa-user"></i>
                </div>
                <span class="nav-text">Mon Profil</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full nav-item nav-item-logout" @click="sidebarOpen = false">
                    <div class="nav-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <span class="nav-text">Se déconnecter</span>
                </button>
            </form>
        </div>

        <!-- Toggle Thème Premium -->
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-center">
                <x-theme-toggle />
            </div>
        </div>
    </nav>

    <!-- Footer -->
    <div class="p-6 bg-gray-50 border-t border-gray-200">
        <div class="text-center">
            <div class="flex justify-center mb-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Planify" class="w-5 h-5 object-contain filter brightness-0 invert">
                </div>
            </div>
            <p class="text-xs text-gray-500">© 2025 Planify. Tous droits réservés.</p>
            <div class="flex justify-center space-x-4 mt-2">
                <a href="#" class="text-xs text-gray-400 hover:text-gray-600 transition-colors">Aide</a>
                <a href="#" class="text-xs text-gray-400 hover:text-gray-600 transition-colors">Support</a>
                <a href="#" class="text-xs text-gray-400 hover:text-gray-600 transition-colors">Mentions légales</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Navigation Styles - Simple et Direct */
    .nav-section {
        @apply mb-6;
    }
    
    .nav-section-title {
        @apply text-sm font-semibold text-gray-700 mb-3 px-2;
        @apply border-l-4 border-blue-500 pl-3;
    }
    
    .nav-item {
        @apply flex items-center p-3 rounded-lg transition-all duration-200 cursor-pointer;
        @apply bg-white border border-gray-200 hover:bg-gray-50 hover:border-blue-300;
        @apply mb-2;
    }
    
    .nav-item:hover {
        @apply shadow-sm transform translate-x-1;
    }
    
    .nav-item-active {
        @apply bg-blue-50 border-blue-300 text-blue-700 shadow-sm;
    }
    
    .nav-item-premium {
        @apply bg-purple-50 border-purple-200 text-purple-700;
    }
    
    .nav-item-premium:hover {
        @apply bg-purple-100 border-purple-300;
    }
    
    .nav-item-admin {
        @apply bg-red-50 border-red-200 text-red-700;
    }
    
    .nav-item-admin:hover {
        @apply bg-red-100 border-red-300;
    }
    
    .nav-item-logout {
        @apply text-red-600 hover:bg-red-50 hover:text-red-700 hover:border-red-300;
    }
    
    .nav-icon {
        @apply w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 mr-3;
        @apply bg-gray-100 text-gray-600 transition-all duration-200;
    }
    
    .nav-item:hover .nav-icon {
        @apply bg-gray-200 text-gray-700;
    }
    
    .nav-item-active .nav-icon {
        @apply bg-blue-200 text-blue-700;
    }
    
    .nav-icon-premium {
        @apply bg-purple-100 text-purple-600;
    }
    
    .nav-icon-admin {
        @apply bg-red-100 text-red-600;
    }
    
    .nav-text {
        @apply text-sm font-medium;
    }
    
    /* Responsive adjustments */
    @media (max-width: 640px) {
        .nav-item {
            @apply p-2;
        }
        
        .nav-icon {
            @apply w-8 h-8 mr-2;
        }
    }
    
    /* Focus states for accessibility */
    .nav-item:focus {
        @apply outline-none ring-2 ring-blue-500 ring-opacity-50;
    }
</style>

<script>
// Système de mode sombre simple
document.addEventListener('alpine:init', () => {
    Alpine.data('darkMode', () => ({
        isDarkMode: false,
        
        init() {
            // Vérifier le mode sombre depuis le localStorage
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
    }));
});

// Appliquer le mode sombre au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    if (isDarkMode) {
        document.documentElement.classList.add('dark-mode');
    }
});
</script>

</div>