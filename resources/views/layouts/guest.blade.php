<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Vite (for CSS and JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
        <!-- Logo (optional) -->
        <div>
            <a href="/">
                <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" class="w-20 h-20">
            </a>
        </div>

        <!-- Page Content -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </div>
</body>

</html>