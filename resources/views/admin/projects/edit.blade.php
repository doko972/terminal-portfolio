<x-app-layout>
    <x-slot name="header">
        <div class="terminal-header">
            <h2>
                <span class="prompt">root@portfolio:~$</span> nano project_{{ $project->id }}.json
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="terminal-card">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl">
                        <span class="prompt">></span> Modifier le projet
                    </h3>
                    <a href="{{ route('admin.projects.index') }}" class="terminal-btn-secondary">
                        ← Retour
                    </a>
                </div>

                <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <!-- Titre -->
                        <div class="form-group full-width">
                            <label for="title" class="form-label">
                                <span class="prompt">></span> Titre du projet *
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   class="terminal-input @error('title') error @enderror" 
                                   value="{{ old('title', $project->title) }}" 
                                   required
                                   placeholder="Ex: Portfolio Personnel">
                            @error('title')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group full-width">
                            <label for="description" class="form-label">
                                <span class="prompt">></span> Description *
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="5" 
                                      class="terminal-input @error('description') error @enderror" 
                                      required
                                      placeholder="Décrivez votre projet en détail...">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Technologies -->
                        <div class="form-group full-width">
                            <label for="technologies" class="form-label">
                                <span class="prompt">></span> Technologies * <small class="opacity-70">(séparées par des virgules)</small>
                            </label>
                            <input type="text" 
                                   name="technologies" 
                                   id="technologies" 
                                   class="terminal-input @error('technologies') error @enderror" 
                                   value="{{ old('technologies', implode(', ', $project->technologies_array)) }}" 
                                   required
                                   placeholder="Ex: Laravel, Vue.js, MySQL, Tailwind CSS">
                            @error('technologies')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image actuelle -->
                        @if($project->image)
                            <div class="form-group full-width">
                                <label class="form-label">
                                    <span class="prompt">></span> Image actuelle
                                </label>
                                <div class="current-image">
                                    <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}">
                                </div>
                            </div>
                        @endif

                        <!-- Nouvelle image -->
                        <div class="form-group full-width">
                            <label for="image" class="form-label">
                                <span class="prompt">></span> {{ $project->image ? 'Remplacer l\'image' : 'Ajouter une image' }}
                            </label>
                            <input type="file" 
                                   name="image" 
                                   id="image" 
                                   class="terminal-input-file @error('image') error @enderror" 
                                   accept="image/*">
                            <p class="help-text">Format accepté : JPG, PNG, GIF, WEBP (max 2Mo)</p>
                            @error('image')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- URL du projet -->
                        <div class="form-group">
                            <label for="url" class="form-label">
                                <span class="prompt">></span> URL du projet
                            </label>
                            <input type="url" 
                                   name="url" 
                                   id="url" 
                                   class="terminal-input @error('url') error @enderror" 
                                   value="{{ old('url', $project->url) }}"
                                   placeholder="https://monprojet.com">
                            @error('url')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- URL GitHub -->
                        <div class="form-group">
                            <label for="github_url" class="form-label">
                                <span class="prompt">></span> URL GitHub
                            </label>
                            <input type="url" 
                                   name="github_url" 
                                   id="github_url" 
                                   class="terminal-input @error('github_url') error @enderror" 
                                   value="{{ old('github_url', $project->github_url) }}"
                                   placeholder="https://github.com/username/projet">
                            @error('github_url')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <span class="prompt">></span> Status *
                            </label>
                            <select name="status" 
                                    id="status" 
                                    class="terminal-input @error('status') error @enderror" 
                                    required>
                                <option value="termine" {{ old('status', $project->status) == 'termine' ? 'selected' : '' }}>Terminé</option>
                                <option value="en_cours" {{ old('status', $project->status) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="archive" {{ old('status', $project->status) == 'archive' ? 'selected' : '' }}>Archivé</option>
                            </select>
                            @error('status')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de fin -->
                        <div class="form-group">
                            <label for="completed_at" class="form-label">
                                <span class="prompt">></span> Date de fin
                            </label>
                            <input type="date" 
                                   name="completed_at" 
                                   id="completed_at" 
                                   class="terminal-input @error('completed_at') error @enderror" 
                                   value="{{ old('completed_at', $project->completed_at?->format('Y-m-d')) }}">
                            @error('completed_at')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ordre d'affichage -->
                        <div class="form-group">
                            <label for="order" class="form-label">
                                <span class="prompt">></span> Ordre d'affichage
                            </label>
                            <input type="number" 
                                   name="order" 
                                   id="order" 
                                   class="terminal-input @error('order') error @enderror" 
                                   value="{{ old('order', $project->order) }}" 
                                   min="0"
                                   placeholder="0">
                            <p class="help-text">Plus le nombre est petit, plus le projet apparaît en premier</p>
                            @error('order')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Projet mis en avant -->
                        <div class="form-group">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="is_featured" 
                                       value="1" 
                                       class="terminal-checkbox"
                                       {{ old('is_featured', $project->is_featured) ? 'checked' : '' }}>
                                <span class="ml-3">
                                    <span class="prompt">★</span> Projet mis en avant
                                </span>
                            </label>
                            <p class="help-text">Les projets mis en avant apparaissent en premier sur la page d'accueil</p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="terminal-btn">
                            <span class="prompt">✓</span> Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.projects.index') }}" class="terminal-btn-secondary">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>