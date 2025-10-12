<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Terminal Portfolio') }} - @yield('title', 'Accueil')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- Styles avec Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <!-- ===================== BOOT SCREEN ===================== -->
    <div id="boot-screen" aria-hidden="false">
        <canvas id="boot-matrix"></canvas>

        <div class="boot-wrap">
            <div class="boot-header">
                <span class="prompt">&gt; booting system...</span>
                <button id="boot-skip" class="boot-skip" aria-label="Passer l'intro (Échap)">
                    Skip ⎋
                </button>
            </div>

            <div class="boot-logo">
                <div class="logo-ring">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Terminal Portfolio">
                </div>
            </div>

            <pre class="boot-log" aria-live="polite">
[ OK ] Initializing Neon Kernel v2025.1
[ OK ] Loading drivers: web.sys, network.stack, auth.layer
[ OK ] Spawning services: matrix.fx, halo.light, scan.vertical
            </pre>

            <div class="boot-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" aria-label="Chargement du système">
                <div class="boot-bar"></div>
            </div>

            <div class="boot-footer">
                <span class="ready" aria-hidden="true">system ready_</span>
            </div>
        </div>
    </div>
    <!-- ===================== /BOOT SCREEN ===================== -->

    <!-- Canvas Matrix -->
    <canvas id="matrix-canvas"></canvas>

    <!-- Header -->
    <header>
        <div class="header-content">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Terminal Portfolio">
            </div>
            <button class="menu-toggle" id="menuToggle" onclick="toggleMenu()" aria-label="Menu">
                <div class="burger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            <nav id="mainNav">
                <ul>
                    <li><a href="{{ url('/') }}#home">Accueil</a></li>
                    <li><a href="{{ url('/') }}#about">À propos</a></li>
                    <li><a href="{{ url('/') }}#skills-web">Web Dev</a></li>
                    <li><a href="{{ url('/') }}#skills-sys">Systèmes</a></li>
                    <li><a href="{{ url('/') }}#contact">Contact</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <a href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    Déconnexion
                                </a>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Connexion</a></li>
                        <li><a href="{{ route('register') }}">Inscription</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <!-- Overlay pour le menu -->
    <div class="menu-overlay" id="menuOverlay" onclick="toggleMenu()"></div>

    <!-- Contenu principal -->
    @yield('content')

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content">
            <h3 class="section-title">Contact</h3>
            <div class="social-links">
                <a href="mailto:contact@example.com">Email</a>
                <a href="#" target="_blank">GitHub</a>
                <a href="#" target="_blank">LinkedIn</a>
                <a href="#" target="_blank">Portfolio</a>
            </div>
            <p style="margin-top: 30px; opacity: 0.7">
                © {{ date('Y') }} Terminal Portfolio | Développé avec passion
            </p>
            <p style="opacity: 0.5; font-size: 0.9rem">user@terminal:~$</p>
        </div>
    </footer>
</body>
</html>