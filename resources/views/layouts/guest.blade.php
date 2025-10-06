<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0ea5e9">

        <title>{{ config('app.name', 'Planify') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="h-full bg-gradient-to-br from-primary-50 via-white to-accent-50 font-sans antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
            <!-- Logo Section -->
            <div class="mb-8">
                <a href="/" class="flex items-center space-x-3 group">
                    <div class="w-16 h-16 bg-gradient-primary rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300">
                        <span class="text-white font-bold text-2xl">P</span>
                    </div>
                    <div class="text-left">
                        <h1 class="text-3xl font-bold text-gray-900">Planify</h1>
                        <p class="text-sm text-gray-600">Gestion de projets simplifiée</p>
                    </div>
                </a>
            </div>

            <!-- Auth Card -->
            <div class="w-full max-w-md">
                <div class="modern-card">
                    <div class="modern-card-body">
                        {{ $slot }}
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        &copy; 2024 Planify. Tous droits réservés.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
