<!-- Menu Hamburger Simple -->
<button onclick="toggleMenu()" 
        class="fixed top-4 left-4 z-50 p-3 bg-white rounded-lg shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-200">
    <div class="w-6 h-6 flex flex-col justify-center items-center space-y-1">
        <span class="block w-5 h-0.5 bg-gray-600"></span>
        <span class="block w-5 h-0.5 bg-gray-600"></span>
        <span class="block w-5 h-0.5 bg-gray-600"></span>
    </div>
</button>

<!-- Overlay -->
<div id="menu-overlay" onclick="closeMenu()" class="fixed inset-0 bg-black/50 z-40 hidden"></div>

<!-- Sidebar -->
<div id="sidebar" class="fixed top-0 left-0 w-72 h-full bg-white shadow-2xl z-50 overflow-y-auto hidden">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Planify" class="w-6 h-6 object-contain filter brightness-0 invert">
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Planify</h2>
                    <p class="text-sm text-blue-100">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                </div>
            </div>
            <button onclick="closeMenu()" class="text-white/80 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-6 space-y-6">
        <!-- 🏠 ACCUEIL -->
        @if(Auth::user() && Auth::user()->isPartOfCompany())
            <!-- Routes entreprise -->
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Accueil</h3>
                <a href="{{ route('entreprise.dashboard') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('entreprise.dashboard') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                   onclick="closeMenu()">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('entreprise.dashboard') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-home text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
            </div>
        @else
            <!-- Routes indépendant -->
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Accueil</h3>
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                   onclick="closeMenu()">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('dashboard') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-home text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
            </div>
        @endif

        <!-- 📋 PROJETS -->
        @auth
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Projets</h3>
            @if(Auth::user() && Auth::user()->isPartOfCompany())
                <!-- Routes entreprise -->
                <a href="{{ route('entreprise.projets.index') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('entreprise.projets.*') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                   onclick="closeMenu()">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('entreprise.projets.*') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-project-diagram text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Projets Entreprise</span>
                </a>
            @else
                <!-- Routes indépendant -->
                <a href="{{ route('projects.index') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('projects.*') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                   onclick="closeMenu()">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('projects.*') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-project-diagram text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Mes Projets</span>
                </a>
            @endif
        </div>
        @endauth

        <!-- 🛡️ ADMINISTRATION (Admin entreprise uniquement) -->
        @if (Auth::user() && Auth::user()->isAdminEntreprise())
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Administration</h3>
            <div class="space-y-1">
                <a href="{{ route('entreprise.utilisateurs.index') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('entreprise.utilisateurs.*') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                   onclick="closeMenu()">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('entreprise.utilisateurs.*') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Utilisateurs</span>
                </a>
                
                <a href="{{ route('entreprise.abonnement.index') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('entreprise.abonnement.*') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                   onclick="closeMenu()">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('entreprise.abonnement.*') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-credit-card text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Abonnement</span>
                </a>
            </div>
        </div>
        @endif

        <!-- 📄 EXPORT -->
        @if (Auth::user() && Auth::user()->canExportPdf())
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Export</h3>
            <div class="space-y-1">
                <a href="{{ route('export.dashboard') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 bg-white text-purple-700 border border-purple-200 hover:bg-purple-50"
                   target="_blank"
                   onclick="closeMenu()">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-purple-100 text-purple-600">
                        <i class="fas fa-file-pdf text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Export Dashboard PDF</span>
                </a>
                
                <a href="{{ route('export.projects') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 bg-white text-purple-700 border border-purple-200 hover:bg-purple-50"
                   target="_blank"
                   onclick="closeMenu()">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-purple-100 text-purple-600">
                        <i class="fas fa-file-pdf text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Export Projets PDF</span>
                </a>
                
                <a href="{{ route('export.tasks') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 bg-white text-purple-700 border border-purple-200 hover:bg-purple-50"
                   target="_blank"
                   onclick="closeMenu()">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-purple-100 text-purple-600">
                        <i class="fas fa-file-pdf text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Export Tâches PDF</span>
                </a>
            </div>
        </div>
        @endif

        <!-- 👤 MON COMPTE -->
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Mon Compte</h3>
            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                   onclick="closeMenu()">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('profile.*') ? 'bg-blue-200 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <span class="text-sm font-medium">Mon Profil</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-3 rounded-lg transition-all duration-200 bg-white text-red-600 border border-red-200 hover:bg-red-50" onclick="closeMenu()">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-red-100 text-red-600">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">Se déconnecter</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- ⚙️ PARAMÈTRES -->
        @if (Auth::user() && (Auth::user()->is_premium() || Auth::user()->is_admin() || Auth::user()->isPartOfCompany()))
        <div>
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Paramètres</h3>
            <a href="{{ Auth::user() && Auth::user()->isPartOfCompany() ? route('entreprise.preferences.edit') : route('preferences.edit') }}" 
               class="flex items-center p-3 rounded-lg transition-all duration-200 {{ request()->routeIs('preferences.*') ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
               onclick="closeMenu()">
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

<script>
function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('menu-overlay');
    
    if (sidebar.classList.contains('hidden')) {
        sidebar.classList.remove('hidden');
        overlay.classList.remove('hidden');
    } else {
        sidebar.classList.add('hidden');
        overlay.classList.add('hidden');
    }
}

function closeMenu() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('menu-overlay');
    
    sidebar.classList.add('hidden');
    overlay.classList.add('hidden');
}
</script>
