<header>
    <div class="header-content admin-header-content">
        <div class="logo">
            <a href="{{ route('dashboard') }}" class="terminal-logo">
                <span class="prompt">root@</span>portfolio<span class="blink">_</span>
            </a>
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
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                        Projets
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        Mon profil
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}" target="_blank">
                        Voir le site
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); this.closest('form').submit();">
                            Déconnexion
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</header>
