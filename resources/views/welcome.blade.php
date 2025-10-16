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
                <nav class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ Auth::user() && Auth::user()->isPartOfCompany() ? route('entreprise.dashboard') : route('dashboard') }}" class="text-gray-600 hover:text-gray-900 font-medium">Dashboard</a>
                        <a href="{{ url('/projects') }}" class="text-gray-600 hover:text-gray-900 font-medium">Projets</a>
                    @else
                        <!-- Bouton Connexion Indépendant -->
                        <a href="{{ route('login.indep') }}" class="flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors font-medium">
                            <i class="fas fa-user mr-2"></i>
                            Connexion Indépendant
                        </a>
                        
                        <!-- Bouton Connexion Employé -->
                        <a href="{{ route('entreprise.login') }}?type=employee" class="flex items-center bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors font-medium">
                            <i class="fas fa-users mr-2"></i>
                            Connexion Employé
                        </a>
                        
                        <!-- Bouton Connexion Entreprise -->
                        <a href="{{ route('entreprise.login') }}" class="flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            <i class="fas fa-building mr-2"></i>
                            Connexion Entreprise
                        </a>
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
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent animate-gradient">
                        pour Tous
                    </span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Que vous soyez indépendant ou entreprise, Planify s'adapte à vos besoins. 
                    Interface intuitive, sécurité maximale, collaboration fluide.
                </p>
                
                <!-- Boutons d'action principaux -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                    <a href="{{ route('entreprise.register') }}" class="btn-primary-modern px-8 py-4 text-lg font-semibold">
                        <i class="fas fa-building mr-2"></i>
                        Créer mon Entreprise
                    </a>
                    <a href="{{ route('register.indep') }}" class="btn-secondary-modern px-8 py-4 text-lg font-semibold">
                        <i class="fas fa-user mr-2"></i>
                        Inscription Indépendant
                    </a>
                </div>
                
                <!-- Séparateur -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center">
                        <div class="flex-1 h-px bg-gray-300"></div>
                        <span class="px-4 text-sm text-gray-500 bg-gray-50">Ou connectez-vous</span>
                        <div class="flex-1 h-px bg-gray-300"></div>
                    </div>
                </div>
                
                <!-- Boutons de connexion -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <a href="{{ route('entreprise.login') }}?type=employee" class="inline-flex items-center justify-center px-8 py-4 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-users mr-3 text-xl"></i>
                        Connexion Employé
                    </a>
                    <a href="{{ route('entreprise.login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-building mr-3 text-xl"></i>
                        Connexion Entreprise
                    </a>
                    <a href="{{ route('login.indep') }}" class="inline-flex items-center justify-center px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-semibold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-user mr-3 text-xl"></i>
                        Connexion Indépendant
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
                    Une plateforme adaptée à tous vos besoins : que vous soyez indépendant ou entreprise, 
                    Planify s'adapte à votre style de travail.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Sécurité -->
                <div class="modern-card hover-lift text-center card-hover">
                    <div class="feature-icon bg-gradient-to-br from-green-500 to-green-600">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Sécurité Totale</h3>
                    <p class="text-gray-600">Cloisonnement par entreprise. Vos données sont parfaitement isolées et protégées.</p>
                </div>

                <!-- Collaboration -->
                <div class="modern-card hover-lift text-center card-hover">
                    <div class="feature-icon bg-gradient-to-br from-blue-500 to-blue-600">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Collaboration d'Équipe</h3>
                    <p class="text-gray-600">Gérez vos équipes, assignez des rôles, invitez des membres avec des permissions granulaires.</p>
                </div>


                <!-- Plans Flexibles -->
                <div class="modern-card hover-lift text-center card-hover">
                    <div class="feature-icon bg-gradient-to-br from-orange-500 to-orange-600">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Plans Flexibles</h3>
                    <p class="text-gray-600">3 plans tarifaires adaptés à votre taille : Starter, Growth, Enterprise.</p>
                </div>

                <!-- Premium Features -->
                <div class="modern-card hover-lift text-center card-hover">
                    <div class="feature-icon bg-gradient-to-br from-yellow-400 to-orange-500">
                        <i class="fas fa-crown text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Premium Indépendants</h3>
                    <p class="text-gray-600">Projets illimités, export de données, thème sombre, analyses détaillées.</p>
                </div>

                <!-- Gestion Avancée -->
                <div class="modern-card hover-lift text-center card-hover">
                    <div class="feature-icon bg-gradient-to-br from-indigo-500 to-indigo-600">
                        <i class="fas fa-cogs text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Gestion Avancée</h3>
                    <p class="text-gray-600">Statistiques détaillées, rapports, exports, et outils d'administration complets.</p>
                </div>

                <!-- Support -->
                <div class="modern-card hover-lift text-center card-hover">
                    <div class="feature-icon bg-gradient-to-br from-pink-500 to-pink-600">
                        <i class="fas fa-headset text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Support Dédié</h3>
                    <p class="text-gray-600">Support technique prioritaire et accompagnement personnalisé pour votre entreprise.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Indépendants -->
    <section class="py-20 bg-gradient-to-br from-green-50 to-emerald-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-user mr-2"></i>
                        Pour les Indépendants
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        Gérez vos projets personnels avec simplicité
                    </h2>
                    <p class="text-xl text-gray-600 mb-8">
                        Parfait pour les freelances, consultants et entrepreneurs qui veulent organiser 
                        leurs projets personnels sans complexité.
                    </p>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <span class="text-gray-700">Jusqu'à 10 projets gratuits</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <span class="text-gray-700">Gestion des tâches et deadlines</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <span class="text-gray-700">Interface simple et intuitive</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <span class="text-gray-700">Possibilité d'upgrade premium</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register.indep') }}" class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-rocket mr-2"></i>
                            Commencer gratuitement
                        </a>
                        <a href="{{ route('login.indep') }}" class="inline-flex items-center justify-center px-6 py-3 border-2 border-green-600 text-green-600 font-semibold rounded-lg hover:bg-green-600 hover:text-white transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Se connecter
                        </a>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 transform rotate-3 hover:rotate-0 transition-transform duration-300 animate-float">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Mes Projets</h3>
                            <span class="text-sm text-green-600 font-medium">3/10 projets</span>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg border-l-4 border-green-500">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Site Web Client</h4>
                                        <p class="text-sm text-gray-600">5 tâches • En cours</p>
                                    </div>
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg border-l-4 border-blue-500">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Application Mobile</h4>
                                        <p class="text-sm text-gray-600">12 tâches • Planifié</p>
                                    </div>
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-lg border-l-4 border-purple-500">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Formation en ligne</h4>
                                        <p class="text-sm text-gray-600">8 tâches • Terminé</p>
                                    </div>
                                    <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-star text-yellow-500 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">Upgrade Premium disponible</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">Créez plus de projets et débloquez des fonctionnalités avancées</p>
                        </div>
                    </div>
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
                        </ul>
                    </div>
                    <div class="pt-6">
                        <a href="{{ route('entreprise.register') }}?plan=starter" class="btn-secondary-modern w-full text-center">
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
                        <a href="{{ route('entreprise.register') }}?plan=growth" class="btn-primary-modern w-full text-center">
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
                        <a href="{{ route('entreprise.register') }}?plan=enterprise" class="btn-secondary-modern w-full text-center">
                            Choisir Enterprise
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Témoignages Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Ce que disent nos utilisateurs
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Découvrez comment Planify transforme la gestion de projets pour nos clients
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Témoignage Entreprise -->
                <div class="testimonial-card">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">M</span>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Marie Dubois</h4>
                            <p class="text-sm text-gray-600">CEO, TechCorp</p>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4">
                        "Planify a révolutionné notre gestion de projets. L'interface est intuitive, 
                        la collaboration d'équipe fluide, et la sécurité au top."
                    </p>
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <!-- Témoignage Indépendant -->
                <div class="testimonial-card">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">P</span>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Pierre Martin</h4>
                            <p class="text-sm text-gray-600">Freelance Designer</p>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4">
                        "Parfait pour organiser mes projets clients. Simple, efficace, et le fait 
                        que ce soit gratuit pour les petits projets est un vrai plus !"
                    </p>
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <!-- Témoignage PM -->
                <div class="testimonial-card">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">S</span>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">Sophie Leroy</h4>
                            <p class="text-sm text-gray-600">Project Manager, StartupX</p>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4">
                        "L'interface est exceptionnelle. Mon équipe de 15 personnes 
                        travaille maintenant de manière beaucoup plus efficace."
                    </p>
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
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
            <!-- Sélecteur de type de compte -->
            <div class="max-w-4xl mx-auto">
                <h3 class="text-xl font-semibold text-white mb-8 text-center">Choisissez votre type de compte</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Entreprise -->
                    <div class="group relative bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 p-8 hover:bg-white/20 hover:border-white/40 transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('entreprise.register') }}'">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                                <i class="fas fa-building text-white text-2xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-white mb-3">Entreprise</h4>
                            <p class="text-white/80 mb-4">Créez une équipe et gérez vos projets collaboratifs</p>
                            
                            <ul class="text-left text-sm text-white/80 space-y-2 mb-4">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-400 mr-2"></i>
                                    Gestion d'équipe complète
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-400 mr-2"></i>
                                    Projets collaboratifs
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-400 mr-2"></i>
                                    Support prioritaire
                                </li>
                            </ul>
                            
                            <div class="text-center">
                                <span class="text-2xl font-bold text-white">À partir de 399€</span>
                                <span class="text-white/80">/mois</span>
                            </div>
                        </div>
                    </div>

                    <!-- Indépendant -->
                    <div class="group relative bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 p-8 hover:bg-white/20 hover:border-white/40 transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('register.indep') }}'">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                                <i class="fas fa-user text-white text-2xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-white mb-3">Indépendant</h4>
                            <p class="text-white/80 mb-4">Gérez vos projets personnels et restez organisé</p>
                            
                            <ul class="text-left text-sm text-white/80 space-y-2 mb-4">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-400 mr-2"></i>
                                    Projets personnels
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-400 mr-2"></i>
                                    Gestion des tâches
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-400 mr-2"></i>
                                    Support communautaire
                                </li>
                            </ul>
                            
                            <div class="text-center">
                                <span class="text-2xl font-bold text-white">Gratuit</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Questions fréquentes
                </h2>
                <p class="text-xl text-gray-600">
                    Tout ce que vous devez savoir sur Planify
                </p>
            </div>
            
            <div class="space-y-8">
                <!-- FAQ 1 -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        Quelle est la différence entre un compte Indépendant et Entreprise ?
                    </h3>
                    <p class="text-gray-600">
                        <strong>Indépendant :</strong> Parfait pour les freelances et entrepreneurs individuels. 
                        Gratuit jusqu'à 10 projets, interface simplifiée, gestion personnelle.
                        <br><br>
                        <strong>Entreprise :</strong> Conçu pour les équipes. Plans tarifaires à partir de 399€/mois, 
                        gestion d'équipe, invitations, collaboration avancée, et support prioritaire.
                    </p>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        Puis-je passer d'un compte Indépendant à Entreprise ?
                    </h3>
                    <p class="text-gray-600">
                        Oui, absolument ! Vous pouvez à tout moment upgrader votre compte indépendant vers 
                        un plan premium ou créer une entreprise. Vos projets existants seront conservés.
                    </p>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        Comment fonctionne l'invitation d'employés ?
                    </h3>
                    <p class="text-gray-600">
                        En tant qu'administrateur d'entreprise, vous pouvez inviter des employés par email. 
                        Ils recevront un lien sécurisé pour créer leur compte et rejoindre automatiquement votre équipe.
                    </p>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        Mes données sont-elles sécurisées ?
                    </h3>
                    <p class="text-gray-600">
                        Absolument ! Chaque entreprise a son propre espace cloisonné. Vos données sont 
                        chiffrées, sauvegardées régulièrement, et nous respectons le RGPD. La sécurité 
                        est notre priorité absolue.
                    </p>
                </div>

                <!-- FAQ 5 -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        Y a-t-il un essai gratuit pour les entreprises ?
                    </h3>
                    <p class="text-gray-600">
                        Oui ! Tous nos plans entreprise incluent un essai gratuit de 14 jours. 
                        Vous pouvez tester toutes les fonctionnalités sans engagement.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Connexion -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Déjà un compte ?
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Connectez-vous à votre espace personnel ou d'entreprise pour accéder à vos projets.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                <!-- Connexion Entreprise -->
                <div class="group relative bg-white rounded-2xl border-2 border-gray-200 p-8 hover:border-blue-500 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('entreprise.login') }}'">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                            <i class="fas fa-building text-white text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Connexion Entreprise</h4>
                        <p class="text-gray-600 mb-6">Accédez à votre espace d'équipe et gérez vos projets collaboratifs</p>
                        
                        <div class="text-center">
                            <span class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Se connecter
                            </span>
                        </div>
                    </div>
                    
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-500/5 to-indigo-600/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>

                <!-- Connexion Indépendant -->
                <div class="group relative bg-white rounded-2xl border-2 border-gray-200 p-8 hover:border-green-500 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('login.indep') }}'">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                            <i class="fas fa-user text-white text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Connexion Indépendant</h4>
                        <p class="text-gray-600 mb-6">Accédez à votre espace personnel et gérez vos projets individuels</p>
                        
                        <div class="text-center">
                            <span class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Se connecter
                            </span>
                        </div>
                    </div>
                    
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-green-500/5 to-emerald-600/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
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

    <style>
        /* Boutons Premium */
        .btn-premium-modern {
            @apply inline-flex items-center px-8 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300;
        }
    </style>
</body>
</html>