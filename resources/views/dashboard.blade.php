@extends('layouts.terminal')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <section class="dashboard-section">
            <h2 class="section-title">Dashboard Admin</h2>

            <!-- Message de bienvenue -->
            <div class="terminal-box">
                <p class="welcome-message">
                    <strong>{{ Auth::user()->name }}</strong>, bienvenue dans votre espace administrateur.
                </p>
                <p style="margin-top: 10px; opacity: 0.8;">
                    Email: {{ Auth::user()->email }}
                </p>
                <p style="margin-top: 5px; opacity: 0.7; font-size: 0.9rem;">
                    Membre depuis: {{ Auth::user()->created_at->format('d/m/Y') }}
                </p>
            </div>

            <!-- Statistiques rapides -->
            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-content">
                        <h3>Système</h3>
                        <p class="stat-value">Opérationnel</p>
                        <p class="stat-label">Status</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">🚀</div>
                    <div class="stat-content">
                        <h3>Performance</h3>
                        <p class="stat-value">Optimale</p>
                        <p class="stat-label">État</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">🔒</div>
                    <div class="stat-content">
                        <h3>Sécurité</h3>
                        <p class="stat-value">Active</p>
                        <p class="stat-label">Protection</p>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="dashboard-actions">
                <h3 class="section-subtitle">Actions rapides</h3>

                <div class="action-grid">
                    <a href="{{ url('/') }}" class="action-card">
                        <span class="action-icon">🏠</span>
                        <span class="action-title">Voir le site</span>
                        <span class="action-arrow">→</span>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="action-card">
                        <span class="action-icon">👤</span>
                        <span class="action-title">Mon profil</span>
                        <span class="action-arrow">→</span>
                    </a>

                    <a href="{{ route('admin.projects.index') }}" class="action-card">
                        <span class="action-icon">📝</span>
                        <span class="action-title">Projets</span>
                        <span class="action-badge">→</span>
                    </a>

                    <a href="{{ route('admin.experiences.index') }}" class="action-card">
                        <span class="action-icon">💼</span>
                        <span class="action-title">Expériences</span>
                        <span class="action-badge">→</span>
                    </a>

                </div>
            </div>

            <!-- Terminal interactif (décoratif pour l'instant) -->
            <div class="dashboard-terminal">
                <div class="terminal-header">
                    <span class="terminal-title">$ system.log</span>
                    <span class="terminal-status">● ONLINE</span>
                </div>
                <div class="terminal-content">
                    <p>> Session started: {{ now()->format('H:i:s') }}</p>
                    <p>> User: {{ Auth::user()->name }}</p>
                    <p>> Access level: Administrator</p>
                    <p>> System status: All services operational</p>
                    <p class="terminal-cursor-line">> <span class="terminal-cursor"></span></p>
                </div>
            </div>
        </section>
    </div>
@endsection
