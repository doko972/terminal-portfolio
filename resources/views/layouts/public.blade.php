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

    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="admin-header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Contenu principal -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content">
            <h3 class="section-title">Contact</h3>
            <div class="social-links">
                <a href="david.grougi@gmail.com">Email</a>
                <a href="https://github.com/doko972" target="_blank">GitHub</a>
            </div>
            <p style="margin-top: 30px; opacity: 0.7">
                © {{ date('Y') }} Terminal Portfolio | David GROUGI
            </p>
            <p style="opacity: 0.5; font-size: 0.9rem">user@terminal:~$</p>
        </div>
    </footer>
</body>

</html>
