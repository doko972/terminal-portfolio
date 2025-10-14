@extends('layouts.public')

@section('title', 'Mon Parcours')

@section('content')
<section id="timeline" class="timeline-section">
    <div class="container">
<div class="timeline-header">
    <div class="terminal-window">
        <div class="terminal-window-header">
            <span class="terminal-title">> parcours.sh</span>
            <div class="terminal-buttons">
                <span class="btn-close"></span>
                <span class="btn-minimize"></span>
                <span class="btn-maximize"></span>
            </div>
        </div>
        <div class="terminal-window-body">
            <h1 class="section-title">
                <span class="prompt">root@portfolio:~$</span> cat mon_parcours.txt
            </h1>
            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">💼</div>
                    <div class="stat-value">{{ $stats['total_years'] }}</div>
                    <div class="stat-label">Années d'expérience</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🏢</div>
                    <div class="stat-value">{{ $stats['companies_count'] }}</div>
                    <div class="stat-label">Entreprises</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🏆</div>
                    <div class="stat-value">{{ $stats['certifications_count'] }}</div>
                    <div class="stat-label">Certifications</div>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Filtres par type -->
        <div class="timeline-filters">
            <button class="filter-btn active" data-filter="all">
                Tout afficher
            </button>
            <button class="filter-btn" data-filter="work">
                💼 Expériences
            </button>
            <button class="filter-btn" data-filter="education">
                🎓 Formations
            </button>
            <button class="filter-btn" data-filter="certification">
                🏆 Certifications
            </button>
        </div>

        <!-- Timeline -->
        <div class="timeline">
            @forelse($experiences as $index => $experience)
                <div class="timeline-item" data-type="{{ $experience->type }}">
                    <!-- Point sur la ligne -->
                    <div class="timeline-marker">
                        <div class="timeline-dot"></div>
                    </div>

                    <!-- Contenu -->
                    <div class="timeline-content {{ $index % 2 === 0 ? 'left' : 'right' }}">
                        <div class="timeline-card">
                            <!-- En-tête de la card -->
                            <div class="timeline-card-header">
                                @if($experience->logo_url)
                                    <img src="{{ $experience->logo_url }}" 
                                         alt="{{ $experience->company }}"
                                         class="company-logo">
                                @else
                                    <div class="company-logo-placeholder">
                                        @if($experience->type === 'work')
                                            💼
                                        @elseif($experience->type === 'education')
                                            🎓
                                        @else
                                            🏆
                                        @endif
                                    </div>
                                @endif

                                <div class="timeline-card-info">
                                    <h3 class="timeline-title">{{ $experience->title }}</h3>
                                    
                                    @if($experience->company_url)
                                        <a href="{{ $experience->company_url }}" 
                                           target="_blank" 
                                           class="company-name">
                                            {{ $experience->company }}
                                        </a>
                                    @else
                                        <div class="company-name">{{ $experience->company }}</div>
                                    @endif

                                    @if($experience->location)
                                        <div class="timeline-location">
                                            📍 {{ $experience->location }}
                                        </div>
                                    @endif
                                </div>

                                <div class="timeline-badge type-{{ $experience->type }}">
                                    @if($experience->type === 'work')
                                        💼
                                    @elseif($experience->type === 'education')
                                        🎓
                                    @else
                                        🏆
                                    @endif
                                </div>
                            </div>

                            <!-- Période et durée -->
                            <div class="timeline-period">
                                <span class="period-text">{{ $experience->period_fr }}</span>
                                @if($experience->is_current)
                                    <span class="badge-current">En cours</span>
                                @endif
                                <span class="duration-text">• {{ $experience->duration }}</span>
                            </div>

                            <!-- Description -->
                            @if($experience->description)
                                <div class="timeline-description">
                                    <p>{{ $experience->description }}</p>
                                </div>
                            @endif

                            <!-- Tâches / Réalisations -->
                            @if($experience->tasks_array && count($experience->tasks_array) > 0)
                                <div class="timeline-tasks">
                                    <h4 class="tasks-title">
                                        <span class="prompt">></span> Réalisations :
                                    </h4>
                                    <ul class="tasks-list">
                                        @foreach($experience->tasks_array as $task)
                                            <li>{{ $task }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Technologies -->
                            @if($experience->technologies_array && count($experience->technologies_array) > 0)
                                <div class="timeline-technologies">
                                    @foreach($experience->technologies_array as $tech)
                                        <span class="tech-badge">{{ $tech }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Lien certificat -->
                            @if($experience->certificate_url)
                                <div class="timeline-certificate">
                                    <a href="{{ $experience->certificate_url }}" 
                                       target="_blank" 
                                       class="certificate-link">
                                        🏆 Voir le certificat
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="timeline-empty">
                    <p><span class="prompt">!</span> Aucune expérience à afficher pour le moment</p>
                </div>
            @endforelse
        </div>

        <!-- CTA -->
        <div class="timeline-footer">
            <a href="{{ route('contact') }}" class="terminal-btn-cta">
                <span class="prompt">></span> Me contacter
            </a>
        </div>
    </div>
</section>
@endsection