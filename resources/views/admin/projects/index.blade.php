<x-app-layout>
    <x-slot name="header">
        <div class="terminal-header">
            <h2>
                <span class="prompt">root@portfolio:~$</span> ls projects/
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="terminal-alert success mb-6">
                    <span class="prompt">[OK]</span> {{ session('success') }}
                </div>
            @endif

            <div class="terminal-card mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl">
                        <span class="prompt">></span> Gestion des projets
                    </h3>
                    <a href="{{ route('admin.projects.create') }}" class="terminal-btn">
                        <span class="prompt">+</span> Nouveau projet
                    </a>
                </div>

                @if ($projects->isEmpty())
                    <div class="terminal-empty">
                        <p><span class="prompt">!</span> Aucun projet trouvé</p>
                        <p class="text-sm mt-2 opacity-70">Créez votre premier projet pour commencer</p>
                    </div>
                @else
                    <!-- MESSAGE INFO MOBILE -->
                    <div class="mobile-scroll-hint">
                        <p>← Faites défiler horizontalement pour voir toutes les colonnes →</p>
                    </div>
                    <div class="terminal-table-wrapper">
                        <table class="terminal-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Titre</th>
                                    <th>Technologies</th>
                                    <th>Status</th>
                                    <th>Ordre</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $project)
                                    <tr>
                                        <td>
                                            @php
                                                $mainImage = $project->images
                                                    ? $project->images->where('is_main', true)->first()
                                                    : null;

                                                if (!$mainImage && $project->image) {
                                                    $hasOldImage = true;
                                                } else {
                                                    $hasOldImage = false;
                                                }
                                            @endphp

                                            @if ($mainImage)
                                                <img src="{{ Storage::url($mainImage->image_path) }}"
                                                    alt="{{ $project->title }}" class="project-thumbnail">
                                                @if ($project->images->count() > 1)
                                                    <span
                                                        class="images-count-badge">+{{ $project->images->count() - 1 }}</span>
                                                @endif
                                            @elseif($hasOldImage)
                                                {{-- Afficher l'ancienne image si elle existe --}}
                                                <img src="{{ Storage::url($project->image) }}"
                                                    alt="{{ $project->title }}" class="project-thumbnail">
                                            @else
                                                <div class="project-thumbnail-placeholder">
                                                    <span>?</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="project-title">
                                                {{ $project->title }}
                                                @if ($project->is_featured)
                                                    <span class="badge-featured">★</span>
                                                @endif
                                            </div>
                                            @if ($project->completed_at)
                                                <small
                                                    class="opacity-70">{{ $project->completed_at->format('Y') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="tech-tags">
                                                @foreach (array_slice($project->technologies_array, 0, 3) as $tech)
                                                    <span class="tech-tag">{{ $tech }}</span>
                                                @endforeach
                                                @if (count($project->technologies_array) > 3)
                                                    <span
                                                        class="tech-tag">+{{ count($project->technologies_array) - 3 }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $project->status }}">
                                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $project->order }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.projects.edit', $project) }}"
                                                    class="action-btn edit" title="Modifier">
                                                    ✎
                                                </a>
                                                <form action="{{ route('admin.projects.destroy', $project) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn delete" title="Supprimer">
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

                    <div class="mt-6">
                        {{ $projects->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
