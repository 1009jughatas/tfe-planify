<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Responsive - Planify</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-primary rounded-xl flex items-center justify-center shadow-sm">
                            <span class="text-white font-bold text-lg">P</span>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Test Responsive</h1>
                            <p class="text-sm text-gray-600">Design moderne et mobile-first</p>
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <span class="text-sm text-gray-500">Planify v2.0</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stats-card hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Projets actifs</p>
                                <p class="text-3xl font-bold text-gray-900">12</p>
                                <p class="text-xs text-gray-500 mt-1">En cours</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-primary rounded-xl flex items-center justify-center">
                                <i class="fas fa-project-diagram text-white text-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Tâches ouvertes</p>
                                <p class="text-3xl font-bold text-gray-900">45</p>
                                <p class="text-xs text-gray-500 mt-1">À faire</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-tasks text-white text-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-1">Projets terminés</p>
                                <p class="text-3xl font-bold text-gray-900">8</p>
                                <p class="text-xs text-gray-500 mt-1">Finalisés</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-white text-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card hover-lift bg-gradient-accent text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-white/90 mb-1">Premium</p>
                                <p class="text-lg font-semibold text-white">Actif</p>
                                <p class="text-xs text-white/75 mt-1">Fonctionnalités avancées</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-crown text-white text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modern Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="modern-card">
                        <div class="modern-card-header">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-calendar-alt text-primary-600 mr-2"></i>
                                Calendrier
                            </h3>
                        </div>
                        <div class="modern-card-body">
                            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-calendar text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-gray-600">Calendrier des projets</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modern-card">
                        <div class="modern-card-header">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-chart-pie text-primary-600 mr-2"></i>
                                Statistiques
                            </h3>
                        </div>
                        <div class="modern-card-body">
                            <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-chart-pie text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-gray-600">Graphiques et rapports</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kanban Board Test -->
                <div class="modern-card mb-8">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-tasks text-primary-600 mr-2"></i>
                            Tableau Kanban
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- À Faire -->
                            <div class="kanban-column">
                                <div class="kanban-header bg-gray-100">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-semibold text-gray-700">
                                            <i class="fas fa-circle text-gray-400 mr-2"></i>
                                            À faire
                                        </h4>
                                        <span class="badge-secondary">3</span>
                                    </div>
                                </div>
                                <div class="kanban-content">
                                    <div class="task-card hover-lift">
                                        <div class="task-card-header">
                                            <h5 class="task-title">Tâche exemple 1</h5>
                                            <div class="task-status-badge">
                                                <span class="badge-modern bg-gray-100 text-gray-700">
                                                    <i class="fas fa-circle mr-1"></i>
                                                    À faire
                                                </span>
                                            </div>
                                        </div>
                                        <div class="task-card-body">
                                            <p class="task-description">Description de la tâche exemple pour tester le design responsive.</p>
                                            <div class="task-meta">
                                                <div class="task-meta-item">
                                                    <i class="fas fa-user text-gray-400"></i>
                                                    <span class="text-sm text-gray-600">Jean Dupont</span>
                                                </div>
                                                <div class="task-meta-item">
                                                    <i class="fas fa-calendar text-gray-400"></i>
                                                    <span class="text-sm text-gray-600">15/01/2024</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="task-card-footer">
                                            <a href="#" class="task-view-btn">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir la tâche
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- En Cours -->
                            <div class="kanban-column">
                                <div class="kanban-header bg-blue-100">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-semibold text-blue-700">
                                            <i class="fas fa-play text-blue-500 mr-2"></i>
                                            En cours
                                        </h4>
                                        <span class="badge-primary">2</span>
                                    </div>
                                </div>
                                <div class="kanban-content">
                                    <div class="task-card hover-lift">
                                        <div class="task-card-header">
                                            <h5 class="task-title">Tâche en cours</h5>
                                            <div class="task-status-badge">
                                                <span class="badge-modern bg-blue-100 text-blue-700">
                                                    <i class="fas fa-play mr-1"></i>
                                                    En cours
                                                </span>
                                            </div>
                                        </div>
                                        <div class="task-card-body">
                                            <p class="task-description">Cette tâche est actuellement en cours de développement.</p>
                                            <div class="task-meta">
                                                <div class="task-meta-item">
                                                    <i class="fas fa-user text-gray-400"></i>
                                                    <span class="text-sm text-gray-600">Marie Martin</span>
                                                </div>
                                                <div class="task-meta-item">
                                                    <i class="fas fa-arrow-up text-red-600"></i>
                                                    <span class="text-sm text-gray-600">Haute</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="task-card-footer">
                                            <a href="#" class="task-view-btn">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir la tâche
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bloqué -->
                            <div class="kanban-column">
                                <div class="kanban-header bg-red-100">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-semibold text-red-700">
                                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                            Bloqué
                                        </h4>
                                        <span class="badge-danger">1</span>
                                    </div>
                                </div>
                                <div class="kanban-content">
                                    <div class="task-card hover-lift">
                                        <div class="task-card-header">
                                            <h5 class="task-title">Tâche bloquée</h5>
                                            <div class="task-status-badge">
                                                <span class="badge-modern bg-red-100 text-red-700">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Bloqué
                                                </span>
                                            </div>
                                        </div>
                                        <div class="task-card-body">
                                            <p class="task-description">Cette tâche est bloquée en attendant une décision.</p>
                                            <div class="task-meta">
                                                <div class="task-meta-item">
                                                    <i class="fas fa-user text-gray-400"></i>
                                                    <span class="text-sm text-gray-600">Pierre Durand</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="task-card-footer">
                                            <a href="#" class="task-view-btn">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir la tâche
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terminé -->
                            <div class="kanban-column">
                                <div class="kanban-header bg-green-100">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-semibold text-green-700">
                                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                            Terminé
                                        </h4>
                                        <span class="badge-success">5</span>
                                    </div>
                                </div>
                                <div class="kanban-content">
                                    <div class="task-card hover-lift">
                                        <div class="task-card-header">
                                            <h5 class="task-title">Tâche terminée</h5>
                                            <div class="task-status-badge">
                                                <span class="badge-modern bg-green-100 text-green-700">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Terminé
                                                </span>
                                            </div>
                                        </div>
                                        <div class="task-card-body">
                                            <p class="task-description">Cette tâche a été terminée avec succès.</p>
                                            <div class="task-meta">
                                                <div class="task-meta-item">
                                                    <i class="fas fa-user text-gray-400"></i>
                                                    <span class="text-sm text-gray-600">Sophie Leroy</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="task-card-footer">
                                            <a href="#" class="task-view-btn">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir la tâche
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons Test -->
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-mouse-pointer text-primary-600 mr-2"></i>
                            Boutons et Formulaires
                        </h3>
                    </div>
                    <div class="modern-card-body">
                        <div class="space-y-6">
                            <!-- Buttons -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Boutons</h4>
                                <div class="flex flex-wrap gap-3">
                                    <button class="btn-primary-modern">
                                        <i class="fas fa-plus mr-2"></i>
                                        Bouton Principal
                                    </button>
                                    <button class="btn-secondary-modern">
                                        <i class="fas fa-cog mr-2"></i>
                                        Bouton Secondaire
                                    </button>
                                    <button class="btn-accent-modern">
                                        <i class="fas fa-crown mr-2"></i>
                                        Bouton Accent
                                    </button>
                                </div>
                            </div>

                            <!-- Form Inputs -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Champs de formulaire</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="form-label-modern">Nom du projet</label>
                                        <input type="text" class="input-modern" placeholder="Mon nouveau projet">
                                    </div>
                                    <div>
                                        <label class="form-label-modern">Description</label>
                                        <textarea class="input-modern" rows="3" placeholder="Description du projet..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Badges -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Badges</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="badge-primary">Primaire</span>
                                    <span class="badge-success">Succès</span>
                                    <span class="badge-warning">Attention</span>
                                    <span class="badge-danger">Danger</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <div class="flex items-center justify-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-primary rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">P</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Planify</span>
                    </div>
                    <p class="text-sm text-gray-500">
                        &copy; 2024 Planify - Design moderne et responsive
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>