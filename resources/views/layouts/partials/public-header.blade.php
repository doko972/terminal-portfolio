{{-- En-tête public, partagé par layouts.terminal et layouts.public. --}}
<header>
    <div class="header-content">
        <div class="logo" id="secretLogo" onclick="secretClick()">
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
                <li><a href="{{ route('timeline') }}">Parcours</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
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
                    <li id="hiddenLoginLink" style="display: none;">
                        <a href="{{ route('login') }}">Connexion</a>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</header>

<!-- Overlay pour le menu -->
<div class="menu-overlay" id="menuOverlay" onclick="toggleMenu()"></div>
