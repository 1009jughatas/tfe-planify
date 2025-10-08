<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planify - Plateforme SaaS de Gestion de Projets pour Entreprises</title>
    <meta name="description" content="Planify est la plateforme SaaS B2B ultime pour gérer vos projets d'équipe. Cloisonnement par entreprise, plans tarifaires flexibles, collaboration sécurisée. Découvrez nos solutions pour entreprises.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-project-diagram text-white text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">Planify</span>
                </div>
                
                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-gray-900 font-medium">Dashboard</a>
                        <a href="{{ url('/projects') }}" class="text-gray-600 hover:text-gray-900 font-medium">Projets</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 font-medium">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">S'inscrire</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-blue-50 via-white to-purple-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                    Gestion de Projets
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        pour Entreprises
                    </span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Planify est la plateforme SaaS B2B qui révolutionne la gestion de projets d'équipe. 
                    Cloisonnement sécurisé par entreprise, plans tarifaires flexibles, collaboration avancée.
                </p>
                
                <!-- Boutons d'action principaux -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <a href="{{ route('company.register') }}" class="btn-primary-modern px-8 py-4 text-lg font-semibold">
                        <i class="fas fa-building mr-2"></i>
                        Créer mon Entreprise
                    </a>
                    <a href="{{ route('register') }}" class="btn-secondary-modern px-8 py-4 text-lg font-semibold">
                        <i class="fas fa-user mr-2"></i>
                        Inscription Indépendant
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">100+</div>
                        <div class="text-gray-600">Entreprises</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">10k+</div>
                        <div class="text-gray-600">Projets Gérés</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">99.9%</div>
                        <div class="text-gray-600">Disponibilité</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Pourquoi choisir Planify ?
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Une plateforme conçue spécifiquement pour les entreprises qui ont besoin de sécurité, 
                    de flexibilité et d'efficacité dans la gestion de leurs projets.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Sécurité -->
                <div class="modern-card hover-lift text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Sécurité Totale</h3>
                    <p class="text-gray-600">Cloisonnement par entreprise. Vos données sont parfaitement isolées et protégées.</p>
                </div>

                <!-- Collaboration -->
                <div class="modern-card hover-lift text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Collaboration d'Équipe</h3>
                    <p class="text-gray-600">Gérez vos équipes, assignez des rôles, invitez des membres avec des permissions granulaires.</p>
                </div>

                <!-- Kanban -->
                <div class="modern-card hover-lift text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-columns text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Tableaux Kanban</h3>
                    <p class="text-gray-600">Visualisez vos projets avec des tableaux Kanban intuitifs et personnalisables.</p>
                </div>

                <!-- Plans Flexibles -->
                <div class="modern-card hover-lift text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Plans Flexibles</h3>
                    <p class="text-gray-600">3 plans tarifaires adaptés à votre taille : Starter, Growth, Enterprise.</p>
                </div>

                <!-- Gestion Avancée -->
                <div class="modern-card hover-lift text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-cogs text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Gestion Avancée</h3>
                    <p class="text-gray-600">Statistiques détaillées, rapports, exports, et outils d'administration complets.</p>
                </div>

                <!-- Support -->
                <div class="modern-card hover-lift text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-headset text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Support Dédié</h3>
                    <p class="text-gray-600">Support technique prioritaire et accompagnement personnalisé pour votre entreprise.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Plans Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Plans Tarifaires Entreprise
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Choisissez le plan qui correspond à la taille de votre équipe. 
                    Tous les plans incluent un essai gratuit de 14 jours.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Starter -->
                <div class="modern-card hover-lift">
                    <div class="text-center pb-6 border-b border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
                        <div class="text-4xl font-bold text-blue-600 mb-2">399€<span class="text-lg text-gray-500">/mois</span></div>
                        <p class="text-gray-600">Parfait pour les petites équipes</p>
                    </div>
                    <div class="py-6">
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>10 utilisateurs</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Projets illimités</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Support email</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Tableaux Kanban</span>
                            </li>
                        </ul>
                    </div>
                    <div class="pt-6">
                        <a href="{{ route('company.register') }}?plan=starter" class="btn-secondary-modern w-full text-center">
                            Choisir Starter
                        </a>
                    </div>
                </div>

                <!-- Growth -->
                <div class="modern-card hover-lift border-2 border-blue-500 relative">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-blue-500 text-white px-4 py-1 rounded-full text-sm font-medium">Populaire</span>
                    </div>
                    <div class="text-center pb-6 border-b border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Growth</h3>
                        <div class="text-4xl font-bold text-blue-600 mb-2">599€<span class="text-lg text-gray-500">/mois</span></div>
                        <p class="text-gray-600">Idéal pour les équipes en croissance</p>
                    </div>
                    <div class="py-6">
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>20 utilisateurs</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Projets illimités</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Support prioritaire</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Statistiques avancées</span>
                            </li>
                        </ul>
                    </div>
                    <div class="pt-6">
                        <a href="{{ route('company.register') }}?plan=growth" class="btn-primary-modern w-full text-center">
                            Choisir Growth
                        </a>
                    </div>
                </div>

                <!-- Enterprise -->
                <div class="modern-card hover-lift">
                    <div class="text-center pb-6 border-b border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
                        <div class="text-4xl font-bold text-blue-600 mb-2">999€<span class="text-lg text-gray-500">/mois</span></div>
                        <p class="text-gray-600">Pour les grandes organisations</p>
                    </div>
                    <div class="py-6">
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Utilisateurs illimités</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Projets illimités</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Support dédié</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>Fonctionnalités premium</span>
                            </li>
                        </ul>
                    </div>
                    <div class="pt-6">
                        <a href="{{ route('company.register') }}?plan=enterprise" class="btn-secondary-modern w-full text-center">
                            Choisir Enterprise
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Prêt à transformer votre gestion de projets ?
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Rejoignez des centaines d'entreprises qui font confiance à Planify pour gérer leurs projets d'équipe.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('company.register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    <i class="fas fa-building mr-2"></i>
                    Créer mon Entreprise
                </a>
                <a href="{{ route('register') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                    <i class="fas fa-user mr-2"></i>
                    Essayer Individuellement
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-project-diagram text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-white">Planify</span>
                    </div>
                    <p class="text-gray-400">La plateforme SaaS de gestion de projets pour entreprises modernes.</p>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Produit</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white">Fonctionnalités</a></li>
                        <li><a href="#" class="hover:text-white">Tarifs</a></li>
                        <li><a href="#" class="hover:text-white">Sécurité</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white">Documentation</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                        <li><a href="#" class="hover:text-white">Statut</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Légal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white">Mentions légales</a></li>
                        <li><a href="#" class="hover:text-white">CGU</a></li>
                        <li><a href="#" class="hover:text-white">Confidentialité</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p>&copy; 2024 Planify. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>