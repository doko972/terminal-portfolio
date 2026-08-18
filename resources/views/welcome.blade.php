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
                <div class="hero-actions">
                    <a href="#about" class="cta-button">./explorer_portfolio.sh</a>
                    @if ($hasCv)
                        <a href="{{ route('cv.download') }}" class="cta-button cta-secondary">
                            ./telecharger_cv.pdf
                        </a>
                    @endif
                </div>
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

        <!-- Section Portfolio -->
        <section id="portfolio" class="section" data-projects='@json($projects->keyBy('id'))'>
            <div class="container">
                <h2 class="section-title">
                    <span class="prompt">root@portfolio:~$</span> ls projects/
                </h2>

                <!-- Projets mis en avant -->
                @if ($featuredProjects->isNotEmpty())
                    <div class="featured-projects-wrapper">
                        <h3 class="subsection-title">
                            <span class="star">★</span> Projets mis en avant
                        </h3>
                        <div class="featured-projects">
                            @foreach ($featuredProjects as $project)
                                @php
                                    $mainImage = $project->images
                                        ? $project->images->where('is_main', true)->first()
                                        : null;
                                @endphp
                                <div class="project-card featured" data-project-id="{{ $project->id }}">
                                    <div class="project-image">
                                        @if ($mainImage)
                                            <img src="{{ Storage::url($mainImage->image_path) }}" alt="{{ $project->title }}">
                                        @elseif($project->image)
                                            {{-- Fallback sur l'ancienne colonne image --}}
                                            <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}">
                                        @else
                                            <div class="project-image-placeholder">
                                                <span>{{ strtoupper(substr($project->title, 0, 2)) }}</span>
                                            </div>
                                        @endif
                                        <div class="project-overlay">
                                            <button class="btn-view-details" data-project-id="{{ $project->id }}"
                                                onclick="openProjectModal(this.dataset.projectId)">
                                                <span class="prompt">></span> Voir les détails
                                            </button>
                                        </div>
                                    </div>
                                    <div class="project-content">
                                        <div class="project-header">
                                            <h3 class="project-title">{{ $project->title }}</h3>
                                            <span class="featured-badge">★</span>
                                        </div>
                                        <p class="project-description">{{ Str::limit($project->description, 100) }}</p>
                                        <div class="project-technologies">
                                            @foreach ($project->technologies_array as $tech)
                                                <span class="tech-badge">{{ $tech }}</span>
                                            @endforeach
                                        </div>
                                        <div class="project-footer">
                                            @if ($project->completed_at)
                                                <span class="project-date">
                                                    <span class="prompt">📅</span>
                                                    {{ $project->completed_at->format('Y') }}
                                                </span>
                                            @endif
                                            <div class="project-links">
                                                <a href="{{ route('project.show', $project->slug) }}"
                                                    class="project-link" title="Lien permanent">
                                                    <span class="prompt">🔗</span>
                                                </a>
                                                @if ($project->url)
                                                    <a href="{{ $project->url }}" target="_blank" class="project-link"
                                                        title="Voir le site">
                                                        <span class="prompt">🌐</span>
                                                    </a>
                                                @endif
                                                @if ($project->github_url)
                                                    <a href="{{ $project->github_url }}" target="_blank" class="project-link"
                                                        title="Voir sur GitHub">
                                                        <span class="prompt">💻</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Filtres par technologies -->
                @if ($allTechnologies->isNotEmpty())
                    <div class="filters-wrapper">
                        <div class="filters-header">
                            <span class="prompt">></span> Filtrer par technologie :
                        </div>
                        <div class="filters">
                            <button class="filter-btn active" data-filter="all">
                                Tous les projets
                            </button>
                            @foreach ($allTechnologies as $tech)
                                <button class="filter-btn" data-filter="{{ strtolower($tech) }}">
                                    {{ $tech }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Tous les projets -->
                <div class="projects-grid">
                    @foreach ($projects->where('is_featured', false) as $project)
                        @php
                            $mainImage = $project->images ? $project->images->where('is_main', true)->first() : null;
                        @endphp
                        <div class="project-card" data-project-id="{{ $project->id }}"
                            data-technologies="{{ strtolower(implode(' ', $project->technologies_array)) }}">
                            <div class="project-image">
                                @if ($mainImage)
                                    <img src="{{ Storage::url($mainImage->image_path) }}" alt="{{ $project->title }}">
                                @elseif($project->image)
                                    {{-- Fallback sur l'ancienne colonne image --}}
                                    <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}">
                                @else
                                    <div class="project-image-placeholder">
                                        <span>{{ strtoupper(substr($project->title, 0, 2)) }}</span>
                                    </div>
                                @endif
                                <div class="project-overlay">
                                    <button class="btn-view-details" data-project-id="{{ $project->id }}"
                                        onclick="openProjectModal(this.dataset.projectId)">
                                        <span class="prompt">></span> Voir les détails
                                    </button>
                                </div>
                            </div>
                            <div class="project-content">
                                <h3 class="project-title">{{ $project->title }}</h3>
                                <p class="project-description">{{ Str::limit($project->description, 100) }}</p>
                                <div class="project-technologies">
                                    @foreach ($project->technologies_array as $tech)
                                        <span class="tech-badge">{{ $tech }}</span>
                                    @endforeach
                                </div>
                                <div class="project-footer">
                                    @if ($project->completed_at)
                                        <span class="project-date">
                                            <span class="prompt">📅</span> {{ $project->completed_at->format('Y') }}
                                        </span>
                                    @endif
                                    <div class="project-links">
                                        <a href="{{ route('project.show', $project->slug) }}" class="project-link"
                                            title="Lien permanent">
                                            <span class="prompt">🔗</span>
                                        </a>
                                        @if ($project->url)
                                            <a href="{{ $project->url }}" target="_blank" class="project-link" title="Voir le site">
                                                <span class="prompt">🌐</span>
                                            </a>
                                        @endif
                                        @if ($project->github_url)
                                            <a href="{{ $project->github_url }}" target="_blank" class="project-link"
                                                title="Voir sur GitHub">
                                                <span class="prompt">💻</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($projects->isEmpty())
                    <div class="no-projects">
                        <p><span class="prompt">!</span> Aucun projet disponible pour le moment.</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- Section Expériences Professionnelles -->
        <section id="experiences" class="experiences-section">
            <div class="container">
                <!-- En-tête -->
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="prompt">root@portfolio:~$</span> cat experiences.txt
                    </h2>
                    <p class="section-subtitle">
                        Mon parcours professionnel • {{ $stats['years_experience'] ?? 0 }} ans d'expérience
                    </p>
                </div>

                <!-- Mini statistiques -->
                <div class="mini-stats">
                    <div class="mini-stat">
                        <span class="mini-stat-value">{{ $stats['years_experience'] ?? 0 }}</span>
                        <span class="mini-stat-label">Années</span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-stat-value">{{ $stats['experiences_count'] ?? 0 }}</span>
                        <span class="mini-stat-label">Expériences</span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-stat-value">{{ $stats['projects_count'] ?? 0 }}</span>
                        <span class="mini-stat-label">Projets</span>
                    </div>
                </div>

                <!-- Liste des expériences -->
                <div class="experiences-grid">
                    @forelse($experiences as $experience)
                        <div class="experience-card">
                            <!-- Logo -->
                            <div class="experience-logo-container">
                                @if($experience->logo_url)
                                    <img src="{{ $experience->logo_url }}" alt="{{ $experience->company }}" class="experience-logo">
                                @else
                                    <div class="experience-logo-placeholder">💼</div>
                                @endif
                            </div>

                            <!-- Contenu -->
                            <div class="experience-content">
                                <div class="experience-header">
                                    <h3 class="experience-title">{{ $experience->title }}</h3>
                                    @if($experience->is_current)
                                        <span class="badge-current">En cours</span>
                                    @endif
                                </div>

                                <div class="experience-company">
                                    @if($experience->company_url)
                                        <a href="{{ $experience->company_url }}" target="_blank" class="company-link">
                                            {{ $experience->company }}
                                        </a>
                                    @else
                                        {{ $experience->company }}
                                    @endif
                                </div>

                                <div class="experience-meta">
                                    <span class="experience-period">{{ $experience->period_fr }}</span>
                                    <span class="separator">•</span>
                                    <span class="experience-duration">{{ $experience->duration }}</span>
                                </div>

                                @if($experience->location)
                                    <div class="experience-location">
                                        📍 {{ $experience->location }}
                                    </div>
                                @endif

                                @if($experience->description)
                                    <p class="experience-description">
                                        {{ Str::limit($experience->description, 120) }}
                                    </p>
                                @endif

                                <!-- Technologies -->
                                @if($experience->technologies_array && count($experience->technologies_array) > 0)
                                    <div class="experience-technologies">
                                        @foreach(array_slice($experience->technologies_array, 0, 4) as $tech)
                                            <span class="tech-tag">{{ $tech }}</span>
                                        @endforeach
                                        @if(count($experience->technologies_array) > 4)
                                            <span class="tech-tag">+{{ count($experience->technologies_array) - 4 }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="no-experiences">
                            <p><span class="prompt">!</span> Aucune expérience à afficher pour le moment</p>
                        </div>
                    @endforelse
                </div>

                <!-- Bouton pour voir tout le parcours -->
                <div class="section-footer">
                    <a href="{{ route('timeline') }}" class="terminal-btn-outline">
                        <span class="prompt">></span> Voir tout mon parcours
                    </a>
                </div>
            </div>
        </section>

        <!-- Modal de détails du projet -->
        <div id="projectModal" class="project-modal">
            <div class="modal-overlay" onclick="closeProjectModal()"></div>
            <div class="modal-content">
                <button class="modal-close" onclick="closeProjectModal()">
                    <span>✕</span>
                </button>
                <div id="modalProjectContent">
                    <!-- Contenu Injecté dynamiquement -->
                </div>
            </div>
        </div>
        <!-- Lightbox plein écran -->
        <div id="imageLightbox" class="image-lightbox">
            <button class="lightbox-close" onclick="closeLightbox()">✕</button>
            <button class="lightbox-nav lightbox-prev" onclick="navigateLightbox(-1)">‹</button>
            <button class="lightbox-nav lightbox-next" onclick="navigateLightbox(1)">›</button>
            <img id="lightboxImage" src="" alt="Image en grand">
            <div class="lightbox-counter">
                <span id="lightboxImageIndex">1</span> / <span id="lightboxImageTotal">1</span>
            </div>
        </div>

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