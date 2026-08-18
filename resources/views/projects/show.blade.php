@extends('layouts.terminal')

@section('title', $project->title)

@section('content')
    <div class="container">
        <section class="section project-detail">

            <nav class="project-breadcrumb">
                <a href="{{ route('home') }}#portfolio" class="terminal-link">
                    <span class="prompt">&lt;</span> cd ../projects
                </a>
            </nav>

            <h2 class="section-title">
                <span class="prompt">root@portfolio:~$</span> cat {{ $project->slug }}.md
            </h2>

            <article class="project-detail-body">

                @if ($project->images->isNotEmpty())
                    <div class="project-detail-gallery">
                        @foreach ($project->images as $image)
                            <figure class="project-detail-image">
                                <img src="{{ Storage::url($image->image_path) }}"
                                     alt="{{ $project->title }} — visuel {{ $loop->iteration }}"
                                     loading="lazy">
                            </figure>
                        @endforeach
                    </div>
                @elseif ($project->image)
                    <div class="project-detail-gallery">
                        <figure class="project-detail-image">
                            <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}" loading="lazy">
                        </figure>
                    </div>
                @endif

                <header class="project-detail-header">
                    <h1 class="project-detail-title">
                        {{ $project->title }}
                        @if ($project->is_featured)
                            <span class="featured-badge" title="Projet mis en avant">★</span>
                        @endif
                    </h1>

                    <ul class="project-detail-meta">
                        <li>
                            <span class="prompt">&gt;</span> statut :
                            <span class="status-badge status-{{ $project->status }}">
                                {{ ['en_cours' => 'En cours', 'termine' => 'Terminé', 'archive' => 'Archivé'][$project->status] ?? $project->status }}
                            </span>
                        </li>
                        @if ($project->completed_at)
                            <li>
                                <span class="prompt">&gt;</span> livré :
                                {{ $project->completed_at->format('m/Y') }}
                            </li>
                        @endif
                    </ul>
                </header>

                <div class="project-detail-description">
                    {!! nl2br(e($project->description)) !!}
                </div>

                @if (count($project->technologies_array))
                    <div class="project-detail-section">
                        <h3 class="subsection-title"><span class="prompt">&gt;</span> Stack technique</h3>
                        <div class="project-technologies">
                            @foreach ($project->technologies_array as $tech)
                                <span class="tech-badge">{{ $tech }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($project->url || $project->github_url)
                    <div class="project-detail-section">
                        <h3 class="subsection-title"><span class="prompt">&gt;</span> Liens</h3>
                        <div class="project-detail-links">
                            @if ($project->url)
                                <a href="{{ $project->url }}" target="_blank" rel="noopener noreferrer"
                                   class="terminal-button">
                                    <span class="prompt">🌐</span> Voir le site
                                </a>
                            @endif
                            @if ($project->github_url)
                                <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer"
                                   class="terminal-button">
                                    <span class="prompt">💻</span> Code source
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </article>

            @if ($otherProjects->isNotEmpty())
                <aside class="project-detail-section">
                    <h3 class="subsection-title"><span class="prompt">&gt;</span> Autres projets</h3>
                    <ul class="project-detail-others">
                        @foreach ($otherProjects as $other)
                            <li>
                                <a href="{{ route('project.show', $other->slug) }}" class="terminal-link">
                                    <span class="prompt">&gt;</span> {{ $other->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </aside>
            @endif

        </section>
    </div>
@endsection
