<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Planify') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100" style="display:flex">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main style="width: 100%">

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
    <footer class="bg-gray-800 text-white text-center py-6 mt-12">
        <p class="text-sm mt-2">
            <a href="{{ route('mentions.legales') }}" class="underline text-blue-400 hover:text-blue-200">Mentions légales</a>
        </p>

        <p>&copy; 2024 Planify - Tous droits réservés.</p>
        <p>
            Planify<br>
            Siège social : Boulevard de l'Empereur 10, 1000 Bruxelles<br>
            Contact : <a href="mailto:info@mcedia.com" class="underline text-blue-300">info@mcedia.com</a>
        </p>
        <p class="mt-4 text-sm text-gray-400">
            Hébergeur : OVH – 2 rue Kellermann, 59100 Roubaix, France – Tél : +33 9 72 10 10 07
        </p>
    </footer>

</body>

</html>