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
[ OK ] Secure Boot Chain Verified
[ OK ] TPM 2.0 Active | Disk Encryption: ON
[ OK ] Firewall + IDS/IPS Running
[ OK ] Network: eth0 (1Gbps) | VPN: Connected
[ OK ] SSH (port 22) | Key-based auth only
[ OK ] Web Services: nginx + php-fpm + mysql
[ OK ] SSL/TLS Certificates Valid
[ OK ] Security Monitoring Active
[ ✓ ] ACCESS GRANTED | All systems GREEN
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
    @include('layouts.partials.public-header')

    <!-- Contenu principal -->
    @yield('content')

    <!-- Footer -->
    @include('layouts.partials.public-footer')
</body>
</html>