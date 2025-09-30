<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Planify') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex flex-col lg:flex-row">
        <!-- Mobile Navigation -->
        <x-mobile-nav />
        
        <!-- Desktop Navigation -->
        <div class="hidden lg:block">
            @include('layouts.navigation')
        </div>

        <!-- Page Content -->
        <main class="flex-1 lg:ml-0">

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            {{ $slot }}
        </main>
    </div>
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="container px-4">
            <div class="row">
                <div class="col-12 col-md-6 mb-4">
                    <h5 class="mb-3">Planify</h5>
                    <p class="text-sm text-gray-300 mb-2">
                        Siège social : Boulevard de l'Empereur 10, 1000 Bruxelles
                    </p>
                    <p class="text-sm text-gray-300">
                        Contact : <a href="mailto:info@mcedia.com" class="text-blue-300 hover:text-blue-200">info@mcedia.com</a>
                    </p>
                </div>
                <div class="col-12 col-md-6 mb-4">
                    <h6 class="mb-3">Informations</h6>
                    <p class="text-sm text-gray-300 mb-2">
                        <a href="{{ route('mentions.legales') }}" class="text-blue-300 hover:text-blue-200">Mentions légales</a>
                    </p>
                    <p class="text-sm text-gray-400">
                        Hébergeur : OVH – 2 rue Kellermann, 59100 Roubaix, France
                    </p>
                </div>
            </div>
            <hr class="border-gray-600 my-4">
            <div class="text-center">
                <p class="text-sm text-gray-300 mb-0">
                    &copy; 2024 Planify - Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>

</body>

</html>