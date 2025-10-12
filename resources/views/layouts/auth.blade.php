<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Terminal Portfolio') }} - @yield('title', 'Authentification')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <!-- Canvas Matrix -->
    <canvas id="matrix-canvas"></canvas>

    <!-- Container Auth -->
    <div class="auth-container">
        <div class="auth-box">
            <!-- Logo -->
            <div class="auth-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>

            <!-- Titre de la page -->
            <h1 class="auth-title">@yield('auth-title')</h1>

            <!-- Messages de session -->
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Contenu -->
            @yield('content')

            <!-- Lien retour accueil -->
            <div class="auth-footer">
                <a href="{{ route('welcome') }}" class="back-link">← Retour à l'accueil</a>
            </div>
        </div>
    </div>
</body>
</html>