<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Portfolio Terminal')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <!-- Animation Matrix en arrière-plan -->
    <canvas id="matrix-bg"></canvas>

    <div class="page-shell">
        {{-- En-tête public : ce layout sert des pages accessibles aux visiteurs
             anonymes (contact, parcours), il ne doit donc pas exposer la
             navigation d'administration. --}}
        @include('layouts.partials.public-header')

        <!-- Page Heading -->
        @isset($header)
            <header class="admin-header">
                <div class="admin-header-inner">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Contenu principal -->
        <main>
            @yield('content')
        </main>
    </div>

    @include('layouts.partials.public-footer')
</body>

</html>
