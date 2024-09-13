<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Planify</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }

        .btn-primary {
            background-color: #FF2D20;
            color: white;
        }

        .btn-primary:hover {
            background-color: #e0261c;
        }
    </style>
</head>

<body class="antialiased">
    <!-- Header -->
    <header class="bg-gray-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="#" class="text-white text-3xl font-bold">Planify</a>
                </div>
                <!-- Links -->
                <nav class="space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-300 hover:text-white">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white">Connexion</a>
                        <a href="{{ route('register') }}" class="text-gray-300 hover:text-white">S'inscrire</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gray-900 text-white py-20">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="text-5xl font-bold mb-4">Simplifiez la Gestion de Vos Projets avec Planify</h1>
            <p class="text-lg mb-6">Planifiez, collaborez et exécutez vos projets avec facilité. Optimisez la
                productivité de votre équipe grâce à des outils de gestion avancés.</p>
            <a href="{{ route('register') }}" class="btn-primary px-8 py-4 text-lg font-bold rounded">Commencer
                gratuitement</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-8">Pourquoi choisir Planify ?</h2>
            <div class="grid gap-8 md:grid-cols-3">
                <div class="p-6 border rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Gestion Intuitive des Projets</h3>
                    <p class="text-gray-600">Créez, assignez et suivez facilement vos tâches et projets grâce à notre
                        interface conviviale.</p>
                </div>
                <div class="p-6 border rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Collaboration d'Équipe Efficace</h3>
                    <p class="text-gray-600">Travaillez en équipe, partagez des tâches et communiquez facilement pour
                        rester synchronisés.</p>
                </div>
                <div class="p-6 border rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-4">Suivi en Temps Réel</h3>
                    <p class="text-gray-600">Gardez un œil sur les progrès de chaque projet en temps réel pour ne jamais
                        manquer une échéance.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="bg-gray-900 text-white py-20">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-4xl font-bold mb-6">Passez à la Version Premium</h2>
            <p class="text-lg mb-6">Accédez à des fonctionnalités exclusives et offrez à votre équipe l'outil ultime
                pour la gestion de projets.</p>
            <a href="{{ route('premium.show') }}" class="btn-primary px-8 py-4 text-lg font-bold rounded">Découvrir
                l'offre Premium</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-6">
        <div class="max-w-6xl mx-auto text-center">
            <p>&copy; 2024 Planify. Tous droits réservés.</p>
        </div>
    </footer>
</body>

</html>