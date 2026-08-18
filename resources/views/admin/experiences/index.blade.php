<x-app-layout>
    <x-slot name="header">
        <div class="terminal-header">
            <h2>
                <span class="prompt">root@portfolio:~$</span> ls experiences/
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="admin-page-inner">

            @if (session('success'))
                <div class="terminal-alert success mb-6">
                    <span class="prompt">[OK]</span> {{ session('success') }}
                </div>
            @endif

            <div class="terminal-card mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl">
                        <span class="prompt">></span> Gestion des expériences professionnelles
                    </h3>
                    <a href="{{ route('admin.experiences.create') }}" class="terminal-btn">
                        <span class="prompt">+</span> Nouvelle expérience
                    </a>
                </div>

                @if ($experiences->isEmpty())
                    <div class="terminal-empty">
                        <p><span class="prompt">!</span> Aucune expérience trouvée</p>
                        <p class="text-sm mt-2 opacity-70">Créez votre première expérience pour commencer</p>
                    </div>
                @else
                    <!-- MESSAGE INFO MOBILE -->
                    <div class="mobile-scroll-hint">
                        <p>← Faites défiler horizontalement pour voir toutes les colonnes →</p>
                    </div>

                    <!-- FILTRES PAR TYPE -->
                    <div class="mb-4 flex gap-2 flex-wrap">
                        <a href="{{ route('admin.experiences.index') }}" 
                           class="filter-badge {{ !request('type') ? 'active' : '' }}">
                            Toutes
                        </a>
                        <a href="{{ route('admin.experiences.index', ['type' => 'work']) }}" 
                           class="filter-badge {{ request('type') === 'work' ? 'active' : '' }}">
                            💼 Expériences pro
                        </a>
                        <a href="{{ route('admin.experiences.index', ['type' => 'education']) }}" 
                           class="filter-badge {{ request('type') === 'education' ? 'active' : '' }}">
                            🎓 Formations
                        </a>
                        <a href="{{ route('admin.experiences.index', ['type' => 'certification']) }}" 
                           class="filter-badge {{ request('type') === 'certification' ? 'active' : '' }}">
                            🏆 Certifications
                        </a>
                    </div>

                    <div class="terminal-table-wrapper">
                        <table class="terminal-table">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Poste / Formation</th>
                                    <th>Entreprise</th>
                                    <th>Type</th>
                                    <th>Période</th>
                                    <th>Durée</th>
                                    <th>Visible</th>
                                    <th>Ordre</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($experiences as $experience)
                                    <tr>
                                        <!-- Logo -->
                                        <td>
                                            @if ($experience->logo_url)
                                                <img src="{{ $experience->logo_url }}" 
                                                     alt="{{ $experience->company }}" 
                                                     class="experience-logo">
                                            @else
                                                <div class="experience-logo-placeholder">
                                                    @if($experience->type === 'work')
                                                        💼
                                                    @elseif($experience->type === 'education')
                                                        🎓
                                                    @else
                                                        🏆
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Poste/Formation -->
                                        <td>
                                            <div class="font-semibold">{{ $experience->title }}</div>
                                            @if($experience->location)
                                                <small class="opacity-70">📍 {{ $experience->location }}</small>
                                            @endif
                                        </td>

                                        <!-- Entreprise -->
                                        <td>
                                            @if($experience->company_url)
                                                <a href="{{ $experience->company_url }}" 
                                                   target="_blank" 
                                                   class="terminal-link">
                                                    {{ $experience->company }}
                                                </a>
                                            @else
                                                {{ $experience->company }}
                                            @endif
                                        </td>

                                        <!-- Type -->
                                        <td>
                                            <span class="type-badge type-{{ $experience->type }}">
                                                @if($experience->type === 'work')
                                                    💼 Travail
                                                @elseif($experience->type === 'education')
                                                    🎓 Formation
                                                @else
                                                    🏆 Certification
                                                @endif
                                            </span>
                                        </td>

                                        <!-- Période -->
                                        <td>
                                            <div class="text-sm">{{ $experience->period_fr }}</div>
                                            @if($experience->is_current)
                                                <span class="badge-current">En cours</span>
                                            @endif
                                        </td>

                                        <!-- Durée -->
                                        <td>
                                            <small class="opacity-70">{{ $experience->duration }}</small>
                                        </td>

                                        <!-- Visible -->
                                        <td class="text-center">
                                            @if($experience->is_visible)
                                                <span class="status-badge status-active">✓ Oui</span>
                                            @else
                                                <span class="status-badge status-inactive">✗ Non</span>
                                            @endif
                                        </td>

                                        <!-- Ordre -->
                                        <td class="text-center">{{ $experience->order }}</td>

                                        <!-- Actions -->
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.experiences.edit', $experience) }}" 
                                                   class="action-btn edit" 
                                                   title="Modifier">
                                                    ✎
                                                </a>
                                                <form action="{{ route('admin.experiences.destroy', $experience) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette expérience ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="action-btn delete" 
                                                            title="Supprimer">
                                                        ✖
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $experiences->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>