@props(['class' => ''])

@if(Auth::user() && Auth::user()->is_premium())
<div class="theme-toggle {{ $class }}" x-data="{ 
    theme: '{{ request()->cookie('theme', 'light') }}',
    loading: false,
    async toggleTheme() {
        this.loading = true;
        try {
            const response = await fetch('/theme/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.theme = data.theme;
                // Appliquer le thème immédiatement
                document.documentElement.setAttribute('data-theme', data.theme);
                
                // Recharger la page pour appliquer le thème CSS
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            }
        } catch (error) {
            console.error('Erreur lors du changement de thème:', error);
        } finally {
            this.loading = false;
        }
    }
}" :class="{ 'loading': loading }">
    <button @click="toggleTheme()" 
            class="flex items-center justify-center w-12 h-12 rounded-xl transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-800 group"
            :disabled="loading"
            title="Changer de thème (Premium)">
        <div x-show="!loading">
            <!-- Icône soleil (mode clair) -->
            <i x-show="theme === 'light'" 
               class="fas fa-sun text-yellow-500 text-lg group-hover:rotate-180 transition-transform duration-300"></i>
            
            <!-- Icône lune (mode sombre) -->
            <i x-show="theme === 'dark'" 
               class="fas fa-moon text-blue-400 text-lg group-hover:rotate-12 transition-transform duration-300"></i>
        </div>
        
        <!-- Spinner de chargement -->
        <div x-show="loading" class="animate-spin">
            <i class="fas fa-spinner text-gray-400 text-lg"></i>
        </div>
    </button>
</div>
@else
<!-- Indicateur Premium pour le thème -->
<div class="theme-toggle-premium {{ $class }}" title="Thème sombre disponible avec Premium">
    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 opacity-50 cursor-not-allowed">
        <i class="fas fa-moon text-gray-400 text-lg"></i>
        <div class="absolute -top-1 -right-1 w-4 h-4 bg-yellow-500 rounded-full flex items-center justify-center">
            <i class="fas fa-crown text-white text-xs"></i>
        </div>
    </div>
</div>
@endif

<style>
/* Styles pour le thème sombre */
[data-theme="dark"] {
    --bg-primary: #1f2937;
    --bg-secondary: #374151;
    --text-primary: #f9fafb;
    --text-secondary: #d1d5db;
    --border-color: #4b5563;
}

[data-theme="dark"] body {
    background-color: var(--bg-primary);
    color: var(--text-primary);
}

[data-theme="dark"] .modern-card {
    background-color: var(--bg-secondary);
    border-color: var(--border-color);
    color: var(--text-primary);
}

[data-theme="dark"] .nav-section-title {
    color: var(--text-secondary);
}

[data-theme="dark"] .nav-text {
    color: var(--text-primary);
}

[data-theme="dark"] .nav-desc {
    color: var(--text-secondary);
}

/* Animation pour le toggle */
.theme-toggle button {
    transition: all 0.3s ease;
}

.theme-toggle button:hover {
    transform: scale(1.05);
}

.theme-toggle.loading button {
    cursor: not-allowed;
    opacity: 0.7;
}
</style>
