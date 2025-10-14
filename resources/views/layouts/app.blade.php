<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
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

<body class="h-full bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Mobile Navigation -->
        <x-mobile-nav />
        
        <!-- Desktop Navigation -->
        <div class="hidden lg:block lg:w-64 lg:flex-shrink-0">
            @include('layouts.navigation')
        </div>

        <!-- Page Content -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white border-b border-gray-200 shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Main Content -->
            <div class="flex-1">
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Modern Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-primary rounded-lg flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('images/logo.png') }}" alt="Planify" class="w-5 h-5 object-contain filter brightness-0 invert">
                        </div>
                        <span class="text-xl font-bold text-gray-900">Planify</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">
                        Siège social : Boulevard de l'Empereur 10, 1000 Bruxelles
                    </p>
                    <p class="text-sm text-gray-600">
                        Contact : <a href="mailto:info@mcedia.com" class="text-primary-600 hover:text-primary-700 transition-colors">info@mcedia.com</a>
                    </p>
                </div>
                <div>
                    <h6 class="text-sm font-semibold text-gray-900 mb-3">Informations</h6>
                    <p class="text-sm text-gray-600 mb-2">
                        <a href="{{ route('mentions.legales') }}" class="text-primary-600 hover:text-primary-700 transition-colors">Mentions légales</a>
                    </p>
                    <p class="text-sm text-gray-500">
                        Hébergeur : OVH – 2 rue Kellermann, 59100 Roubaix, France
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-200 mt-8 pt-6">
                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        &copy; 2024 Planify - Tous droits réservés.
                    </p>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>