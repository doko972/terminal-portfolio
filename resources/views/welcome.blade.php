@extends('layouts.terminal')

@section('title', 'Accueil')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>
                Développeur Web & Technicien IT<span class="terminal-cursor"></span>
            </h1>
            <p>> Création d'applications web modernes et solutions système</p>
            <a href="#about" class="cta-button">./explorer_portfolio.sh</a>
        </div>
    </section>

    <!-- À propos -->
    <section id="about">
        <h2 class="section-title">À propos</h2>
        <div class="about-content">
            <div class="terminal-box">
                <p>
                    Passionné par le développement web et les technologies système, je
                    conçois des solutions digitales performantes et innovantes.
                </p>
                <p>
                    Mon expertise couvre aussi bien la création d'applications web
                    avec Laravel, HTML, CSS, JavaScript, PHP et SQL, que
                    l'administration de systèmes et réseaux informatiques.
                </p>
                <p>
                    Je m'engage à livrer des projets de qualité, optimisés et adaptés
                    aux besoins spécifiques de chaque client.
                </p>
            </div>
        </div>
    </section>

    <!-- Compétences Web -->
    <section id="skills-web">
        <h2 class="section-title">Compétences Développement Web</h2>
        <div class="skills-grid">
            <div class="skill-card">
                <h3>Frontend</h3>
                <ul class="skill-list">
                    <li>HTML5 / CSS3</li>
                    <li>JavaScript (ES6+)</li>
                    <li>Responsive Design</li>
                    <li>Frameworks CSS</li>
                    <li>Optimisation performance</li>
                </ul>
            </div>
            <div class="skill-card">
                <h3>Backend</h3>
                <ul class="skill-list">
                    <li>PHP (POO)</li>
                    <li>Laravel Framework</li>
                    <li>API REST</li>
                    <li>Authentification</li>
                    <li>Architecture MVC</li>
                </ul>
            </div>
            <div class="skill-card">
                <h3>Bases de données</h3>
                <ul class="skill-list">
                    <li>MySQL / MariaDB</li>
                    <li>SQL avancé</li>
                    <li>Conception BDD</li>
                    <li>Optimisation requêtes</li>
                    <li>Migrations Laravel</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Compétences Systèmes -->
    <section id="skills-sys">
        <h2 class="section-title">Compétences Systèmes & Réseaux</h2>
        <div class="skills-grid">
            <div class="skill-card">
                <h3>Administration Système</h3>
                <ul class="skill-list">
                    <li>Linux (Debian, Ubuntu)</li>
                    <li>Windows Server</li>
                    <li>Virtualisation</li>
                    <li>Scripting Bash/PowerShell</li>
                    <li>Gestion des services</li>
                </ul>
            </div>
            <div class="skill-card">
                <h3>Réseaux</h3>
                <ul class="skill-list">
                    <li>Configuration routeurs/switchs</li>
                    <li>TCP/IP, DNS, DHCP</li>
                    <li>VPN et sécurité réseau</li>
                    <li>Diagnostic et troubleshooting</li>
                    <li>Architecture réseau</li>
                </ul>
            </div>
            <div class="skill-card">
                <h3>DevOps & Outils</h3>
                <ul class="skill-list">
                    <li>Git / GitHub</li>
                    <li>Docker</li>
                    <li>CI/CD</li>
                    <li>Monitoring système</li>
                    <li>Sauvegardes et sécurité</li>
                </ul>
            </div>
        </div>
    </section>
</div>
@endsection